@extends('adminlte::page')

@section('title', 'Mantenimientos')

@section('content')
    <div class="p-2"></div>
    <div class="card">
        <div class="card-header">
            <button type="button" class="btn btn-primary float-right" id="btnNuevo"><i class="fas fa-folder-plus"></i>
                Nuevo</button>
            <h3>Mantenimientos</h3>
        </div>
        <div class="card-body">
            <table class="table table-sm table-bordered text-center" id="datatable">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Horarios</th>
                        <th>Editar</th>
                        <th>Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se cargan los datos con AJAX -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ModalCenter" tabindex="-1" role="dialog" aria-labelledby="ModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- Aquí se carga el formulario --}}
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        var editUrl = "{{ route('admin.maintenances.edit', ['maintenance' => 'MAINTENANCE_ID']) }}";
        var destroyRoute = "{{ route('admin.maintenances.destroy', ['maintenance' => 'MAINTENANCE_ID']) }}";
        var showUrl = "{{ route('admin.maintenance.schedule.index', ['maintenanceId' => 'MAINTENANCE_ID']) }}";

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/2.3.2/i18n/es-ES.json'
                },
                responsive: true,
                autoWidth: false,
                "ajax": "{{ route('admin.maintenances.index') }}",
                "columns": [{
                        "data": "name"
                    },
                    {
                        "data": "init_date"
                    },
                    {
                        "data": "end_date"
                    },
                    {
                        "data": "id",
                        "orderable": false,
                        "searchable": false,
                        "width": "4%",
                        "render": function(data, type, row) {
                            var url = showUrl.replace('MAINTENANCE_ID', data);
                            return '<a href="' + url + '"><button class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></button></a>';
                        }
                    },
                    {
                        "data": "id",
                        "orderable": false,
                        "searchable": false,
                        "width": "4%",
                        "render": function(data, type, row) {
                            return '<button class="btn btn-success btn-sm btnEditar" id="' + data +
                                '"><i class="fas fa-pen"></i></button>';
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        width: "4%",
                        render: function(data) {
                            let actionUrl = destroyRoute.replace('MAINTENANCE_ID', data);
                            return `
                                <form action="${actionUrl}" method="POST" class="frmDelete">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="delete">
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            `;
                        }
                    }
                ]
            });

            // Eliminar mantenimiento por AJAX
            $('#datatable').on('submit', '.frmDelete', function(e) {
                e.preventDefault();
                var form = $(this);
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
                            url: form.attr('action'),
                            type: 'POST',
                            data: form.serialize(),
                            success: function(response) {
                                table.ajax.reload();
                                Swal.fire('Eliminado', response.message, 'success');
                            },
                            error: function(xhr) {
                                let response = xhr.responseJSON;
                                Swal.fire('Error', response && response.message ? response.message : 'No se pudo eliminar', 'error');
                            }
                        });
                    }
                });
            });
        });

        $('#btnNuevo').click(function() {
            $.ajax({
                url: "{{ route('admin.maintenances.create') }}",
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Nuevo Mantenimiento");
                    $('#ModalCenter .modal-body').html(response);
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
                                    text: response.message,
                                    draggable: true
                                });
                            }
                        });
                    });
                }
            });
        });

        $(document).on('click', '.btnEditar', function() {
            var id = $(this).attr("id");
            $.ajax({
                url: editUrl.replace('MAINTENANCE_ID', id),
                type: "GET",
                success: function(response) {
                    $('.modal-title').html("Editar Mantenimiento");
                    $('#ModalCenter .modal-body').html(response);
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
                                    text: response.message,
                                    draggable: true
                                });
                            }
                        });
                    });
                }
            });
        });

        $(document).on('submit', '.frmDelete', function(e) {
            e.preventDefault();
            var form = this;
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarlo!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>
@endsection
