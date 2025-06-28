@extends('adminlte::page')

@section('title', 'Horarios de Mantenimiento')

@section('content')
<div class="p-2"></div>
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary float-right" id="btnNuevo"><i class="fas fa-folder-plus"></i> Nuevo</button>
        <h3>Horarios de Mantenimiento</h3>
    </div>
    <div class="card-body">
        <table class="table table-sm table-bordered text-center" id="datatable">
            <thead class="thead-dark">
                <tr>
                    <th>Tipo</th>
                    <th>Día</th>
                    <th>Hora Inicio</th>
                    <th>Hora Fin</th>
                    <th>Empleado</th>
                    <th>Vehículo</th>
                    <th>Actividades</th>
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
const maintenanceId = @json($maintenanceId);
$(function() {
    // URLs base para acciones, con marcador
    var editUrl = "{{ route('admin.maintenance.schedule.edit', ['maintenanceId' => 'MAINTENANCE_ID', 'id' => 'SCHEDULE_ID']) }}";
    var destroyUrl = "{{ route('admin.maintenance.schedule.destroy', ['maintenanceId' => 'MAINTENANCE_ID', 'id' => 'SCHEDULE_ID']) }}";

    let table = $('#datatable').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/2.3.2/i18n/es-ES.json'
        },
        responsive: true,
        autoWidth: false,
        ajax: {
            url: `{{ route('admin.maintenance.schedule.index', ['maintenanceId' => $maintenanceId]) }}`,
            dataSrc: 'data'
        },
        columns: [
            { data: 'type' },
            { data: 'day_of_week' },
            { data: 'start_time' },
            { data: 'end_time' },
            { data: 'employee.names', defaultContent: '' },
            { data: 'vehicle.name', defaultContent: '' },
            {
                data: 'id',
                orderable: false,
                searchable: false,
                render: function(data) {
                    var url = "{{ route('admin.maintenance.activities.index', ['maintenanceScheduleId' => 'SCHEDULE_ID']) }}";
                    url = url.replace('SCHEDULE_ID', data);
                    return `<a href='${url}' class='btn btn-primary btn-sm' title='Ver Actividades'><i class='fas fa-tasks'></i></a>`;
                }
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
            url: `{{ route('admin.maintenance.schedule.create', ['maintenanceId' => $maintenanceId]) }}`,
            type: 'GET',
            success: function(html) {
                $('#ModalLongTitle').text('Registrar Horario');
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
            url: editUrl.replace('SCHEDULE_ID', id).replace('MAINTENANCE_ID', maintenanceId),
            type: 'GET',
            success: function(html) {
                $('#ModalLongTitle').text('Editar Horario');
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
                    url: destroyUrl.replace('SCHEDULE_ID', id).replace('MAINTENANCE_ID', maintenanceId) ,
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
