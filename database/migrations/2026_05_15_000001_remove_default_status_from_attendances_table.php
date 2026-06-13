<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'absent', 'late') NULL DEFAULT NULL");

            return;
        }

        if (Schema::hasColumn('attendances', 'status')) {
            Schema::table('attendances', function ($table) {
                $table->string('status')->nullable()->default(null)->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE attendances MODIFY status ENUM('present', 'absent', 'late') NOT NULL DEFAULT 'absent'");
        }
    }
};
