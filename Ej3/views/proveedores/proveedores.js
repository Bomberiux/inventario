$(document).ready(function() {
    cargarProveedores();
});

var submitting = false;

function cargarProveedores() {
    $.ajax({
        url: '../../controllers/proveedores.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'todos' }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response !== "No se encontraron proveedores.") {
                mostrarProveedores(response);
            } else {
                toastr.warning('No se encontraron proveedores.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al cargar los proveedores.');
        }
    });
}

$('#formNuevoProveedor').submit(function(event) {
    event.preventDefault();

    if (submitting) {
        return; // Si ya se está enviando una solicitud, no permitir que se envíe otra
    }

    submitting = true;

    var nombre = $('#nombre_proveedor').val();
    var direccion = $('#direccion_proveedor').val();
    var telefono = $('#telefono_proveedor').val();
    var email = $('#email_proveedor').val();

    var data = {
        op: 'insertar',
        nombre: nombre,
        direccion: direccion,
        telefono: telefono,
        email: email
    };

    $.ajax({
        url: '../../controllers/proveedores.controllers.php',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response && response.error) {
                toastr.error("Error al insertar proveedor")
            } else {
                toastr.success("Inserción exitosa");
                $('#formNuevoProveedor')[0].reset();
                $('#modalNuevoProveedor').modal('hide'); // Resetear el formulario
                cargarProveedores();
            }
        },
        complete: function() {
            submitting = false; // Restablecer el marcador después de completar la solicitud
        },
    });
});

$('#cuerpoProveedores').on('click', '.btn-editar', function() {
    var idProveedor = $(this).data('id');

    $.ajax({
        url: '../../controllers/proveedores.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'detalle', proveedor_id: idProveedor }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
        },
        success: function(response) {
            if (response) {
                $('#proveedor_id').val(response.proveedor_id);
                $('#nombre_proveedor_editar').val(response.nombre);
                $('#direccion_proveedor_editar').val(response.direccion);
                $('#telefono_proveedor_editar').val(response.telefono);
                $('#email_proveedor_editar').val(response.email);

                // Open the modal
                $('#modalEditarProveedor').modal('show');

                // Remove the loading indicator from the button
                $('.btn-editar').prop('disabled', false).html('Editar');
            } else {
                toastr.error('No se pudo obtener los detalles del proveedor.');
                $('.btn-editar').prop('disabled', false).html('Editar');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al obtener detalles del proveedor.');
            $('.btn-editar').prop('disabled', false).html('Editar');
        }
    });
});

$('#formEditarProveedor').submit(function(event) {
    event.preventDefault();
    if (submitting) {
        return; // Si ya se está enviando una solicitud, no permitir que se envíe otra
    }

    submitting = true;

    var proveedor_id = $('#proveedor_id').val();
    var nombre = $('#nombre_proveedor_editar').val();
    var direccion = $('#direccion_proveedor_editar').val();
    var telefono = $('#telefono_proveedor_editar').val();
    var email = $('#email_proveedor_editar').val();

    if (!nombre || !direccion || !telefono || !email) {
        toastr.warning('Por favor, complete todos los campos.');
        return;
    }

    $.ajax({
        url: '../../controllers/proveedores.controllers.php',
        type: 'POST',
        data: JSON.stringify({
            op: 'actualizar',
            proveedor_id: proveedor_id,
            nombre: nombre,
            direccion: direccion,
            telefono: telefono,
            email: email
        }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            $('#loading-indicator').show();
        },
        success: function(response) {
            console.log(response);  // Verifica la respuesta del servidor
            if (response && response.resultado === "ok") {
                cargarProveedores();
                $('#modalEditarProveedor').modal('hide');
                toastr.success('Proveedor actualizado con éxito.');
            } else {
                toastr.error('Error al actualizar proveedor: ' + (response.error || 'Respuesta inesperada del servidor'));
            }
        },
        complete: function() {
            submitting = false; // Restablecer el marcador después de completar la solicitud
            $('#loading-indicator').hide(); // Ocultar indicador de carga después de completar la solicitud
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error de conexión al servidor. Por favor, inténtelo de nuevo más tarde.');
            $('#loading-indicator').hide(); // Asegúrate de ocultar el indicador de carga en caso de error
        }
    });
});

