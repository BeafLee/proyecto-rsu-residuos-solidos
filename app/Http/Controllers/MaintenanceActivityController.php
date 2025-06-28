<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceActivity;
use Illuminate\Http\Request;

class MaintenanceActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, $maintenanceScheduleId)
    {
        $activities = MaintenanceActivity::where('maintenance_schedule_id', $maintenanceScheduleId)->get();
        if ($request->ajax()) {
            return response()->json(['data' => $activities]);
        }
        return view('admin.maintenance.shedule.activity.index', compact('activities', 'maintenanceScheduleId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($maintenanceScheduleId)
    {
        return view('admin.maintenance.shedule.activity.create', compact('maintenanceScheduleId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $maintenanceScheduleId)
    {
        try {
            $request->validate([
                'activity_date' => 'required|date',
                'description' => 'required|string',
                'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $activity = new MaintenanceActivity();
            $activity->activity_date = $request->activity_date;
            $activity->description = $request->description;
            $activity->maintenance_schedule_id = $maintenanceScheduleId;

            if ($request->hasFile('image_url')) {
                $path = $request->file('image_url')->store('activities', 'public');
                $activity->image_url = $path;
            }

            $activity->save();

            return response()->json(['success' => true, 'message' => 'Actividad creada exitosamente.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la actividad: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($maintenanceScheduleId, $id)
    {
        $activity = MaintenanceActivity::where('maintenance_schedule_id', $maintenanceScheduleId)->findOrFail($id);
        return response()->json(['data' => $activity]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($maintenanceScheduleId, $id)
    {
        $activity = MaintenanceActivity::findOrFail($id);
        return view('admin.maintenance.shedule.activity.edit', compact('activity', 'maintenanceScheduleId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $maintenanceScheduleId, $id)
    {
        $request->validate([
            'activity_date' => 'required|date',
            'description' => 'required|string',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        $activity = MaintenanceActivity::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('image_url')) {
            $data['image_url'] = $request->file('image_url')->store('activities', 'public');
        }
        $activity->update($data);
        return response()->json(['success' => true, 'message' => 'Actividad actualizada exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($maintenanceScheduleId, $id)
    {
        $activity = MaintenanceActivity::findOrFail($id);
        $activity->delete();
        return response()->json(['success' => true, 'message' => 'Actividad eliminada exitosamente.']);
    }
}
