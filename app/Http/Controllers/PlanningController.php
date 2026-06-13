<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Planning;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PlanningController extends Controller
{
    public function index(Request $request): View
    {
        $weekStart = $this->weekStart($request);

        return view('planning.index', $this->planningData($weekStart));
    }

    public function bulkSave(Request $request): RedirectResponse
    {
        $request->validate([
            'week' => ['required', 'date'],
            'schedule' => ['nullable', 'array'],
        ]);

        $weekStart = Carbon::parse($request->input('week'))->startOfWeek();
        $weekDates = collect(range(0, 6))
            ->map(fn (int $offset) => $weekStart->copy()->addDays($offset)->toDateString())
            ->all();

        $employeeIds = Employee::pluck('id')->map(fn ($id) => (string) $id)->all();
        $schedule = $request->input('schedule', []);

        DB::transaction(function () use ($schedule, $weekDates, $employeeIds, $weekStart) {
            Planning::whereBetween('date', [
                $weekStart->toDateString(),
                $weekStart->copy()->addDays(6)->toDateString(),
            ])->delete();

            foreach (Planning::PERIODS as $period) {
                foreach (($schedule[$period] ?? []) as $employeeId => $days) {
                    if (! in_array((string) $employeeId, $employeeIds, true)) {
                        continue;
                    }

                    foreach ((array) $days as $date => $role) {
                        $date = Carbon::parse($date)->toDateString();

                        if (! in_array($date, $weekDates, true)) {
                            continue;
                        }

                        $role = $role ?: null;

                        if ($role === null) {
                            continue;
                        }

                        if (! in_array($role, Planning::ROLES, true)) {
                            throw ValidationException::withMessages([
                                'schedule' => 'Invalid planning role selected.',
                            ]);
                        }

                        Planning::updateOrCreate(
                            [
                                'employee_id' => $employeeId,
                                'date' => $date,
                                'period' => $period,
                            ],
                            ['role' => $role],
                        );
                    }
                }
            }
        });

        return redirect()
            ->route('planning.index', ['week' => $weekStart->toDateString()])
            ->with('success', 'Weekly planning saved successfully.');
    }

    private function weekStart(Request $request): Carbon
    {
        $request->validate([
            'week' => ['nullable', 'date'],
        ]);

        return $request->filled('week')
            ? Carbon::parse($request->input('week'))->startOfWeek()
            : now()->startOfWeek();
    }

    /**
     * @return array<string, mixed>
     */
    private function planningData(Carbon $weekStart): array
    {
        $days = collect(range(0, 6))->map(fn (int $offset) => $weekStart->copy()->addDays($offset));
        $weekEnd = $weekStart->copy()->addDays(6);
        $employees = Employee::active()->with('user')->orderBy('name')->get();
        $splitAt = (int) ceil($employees->count() / 2);

        $planningMatrix = Planning::whereBetween('date', [
            $weekStart->toDateString(),
            $weekEnd->toDateString(),
        ])
            ->get()
            ->groupBy('employee_id')
            ->map(function ($employeeRows) {
                return $employeeRows
                    ->groupBy(fn (Planning $planning) => $planning->date->toDateString())
                    ->map(fn ($dateRows) => $dateRows->keyBy('period'));
            });

        return [
            'days' => $days,
            'employees' => $employees,
            'employeeOptions' => $employees,
            'periodEmployees' => [
                'MATIN' => $employees->take($splitAt)->values(),
                'SOIR' => $employees->skip($splitAt)->values(),
            ],
            'periods' => Planning::PERIODS,
            'planningMatrix' => $planningMatrix,
            'roles' => Planning::ROLES,
            'weekEnd' => $weekEnd,
            'weekStart' => $weekStart,
        ];
    }
}
