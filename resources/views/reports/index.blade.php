@extends('layouts.app')

@section('title', 'Rapports | Gestion des Absences')
@section('page-title', 'Tableau des rapports')

@section('content')

<style>
.report-group{
    border:1px solid #eef2f7;
    border-radius:18px;
    background:#fff;
    overflow:hidden;
    box-shadow:0 12px 26px rgba(15,23,42,0.04);
}

.report-group-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:16px 18px;
    background:#f8fafc;
    border-bottom:1px solid #eef2f7;
}
</style>

<div class="page-head">
    <div>
        <div class="page-kicker">Analyse</div>
        <h1 class="page-heading">Rapports</h1>
        <div class="page-subtitle">
            Synthèse quotidienne et historique mensuel.
        </div>
    </div>
</div>

@php
    $metrics = [
        [
            'label' => 'Employés',
            'value' => $summary['employees'] ?? 0,
            'subtitle' => 'Effectif suivi',
            'class' => 'metric-primary',
            'path' => 'M8 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm8 2a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM2 21a6 6 0 0 1 12 0M14 18a5 5 0 0 1 8 3',
        ],
        [
            'label' => 'Présents',
            'value' => $summary['present'] ?? 0,
            'subtitle' => 'Présences du jour',
            'class' => 'metric-present',
            'path' => 'M8.5 12.5 11 15l5-6',
        ],
        [
            'label' => 'Absents',
            'value' => $summary['absent'] ?? 0,
            'subtitle' => 'Absences du jour',
            'class' => 'metric-absent',
            'path' => 'M9 9l6 6m0-6-6 6',
        ],
        [
            'label' => 'Congé',
            'value' => $summary['conge'] ?? 0,
            'subtitle' => 'Congés affichés',
            'class' => 'metric-conge',
            'path' => 'M8 7h8m-8 4h8m-8 4h4',
        ],
        [
            'label' => 'Repos',
            'value' => $summary['repos'] ?? 0,
            'subtitle' => 'Repos affichés',
            'class' => 'metric-repos',
            'path' => 'M8 12h8',
        ],
    ];
@endphp

<div class="metric-grid">
    @foreach ($metrics as $metric)

        <div class="metric-card {{ $metric['class'] }}">

            <div class="metric-top">

                <div class="metric-label">
                    {{ $metric['label'] }}
                </div>

                <div class="metric-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path
                            d="{{ $metric['path'] }}"
                            stroke="currentColor"
                            stroke-width="2.2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

            </div>

            <div class="metric-number">
                {{ $metric['value'] }}
            </div>

            <div class="metric-subtitle">
                {{ $metric['subtitle'] }}
            </div>

        </div>

    @endforeach
</div>

<div class="card-box mb-4">

    <div class="panel-head">
        <div>
            <h2 class="section-title">Filtres</h2>

            <div class="section-sub">
                Choisir le jour et le mois à afficher
            </div>
        </div>
    </div>

    <form
        method="GET"
        action="{{ route('reports.index') }}"
        class="row g-3 align-items-end"
    >

        <div class="col-lg-5">
            <label class="form-label">Jour</label>

            <input
                class="form-control control-lg"
                name="day"
                type="date"
                value="{{ $day }}"
            >
        </div>

        <div class="col-lg-5">
            <label class="form-label">Mois</label>

            <input
                class="form-control control-lg"
                name="month"
                type="month"
                value="{{ $month }}"
            >
        </div>

        <div class="col-lg-2">
            <button class="btn btn-primary w-100 control-lg">
                Afficher
            </button>
        </div>

    </form>

</div>



<div class="card-box">

    <div class="panel-head">
        <div>
            <h2 class="section-title">
                Rapport mensuel
            </h2>

            <div class="section-sub">
                {{ $month }}
            </div>
        </div>
    </div>

    <div class="d-flex flex-column gap-3">

        @forelse ($reportRows as $employeeName => $rows)

            @php
                $totalHours = round($rows->sum('worked_minutes') / 60, 2);
            @endphp

            <div class="report-group">

                <div class="report-group-head">

                    <div class="employee-cell">

                        <span class="employee-name">
                            {{ $employeeName }}
                        </span>

                        <span class="employee-meta">
                            {{ $rows->count() }} lignes
                        </span>

                    </div>

                    <span class="status-badge status-badge--default">
                        {{ $totalHours }} h
                    </span>

                </div>

                <div class="p-3">

                    <div class="table-responsive">

                        <table class="table table-sm align-middle">

                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Statut</th>
                                    <th>Entrée</th>
                                    <th>Sortie</th>
                                    <th>Heures</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($rows as $attendance)

                                    @php

                                        $s = $attendance->status ?? 'Absent';

                                        $sKey = strtolower((string) $s);

                                        $sClass = match(true) {

                                            str_contains($sKey, 'present')
                                                => 'status-badge--present',

                                            str_contains($sKey, 'absent')
                                                => 'status-badge--absent',

                                            str_contains($sKey, 'repos')
                                                => 'status-badge--repos',

                                            str_contains($sKey, 'cong')
                                                => 'status-badge--conge',

                                            str_contains($sKey, 'maladie')
                                                => 'status-badge--maladie',

                                            str_contains($sKey, 'late')
                                                => 'status-badge--late',

                                            str_contains($sKey, 'not_planned')
                                                => 'status-badge--default',

                                            default
                                                => 'status-badge--default',
                                        };

                                        $sLabel = match(true) {

                                            str_contains($sKey, 'present')
                                                => 'Présent',

                                            str_contains($sKey, 'absent')
                                                => 'Absent',

                                            str_contains($sKey, 'repos')
                                                => 'Repos',

                                            str_contains($sKey, 'cong')
                                                => 'Congé',

                                            str_contains($sKey, 'maladie')
                                                => 'Maladie',

                                            str_contains($sKey, 'late')
                                                => 'En retard',

                                            str_contains($sKey, 'not_planned')
                                                => 'Non planifié',

                                            default
                                                => $s,
                                        };

                                    @endphp

                                    <tr>

                                        <td class="fw-semibold">
                                            {{ \Carbon\Carbon::parse($attendance->date)->format('Y-m-d') }}
                                        </td>

                                        <td>
                                            <span class="status-badge {{ $sClass }}">
                                                {{ $sLabel }}
                                            </span>
                                        </td>

                                        <td>

                                         {{ $attendance->attendance?->check_in_time
    ? \Carbon\Carbon::parse($attendance->attendance->check_in_time)->format('H:i')
    : '-' }}

                                        </td>

                                        <td>

                                         {{ $attendance->attendance?->check_out_time
    ? \Carbon\Carbon::parse($attendance->attendance->check_out_time)->format('H:i')
    : '-' }}

                                        </td>

                                        <td>
                                            {{ round(($attendance->worked_minutes ?? 0) / 60, 2) }} h
                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        @empty

            <div class="text-muted">
                Aucun rapport mensuel disponible.
            </div>

        @endforelse

    </div>

</div>

@endsection