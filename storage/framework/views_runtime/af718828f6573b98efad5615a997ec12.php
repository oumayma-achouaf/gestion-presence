<?php $__env->startSection('title', 'Employés | Gestion des Absences'); ?>
<?php $__env->startSection('page-title', 'Employés'); ?>

<?php $__env->startSection('content'); ?>

<div class="page-head">
    <div>
        <div class="page-kicker">Répertoire</div>
        <h1 class="page-heading">Employés</h1>
        <div class="page-subtitle">Gérez les profils, PIN et statuts des collaborateurs.</div>
    </div>

    <a class="btn btn-primary px-4 py-2" href="<?php echo e(route('employees.create')); ?>">
        + Ajouter un employé
    </a>
</div>

<div class="card-box">
    <div class="table-toolbar">
        <div>
            <h2 class="section-title">Liste des employés</h2>
            <div class="section-sub"><?php echo e($employees->total() ?? $employees->count()); ?> profils trouvés</div>
        </div>

        <form method="GET" action="<?php echo e(route('employees.index')); ?>" class="d-flex flex-wrap gap-2">
            <input class="form-control search-field"
                   name="search"
                   value="<?php echo e($search); ?>"
                   placeholder="Rechercher un employé">

            <button class="btn btn-outline-primary px-4">
                Rechercher
            </button>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Poste</th>
                    <th>Statut</th>
                    <th>Date d’embauche</th>
                    <th>PIN</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <div class="employee-cell">
                                <span class="employee-name"><?php echo e($employee->name); ?></span>
                                <span class="employee-meta"><?php echo e($employee->department ?? 'Personnel'); ?></span>
                            </div>
                        </td>
                        <td><?php echo e($employee->email ?? '-'); ?></td>
                        <td><?php echo e($employee->phone ?? '-'); ?></td>
                        <td><?php echo e($employee->position ?? '-'); ?></td>

                        <td>
                            <span class="status-badge <?php echo e($employee->status === 'active' ? 'status-badge--present' : 'status-badge--default'); ?>">
                                <?php echo e($employee->status === 'active' ? 'Actif' : 'Inactif'); ?>

                            </span>
                        </td>

                        <td><?php echo e($employee->hire_date?->format('Y-m-d') ?? '-'); ?></td>

                        <td class="fw-semibold text-muted">
                            <?php echo e($employee->user?->pin_code ?? '-'); ?>

                        </td>

                        <td class="text-end">
                            <div class="d-inline-flex flex-wrap justify-content-end gap-2">
                                <a class="btn btn-sm btn-outline-primary"
                                   href="<?php echo e(route('employees.edit', $employee)); ?>">
                                    Modifier
                                </a>

                                <form method="POST"
                                      action="<?php echo e(route('employees.destroy', $employee)); ?>"
                                      onsubmit="return confirm('Supprimer cet employé ?')">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button class="btn btn-sm btn-outline-danger">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center text-secondary py-4">
                            Aucun employé trouvé.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        <?php echo e($employees->links()); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views/employees/index.blade.php ENDPATH**/ ?>