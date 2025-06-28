@extends('adminlte::page')

@section('title', 'Actividades de Mantenimiento')

@section('content')
<div class="p-2"></div>
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary float-right" id="btnNuevo"><i class="fas fa-folder-plus"></i> Nueva Actividad</button>
        <h3>Actividades de Mantenimiento</h3>
    </div>
    <div class="card-body">
        <table class="table table-sm table-bordered text-center" id="datatable">
            <thead class="thead-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Imagen</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLongTitle"></h5>
            </div>
            <div class="modal-body" id="modalContent"></div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
const maintenanceScheduleId = @json($maintenanceScheduleId);
$(function() {
    // URLs base para acciones, con marcador
    var editUrl = "{{ route('admin.maintenance.activities.edit', ['maintenanceScheduleId' => 'SCHEDULE_ID', 'id' => 'ACTIVITY_ID']) }}";
    var destroyUrl = "{{ route('admin.maintenance.activities.destroy', ['maintenanceScheduleId' => 'SCHEDULE_ID', 'id' => 'ACTIVITY_ID']) }}";

    let table = $('#datatable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.3.2/i18n/es-ES.json'
        },
        responsive: true,
        autoWidth: false,
        ajax: {
            url: `{{ route('admin.maintenance.activities.index', ['maintenanceScheduleId' => $maintenanceScheduleId]) }}`,
            dataSrc: 'data'
        },
        columns: [
            { data: 'activity_date' },
            { data: 'description' },
            {
                data: 'image_url',
                render: function(data) {
                    if (data) {
                        return `<img src='/storage/${data}' alt='Imagen' width='60' height='60' class='img-thumbnail'/>`;
                    }
                    return '';
                },
                orderable: false,
                searchable: false
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<button class='btn btn-success btn-sm btnEditar' data-id='${data}'><i class='fas fa-pen'></i></button>`;
                }
            },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    return `<button class='btn btn-danger btn-sm btnEliminar' data-id='${data}'><i class='fas fa-trash'></i></button>`;
                }
            }
        ]
    });

    // Nuevo
    $('#btnNuevo').click(function() {
        $.ajax({
            url: `{{ route('admin.maintenance.activities.create', ['maintenanceScheduleId' => $maintenanceScheduleId]) }}`,
            type: 'GET',
            success: function(html) {
                $('#ModalLongTitle').text('Registrar Actividad');
                $('#modalContent').html(html);
                $('#ModalCenter').modal('show');

                $('#ModalCenter form').on('submit', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var formdata = new FormData(this);
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('#ModalCenter').modal('hide');
                            $('#datatable').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Proceso exitoso",
                                icon: "success",
                                text: response.message,
                                draggable: true
                            });
                        },
                        error: function(xhr) {
                            var response = xhr.responseJSON;
                            Swal.fire({
                                title: "Error",
                                icon: "error",
                                text: response.message || 'Ocurrió un error',
                                draggable: true
                            });
                        }
                    });
                });
            }
        });
    });

    // Editar
    $('#datatable tbody').on('click', '.btnEditar', function() {
        let id = $(this).data('id');
        $.ajax({
            url: editUrl.replace('ACTIVITY_ID', id).replace('SCHEDULE_ID', maintenanceScheduleId),
            type: 'GET',
            success: function(html) {
                $('#ModalLongTitle').text('Editar Actividad');
                $('#modalContent').html(html);
                $('#ModalCenter').modal('show');

                $('#ModalCenter form').on('submit', function(e) {
                    e.preventDefault();
                    var form = $(this);
                    var formdata = new FormData(this);
                    $.ajax({
                        url: form.attr('action'),
                        type: form.attr('method'),
                        data: formdata,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('#ModalCenter').modal('hide');
                            $('#datatable').DataTable().ajax.reload();
                            Swal.fire({
                                title: "Proceso exitoso",
                                icon: "success",
                                text: response.message,
                                draggable: true
                            });
                        },
                        error: function(xhr) {
                            var response = xhr.responseJSON;
                            Swal.fire({
                                title: "Error",
                                icon: "error",
                                text: response.message || 'Ocurrió un error',
                                draggable: true
                            });
                        }
                    });
                });
            }
        });
    });

    // Eliminar
    $('#datatable tbody').on('click', '.btnEliminar', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: destroyUrl.replace('ACTIVITY_ID', id).replace('SCHEDULE_ID', maintenanceScheduleId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        Swal.fire('Eliminado', response.message, 'success');
                    },
                    error: function(xhr) {
                        let response = xhr.responseJSON;
                        Swal.fire('Error', response.message || 'No se pudo eliminar', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection
