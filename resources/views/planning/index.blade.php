@extends('layouts.app')

@section('title', 'Planning | Gestion des Absences')
@section('page-title', 'Planning')

@section('content')

<style>
.planning-toolbar{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}

.planning-shell{
    border-radius:18px;
    border:1px solid #eef2f7;
    background:#f8fafc;
    padding:14px;
}

.station-planning{
    width:100%;
    min-width:1120px;
    border-collapse:separate;
    border-spacing:0 8px;
}

.station-planning th{
    color:#64748b;
    font-size:11px;
    font-weight:900;
    letter-spacing:0.08em;
    text-transform:uppercase;
    padding:12px;
}

.station-planning td,
.station-planning .planning-employee-cell{
    background:#fff;
    border-top:1px solid #eef2f7;
    border-bottom:1px solid #eef2f7;
    padding:0;
    height:54px;
    vertical-align:middle;
    box-shadow:0 8px 20px rgba(15,23,42,0.04);
}

.station-planning tr:hover td,
.station-planning tr:hover .planning-employee-cell{
    background:#f8fafc;
}

.planning-employee-cell{
    width:240px;
    border-left:1px solid #eef2f7;
    border-radius:14px 0 0 14px;
}

.station-planning td:last-child{
    border-right:1px solid #eef2f7;
    border-radius:0 14px 14px 0;
}

.day-head{
    min-width:120px;
    text-align:center;
}

.day-name{
    display:block;
    color:#0f172a;
    font-size:13px;
    font-weight:900;
    letter-spacing:0;
    text-transform:capitalize;
}

.day-date{
    display:block;
    margin-top:4px;
    color:#64748b;
    font-size:12px;
    font-weight:700;
    letter-spacing:0;
}

.period-row th{
    padding:18px 12px 8px;
    color:#0f172a;
    font-size:14px;
    letter-spacing:0;
    text-align:left;
    text-transform:none;
}

.period-pill{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 12px;
    border-radius:999px;
    background:#eef2ff;
    color:#3730a3;
    font-weight:900;
}

.planning-select{
    width:100%;
    height:54px;
    border:0;
    background:transparent;
    color:#0f172a;
    font-size:13px;
    font-weight:800;
    text-align:center;
    cursor:pointer;
}

.employee-row-label{
    display:flex;
    align-items:center;
    width:100%;
    height:54px;
    padding:0 14px;
    color:#0f172a;
    font-size:13px;
    font-weight:800;
}

.planning-select:focus{
    outline:none;
    box-shadow:inset 0 0 0 3px rgba(37,99,235,0.18);
    background:#fff;
}

.role-repos select{ background:#ede9fe; color:#5b21b6; }
.role-conge select{ background:#ffedd5; color:#c2410c; }
.role-controle select{ background:#dcfce7; color:#15803d; }

.save-row{
    display:flex;
    justify-content:flex-end;
    margin-top:18px;
}

@media print{
    .sidebar,.topbar,.planning-toolbar,.save-row,.page-head{
        display:none !important;
    }

    .card-box,
    .planning-shell{
        border:0;
        box-shadow:none;
        padding:0;
    }
}
</style>

<div class="page-head">
    <div>
        <div class="page-kicker">Organisation hebdomadaire</div>
        <h1 class="page-heading">Planning</h1>
        <div class="page-subtitle">
            Du {{ $weekStart->format('d/m/Y') }} au {{ $weekEnd->format('d/m/Y') }}
        </div>
    </div>

    <div class="planning-toolbar">
        <a class="btn btn-outline-secondary" href="{{ route('planning.index', ['week' => $weekStart->copy()->subWeek()->toDateString()]) }}">Précédent</a>
        <a class="btn btn-outline-secondary" href="{{ route('planning.index', ['week' => now()->startOfWeek()->toDateString()]) }}">Semaine actuelle</a>
        <a class="btn btn-outline-secondary" href="{{ route('planning.index', ['week' => $weekStart->copy()->addWeek()->toDateString()]) }}">Suivant</a>
        <button type="button" class="btn btn-outline-primary" onclick="window.print()">Imprimer</button>
    </div>
</div>

<div class="card-box">
    <div class="panel-head">
        <div>
            <h2 class="section-title">TAZA Gare Routière</h2>
            <div class="section-sub">Affectations par période et par jour</div>
        </div>
    </div>

    <form method="POST" action="{{ route('planning.bulk-save') }}">
        @csrf
        <input type="hidden" name="week" value="{{ $weekStart->toDateString() }}">

        <div class="planning-shell table-responsive">
            <table class="station-planning">
                <thead>
                    <tr>
                        <th class="day-head text-start">Employé</th>
                        @foreach ($days as $day)
                            <th class="day-head">
                                <span class="day-name">{{ $day->locale('fr')->translatedFormat('l') }}</span>
                                <span class="day-date">{{ $day->format('d/m') }}</span>
                            </th>
                        @endforeach
                    </tr>
                </thead>

                <tbody>
                    <tr class="period-row">
                        <th colspan="8"><span class="period-pill">Matin</span></th>
                    </tr>

                    @foreach ($periodEmployees['MATIN'] as $employee)
                        @include('planning.partials.employee-row', ['period' => 'MATIN'])
                    @endforeach

                    <tr class="period-row">
                        <th colspan="8"><span class="period-pill">Soir</span></th>
                    </tr>

                    @foreach ($periodEmployees['SOIR'] as $employee)
                        @include('planning.partials.employee-row', ['period' => 'SOIR'])
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="save-row">
            <button class="btn btn-primary px-4">Enregistrer la semaine</button>
        </div>
    </form>
</div>

@endsection
