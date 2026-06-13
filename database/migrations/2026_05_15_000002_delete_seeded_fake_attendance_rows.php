<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('attendances')
            ->where('check_in_time', '08:45:00')
            ->where('check_out_time', '17:00:00')
            ->where('worked_minutes', 480)
            ->where('status', 'present')
            ->delete();
    }

    public function down(): void
    {
        //
    }
};
