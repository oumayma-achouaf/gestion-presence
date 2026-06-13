<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Planning hebdomadaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1 class="h4 fw-bold mb-1">Planning hebdomadaire</h1>
    <p class="text-secondary">Du {{ $weekStart->format('d/m/Y') }} au {{ $weekEnd->format('d/m/Y') }}</p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employé</th>
                @foreach ($days as $day)
                    <th>{{ $day->locale('fr')->translatedFormat('D d/m') }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employee)
                <tr>
                    <td>{{ $employee->name }}</td>
                    @foreach ($days as $day)
                        @php
                            $dayKey = $day->toDateString();
                            $dateRows = $planningMatrix->get($employee->id)?->get($dayKey);
                            $rolesForDay = collect($periods ?? \App\Models\Planning::PERIODS)
                                ->map(fn ($period) => $dateRows?->get($period)?->role)
                                ->filter()
                                ->map(fn ($role) => ['CONGE' => 'CONGÉ'][$role] ?? $role)
                                ->implode(' / ');
                        @endphp
                        <td>{{ $rolesForDay ?: '-' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
