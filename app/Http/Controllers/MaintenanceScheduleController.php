<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\Employee;
use App\Models\Vehicle;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $maintenanceId)
    {
        $maintenanceSchedules = MaintenanceSchedule::with(['employee', 'vehicle'])
            ->where('maintenance_id', $maintenanceId)
            ->get();
        if ($request->ajax()) {
            return response()->json(['data' => $maintenanceSchedules]);
        }
        return view('admin.maintenance.shedule.index', compact('maintenanceSchedules', 'maintenanceId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($maintenanceId)
    {
        $employees = Employee::pluck('names', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        return view('admin.maintenance.shedule.create', compact('maintenanceId', 'employees', 'vehicles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $maintenanceId)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'employee_id' => 'required|exists:employees,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);
        $data = $request->all();
        $data['maintenance_id'] = $maintenanceId;
        MaintenanceSchedule::create($data);
        return response()->json(['success' => true, 'message' => 'Horario creado exitosamente.']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $schedule = MaintenanceSchedule::with(['employee', 'vehicle'])
            ->findOrFail($id);
        return response()->json(['data' => $schedule]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($maintenanceId, $id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $employees = Employee::pluck('names', 'id');
        $vehicles = Vehicle::pluck('name', 'id');
        return view('admin.maintenance.shedule.edit', compact('schedule', 'maintenanceId', 'employees', 'vehicles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|max:50',
            'day_of_week' => 'required|string|max:20',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'employee_id' => 'required|exists:employees,id',
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->update($request->all());
        return response()->json(['success' => true, 'message' => 'Horario actualizado exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = MaintenanceSchedule::findOrFail($id);
        $schedule->delete();
        return response()->json(['success' => true, 'message' => 'Horario eliminado exitosamente.']);
    }
}
