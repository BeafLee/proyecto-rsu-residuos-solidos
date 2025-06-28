<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandmodelController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\VehicletypeController;
use App\Http\Controllers\ScheduleShiftController;
use App\Http\Controllers\ContracttypeController;
use Illuminate\Support\Facades\Route;


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/admin', function () {
        return view('admin.index');
    });
    // Aquí puedes agregar más rutas administrativas
});


Route::resource('brands', BrandController::class)->names('admin.brands');
Route::resource('brandmodels', BrandmodelController::class)->names('admin.brandmodels');
Route::resource('colors', ColorController::class)->names('admin.colors');
Route::resource('employees', \App\Http\Controllers\EmployeeController::class)->names('admin.employees');

Route::resource('vehicle_types', VehicleTypeController::class)->names('admin.vehicle_types');
Route::resource('schedule_shifts', ScheduleShiftController::class)->names('admin.schedule_shifts');
Route::resource('contract_types', ContracttypeController::class)->names('admin.contract_types');
Route::resource('schedule_statuses', \App\Http\Controllers\SchedulestatusController::class)->names('admin.schedule_statuses');

Route::resource('vehicles', \App\Http\Controllers\VehicleController::class)->names('admin.vehicles');
Route::resource('periods', \App\Http\Controllers\PeriodController::class)->names('admin.periods');


Route::resource('zones', \App\Http\Controllers\ZoneController::class)->names('admin.zones');
Route::post('zones/store-coords', [\App\Http\Controllers\ZoneController::class, 'storeCoords'])->name('admin.zones.storeCoords');

Route::resource('employee_functions', \App\Http\Controllers\EmployeeFunctionController::class)->names('admin.employee_functions');

Route::resource('holidays', \App\Http\Controllers\HolydaysController::class)->names('admin.holidays');

Route::resource('maintenances', \App\Http\Controllers\MaintenanceController::class)->names('admin.maintenances');
Route::get('maintenance/{maintenanceId}/schedule', [\App\Http\Controllers\MaintenanceScheduleController::class, 'index'])->name('admin.maintenance.schedule.index');
// Route::resource('maintenance_schedules', \App\Http\Controllers\MaintenanceScheduleController::class)->names('admin.maintenance.schedules');
Route::get('maintenance/{maintenanceId}/schedule/create', [\App\Http\Controllers\MaintenanceScheduleController::class, 'create'])->name('admin.maintenance.schedule.create');
Route::post('maintenance/{maintenanceId}/schedule/store', [\App\Http\Controllers\MaintenanceScheduleController::class, 'store'])->name('admin.maintenance.schedule.store');
Route::get('maintenance/{maintenanceId}/schedule/edit/{id}', [\App\Http\Controllers\MaintenanceScheduleController::class, 'edit'])->name('admin.maintenance.schedule.edit');
Route::put('maintenance/{maintenanceId}/schedule/update/{id}', [\App\Http\Controllers\MaintenanceScheduleController::class, 'update'])->name('admin.maintenance.schedule.update');
Route::delete('maintenance/{maintenanceId}/schedule/destroy/{id}', [\App\Http\Controllers\MaintenanceScheduleController::class, 'destroy'])->name('admin.maintenance.schedule.destroy');



// Route::resource('maintenance_activities', \App\Http\Controllers\MaintenanceActivityController::class)->names('admin.maintenance.activities');
Route::get('maintenance-shedules/{maintenanceScheduleId}/activities', [\App\Http\Controllers\MaintenanceActivityController::class, 'index'])->name('admin.maintenance.activities.index');
Route::get('maintenance-shedules/{maintenanceScheduleId}/activities/create', [\App\Http\Controllers\MaintenanceActivityController::class, 'create'])->name('admin.maintenance.activities.create');
Route::post('maintenance-shedules/{maintenanceScheduleId}/activities/store', [\App\Http\Controllers\MaintenanceActivityController::class, 'store'])->name('admin.maintenance.activities.store');
Route::get('maintenance-shedules/{maintenanceScheduleId}/activities/edit/{id}', [\App\Http\Controllers\MaintenanceActivityController::class, 'edit'])->name('admin.maintenance.activities.edit');
Route::put('maintenance-shedules/{maintenanceScheduleId}/activities/update/{id}', [\App\Http\Controllers\MaintenanceActivityController::class, 'update'])->name('admin.maintenance.activities.update');
Route::delete('maintenance-shedules/{maintenanceScheduleId}/activities/destroy/{id}', [\App\Http\Controllers\MaintenanceActivityController::class, 'destroy'])->name('admin.maintenance.activities.destroy');


