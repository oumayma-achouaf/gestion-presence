<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Espace employé | Gestion des Absences</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --bg:#f6f8fc;
    --card:#ffffff;
    --text:#0f172a;
    --muted:#64748b;
    --border:#e2e8f0;
    --primary:#2563eb;
    --success:#22c55e;
    --warning:#f59e0b;
    --danger:#ef4444;
    --radius:16px;
}

body{
    background:var(--bg);
    font-family:Arial, sans-serif;
    color:var(--text);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
}

.container-box{
    width:min(92vw, 760px);
}

.card-box{
    background:var(--card);
    border:1px solid var(--border);
    border-radius:var(--radius);
    padding:28px;
    box-shadow:0 10px 30px rgba(15,23,42,0.06);
}

/* HEADER */
.top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    margin-bottom:22px;
}

.subtitle{
    font-size:11px;
    text-transform:uppercase;
    color:var(--muted);
    font-weight:700;
    letter-spacing:1px;
}

.name{
    font-size:24px;
    font-weight:900;
}

/* TABLE */
.table{
    margin-bottom:0;
}

.table th{
    color:var(--muted);
    font-size:13px;
    width:150px;
}

.table td{
    font-weight:600;
}

/* BADGES CLEAN */
.badge-soft{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
}

.badge-present{ background:rgba(34,197,94,0.12); color:var(--success); }
.badge-late{ background:rgba(245,158,11,0.12); color:var(--warning); }
.badge-absent{ background:rgba(239,68,68,0.12); color:var(--danger); }
.badge-repos{ background:#ede9fe; color:#5b21b6; }
.badge-conge{ background:#ffedd5; color:#c2410c; }
.badge-maladie{ background:#e0e7ff; color:#4338ca; }
.badge-default{ background:#f1f5f9; color:#475569; }

/* BUTTONS */
.actions{
    display:flex;
    gap:12px;
    margin-top:22px;
}

.btn{
    border-radius:14px;
    font-weight:700;
    padding:14px;
}

.btn-primary{
    background:var(--primary);
    border:none;
}

.btn-outline-primary{
    border:1px solid var(--primary);
    color:var(--primary);
}

.btn-outline-primary:hover{
    background:var(--primary);
    color:#fff;
}
</style>
</head>

<body>
<?php
    $uiMessage = function (?string $message): string {
        return [
            'Check-in recorded successfully.' => 'Entrée enregistrée avec succès.',
            'Check-out recorded successfully.' => 'Sortie enregistrée avec succès.',
        ][$message ?? ''] ?? (string) $message;
    };

?>

<div class="container-box">

<div class="card-box">

    <!-- HEADER -->
    <div class="top">
        <div>
            <div class="subtitle">Gestion des Absences</div>
            <div class="name"><?php echo e($user->name); ?></div>
        </div>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>
            <button class="btn btn-outline-secondary btn-sm">
                Déconnexion
            </button>
        </form>
    </div>

    <!-- SUCCESS -->
    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e($uiMessage(session('success'))); ?>

        </div>
    <?php endif; ?>

    <!-- INFO -->
    <div class="bg-light rounded-4 p-3 mb-3">

        <table class="table">

            <tr>
                <th>Date</th>
                <td><?php echo e(today()->format('Y-m-d')); ?></td>
            </tr>

            <tr>
                <th>Statut</th>
                <?php
                    $status = $finalStatus ?? $attendance?->status ?? $attendance?->derivedStatus() ?? 'absent';
                    $statusKey = strtolower((string) $status);
                    $statusClass = match(true) {
                        str_contains($statusKey, 'present') => 'badge-present',
                        str_contains($statusKey, 'absent') => 'badge-absent',
                        str_contains($statusKey, 'repos') => 'badge-repos',
                        str_contains($statusKey, 'cong') => 'badge-conge',
                        str_contains($statusKey, 'maladie') => 'badge-maladie',
                        str_contains($statusKey, 'late') => 'badge-late',
                        default => 'badge-default',
                    };
                    $statusLabel = match(true) {
                        str_contains($statusKey, 'present') => 'Présent',
                        str_contains($statusKey, 'absent') => 'Absent',
                        str_contains($statusKey, 'repos') => 'Repos',
                        str_contains($statusKey, 'cong') => 'Congé',
                        str_contains($statusKey, 'maladie') => 'Maladie',
                        str_contains($statusKey, 'late') => 'En retard',
                        default => $status,
                    };
                ?>

                <td>
                    <span class="badge-soft <?php echo e($statusClass); ?>">
                        <?php echo e($statusLabel); ?>

                    </span>
                </td>
            </tr>

           <tr>
    <th>Entrée</th>
    <td>
        <?php echo e($attendance?->check_in_time
            ? \Carbon\Carbon::parse($attendance->check_in_time)->format('H:i')
            : '-'); ?>

    </td>
</tr>

<tr>
    <th>Sortie</th>
    <td>
        <?php echo e($attendance?->check_out_time
            ? \Carbon\Carbon::parse($attendance->check_out_time)->format('H:i')
            : '-'); ?>

    </td>
</tr>

            <tr>
                <th>Minutes travaillées</th>
                <td class="fw-bold"><?php echo e($attendance?->worked_minutes ?? 0); ?></td>
            </tr>

        </table>

    </div>

    <!-- ACTIONS -->
    <div class="actions">

        <form method="POST" action="<?php echo e(route('employee.check-in')); ?>" class="flex-fill">
            <?php echo csrf_field(); ?>
            <button class="btn btn-primary w-100">
                Entrée
            </button>
        </form>

        <form method="POST" action="<?php echo e(route('employee.check-out')); ?>" class="flex-fill">
            <?php echo csrf_field(); ?>
            <button class="btn btn-outline-primary w-100">
                Sortie
            </button>
        </form>

    </div>

</div>

</div>

</body>
</html>
<?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views\employee\attendance.blade.php ENDPATH**/ ?>