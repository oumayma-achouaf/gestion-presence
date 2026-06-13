<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'worked_minutes',
    ];

    // ✅ RELATION MISSING (IMPORTANT)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // SIMPLE STATUS
    public function derivedStatus(): string
    {
        return $this->check_in_time ? 'present' : 'absent';
    }

    // WORKED HOURS (SAFE)
    public function getWorkedHoursAttribute(): float
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return 0;
        }

        $start = Carbon::parse($this->date . ' ' . $this->check_in_time);
        $end = Carbon::parse($this->date . ' ' . $this->check_out_time);

        if ($end->lessThan($start)) {
            $end->addDay();
        }

        return round($start->diffInMinutes($end) / 60, 2);
    }
}
