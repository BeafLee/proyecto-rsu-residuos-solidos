<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',   
        'day_of_week',
        'start_time',
        'end_time',
        'maintenance_id',
        'employee_id',
        'vehicle_id',
    ];

    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
