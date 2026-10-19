<?php

namespace App\Http\Controllers\Nurse;

use App\Http\Controllers\Controller;
use App\Models\NursingStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NursingStatisticController extends Controller
{
    public function index()
    {
        $nurse = Auth::guard('nurse')->user();

        $statistics = NursingStatistic::with('ward')
            ->where('hospital_id', $nurse->hospital_id)
            ->where('ward_id', $nurse->ward_id)
            ->latest('stats_date')
            ->paginate(15);

        return view('parklane.nurses.digital_statistics.index', compact('statistics'));
    }

    public function create()
    {
        return view('parklane.nurses.digital_statistics.create');
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
        ]);

        $validated['hospital_id'] = $nurse->hospital_id;
        $validated['ward_id'] = $nurse->ward_id;
        $validated['nurse_id'] = $nurse->id;

        $statistic = NursingStatistic::create($validated);

        return redirect()->route('nurse.statistics.show', $statistic)
            ->with('success', 'Nursing statistics submitted successfully.');
    }

    public function show(NursingStatistic $statistic)
    {
        $nurse = Auth::guard('nurse')->user();

        if (! $this->belongsToNurseAssignment($statistic, $nurse)) {
            return redirect()->route('nurse.statistics.index')
                ->with('error', 'You can only view statistics for your assigned ward or clinic.');
        }

        $statistic->load(['ward', 'nurse']);

        return view('parklane.nurses.digital_statistics.show', compact('statistic'));
    }

    private function belongsToNurseAssignment(NursingStatistic $statistic, $nurse): bool
    {
        return (string) $statistic->hospital_id === (string) $nurse->hospital_id
            && (string) $statistic->ward_id === (string) $nurse->ward_id;
    }
}
