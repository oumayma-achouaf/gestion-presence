<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Planning;
use App\Services\AttendanceStatusService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function index(Request $request, AttendanceStatusService $statusService): View
    {
        $today = today()->toDateString();
        $search = $request->string('search')->toString();

        $employees = Employee::with('user')->orderBy('name')->get();

        // ================= FILTER =================
        $employees = $employees->when($search, function ($collection) use ($search) {
            return $collection->filter(function ($e) use ($search) {
                return str_contains(strtolower($e->name), strtolower($search))
                    || str_contains(strtolower((string)$e->department), strtolower($search))
                    || str_contains(strtolower((string)$e->position), strtolower($search));
            });
        })->values();

        $userIds = $employees->pluck('user_id')->filter();
        $employeeIds = $employees->pluck('id');

        // ================= DATA =================
        $attendances = Attendance::whereDate('date', $today)
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $absences = Absence::whereDate('date', $today)
            ->whereIn('user_id', $userIds)
            ->get()
            ->keyBy('user_id');

        $plannings = Planning::whereDate('date', $today)
            ->whereIn('employee_id', $employeeIds)
            ->get()
            ->groupBy('employee_id');

        // ================= DAILY ROWS =================
        $dailyRows = $employees->map(function ($employee) use (
            $attendances,
            $absences,
            $plannings,
            $today,
            $statusService
        ) {
            $attendance = $attendances->get($employee->user_id);
            $absence = $absences->get($employee->user_id);
            $planning = $plannings->get($employee->id, collect());

            $status = $statusService->finalStatus(
                $attendance,
                $planning->pluck('role'),
                $absence
            );

            $minutes = 0;

            if ($attendance?->check_in_time && $attendance?->check_out_time) {
                $start = \Carbon\Carbon::parse($today . ' ' . $attendance->check_in_time);
                $end = \Carbon\Carbon::parse($today . ' ' . $attendance->check_out_time);

                if ($end->lessThan($start)) {
                    $end->addDay();
                }

                $minutes = $start->diffInMinutes($end);
            }

            return (object)[
                'employee' => $employee,
                'status' => $status,
                'check_in_time' => $attendance?->check_in_time,
                'check_out_time' => $attendance?->check_out_time,
                'worked_minutes' => $minutes,
                'absence_reason' => $absence?->reason,
            ];
        });

        // ================= STATS =================
        $totalEmployees = $employees->count();

        $presentToday = $dailyRows->where('status', 'present')->count();
        $absentToday = $dailyRows->where('status', 'absent')->count();

        $congeToday = $dailyRows->where('status', 'conge')->count();
$reposToday = $dailyRows->where('status', 'repos')->count();

        // ================= ABSENCE MOTIFS =================
        $absenceMotifs = Absence::whereDate('date', $today)
            ->select('reason', \DB::raw('count(*) as total'))
            ->groupBy('reason')
            ->orderByDesc('total')
            ->get();
return view('admin.dashboard', compact(
    'dailyRows',
    'presentToday',
    'absentToday',
    'congeToday',
    'reposToday',
    'search',
    'today',
    'totalEmployees',
    'absenceMotifs'
));
    }
}