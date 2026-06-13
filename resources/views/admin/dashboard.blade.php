@extends('layouts.app')

@section('title', 'Gestion des Absences')
@section('page-title', 'Gestion des Absences')

@section('content')

<style>
.dashboard-head{
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    gap:18px;
    margin-bottom:22px;
}

.dashboard-kicker{
    color:#64748b;
    font-size:13px;
    font-weight:750;
}

.dashboard-title{
    margin:0;
    font-size:28px;
    font-weight:900;
    letter-spacing:0;
}

.metric-grid{
    display:grid;
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:18px;
    margin-bottom:24px;
}

.metric-card{
    position:relative;
    overflow:hidden;
    min-height:158px;
    padding:22px;
    border-radius:20px;
    border:1px solid rgba(226,232,240,0.95);
    background:#fff;
    box-shadow:0 18px 42px rgba(15,23,42,0.07);
}

.metric-card:after{
    content:"";
    position:absolute;
    right:-30px;
    top:-36px;
    width:112px;
    height:112px;
    border-radius:999px;
    opacity:.12;
}

.metric-card--present:after{ background:#16a34a; }
.metric-card--absent:after{ background:#dc2626; }
.metric-card--conge:after{ background:#f97316; }
.metric-card--repos:after{ background:#7c3aed; }

.metric-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    margin-bottom:18px;
}

.metric-icon{
    display:grid;
    place-items:center;
    width:44px;
    height:44px;
    border-radius:14px;
}

.metric-card--present .metric-icon{ background:#dcfce7; color:#15803d; }
.metric-card--absent .metric-icon{ background:#fee2e2; color:#b91c1c; }
.metric-card--conge .metric-icon{ background:#ffedd5; color:#c2410c; }
.metric-card--repos .metric-icon{ background:#ede9fe; color:#5b21b6; }

.metric-label{
    color:#475569;
    font-size:13px;
    font-weight:850;
    text-transform:none;
}

.metric-number{
    font-size:40px;
    line-height:1;
    font-weight:950;
    letter-spacing:0;
}

.metric-subtitle{
    margin-top:10px;
    color:#64748b;
    font-size:13px;
    font-weight:650;
}

.panel-head{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:16px;
    margin-bottom:18px;
}

.section-title{
    margin:0;
    font-size:20px;
    font-weight:900;
}

.section-sub{
    color:#64748b;
    font-size:13px;
    font-weight:650;
}

.search-box{
    width:min(100%, 340px);
    background:#fff;
    border:1px solid #dbe3ef;
    border-radius:14px;
    padding:12px 16px;
    font-size:14px;
    font-weight:600;
    outline:none;
    transition:border-color .18s ease, box-shadow .18s ease;
}

.search-box:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 4px rgba(37,99,235,0.10);
}

.employee-cell{
    display:flex;
    flex-direction:column;
    gap:3px;
}

.employee-name{
    font-weight:850;
}

.employee-meta{
    color:#64748b;
    font-size:12px;
    font-weight:600;
}

.time-cell{
    font-weight:760;
    color:#334155;
}

.motif-row{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:12px;
    padding:14px 0;
    border-bottom:1px solid #eef2f7;
}

.motif-row:last-child{
    border-bottom:0;
}

@media (max-width: 1100px){
    .metric-grid{
        grid-template-columns:repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 680px){
    .dashboard-head,
    .panel-head{
        align-items:flex-start;
        flex-direction:column;
    }

    .metric-grid{
        grid-template-columns:1fr;
    }

    .search-box{
        width:100%;
    }
}
</style>

@php
    $metrics = [
        [
            'label' => 'Présents',
            'value' => $presentToday ?? 0,
            'subtitle' => 'Employés pointés aujourd’hui',
            'tone' => 'present',
            'path' => 'M8.5 12.5 11 15l5-6',
        ],
        [
            'label' => 'Absents',
            'value' => $absentToday ?? 0,
            'subtitle' => 'Absences confirmées',
            'tone' => 'absent',
            'path' => 'M9 9l6 6m0-6-6 6',
        ],
        [
            'label' => 'Congé',
            'value' => $congeToday ?? 0,
            'subtitle' => 'Collaborateurs en congé',
            'tone' => 'conge',
            'path' => 'M8 7h8m-8 4h8m-8 4h4',
        ],
        [
            'label' => 'Repos',
            'value' => $reposToday ?? 0,
            'subtitle' => 'Collaborateurs au repos',
            'tone' => 'repos',
            'path' => 'M8 12h8',
        ],
    ];
@endphp

<div class="dashboard-head">
    <div>
        <div class="dashboard-kicker">
            {{ \Carbon\Carbon::parse($today)->locale('fr')->translatedFormat('d F Y') }}
        </div>
        <h1 class="dashboard-title">Vue quotidienne</h1>
    </div>
    <div class="section-sub">
        Total employés: <strong>{{ $totalEmployees }}</strong>
    </div>
</div>

<div class="metric-grid">
    @foreach ($metrics as $metric)
        <div class="metric-card metric-card--{{ $metric['tone'] }}">
            <div class="metric-top">
                <div class="metric-label">{{ $metric['label'] }}</div>
                <div class="metric-icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="{{ $metric['path'] }}" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/>
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" opacity=".28"/>
                    </svg>
                </div>
            </div>
            <div class="metric-number">{{ $metric['value'] }}</div>
            <div class="metric-subtitle">{{ $metric['subtitle'] }}</div>
        </div>
    @endforeach
</div>

<div class="card-box mb-4">
    <div class="panel-head">
        <div>
            <h2 class="section-title">Présences du jour</h2>
            <div class="section-sub">Entrées, sorties et motifs</div>
        </div>

        <form method="GET">
            <input class="search-box"
                   name="search"
                   value="{{ $search }}"
                   placeholder="Rechercher un employé">
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Statut</th>
                    <th>Entrée</th>
                    <th>Sortie</th>
                    <th>Heures</th>
                    <th>Motif</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($dailyRows as $row)
                    @php
                        $statusKey = strtolower((string) $row->status);
                        $badgeClass = match (true) {
                            str_contains($statusKey, 'present') => 'status-badge--present',
                            str_contains($statusKey, 'absent') => 'status-badge--absent',
                            str_contains($statusKey, 'repos') => 'status-badge--repos',
                            str_contains($statusKey, 'cong') => 'status-badge--conge',
                            str_contains($statusKey, 'maladie') => 'status-badge--maladie',
                            str_contains($statusKey, 'late') => 'status-badge--late',
                            default => 'status-badge--default',
                        };
                        $statusLabel = match (true) {
                            str_contains($statusKey, 'present') => 'Présent',
                            str_contains($statusKey, 'absent') => 'Absent',
                            str_contains($statusKey, 'repos') => 'Repos',
                            str_contains($statusKey, 'cong') => 'Congé',
                            str_contains($statusKey, 'maladie') => 'Maladie',
                            str_contains($statusKey, 'late') => 'En retard',
                            default => $row->status,
                        };
                        $reasonKey = strtolower((string) ($row->absence_reason ?? ''));
                        $reasonBadgeClass = str_contains($reasonKey, 'maladie')
                            ? 'status-badge--maladie'
                            : 'status-badge--default';
                    @endphp

                    <tr>
                        <td>
                            <div class="employee-cell">
                                <span class="employee-name">{{ $row->employee->name }}</span>
                                <span class="employee-meta">{{ $row->employee->position ?? $row->employee->department ?? '-' }}</span>
                            </div>
                        </td>

                        <td>
                            <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                        </td>

                        <td class="time-cell">
                            {{ $row->check_in_time ? \Carbon\Carbon::parse($row->check_in_time)->format('H:i') : '-' }}
                        </td>

                        <td class="time-cell">
                            {{ $row->check_out_time ? \Carbon\Carbon::parse($row->check_out_time)->format('H:i') : '-' }}
                        </td>

                        <td class="fw-semibold">
                            @if($row->worked_minutes > 0)
                                {{ intdiv($row->worked_minutes, 60) }}h {{ $row->worked_minutes % 60 }}m
                            @else
                                -
                            @endif
                        </td>

                        <td>
                            @if($row->absence_reason)
                                <span class="status-badge {{ $reasonBadgeClass }}">{{ $row->absence_reason }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucun employé trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card-box">
    <div class="panel-head mb-0">
        <div>
            <h2 class="section-title">Motifs d’absence</h2>
            <div class="section-sub">Répartition des motifs enregistrés</div>
        </div>
    </div>

    @forelse ($absenceMotifs as $motif)
        @php
            $motifKey = strtolower((string) $motif->reason);
            $motifClass = str_contains($motifKey, 'maladie')
                ? 'status-badge--maladie'
                : 'status-badge--absent';
        @endphp
        <div class="motif-row">
            <span class="status-badge {{ $motifClass }}">{{ $motif->reason }}</span>
            <span class="fw-bold">{{ $motif->total }}</span>
        </div>
    @empty
        <div class="text-muted mt-3">Aucune absence aujourd’hui</div>
    @endforelse
</div>

@endsection
