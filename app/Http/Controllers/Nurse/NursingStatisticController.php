<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\Nurses;
use App\Models\NursingStatistic;
use App\Models\Ward;
use App\Notifications\NursingStatisticSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class NursingStatisticController extends Controller
{
    public function index()
    {
        $nurse = Auth::guard('nurse')->user();

        $statistics = NursingStatistic::with('ward')
            ->where('hospital_id', $nurse->hospital_id)
            ->where(fn ($query) => $query
                ->where('nurse_id', $nurse->id)
                ->orWhere('ward_id', $nurse->ward_id))
            ->latest('stats_date')
            ->paginate(15);

        return view('parklane.nurses.digital_statistics.index', compact('statistics'));
    }

    public function create()
    {
        $nurse = Auth::guard('nurse')->user();
        $wards = Ward::where('hospital_id', $nurse->hospital_id)
            ->orderBy('name')
            ->get();

        return view('parklane.nurses.digital_statistics.create', compact('wards'));
    }

    public function store(Request $request)
    {
        $nurse = Auth::guard('nurse')->user();

        $validated = $request->validate([
            'stats_date' => 'required|date',
            'admissions' => 'required|integer|min:0',
            'discharges' => 'required|integer|min:0',
            'transfers' => 'required|integer|min:0',
            'deaths' => 'required|integer|min:0',
            'bed_occupied' => 'required|integer|min:0',
            'bed_vacant' => 'required|integer|min:0',
            'clinic_attendance' => 'required|integer|min:0',
            'specialized_service_count' => 'required|integer|min:0',
            'notes' => 'nullable|string',
            'ward_ids' => 'required|array|min:1',
            'ward_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('wards', 'id')->where(fn ($query) => $query->where('hospital_id', $nurse->hospital_id)),
            ],
        ]);

        $wardIds = $validated['ward_ids'];
        unset($validated['ward_ids']);

        $statistics = collect($wardIds)->map(function (int $wardId) use ($validated, $nurse) {
            $statistic = NursingStatistic::create($validated + [
                'hospital_id' => $nurse->hospital_id,
                'ward_id' => $wardId,
                'nurse_id' => $nurse->id,
            ]);

            $notification = new NursingStatisticSubmitted($statistic->load(['nurse.ward', 'ward']));
            Nurses::where('hospital_id', $nurse->hospital_id)
                ->where('ward_id', $wardId)
                ->get()
                ->each(fn (Nurses $recipient) => $recipient->notify($notification));

            $ward = Ward::with('unitHead')->findOrFail($wardId);
            if ($ward->unitHead) {
                $ward->unitHead->notify($notification);
            }

            return $statistic;
        });

        return redirect()->route('nurse.statistics.show', $statistics->first())
            ->with('success', 'Nursing statistics submitted to the selected wards.');
    }

    public function show(NursingStatistic $statistic)
    {
        $nurse = Auth::guard('nurse')->user();

        if ((int) $statistic->hospital_id !== (int) $nurse->hospital_id
            || ((int) $statistic->nurse_id !== (int) $nurse->id && (int) $statistic->ward_id !== (int) $nurse->ward_id)) {
            return redirect()->route('nurse.statistics.index')
                ->with('error', 'You can only view statistics submitted by you or sent to your assigned ward.');
        }

        $statistic->load(['ward', 'nurse']);

        return view('parklane.nurses.digital_statistics.show', compact('statistic'));
    }

}
