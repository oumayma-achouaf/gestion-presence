<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Planning extends Model
{
    public const PERIODS = ['MATIN', 'SOIR'];

    public const ROLES = ['CAISSE', 'GUICHET', 'CONTRÔLE', 'REPOS', 'CONGE'];

    protected $fillable = [
        'employee_id',
        'date',
        'period',
        'role',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
