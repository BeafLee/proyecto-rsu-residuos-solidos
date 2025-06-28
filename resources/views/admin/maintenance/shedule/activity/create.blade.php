{!! Form::open(['route' => ['admin.maintenance.activities.store', $maintenanceScheduleId], 'method' => 'POST', 'files' => true]) !!}
    @include('admin.maintenance.shedule.activity.template.form')
    <button type="submit" class="btn btn-primary">Registrar</button>
{!! Form::close() !!}
