<?php $__env->startSection('title', 'Modifier un employé | Gestion des Absences'); ?>
<?php $__env->startSection('page-title', 'Modifier un employé'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <div class="page-kicker">Mise à jour</div>
            <h1 class="page-heading">Modifier un employé</h1>
            <div class="page-subtitle">Ajustez les informations du profil sans changer le flux métier.</div>
        </div>
    </div>

    <div class="card-box">
        <form method="POST" action="<?php echo e(route('employees.update', $employee)); ?>">
            <?php echo method_field('PUT'); ?>
            <?php echo $__env->make('employees._form', ['button' => 'Mettre à jour l’employé'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views\employees\edit.blade.php ENDPATH**/ ?>