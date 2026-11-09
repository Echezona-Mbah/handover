<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorAppointment;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(): View
    {
        $doctor = auth()->guard('doctor')->user();
        $patients = $this->manageablePatients($doctor)
            ->orderBy('full_name')
            ->get(['id', 'full_name', 'pt_number', 'patient_number']);
        $appointments = DoctorAppointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->orderByRaw("CASE WHEN status = 'scheduled' AND scheduled_at >= ? THEN 0 ELSE 1 END", [now()])
            ->orderBy('scheduled_at')
            ->paginate(15);

        return view('parklane.doctor.appointments', compact('patients', 'appointments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $doctor = auth()->guard('doctor')->user();
        $validated = $request->validate([
            'patient_id' => ['required', 'integer', 'exists:patients,id'],
            'scheduled_at' => ['required', 'date', 'after:now'],
            'appointment_type' => ['required', Rule::in(['consultation', 'review', 'follow_up', 'procedure'])],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $patient = $this->manageablePatients($doctor)->findOrFail($validated['patient_id']);

        DoctorAppointment::create([
            ...$validated,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient->id,
            'status' => 'scheduled',
        ]);

        return redirect()->route('doctor.appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    public function updateStatus(Request $request, DoctorAppointment $appointment): RedirectResponse
    {
        $doctor = auth()->guard('doctor')->user();
        abort_unless((int) $appointment->doctor_id === (int) $doctor->id, 404);

        $validated = $request->validate([
            'status' => ['required', Rule::in(['scheduled', 'completed', 'cancelled'])],
        ]);

        $appointment->update($validated);

        return redirect()->route('doctor.appointments.index')
            ->with('success', 'Appointment status updated.');
    }

    private function manageablePatients(Doctor $doctor): Builder
    {
        return Patient::query()
            ->where('hospital_id', $doctor->hospital_id)
            ->where(function (Builder $patients) use ($doctor) {
                $patients->where(function (Builder $assigned) use ($doctor) {
                    if ($doctor->doctor_sub_unit_id) {
                        $assigned->where('doctor_sub_unit_id', $doctor->doctor_sub_unit_id);
                    } else {
                        $assigned->where('doctor_unit_id', $doctor->doctor_unit_id)
                            ->whereNull('doctor_sub_unit_id');
                    }
                })->orWhereIn('id', function ($invites) use ($doctor) {
                    $invites->select('patient_id')
                        ->from('patient_unit_invites')
                        ->where('status', 'accepted');

                    if ($doctor->doctor_sub_unit_id) {
                        $invites->where('doctor_sub_unit_id', $doctor->doctor_sub_unit_id);
                    } else {
                        $invites->where('doctor_unit_id', $doctor->doctor_unit_id)
                            ->whereNull('doctor_sub_unit_id');
                    }
                });
            });
    }
}
