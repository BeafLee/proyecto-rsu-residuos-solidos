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
        Schema::create('maintenance_schedules', function (Blueprint $table) {
            $table->id();
            //tipo de mantenimiento (LIMPIEZA O REPARACIÓN)
            $table->enum('type', ['Limpieza', 'Reparación']);
            // Dia de la semana (Lunes - Sabado)
            $table->enum('day_of_week', ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado']);
            $table->time('start_time');
            $table->time('end_time');

            $table->foreignId('maintenance_id')->constrained('maintenances')->onDelete('restrict');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('restrict');
            $table->foreignId('vehicle_id')->constrained('vehicles')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_schedules');
    }
};
