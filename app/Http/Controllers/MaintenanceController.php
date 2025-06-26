<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use App\Models\MaintenanceSchedule;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $maintenances = Maintenance::all();
        if ($request->ajax()) {
            return response()->json(['data' => $maintenances]);
        } else {
            return view('admin.maintenance.index', compact('maintenances'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.maintenance.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'init_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Validar que la fecha fin no sea menor a la fecha inicio
        if ($request->end_date < $request->init_date) {
            return response()->json([
                'success' => false,
                'message' => 'La fecha de fin no puede ser menor a la fecha de inicio.'
            ], 422);
        }

        // Validar solapamiento de fechas
        $overlap = Maintenance::where(function ($query) use ($request) {
            $query->where('init_date', '<=', $request->end_date)
                ->where('end_date', '>=', $request->init_date);
        })->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'Las fechas se solapan con otro mantenimiento existente.'
            ], 422);
        }

        Maintenance::create($request->all());

        return response()->json(['success' => true, 'message' => 'Mantenimiento creado exitosamente.']);
    }

    /**
     * Display the specified resource.
     */
    // public function show($maintenanceId)
    // {
    //     $maintenanceSchedules = MaintenanceSchedule::with(['employee', 'vehicle'])
    //         ->where('maintenance_id', $maintenanceId)
    //         ->get();

    //     return view('admin.maintenance.shedule.index', compact('maintenanceSchedules', 'maintenanceId'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Maintenance $maintenance)
    {
        return view('admin.maintenance.edit', compact('maintenance'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'init_date' => 'required|date',
            'end_date' => 'required|date',
        ]);

        // Validar que la fecha fin no sea menor a la fecha inicio
        if ($request->end_date < $request->init_date) {
            return response()->json([
                'success' => false,
                'message' => 'La fecha de fin no puede ser menor a la fecha de inicio.'
            ], 422);
        }

        // Validar solapamiento de fechas, excluyendo el mantenimiento actual
        $overlap = Maintenance::where('id', '!=', $maintenance->id)
            ->where(function ($query) use ($request) {
                $query->where('init_date', '<=', $request->end_date)
                    ->where('end_date', '>=', $request->init_date);
            })->exists();

        if ($overlap) {
            return response()->json([
                'success' => false,
                'message' => 'Las fechas se solapan con otro mantenimiento existente.'
            ], 422);
        }

        $maintenance->update($request->all());

        return response()->json(['success' => true, 'message' => 'Mantenimiento actualizado exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        // Verifica si existen horarios relacionados
        if ($maintenance->schedules()->exists()) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede eliminar el mantenimiento porque existen horarios relacionados.'
                ], 422);
            }
            return redirect()->route('admin.maintenances.index')->with('error', 'No se puede eliminar el mantenimiento porque existen horarios relacionados.');
        }

        $maintenance->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Mantenimiento eliminado exitosamente.']);
        }
        return redirect()->route('admin.maintenances.index')->with('success', 'Mantenimiento eliminado correctamente');
    }
}
