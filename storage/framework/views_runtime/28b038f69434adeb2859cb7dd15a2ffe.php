<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Espace employé | Gestion des Absences</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
:root{
    --bg:#f8fafc;
    --card:#ffffff;
    --text:#0f172a;
    --muted:#64748b;
    --border:#e2e8f0;
    --primary:#2563eb;
    --success:#16a34a;
    --warning:#f59e0b;
    --danger:#dc2626;
}

*{
    box-sizing:border-box;
}

body{
    margin:0;
    background:linear-gradient(180deg,#f8fafc 0%,#f1f5f9 100%);
    font-family:Inter,Arial,sans-serif;
    color:var(--text);
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:32px;
}

.container-box{
    width:min(100%,900px);
}

.card-box{
    background:#fff;
    border:1px solid rgba(226,232,240,.95);
    border-radius:24px;
    padding:32px;
    box-shadow:0 20px 45px rgba(15,23,42,.08);
}

.top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:20px;
    margin-bottom:28px;
    padding-bottom:24px;
    border-bottom:1px solid #eef2f7;
}

.subtitle{
    color:#64748b;
    font-size:12px;
    font-weight:800;
    text-transform:uppercase;
    letter-spacing:1px;
    margin-bottom:8px;
}

.name{
    font-size:32px;
    font-weight:900;
    line-height:1.1;
    color:#0f172a;
}

.alert{
    border:none;
    border-radius:16px;
    padding:16px 18px;
    font-weight:700;
    margin-bottom:20px;
}

.alert-success{
    background:#dcfce7;
    color:#166534;
}

.bg-light{
    background:#f8fafc !important;
    border:1px solid #eef2f7;
    border-radius:20px !important;
    padding:22px !important;
}

.table{
    margin-bottom:0;
}

.table tr{
    border-color:#eef2f7;
}

.table th{
    width:220px;
    border:none;
    padding:16px 8px;
    color:#64748b;
    font-size:13px;
    font-weight:800;
}

.table td{
    border:none;
    padding:16px 8px;
    color:#0f172a;
    font-weight:700;
}

.badge-soft{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
}

.badge-present{
    background:#dcfce7;
    color:#15803d;
}

.badge-late{
    background:#fef3c7;
    color:#b45309;
}

.badge-absent{
    background:#fee2e2;
    color:#b91c1c;
}

.badge-repos{
    background:#ede9fe;
    color:#5b21b6;
}

.badge-conge{
    background:#ffedd5;
    color:#c2410c;
}

.badge-maladie{
    background:#e0e7ff;
    color:#4338ca;
}

.badge-default{
    background:#f1f5f9;
    color:#475569;
}

.actions{
    display:flex;
    gap:16px;
    margin-top:28px;
}

.btn{
    height:56px;
    border-radius:16px;
    font-size:15px;
    font-weight:800;
    transition:.2s;
}

.btn-primary{
    background:#2563eb;
    border:none;
    box-shadow:0 10px 20px rgba(37,99,235,.20);
}

.btn-primary:hover{
    transform:translateY(-2px);
}

.btn-outline-primary{
    border:2px solid #2563eb;
    color:#2563eb;
    background:#fff;
}

.btn-outline-primary:hover{
    background:#2563eb;
    color:#fff;
}

.btn-outline-secondary{
    border-radius:12px;
    font-weight:700;
}

@media(max-width:768px){

    body{
        padding:16px;
    }

    .card-box{
        padding:22px;
    }

    .top{
        flex-direction:column;
        align-items:flex-start;
    }

    .actions{
        flex-direction:column;
    }

    .name{
        font-size:26px;
    }

    .table th{
        width:140px;
    }
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
<td class="fw-semibold">
    <?php if($attendance && $attendance->worked_minutes > 0): ?>
        <?php echo e(number_format($attendance->worked_minutes / 60, 2)); ?> h
    <?php else: ?>
        -
    <?php endif; ?>
</td>            </tr>

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
<?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views/employee/attendance.blade.php ENDPATH**/ ?>