{!! Form::open(['route' => ['admin.maintenance.schedule.store', $maintenanceId], 'method' => 'POST', 'files' => true]) !!}
    @include('admin.maintenance.shedule.template.form')
    <button type="submit" class="btn btn-primary">Registrar</button>
{!! Form::close() !!}