$('#cuerpoProveedores').on('click', '.btn-eliminar', function() {
    var idProveedor = $(this).data('id');

    Swal.fire({
        title: '¿Está seguro que desea eliminar este proveedor?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../controllers/proveedores.controllers.php',
                type: 'POST',
                data: JSON.stringify({
                    op: 'eliminar',
                    proveedor_id: idProveedor
                }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.resultado === "ok") {
                        cargarProveedores();
                        Swal.fire(
                            '¡Eliminado!',
                            'El proveedor ha sido eliminado correctamente.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error',
                            'No se pudo eliminar el proveedor: ' + response.error,
                            'error'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                    Swal.fire(
                        'Error',
                        'Error de conexión al servidor',
                        'error'
                    );
                }
            });
        }
    });
});

$('#inputBuscar').on('input', function() {
    var nombre = $(this).val().trim();
    buscarProveedores(nombre);
});

function buscarProveedores(nombre) {
    $.ajax({
        url: '../../controllers/proveedores.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'buscarPorNombre', nombre: nombre }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                mostrarProveedores(response);
            } else {
                var mensaje = '<tr><td colspan="5" class="text-center">No se encontraron proveedores con el nombre especificado.</td></tr>';
                $('#cuerpoProveedores').html(mensaje);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al buscar proveedores.');
        }
    });
}

function mostrarProveedores(proveedores) {
    var cuerpoProveedores = $('#cuerpoProveedores');
    cuerpoProveedores.empty();

    // Ordenar los proveedores alfabéticamente por nombre
    proveedores.sort(function(a, b) {
        var nombreA = a.nombre.toUpperCase(); // Convertir a mayúsculas para ordenar correctamente
        var nombreB = b.nombre.toUpperCase(); // Convertir a mayúsculas para ordenar correctamente
        if (nombreA < nombreB) {
            return -1;
        }
        if (nombreA > nombreB) {
            return 1;
        }
        return 0; // Igual si los nombres son iguales
    });

    // Mostrar los proveedores ordenados en la tabla
    if ($.isArray(proveedores) && proveedores.length > 0) {
        $.each(proveedores, function(index, proveedor) {
            var fila = '<tr data-id="' + proveedor.proveedor_id + '">' +
                '<td>' + proveedor.nombre + '</td>' +
                '<td>' + proveedor.direccion + '</td>' +
                '<td>' + proveedor.telefono + '</td>' +
                '<td>' + proveedor.email + '</td>' +
                '<td>' +
                '<button class="btn btn-sm btn-warning btn-editar" data-id="' + proveedor.proveedor_id + '">Editar</button>' +
                '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + proveedor.proveedor_id + '">Eliminar</button>' +
                '</td>' +
                '</tr>';
            cuerpoProveedores.append(fila);
        });
    } else if ($.isPlainObject(proveedores)) {
        var fila = '<tr data-id="' + proveedores.proveedor_id + '">' +
            '<td>' + proveedores.nombre + '</td>' +
            '<td>' + proveedores.direccion + '</td>' +
            '<td>' + proveedores.telefono + '</td>' +
            '<td>' + proveedores.email + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-warning btn-editar" data-id="' + proveedores.proveedor_id + '">Editar</button>' +
            '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + proveedores.proveedor_id + '">Eliminar</button>' +
            '</td>' +
            '</tr>';
        cuerpoProveedores.append(fila);
    } else {
        var mensaje = '<tr><td colspan="5" class="text-center">' + proveedores + '</td></tr>';
        cuerpoProveedores.html(mensaje);
    }
}
