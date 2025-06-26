<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenance_activities', function (Blueprint $table) {
            $table->id();
            // fecha de actividad
            $table->date('activity_date');
            // descipcion de actividad
            $table->text('description');
            // imagen de actividad
            $table->string('image_url')->nullable();
            // horario de mantenimiento
            $table->foreignId('maintenance_schedule_id')->constrained('maintenance_schedules')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_activities');
    }
};
