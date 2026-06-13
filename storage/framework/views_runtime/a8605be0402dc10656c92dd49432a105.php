<?php $__env->startSection('title', 'Absences | Gestion des Absences'); ?>
<?php $__env->startSection('page-title', 'Tableau des absences'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-head">
    <div>
        <div class="page-kicker">Suivi des motifs</div>
        <h1 class="page-heading">Absences</h1>
        <div class="page-subtitle">Consultez, filtrez et enregistrez les absences.</div>
    </div>
</div>

<div class="metric-grid">
    <div class="metric-card metric-absent">
        <div class="metric-top">
            <div class="metric-label">Aujourd’hui</div>
            <div class="metric-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9 9l6 6m0-6-6 6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?php echo e($stats['today']); ?></div>
        <div class="metric-subtitle">Absences du jour</div>
    </div>

    <div class="metric-card metric-conge">
        <div class="metric-top">
            <div class="metric-label">Ce mois-ci</div>
            <div class="metric-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M7 4v3m10-3v3M5 10h14M7 14h4m-4 4h7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?php echo e($stats['month']); ?></div>
        <div class="metric-subtitle">Absences mensuelles</div>
    </div>

    <div class="metric-card metric-neutral">
        <div class="metric-top">
            <div class="metric-label">Total</div>
            <div class="metric-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M8 7h8m-8 5h8m-8 5h5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/>
                </svg>
            </div>
        </div>
        <div class="metric-number"><?php echo e($stats['total']); ?></div>
        <div class="metric-subtitle">Absences enregistrées</div>
    </div>
</div>

<div class="card-box mb-4">
    <div class="panel-head">
        <div>
            <h2 class="section-title">Évolution des absences</h2>
            <div class="section-sub">Tendance du mois en cours</div>
        </div>
    </div>

    <div class="soft-panel">
        <canvas id="absenceChart" height="80"></canvas>
    </div>
</div>

<div class="card-box mb-4">
    <div class="panel-head">
        <div>
            <h2 class="section-title">Ajouter une absence</h2>
            <div class="section-sub">Création d’un motif pour une date donnée</div>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('absences.store')); ?>">
        <?php echo csrf_field(); ?>

        <div class="row g-3 align-items-end">
            <div class="col-lg-4">
                <label class="form-label">Employé</label>
                <select class="form-select control-lg" name="user_id" required>
                    <option value="">Sélectionner un employé</option>
                    <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($employee->user_id); ?>">
                            <?php echo e($employee->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="col-lg-3">
                <label class="form-label">Date</label>
                <input type="date" class="form-control control-lg" name="date" value="<?php echo e(today()->toDateString()); ?>">
            </div>

            <div class="col-lg-3">
                <label class="form-label">Motif</label>
                <input type="text" class="form-control control-lg" name="reason" placeholder="Maladie, congé...">
            </div>

            <div class="col-lg-2">
                <button class="btn btn-primary w-100 control-lg">Enregistrer</button>
            </div>
        </div>
    </form>
</div>

<div class="card-box">
    <div class="table-toolbar">
        <div>
            <h2 class="section-title">Registre des absences</h2>
            <div class="section-sub">Historique filtrable</div>
        </div>

        <form method="GET" class="d-flex flex-wrap gap-2">
            <input class="form-control search-field" name="search" value="<?php echo e($search); ?>" placeholder="Rechercher un employé">
            <input type="date" class="form-control control-lg" name="date" value="<?php echo e($date); ?>">
            <button class="btn btn-outline-primary px-4">Filtrer</button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Employé</th>
                    <th>Date</th>
                    <th>Motif</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $absences; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absence): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="employee-cell">
                                <span class="employee-name"><?php echo e($absence->user?->employee?->name ?? $absence->user?->name); ?></span>
                                <span class="employee-meta"><?php echo e($absence->user?->employee?->position ?? '-'); ?></span>
                            </div>
                        </td>

                        <td class="fw-semibold text-muted">
                            <?php echo e($absence->date->format('Y-m-d')); ?>

                        </td>

                        <td>
                            <?php
                                $reasonKey = strtolower((string) $absence->reason);
                                $reasonBadge = str_contains($reasonKey, 'maladie')
                                    ? 'status-badge--maladie'
                                    : (str_contains($reasonKey, 'cong') ? 'status-badge--conge' : 'status-badge--default');
                            ?>
                            <span class="status-badge <?php echo e($reasonBadge); ?>">
                                <?php echo e($absence->reason); ?>

                            </span>
                        </td>

                        <td class="text-end">
                            <form method="POST" action="<?php echo e(route('absences.destroy', $absence)); ?>">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger">Supprimer</button>
                            </form>
                        </td>
                    </tr>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Aucune absence trouvée
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <?php echo e($absences->links()); ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const ctx = document.getElementById('absenceChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($chartData->pluck('date')); ?>,
        datasets: [{
            label: 'Absences',
            data: <?php echo json_encode($chartData->pluck('total')); ?>,
            borderWidth: 2,
            borderColor: '#2563eb',
            backgroundColor: 'rgba(37,99,235,0.08)',
            tension: 0.35,
            fill: true
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views/absences/index.blade.php ENDPATH**/ ?>