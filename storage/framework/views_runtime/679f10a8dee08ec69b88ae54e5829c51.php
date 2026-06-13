<?php $__env->startSection('title', 'Ajouter un employé | Gestion des Absences'); ?>
<?php $__env->startSection('page-title', 'Ajouter un employé'); ?>

<?php $__env->startSection('content'); ?>
    <div class="page-head">
        <div>
            <div class="page-kicker">Nouveau profil</div>
            <h1 class="page-heading">Ajouter un employé</h1>
            <div class="page-subtitle">Créez l’accès PIN et les informations RH principales.</div>
        </div>
    </div>

    <div class="card-box">
        <form method="POST" action="<?php echo e(route('employees.store')); ?>">
            <?php echo $__env->make('employees._form', ['button' => 'Créer l’employé'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views/employees/create.blade.php ENDPATH**/ ?>