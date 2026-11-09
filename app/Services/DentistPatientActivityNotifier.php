<?php

namespace App\Services;

use App\Models\Dentists;
use App\Models\Doctor;
use App\Models\DoctorNotification;
use App\Models\Patient;
use App\Notifications\DentistPatientActivityNotification;
use Illuminate\Support\Collection;

class DentistPatientActivityNotifier
{
    public function notify(Patient $patient, Dentists $actor, string $title, string $message, string $activity): void
    {
        $notification = new DentistPatientActivityNotification($patient, $title, $message, $activity);

        $this->doctorsForPatient($patient)->each(function (Doctor $doctor) use (
            $patient,
            $notification,
            $title,
            $message
        ) {
            $doctor->notify($notification);

            if ($doctor->doctor_departments && $doctor->hospital_id && $doctor->doctor_unit_id) {
                DoctorNotification::create([
                    'department_id' => $doctor->doctor_departments,
                    'hospital_id' => $doctor->hospital_id,
                    'doctor_unit_id' => $doctor->doctor_unit_id,
                    'doctor_sub_unit_id' => $doctor->doctor_sub_unit_id,
                    'patient_id' => $patient->id,
                    'title' => $title,
                    'message' => $message,
                ]);
            }
        });

        $this->dentistsInPatientUnit($patient, $actor)->each(
            fn (Dentists $dentist) => $dentist->notify($notification)
        );
    }

    private function doctorsForPatient(Patient $patient): Collection
    {
        $doctorIds = collect([$patient->doctor_assign, $patient->doctor_id])
            ->filter()
            ->unique()
            ->values();

        if ($doctorIds->isEmpty() && ! $patient->doctor_departments && ! $patient->doctor_unit_id) {
            return collect();
        }

        return Doctor::where('hospital_id', $patient->hospital_id)
            ->where(function ($query) use ($doctorIds, $patient) {
                if ($doctorIds->isNotEmpty()) {
                    $query->whereIn('id', $doctorIds);
                }

                if ($patient->doctor_departments || $patient->doctor_unit_id) {
                    $teamQuery = function ($team) use ($patient) {
                        if ($patient->doctor_departments) {
                            $team->where('doctor_departments', $patient->doctor_departments);
                        }
                        if ($patient->doctor_unit_id) {
                            $team->where('doctor_unit_id', $patient->doctor_unit_id);
                            if ($patient->doctor_sub_unit_id) {
                                $team->where('doctor_sub_unit_id', $patient->doctor_sub_unit_id);
                            } else {
                                $team->whereNull('doctor_sub_unit_id');
                            }
                        } else {
                            $team->whereNull('doctor_unit_id');
                        }
                    };

                    $doctorIds->isNotEmpty()
                        ? $query->orWhere($teamQuery)
                        : $teamQuery($query);
                }
            })
            ->get();
    }

    private function dentistsInPatientUnit(Patient $patient, Dentists $actor): Collection
    {
        if (! $patient->dentist_unit_id && ! $patient->dentist_department_id) {
            return collect();
        }

        $query = Dentists::where('hospital_id', $patient->hospital_id);
        if ($patient->dentist_department_id) {
            $query->where('dentist_department_id', $patient->dentist_department_id);
        }
        if ($patient->dentist_unit_id) {
            $query->where('dentist_unit_id', $patient->dentist_unit_id);
        } else {
            $query->whereNull('dentist_unit_id');
        }

        return $query
            ->where('id', '!=', $actor->id)
            ->get();
    }
}
