<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Services\AttendanceStatusService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request, AttendanceStatusService $statusService): View
    {
        $request->validate([
            'day' => ['nullable', 'date'],
            'month' => ['nullable', 'date_format:Y-m'],
        ]);

        $day = Carbon::parse($request->input('day', today()->toDateString()))->toDateString();

        $month = $request->input('month', now()->format('Y-m'));
        $monthStart = Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
$monthEnd = Carbon::today(); 
        $employees = Employee::active()
            ->with('user')
            ->orderBy('name')
            ->get();

        $dailyRows = $statusService->buildDailyRows($employees, $day);
        $monthlyRows = $statusService->buildMonthlyRows($employees, $monthStart, $monthEnd);

        $summary = [
            'employees' => $employees->count(),
            'present' => $dailyRows->where('status', AttendanceStatusService::STATUS_PRESENT)->count(),
            'absent' => $dailyRows->where('status', AttendanceStatusService::STATUS_ABSENT)->count(),
            'late' => 0,
            'repos' => $dailyRows->where('status', AttendanceStatusService::STATUS_REPOS)->count(),
            'conge' => $dailyRows->where('status', AttendanceStatusService::STATUS_CONGE)->count(),
            'worked_hours' => round($monthlyRows->sum('worked_minutes') / 60, 2),
        ];

        $reportRows = $monthlyRows->groupBy(function (object $row) {
            return $row->employee?->name
                ?? $row->employee?->user?->name
                ?? 'Unknown employee';
        });

        return view('reports.index', compact(
            'dailyRows',
            'day',
            'month',
            'monthlyRows',
            'reportRows',
            'summary',
        ));
    }
}