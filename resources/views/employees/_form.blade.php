@csrf

<div class="row g-4">
    <div class="col-md-6">
        <label class="form-label">Nom</label>
        <input class="form-control control-lg" name="name"
               value="{{ old('name', $employee->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Code PIN</label>
        <input class="form-control control-lg" name="pin_code"
               value="{{ old('pin_code', $employee->user->pin_code ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label">Email</label>
        <input class="form-control control-lg" type="email" name="email"
               value="{{ old('email', $employee->email ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Téléphone</label>
        <input class="form-control control-lg" name="phone"
               value="{{ old('phone', $employee->phone ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Poste</label>
        <input class="form-control control-lg" name="position"
               value="{{ old('position', $employee->position ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Département</label>
        <input class="form-control control-lg" name="department"
               value="{{ old('department', $employee->department ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Statut</label>
        <select class="form-select control-lg" name="status" required>
            @foreach (['active' => 'Actif', 'inactive' => 'Inactif'] as $value => $label)
                <option value="{{ $value }}"
                    @selected(old('status', $employee->status ?? 'active') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-6">
        <label class="form-label">Date d’embauche</label>
        <input class="form-control control-lg" type="date" name="hire_date"
               value="{{ old('hire_date', isset($employee) && $employee->hire_date ? $employee->hire_date->format('Y-m-d') : '') }}">
    </div>
</div>

<div class="d-flex flex-wrap gap-2 mt-4">
    <button class="btn btn-primary px-4">{{ $button }}</button>
    <a class="btn btn-outline-secondary px-4" href="{{ route('employees.index') }}">Annuler</a>
</div>
