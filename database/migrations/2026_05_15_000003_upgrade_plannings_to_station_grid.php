<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('plannings')) {
            return;
        }

        // ================= ADD COLUMNS =================
        Schema::table('plannings', function (Blueprint $table) {

            if (!Schema::hasColumn('plannings', 'period')) {
                $table->string('period', 10)->nullable()->after('date');
            }

            if (!Schema::hasColumn('plannings', 'role')) {
                $table->string('role', 30)->nullable()->after('period');
            }
        });

        // ================= CLEAN OLD DATA =================
        DB::table('plannings')
            ->whereNull('period')
            ->orWhereNull('role')
            ->delete();

        // ================= CONVERT TYPES (ONLY IF MYSQL) =================
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE plannings MODIFY period ENUM('MATIN', 'SOIR') NOT NULL");
            DB::statement("ALTER TABLE plannings MODIFY role ENUM('CAISSE', 'GUICHET', 'CONTRÔLE', 'REPOS') NOT NULL");
        }

        // ================= INDEX (SAFE) =================
        if (DB::getDriverName() === 'mysql') {

            // avoid duplicate index error
            $exists = collect(DB::select("
                SHOW INDEX FROM plannings WHERE Key_name = ?
            ", ['plannings_employee_date_period_unique']))->isNotEmpty();

            if (! $exists) {
                Schema::table('plannings', function (Blueprint $table) {
                    $table->unique(
                        ['employee_id', 'date', 'period'],
                        'plannings_employee_date_period_unique'
                    );
                });
            }
        }
    }

    public function down(): void
    {
        // optional rollback
    }
};