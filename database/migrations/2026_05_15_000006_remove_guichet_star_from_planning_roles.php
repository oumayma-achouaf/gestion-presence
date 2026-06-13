<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('plannings')
            ->where('role', 'GUICHET*')
            ->update(['role' => 'GUICHET']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE plannings MODIFY role ENUM('CAISSE', 'GUICHET', 'CONTRÔLE', 'REPOS', 'CONGE') NOT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE plannings MODIFY role ENUM('CAISSE', 'GUICHET', 'GUICHET*', 'CONTRÔLE', 'REPOS', 'CONGE') NOT NULL");
        }
    }
};
