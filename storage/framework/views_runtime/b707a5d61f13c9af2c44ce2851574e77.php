<?php echo csrf_field(); ?>

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Nom</label>
        <input class="form-control control-lg" name="name"
               value="<?php echo e(old('name', $employee->name ?? '')); ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Code PIN</label>
        <input class="form-control control-lg" name="pin_code"
               value="<?php echo e(old('pin_code', $employee->user->pin_code ?? '')); ?>" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input class="form-control control-lg" type="email" name="email"
               value="<?php echo e(old('email', $employee->email ?? '')); ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Téléphone</label>
        <input class="form-control control-lg" name="phone"
               value="<?php echo e(old('phone', $employee->phone ?? '')); ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Poste</label>
        <input class="form-control control-lg" name="position"
               value="<?php echo e(old('position', $employee->position ?? '')); ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Département</label>
        <input class="form-control control-lg" name="department"
               value="<?php echo e(old('department', $employee->department ?? '')); ?>">
    </div>

    <div class="col-md-6">
        <label class="form-label">Statut</label>
        <select class="form-select control-lg" name="status" required>
            <?php $__currentLoopData = ['active' => 'Actif', 'inactive' => 'Inactif']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($value); ?>"
                    <?php if(old('status', $employee->status ?? 'active') === $value): echo 'selected'; endif; ?>>
                    <?php echo e($label); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Date d’embauche</label>
        <input class="form-control control-lg" type="date" name="hire_date"
               value="<?php echo e(old('hire_date', isset($employee) && $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '')); ?>">
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mt-4">
    <button class="btn btn-primary px-4"><?php echo e($button); ?></button>
    <a class="btn btn-outline-secondary px-4" href="<?php echo e(route('employees.index')); ?>">Annuler</a>
</div>
<?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views\employees\_form.blade.php ENDPATH**/ ?>