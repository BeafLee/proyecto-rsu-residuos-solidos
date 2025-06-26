<div class="row">
    <div class="col-md-12">
        <div class="form-row">
            <div class="form-group col-md-6">
                {!! Form::label('type', 'Tipo') !!}
                {!! Form::select('type', [
                    'Limpieza' => 'Limpieza',
                    'Reparación' => 'Reparación',
                ], null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('day_of_week', 'Día de la Semana') !!}
                {!! Form::select('day_of_week', [
                    'Lunes' => 'Lunes',
                    'Martes' => 'Martes',
                    'Miércoles' => 'Miércoles',
                    'Jueves' => 'Jueves',
                    'Viernes' => 'Viernes',
                    'Sábado' => 'Sábado',
                    'Domingo' => 'Domingo',
                ], null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('start_time', 'Hora de Inicio') !!}
                {!! Form::time('start_time', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('end_time', 'Hora de Fin') !!}
                {!! Form::time('end_time', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('employee_id', 'Empleado Responsable') !!}
                {!! Form::select('employee_id', $employees, null, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => 'Seleccione un empleado',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('vehicle_id', 'Vehículo Asignado') !!}
                {!! Form::select('vehicle_id', $vehicles, null, [
                    'class' => 'form-control',
                    'required',
                    'placeholder' => 'Seleccione un vehículo',
                ]) !!}
            </div>
        </div>
    </div>
</div>
