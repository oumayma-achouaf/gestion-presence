<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plannings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('period', ['MATIN', 'SOIR']);
            $table->enum('role', ['CAISSE', 'GUICHET', 'CONTRÔLE', 'REPOS', 'CONGE']);
            $table->timestamps();

            $table->unique(['employee_id', 'date', 'period'], 'plannings_employee_date_period_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plannings');
    }
};
