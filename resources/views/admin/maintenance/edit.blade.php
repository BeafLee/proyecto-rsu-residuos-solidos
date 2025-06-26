{!! Form::model($maintenance, [
    'route' => ['admin.maintenances.update', $maintenance],
    'method' => 'PUT',
    'files' => true,
]) !!}
    @include('admin.maintenance.template.form')
    <button type="submit" class="btn btn-primary">Actualizar</button>
{!! Form::close() !!}
