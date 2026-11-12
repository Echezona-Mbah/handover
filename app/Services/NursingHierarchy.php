<?php
namespace App\Services;

use App\Models\{Nurses, NursingAuditLog, NursingReport, Patient, Ward};
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/** Central backend authorization rules for the nursing hierarchy. */
class NursingHierarchy
{
    public static function type(object $actor): string { return $actor instanceof Nurses ? 'nurse' : 'user'; }
    public static function role(object $actor): ?string
    {
        $role = strtolower(trim((string) $actor->nursing_hierarchy_role));
        if (in_array($role, ['hns', 'nursing_hns', 'head_of_nursing_services'], true)) {
            return 'hns';
        }
        if (in_array($role, ['control_162', 'nursing_162', 'nursing_169', '162', 'control_unit', 'nursing_691', 'nursing_management'], true)) {
            return 'control_162';
        }
        if (in_array($role, ['unit_head', 'nursing_unit_head'], true)) {
            return 'unit_head';
        }
        if (in_array($role, ['ward_supervision', 'ward_supervisor'], true)) {
            return 'ward_supervision';
        }
        if ($role === 'ward_nurse') {
            return 'ward_nurse';
        }

        if ($actor instanceof Nurses) {
            return 'ward_nurse';
        }

        if ($actor instanceof User && $actor->isSuperAdmin()) {
            return 'hns';
        }

        $adminRole = strtolower(trim((string) $actor->nursing_admin_role));
        if (in_array($adminRole, ['nursing_162', 'nursing_169', 'nursing_691', 'nursing_management'], true)
            || str_starts_with($adminRole, 'nursing_')) {
            return 'control_162';
        }

        return null;
    }

    public static function isHns(object $actor): bool { return self::role($actor) === 'hns'; }
    public static function isControl(object $actor): bool { return self::role($actor) === 'control_162'; }
    public static function canManageNursingDirectory(object $actor): bool
    {
        return self::isHns($actor)
            || self::isControl($actor)
            || self::role($actor) === 'unit_head';
    }
    public static function wardsFor(object $actor): array
    {
        if (self::isHns($actor) || self::isControl($actor)) return Ward::where('hospital_id', $actor->hospital_id)->pluck('id')->all();
        if (self::role($actor) === 'unit_head') return Ward::where('hospital_id', $actor->hospital_id)->where('unit_head_id', $actor->id)->pluck('id')->all();
        return $actor instanceof Nurses && self::role($actor) === 'ward_supervision' && $actor->ward_id
            ? [(int) $actor->ward_id]
            : [];
    }

    public static function canAccessWard(object $actor, Ward $ward): bool
    {
        return (int) $actor->hospital_id === (int) $ward->hospital_id
            && in_array((int) $ward->id, self::wardsFor($actor), true);
    }

    public static function visiblePatients(object $actor): Builder
    {
        return Patient::query()
            ->where('hospital_id', $actor->hospital_id)
            ->whereIn('ward_id', self::wardsFor($actor));
    }

    public static function visibleNurses(object $actor): Builder
    {
        return Nurses::query()
            ->where('hospital_id', $actor->hospital_id)
            ->whereIn('ward_id', self::wardsFor($actor));
    }
    public static function visibleReports(object $actor): Builder
    {
        $query = NursingReport::query()->where('hospital_id', $actor->hospital_id);
        if ($actor instanceof User) {
            $query->where('status', '!=', 'draft');
        }
        if (self::isHns($actor)) return $query;
        if (self::isControl($actor)) return $query->whereIn('id', []);
        $type = self::type($actor);
        return $query->where(function (Builder $q) use ($actor, $type) {
            $q->where(fn (Builder $q) => $q->where('recipient_type', $type)
                ->where('recipient_id', $actor->id));
            if (self::role($actor) === 'unit_head') {
                $q->orWhereIn('ward_id', self::wardsFor($actor));
            }
            if ($actor instanceof Nurses && self::role($actor) === 'ward_supervision') {
                $q->orWhereIn('ward_id', self::wardsFor($actor));
            } elseif ($actor instanceof Nurses) {
                $q->orWhere('nurse_id', $actor->id);
            }
        });
    }
    public static function canViewReport(object $actor, NursingReport $report): bool { return self::visibleReports($actor)->whereKey($report->id)->exists(); }
    public static function canQuery(object $sender, object $recipient, ?int $wardId): bool
    {
        if ($sender->hospital_id != $recipient->hospital_id) return false;
        $role = self::role($sender); $targetRole = self::role($recipient);
        if ($role === 'hns') return in_array($targetRole, ['hns','control_162','unit_head','ward_supervision','ward_nurse'], true);
        if ($role === 'control_162') return in_array($targetRole, ['hns','unit_head','ward_supervision','ward_nurse'], true);
        if ($role === 'unit_head') return $targetRole === 'hns'
            || (in_array($targetRole, ['ward_supervision','ward_nurse'], true) && in_array($wardId, self::wardsFor($sender), true));
        if ($role === 'ward_supervision') return $targetRole === 'ward_nurse' && (int) $recipient->ward_id === (int) $sender->ward_id;
        return false;
    }

    public static function queryableRecipients(object $actor): array
    {
        $users = User::where('hospital_id', $actor->hospital_id)
            ->where(function (Builder $query) {
                $query->whereIn('nursing_hierarchy_role', [
                    'hns', 'nursing_hns', 'head_of_nursing_services',
                    'control_162', 'nursing_162', 'nursing_169',
                    'unit_head', 'nursing_unit_head',
                ])
                    ->orWhereIn('nursing_admin_role', ['nursing_691', 'nursing_management', 'nursing_162', 'nursing_169'])
                    ->orWhere('is_super_admin', true);
            })
            ->get()
            ->filter(fn (User $recipient) => self::canQuery($actor, $recipient, null))
            ->map(fn (User $recipient) => [
                'type' => 'user',
                'id' => $recipient->id,
                'name' => $recipient->name,
                'role' => self::role($recipient),
            ]);

        $nurses = Nurses::where('hospital_id', $actor->hospital_id)
            ->where(function (Builder $query) {
                $query->whereIn('nursing_hierarchy_role', ['ward_supervision', 'ward_supervisor', 'ward_nurse'])
                    ->orWhereNull('nursing_hierarchy_role');
            })
            ->get()
            ->filter(fn (Nurses $recipient) => self::canQuery($actor, $recipient, $recipient->ward_id))
            ->map(fn (Nurses $recipient) => [
                'type' => 'nurse',
                'id' => $recipient->id,
                'name' => $recipient->full_name,
                'role' => self::role($recipient),
                'ward' => $recipient->ward?->name,
            ]);

        return $users->concat($nurses)->values()->all();
    }

    public static function audit(object $actor, string $action, object $record, array $metadata = []): void
    {
        NursingAuditLog::create(['hospital_id' => $actor->hospital_id, 'actor_type' => self::type($actor), 'actor_id' => $actor->id, 'action' => $action, 'auditable_type' => $record::class, 'auditable_id' => $record->id, 'metadata' => $metadata]);
    }
}
