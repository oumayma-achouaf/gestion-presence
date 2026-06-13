@extends('layouts.app')

@section('title', 'Présences | Gestion des Absences')
@section('page-title', 'Gestion des présences')

@section('content')

<div class="page-head">
    <div>
        <div class="page-kicker">Pointage</div>
        <h1 class="page-heading">Présences</h1>
        <div class="page-subtitle">Suivi quotidien des entrées, sorties et statuts.</div>
    </div>

    <form method="GET" class="d-flex flex-wrap gap-2">
        <input class="form-control control-lg" type="date" name="date" value="{{ $date }}">
        <button class="btn btn-outline-primary px-4">Filtrer</button>
    </form>
</div>

<div class="card-box mb-4">
    <div class="panel-head">
        <div>
            <h2 class="section-title">Ajouter une présence</h2>
            <div class="section-sub">Enregistrement manuel d’un pointage</div>
        </div>
    </div>

    <form method="POST" action="{{ route('attendance.store') }}">
        @csrf

        <div class="row g-3 align-items-end">
            <div class="col-lg-4 col-md-6">
                <label class="form-label">Employé</label>
                <select class="form-select control-lg" name="user_id" required>
                    <option value="">Sélectionner un employé</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->user_id }}">
                            {{ $employee->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="form-label">Date</label>
                <input type="date" class="form-control control-lg" name="date" value="{{ $date }}">
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="form-label">Entrée</label>
                <input type="time" class="form-control control-lg" name="check_in_time">
            </div>

            <div class="col-lg-2 col-md-6">
                <label class="form-label">Sortie</label>
                <input type="time" class="form-control control-lg" name="check_out_time">
            </div>

            <div class="col-lg-2 col-md-12">
                <button class="btn btn-primary w-100 control-lg">Enregistrer</button>
            </div>
        </div>
    </form>
</div>

<div class="card-box">
    <div class="panel-head">
        <div>
            <h2 class="section-title">Présences du jour</h2>
            <div class="section-sub">{{ \Carbon\Carbon::parse($date)->locale('fr')->translatedFormat('d F Y') }}</div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Statut</th>
                    <th>Entrée</th>
                    <th>Sortie</th>
                    <th>Heures travaillées</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($attendanceRows as $row)
                    @php
                        $statusKey = strtolower((string) $row->status);
                        $badge = match(true) {
                            str_contains($statusKey, 'present') => 'status-badge--present',
                            str_contains($statusKey, 'absent') => 'status-badge--absent',
                            str_contains($statusKey, 'repos') => 'status-badge--repos',
                            str_contains($statusKey, 'cong') => 'status-badge--conge',
                            str_contains($statusKey, 'maladie') => 'status-badge--maladie',
                            str_contains($statusKey, 'late') => 'status-badge--late',
                            default => 'status-badge--default',
                        };
                        $statusLabel = match(true) {
                            str_contains($statusKey, 'present') => 'Présent',
                            str_contains($statusKey, 'absent') => 'Absent',
                            str_contains($statusKey, 'repos') => 'Repos',
                            str_contains($statusKey, 'cong') => 'Congé',
                            str_contains($statusKey, 'maladie') => 'Maladie',
                            str_contains($statusKey, 'late') => 'En retard',
                            default => $row->status,
                        };
                    @endphp

                    <tr>
                        <td>
                            <div class="employee-cell">
                                <span class="employee-name">{{ $row->employee->name }}</span>
                                <span class="employee-meta">{{ $row->employee->position ?? $row->employee->department ?? '-' }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="status-badge {{ $badge }}">{{ $statusLabel }}</span>
                        </td>

                        <td class="fw-semibold">{{ $row->check_in_time ?? '-' }}</td>
                        <td class="fw-semibold">{{ $row->check_out_time ?? '-' }}</td>

                        <td class="fw-bold">
                            {{ $row->worked_hours ?? 0 }} h
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Aucun enregistrement trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
