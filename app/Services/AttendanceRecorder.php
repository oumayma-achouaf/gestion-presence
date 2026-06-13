<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceRecorder
{
    public function checkIn(User $user, ?Carbon $time = null): Attendance
    {
        $time ??= now();
        $date = $time->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $date)
            ->first();

        // ❌ منع إعادة check-in
        if ($attendance && $attendance->check_in_time) {
            return $attendance;
        }

        return Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'date' => $date,
            ],
            [
                'check_in_time' => $time->format('H:i:s'),
                'check_out_time' => null,
                'status' => 'present',
                'worked_minutes' => 0,
            ]
        );
    }

    public function checkOut(User $user, ?Carbon $time = null): Attendance
    {
        $time ??= now();
        $date = $time->toDateString();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $date)
            ->first();

        // ❌ ما كاينش check-in → ما ندير والو
        if (! $attendance || ! $attendance->check_in_time) {
            return $attendance ?? new Attendance();
        }

        // ❌ منع إعادة check-out
        if ($attendance->check_out_time) {
            return $attendance;
        }

        $checkIn = Carbon::parse($date . ' ' . $attendance->check_in_time);
        $checkOut = Carbon::parse($date . ' ' . $time->format('H:i:s'));

        if ($checkOut->lessThan($checkIn)) {
            $checkOut->addDay();
        }

        $workedMinutes = $checkIn->diffInMinutes($checkOut);

        $attendance->update([
            'check_out_time' => $time->format('H:i:s'),
            'worked_minutes' => $workedMinutes,
            'status' => 'present',
        ]);

        return $attendance;
    }
}