<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'activity_date',
        'description',
        'image_url',
        'maintenance_schedule_id',
    ];

    public function maintenanceSchedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }
}
