<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('reason');
            $table->timestamps();

            $table->unique(['user_id', 'date'], 'absences_user_date_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
