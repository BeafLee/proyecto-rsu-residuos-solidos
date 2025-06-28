{!! Form::model($activity, [
    'route' => ['admin.maintenance.activities.update', $maintenanceScheduleId, $activity->id],
    'method' => 'PUT',
    'files' => true,
]) !!}
    @include('admin.maintenance.shedule.activity.template.form')
    <button type="submit" class="btn btn-primary">Actualizar</button>
{!! Form::close() !!}
