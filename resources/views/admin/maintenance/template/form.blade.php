<div class="row">
    <div class="col-md-12">
        <div class="form-row">
            <div class="form-group col-md-12">
                {!! Form::label('name', 'Nombre') !!}
                {!! Form::text('name', null, [
                    'class' => 'form-control',
                    'placeholder' => 'Ingrese el nombre del mantenimiento',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('init_date', 'Fecha de Inicio') !!}
                {!! Form::date('init_date', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('end_date', 'Fecha de Fin') !!}
                {!! Form::date('end_date', null, [
                    'class' => 'form-control',
                    'required',
                ]) !!}
            </div>
        </div>
    </div>
</div>