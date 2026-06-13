<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Planning hebdomadaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <h1 class="h4 fw-bold mb-1">Planning hebdomadaire</h1>
    <p class="text-secondary">Du <?php echo e($weekStart->format('d/m/Y')); ?> au <?php echo e($weekEnd->format('d/m/Y')); ?></p>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Employé</th>
                <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <th><?php echo e($day->locale('fr')->translatedFormat('D d/m')); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($employee->name); ?></td>
                    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $dayKey = $day->toDateString();
                            $dateRows = $planningMatrix->get($employee->id)?->get($dayKey);
                            $rolesForDay = collect($periods ?? \App\Models\Planning::PERIODS)
                                ->map(fn ($period) => $dateRows?->get($period)?->role)
                                ->filter()
                                ->map(fn ($role) => ['CONGE' => 'CONGÉ'][$role] ?? $role)
                                ->implode(' / ');
                        ?>
                        <td><?php echo e($rolesForDay ?: '-'); ?></td>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</body>
</html>
<?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views\planning\pdf.blade.php ENDPATH**/ ?>