<?php
namespace App\Http\Controllers;

use App\Models\{Nurses, NursingQuery, NursingQueryResponse, User};
use App\Services\NursingHierarchy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NursingQueryController extends Controller
{
    private function actor(): object { return Auth::guard('nurse')->check() ? Auth::guard('nurse')->user() : Auth::user(); }
    public function index() {
        $actor = $this->actor(); $type = NursingHierarchy::type($actor);
        $queries = NursingQuery::with(['ward','report','responses'])
            ->where('hospital_id', $actor->hospital_id)
            ->when(! NursingHierarchy::isHns($actor), fn ($q) => $q->where(fn ($q) => $q
                ->where([['recipient_type',$type],['recipient_id',$actor->id]])
                ->orWhere([['sender_type',$type],['sender_id',$actor->id]])))
            ->latest()
            ->paginate(20);
        $routePrefix = Auth::guard('nurse')->check() ? 'nurse.queries' : 'nursing.queries';
        return view('parklane.nursing_queries.index', compact('queries', 'actor', 'routePrefix'));
    }
    public function create()
    {
        abort_unless(Auth::guard('nurse')->guest(), 403, 'Nurses cannot send nursing queries.');
        $actor = $this->actor();
        $recipients = NursingHierarchy::queryableRecipients($actor);

        return view('parklane.nursing_queries.create', compact('recipients'));
    }

    public function store(Request $request) {
        abort_unless(Auth::guard('nurse')->guest(), 403, 'Nurses cannot send nursing queries.');
        $actor = $this->actor();
        $data = $request->validate([
            'recipient' => 'required|string',
            'ward_id' => [
                'nullable',
                'integer',
                Rule::exists('wards', 'id')->where(fn ($query) => $query->where('hospital_id', $actor->hospital_id)),
            ],
            'nursing_report_id' => 'nullable|integer|exists:nursing_reports,id',
            'message' => 'required|string|max:10000',
        ]);
        [$recipientType, $recipientId] = array_pad(explode(':', $data['recipient'], 2), 2, null);
        abort_unless(in_array($recipientType, ['user', 'nurse'], true) && ctype_digit((string) $recipientId), 422, 'Select a valid query recipient.');

        $recipient = $recipientType === 'user'
            ? User::where('hospital_id', $actor->hospital_id)->findOrFail($recipientId)
            : Nurses::where('hospital_id', $actor->hospital_id)->findOrFail($recipientId);
        $wardId = $recipient instanceof Nurses ? $recipient->ward_id : ($data['ward_id'] ?? null);
        abort_unless(NursingHierarchy::canQuery($actor, $recipient, $wardId), 403, 'You are not allowed to query this user.');

        if ($data['nursing_report_id'] ?? null) {
            $report = \App\Models\NursingReport::where('hospital_id', $actor->hospital_id)
                ->findOrFail($data['nursing_report_id']);
            abort_unless(NursingHierarchy::canViewReport($actor, $report), 403, 'You cannot attach a report you are not allowed to view.');
        }

        $query = NursingQuery::create([
            'hospital_id' => $actor->hospital_id,
            'ward_id' => $wardId,
            'nursing_report_id' => $data['nursing_report_id'] ?? null,
            'sender_type' => NursingHierarchy::type($actor),
            'sender_id' => $actor->id,
            'recipient_type' => $recipientType,
            'recipient_id' => $recipient->id,
            'message' => $data['message'],
            'status' => 'pending',
        ]);
        NursingHierarchy::audit($actor, 'query_submitted', $query);
        $recipient->notify(new \App\Notifications\NursingQueryReceived($query));
        return redirect()->route(Auth::guard('nurse')->check() ? 'nurse.queries.index' : 'nursing.queries.index')->with('success', 'Query sent.');
    }
    public function respond(Request $request, NursingQuery $query) {
        $actor = $this->actor();
        $messageDetails = json_decode($query->message, true);
        abort_if(($messageDetails['workflow'] ?? null) === 'ward_patient_transfer', 422, 'Ward transfer requests must be accepted or declined from the ward transfer inbox.');
        abort_unless(
            (int) $query->hospital_id === (int) $actor->hospital_id
                && $query->recipient_type === NursingHierarchy::type($actor)
                && (int) $query->recipient_id === (int) $actor->id,
            403
        );
        $data = $request->validate(['message' => 'required|string|max:10000']);
        NursingQueryResponse::create($data + ['nursing_query_id' => $query->id, 'responder_type' => NursingHierarchy::type($actor), 'responder_id' => $actor->id]);
        $query->update(['status' => 'responded']); NursingHierarchy::audit($actor, 'query_responded', $query);
        return back()->with('success', 'Response sent.');
    }
    public function close(NursingQuery $query) {
        $actor = $this->actor(); $type = NursingHierarchy::type($actor);
        $messageDetails = json_decode($query->message, true);
        abort_if(($messageDetails['workflow'] ?? null) === 'ward_patient_transfer', 422, 'Ward transfer requests cannot be closed as general queries.');
        abort_unless((int) $query->hospital_id === (int) $actor->hospital_id
            && (NursingHierarchy::isHns($actor) || ($query->sender_type === $type && (int) $query->sender_id === (int) $actor->id)), 403);
        $query->update(['status' => 'closed', 'closed_at' => now()]); NursingHierarchy::audit($actor, 'query_closed', $query);
        return back()->with('success', 'Query closed.');
    }
}
