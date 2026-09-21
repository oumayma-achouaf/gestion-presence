<tr data-period="{{ $period }}">
    <th class="planning-employee-cell">
        <span class="employee-row-label">{{ strtoupper($employee->name) }}</span>
    </th>

    @foreach ($days as $day)
        @php
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
        @endphp

        <td class="{{ $roleClass }}">
            <select
                class="planning-select planning-role-select"
                data-date="{{ $dateKey }}"
                name="schedule[{{ $period }}][{{ $employee->id }}][{{ $dateKey }}]"
                aria-label="{{ $employee->name }} {{ $period }} {{ $dateKey }}"
                onchange="this.parentElement.className = this.value === 'REPOS' ? 'role-repos' : (this.value === 'CONGE' ? 'role-conge' : (this.value === 'CONTRÔLE' ? 'role-controle' : ''))"
            >
                <option value=""></option>
                @foreach ($roles as $role)
                    @php
                        $roleLabel = [
                            'CONGE' => 'CONGÉ',
                        ][$role] ?? $role;
                    @endphp
                    <option value="{{ $role }}" @selected($selected === $role)>{{ $roleLabel }}</option>
                @endforeach
            </select>
        </td>
    @endforeach
</tr>
