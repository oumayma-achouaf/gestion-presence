<?php

namespace App\Services;

use App\Models\Absence;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Planning;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AttendanceStatusService
{
    public const STATUS_PRESENT = 'present';
    public const STATUS_ABSENT = 'absent';
    public const STATUS_REPOS = 'repos';
    public const STATUS_CONGE = 'conge';
    public const STATUS_NOT_PLANNED = 'not_planned';

    public const ROLE_REPOS = 'REPOS';
    public const ROLE_CONGE = 'CONGE';

    private const WORK_ROLES = ['CAISSE', 'GUICHET', 'CONTROLE'];

    /* ================= DAILY ================= */

    public function buildDailyRows(Collection $employees, Carbon|string $date): Collection
    {
        $date = Carbon::parse($date)->toDateString();

        $userIds = $employees->pluck('user_id')->filter()->values();
        $employeeIds = $employees->pluck('id')->filter()->values();

        $attendances = Attendance::whereDate('date', $date)
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $absences = Absence::whereDate('date', $date)
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $planning = Planning::whereDate('date', $date)
            ->whereIn('employee_id', $employeeIds)
            ->get()
            ->groupBy('employee_id');

        return $employees->map(function ($employee) use ($attendances, $absences, $planning, $date) {

            $attendance = $attendances->get($employee->user_id);
            $absence = $absences->get($employee->user_id);

            $roles = $this->normalizePlanningRoles(
                $planning->get($employee->id, collect())->pluck('role')
            );

            return (object)[
                'employee' => $employee,
                'date' => $date,
                'attendance' => $attendance,
                'status' => $this->finalStatus($attendance, $roles, $absence),
                'planning_label' => $this->planningLabel($roles),
                'check_in_time' => $attendance?->check_in_time,
                'check_out_time' => $attendance?->check_out_time,
                'worked_minutes' => $this->workedMinutes($attendance, $date),
                'absence_reason' => $absence?->reason,
            ];
        });
    }

    /* ================= MONTHLY ================= */

    public function buildMonthlyRows(Collection $employees, Carbon|string $start, Carbon|string $end): Collection
    {
        $start = Carbon::parse($start)->toDateString();
        $end = Carbon::parse($end)->toDateString();

        $userIds = $employees->pluck('user_id')->filter()->values();
        $employeeIds = $employees->pluck('id')->filter()->values();

        $attendances = Attendance::whereBetween('date', [$start, $end])
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy(fn ($a) => $a->user_id . '|' . Carbon::parse($a->date)->toDateString());

        $absences = Absence::whereBetween('date', [$start, $end])
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy(fn ($a) => $a->user_id . '|' . Carbon::parse($a->date)->toDateString());

        $planning = Planning::whereBetween('date', [$start, $end])
            ->whereIn('employee_id', $employeeIds)
            ->get()
            ->groupBy(fn ($p) => $p->employee_id . '|' . Carbon::parse($p->date)->toDateString());

        $rows = collect();

        foreach (CarbonPeriod::create($start, $end) as $date) {
            $dateKey = $date->toDateString();

            foreach ($employees as $employee) {

                $attendance = $attendances->get($employee->user_id . '|' . $dateKey);
                $absence = $absences->get($employee->user_id . '|' . $dateKey);

                $roles = $planning->get($employee->id . '|' . $dateKey, collect())
                    ->pluck('role');

              $rows->push((object)[
    'employee' => $employee,
    'date' => $dateKey,
    'status' => $this->finalStatus($attendance, $roles, null),

    // 🔥 مهم بزاف
    'attendance' => $attendance,

    'worked_minutes' => $this->workedMinutes($attendance, $dateKey),

                ]);
            }
        }

        return $rows;
    }

    /* ================= FINAL STATUS ================= */

    public function finalStatus(?Attendance $attendance, Collection|array $roles, ?Absence $absence = null): string
    {
        $roles = $this->normalizePlanningRoles($roles);

        if ($absence) {
            return self::STATUS_ABSENT;
        }

        if ($roles->contains(self::ROLE_CONGE)) {
            return self::STATUS_CONGE;
        }

        if ($roles->contains(self::ROLE_REPOS)) {
            return self::STATUS_REPOS;
        }

        if ($roles->intersect(self::WORK_ROLES)->isEmpty()) {
            return self::STATUS_NOT_PLANNED;
        }

        if ($attendance?->check_in_time) {
            return self::STATUS_PRESENT;
        }

        return self::STATUS_ABSENT;
    }

    /* ================= HELPERS ================= */

    public function normalizePlanningRoles(Collection|array $roles): Collection
    {
        return collect($roles)
            ->map(fn ($r) => $this->normalizeRole($r))
            ->filter()
            ->unique()
            ->values();
    }

    private function normalizeRole($role): ?string
    {
        if (!$role) return null;

        $role = strtoupper(trim($role));
        $role = str_replace(['É', 'È', 'Ê', 'Ô', 'Ç'], ['E','E','E','O','C'], $role);

        return match ($role) {
            'CAISSE', 'GUICHET', 'CONTROLE' => $role,
            'REPOS' => self::ROLE_REPOS,
            'CONGE' => self::ROLE_CONGE,
            default => null,
        };
    }

    public function planningLabel(Collection $roles): string
    {
        if ($roles->contains(self::ROLE_CONGE)) return "CONGÉ";
        if ($roles->contains(self::ROLE_REPOS)) return "REPOS";

        return $roles->implode(' / ');
    }

    private function workedMinutes(?Attendance $attendance, string $date): int
    {
        if (!$attendance?->check_in_time || !$attendance?->check_out_time) {
            return 0;
        }

        $start = Carbon::parse($date . ' ' . $attendance->check_in_time);
        $end = Carbon::parse($date . ' ' . $attendance->check_out_time);

        if ($end->lessThan($start)) {
            $end->addDay();
        }

        return $start->diffInMinutes($end);
    }
}