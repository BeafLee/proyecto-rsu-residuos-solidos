{!! Form::open(['route' => 'admin.maintenances.store', 'method' => 'POST', 'files' => true]) !!}
    @include('admin.maintenance.template.form')
    <button type="submit" class="btn btn-primary">Registrar</button>
{!! Form::close() !!}
