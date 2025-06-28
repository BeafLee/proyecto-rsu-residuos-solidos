<div class="row">
    <div class="col-md-12">
        <div class="form-row">
            <div class="form-group col-md-6">
                {!! Form::label('activity_date', 'Fecha de Actividad') !!}
                {!! Form::date('activity_date', null, ['class' => 'form-control', 'required']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('description', 'Descripción') !!}
                {!! Form::textarea('description', null, ['class' => 'form-control', 'rows' => 3, 'required']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('image_url', 'Imagen de la Actividad') !!}
                {!! Form::file('image_url', ['class' => 'form-control-file', 'accept' => 'image/*', 'id' => 'imgInput']) !!}
            </div>
        </div>
    </div>
</div>
