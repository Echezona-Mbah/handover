<?php

namespace App\Observers;

use App\Models\Dentists;
use App\Models\Doctor;
use App\Models\Medication;
use App\Models\News;
use App\Models\NurseHandover;
use App\Models\Nurses;
use App\Models\NursingReport;
use App\Models\NursingStatistic;
use App\Models\Patient;
use App\Models\ShiftSchedule;
use App\Models\Ward;
use App\Notifications\HospitalActivityNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HospitalActivityObserver
{
    private const ACTIVITY_LABELS = [
        Dentists::class => 'Dentist staff record',
        Doctor::class => 'Doctor staff record',
        Medication::class => 'Medication plan',
        News::class => 'Hospital news',
        NursingReport::class => 'Nursing report',
        NursingStatistic::class => 'Nursing statistics',
        NurseHandover::class => 'Nurse handover',
        Patient::class => 'Patient record',
        ShiftSchedule::class => 'Staff shift schedule',
        Ward::class => 'Hospital ward',
    ];

    public function created(Model $model): void
    {
        if ($model instanceof Patient) {
            $this->notifyPatientAddedToWard($model);

            return;
        }

        $this->notifyHospital($model, 'added');
    }

    public function updated(Model $model): void
    {
        if ($model instanceof Patient && $this->isEmergency($model) && $model->wasChanged('urgency_status')) {
            $this->notifyPatientWard($model, true, 'updated');

            return;
        }

        if ($model instanceof NursingReport) {
            if ($model->wasChanged('status') && $model->nurse_id) {
                $this->notifyNurseById(
                    $model->nurse_id,
                    'Nursing report status',
                    'updated',
                    route('nurse.reports.show', $model->getKey()),
                );
            }

            return;
        }

        $this->notifyHospital($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->notifyHospital($model, 'removed');
    }

    private function notifyHospital(Model $model, string $action): void
    {
        $hospitalId = $model->getAttribute('hospital_id');
        $activityLabel = self::ACTIVITY_LABELS[$model::class] ?? null;

        if (! $hospitalId || ! $activityLabel) {
            return;
        }

        $wardId = $this->relatedWardId($model);
        $patientId = $model instanceof Patient
            ? $model->getKey()
            : $model->getAttribute('patient_id');

        if ($model instanceof ShiftSchedule && $model->getAttribute('nurse_id')) {
            $this->notifyNurseById(
                $model->getAttribute('nurse_id'),
                $activityLabel,
                $action,
            );

            return;
        }

        if ($wardId) {
            $url = $patientId && $action !== 'removed'
                ? route('nurse.show_patient', $patientId)
                : null;
            $this->notifyWard($hospitalId, $wardId, $activityLabel, $action, $url);

            return;
        }

        if ($model instanceof News || $model instanceof Ward) {
            $url = $model instanceof News ? route('nurse.news') : null;
            $this->notifyHospitalStaff($hospitalId, $activityLabel, $action, $url);
        }
    }

    private function notifyPatientWard(Patient $patient, bool $urgent, string $action): void
    {
        $hospitalId = $patient->hospital_id;
        $wardId = $patient->ward_id;
        if (! $hospitalId || ! $wardId) {
            return;
        }

        $this->notifyWard(
            $hospitalId,
            $wardId,
            'Emergency patient alert',
            $action,
            route('nurse.show_patient', $patient->getKey()),
            $urgent,
        );
    }

    private function notifyPatientAddedToWard(Patient $patient): void
    {
        if (! $patient->hospital_id || ! $patient->ward_id) {
            return;
        }

        $urgency = strtolower((string) ($patient->urgency_status ?: 'routine'));
        $isEmergency = $urgency === 'emergency';
        $fullName = $patient->full_name ?: 'A new patient';
        $title = ($isEmergency ? 'URGENT: ' : '').'New patient added: '.$fullName;
        $message = 'A new patient, '.$fullName.', has been added to your ward. Patient urgency: '.ucfirst($urgency).'.';
        $senderName = Auth::guard('nurse')->user()?->full_name
            ?? Auth::user()?->name
            ?? 'Hospital team';
        Nurses::query()
            ->where('hospital_id', $patient->hospital_id)
            ->where('ward_id', $patient->ward_id)
            ->where('status', 'Active')
            ->each(fn (Nurses $nurse) => $nurse->notify(new HospitalActivityNotification(
                $title,
                $message,
                $senderName,
                route('nurse.show_patient', $patient->getKey()),
                $isEmergency,
                $urgency,
            )));
    }

    private function relatedWardId(Model $model): int|string|null
    {
        if ($model instanceof Patient || $model instanceof NursingReport || $model instanceof NursingStatistic || $model instanceof NurseHandover) {
            return $model->getAttribute('ward_id');
        }

        if ($model instanceof Medication) {
            return $model->patient()->value('ward_id');
        }

        return null;
    }

    private function notifyWard(
        int|string $hospitalId,
        int|string $wardId,
        string $activityLabel,
        string $action,
        ?string $url = null,
        bool $urgent = false,
    ): void {
        $this->notifyNurses(
            $hospitalId,
            $activityLabel,
            $action,
            $url,
            $urgent,
            $wardId,
        );
    }

    private function notifyHospitalStaff(
        int|string $hospitalId,
        string $activityLabel,
        string $action,
        ?string $url = null,
    ): void {
        $this->notifyNurses($hospitalId, $activityLabel, $action, $url);
    }

    private function notifyNurseById(
        int|string $nurseId,
        string $activityLabel,
        string $action,
        ?string $url = null,
    ): void {
        $nurse = Nurses::query()
            ->whereKey($nurseId)
            ->where('status', 'Active')
            ->first();

        if (! $nurse) {
            return;
        }

        $nurseGuard = Auth::guard('nurse');
        if ($nurseGuard->check() && (string) $nurseGuard->id() === (string) $nurse->getKey()) {
            return;
        }

        $title = match ($action) {
            'added' => $activityLabel.' added',
            'removed' => $activityLabel.' removed',
            default => $activityLabel.' updated',
        };

        $nurse->notify(new HospitalActivityNotification(
            $title,
            'Your '.$activityLabel.' was '.$action.'.',
            $nurseGuard->user()?->full_name ?? Auth::user()?->name ?? 'Hospital team',
            $url,
        ));
    }

    private function notifyNurses(
        int|string $hospitalId,
        string $activityLabel,
        string $action,
        ?string $url = null,
        bool $urgent = false,
        int|string|null $wardId = null,
    ): void {
        $nurseGuard = Auth::guard('nurse');
        $senderName = $nurseGuard->user()?->full_name ?? Auth::user()?->name ?? 'Hospital team';
        $title = $urgent ? 'URGENT: Emergency patient alert' : match ($action) {
            'added' => $activityLabel.' added',
            'removed' => $activityLabel.' removed',
            default => $activityLabel.' updated',
        };
        $message = $urgent
            ? 'An emergency patient has been added or marked as an emergency in your ward.'
            : match ($action) {
                'added' => 'A new item was added: '.$activityLabel.'.',
                'removed' => 'An item was removed: '.$activityLabel.'.',
                default => 'An item was updated: '.$activityLabel.'.',
            };

        $query = Nurses::query()
            ->where('hospital_id', $hospitalId)
            ->where('status', 'Active');

        if ($wardId !== null) {
            $query->where('ward_id', $wardId);
        }

        if ($nurseGuard->check()) {
            $query->whereKeyNot($nurseGuard->id());
        }

        $query->each(fn (Nurses $nurse) => $nurse->notify(
            new HospitalActivityNotification($title, $message, $senderName, $url, $urgent)
        ));
    }

    private function isEmergency(Patient $patient): bool
    {
        return strtolower((string) $patient->urgency_status) === 'emergency';
    }
}
