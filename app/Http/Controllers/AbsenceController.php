<?php

namespace App\Http\Controllers;

use App\Models\Absence;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AbsenceController extends Controller
{
    public function index(Request $request): View
    {
        $request->validate([
            'date' => ['nullable', 'date'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);

        $date = $request->input('date');
        $search = $request->string('search')->toString();

        $employees = Employee::active()
            ->with('user')
            ->orderBy('name')
            ->get();

        $absences = Absence::with('user.employee')
            ->when($date, fn ($query) => $query->whereDate('date', $date))
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user.employee', function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%");
                });
            })
            ->latest('date')
            ->paginate(12)
            ->withQueryString();

        // ======================
        // 📊 STATS
        // ======================
        $stats = [
            'today' => Absence::whereDate('date', today())->count(),
            'month' => Absence::whereMonth('date', now()->month)
                ->whereYear('date', now()->year)
                ->count(),
            'total' => Absence::count(),
        ];

        // ======================
        // 📈 CHART DATA
        // ======================
        $chartData = Absence::selectRaw('DATE(date) as date, COUNT(*) as total')
              ->whereMonth('date', now()->month)
              ->whereYear('date', now()->year)
              ->groupBy('date')
              ->orderBy('date')
              ->get();

        return view('absences.index', compact(
            'absences',
            'date',
            'employees',
            'search',
            'stats',
            'chartData'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'reason' => ['required', 'string', 'max:255'],
        ]);

        Absence::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'date' => $validated['date'],
            ],
            ['reason' => $validated['reason']]
        );

        Attendance::updateOrCreate(
            [
                'user_id' => $validated['user_id'],
                'date' => $validated['date'],
            ],
            [
                'check_in_time' => null,
                'check_out_time' => null,
                'status' => null,
                'worked_minutes' => 0,
            ]
        );

        return back()->with('success', 'Absence saved successfully.');
    }

    public function destroy(Absence $absence): RedirectResponse
    {
        $absence->delete();

        return back()->with('success', 'Absence deleted successfully.');
    }
}
