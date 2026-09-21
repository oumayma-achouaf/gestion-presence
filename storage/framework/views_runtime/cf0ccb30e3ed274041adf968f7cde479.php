<tr data-period="<?php echo e($period); ?>">
    <th class="planning-employee-cell">
        <span class="employee-row-label"><?php echo e(strtoupper($employee->name)); ?></span>
    </th>

    <?php $__currentLoopData = $days; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $dateKey = $day->toDateString();
            $selected = old(
                "schedule.{$period}.{$employee->id}.{$dateKey}",
                $planningMatrix->get($employee->id)?->get($dateKey)?->get($period)?->role
            );
            $roleClass = match ($selected) {
                'REPOS' => 'role-repos',
                'CONGE' => 'role-conge',
                'CONTRÔLE' => 'role-controle',
                default => '',
            };
        ?>

        <td class="<?php echo e($roleClass); ?>">
            <select
                class="planning-select planning-role-select"
                data-date="<?php echo e($dateKey); ?>"
                name="schedule[<?php echo e($period); ?>][<?php echo e($employee->id); ?>][<?php echo e($dateKey); ?>]"
                aria-label="<?php echo e($employee->name); ?> <?php echo e($period); ?> <?php echo e($dateKey); ?>"
                onchange="this.parentElement.className = this.value === 'REPOS' ? 'role-repos' : (this.value === 'CONGE' ? 'role-conge' : (this.value === 'CONTRÔLE' ? 'role-controle' : ''))"
            >
                <option value=""></option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $roleLabel = [
                            'CONGE' => 'CONGÉ',
                        ][$role] ?? $role;
                    ?>
                    <option value="<?php echo e($role); ?>" <?php if($selected === $role): echo 'selected'; endif; ?>><?php echo e($roleLabel); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </td>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</tr>
<?php /**PATH C:\xa\htdocs\gestion-d-present\presence-system\resources\views/planning/partials/employee-row.blade.php ENDPATH**/ ?>