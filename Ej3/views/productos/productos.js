$(document).ready(function() {
    cargarProductos();
});

var submitting = false;

function cargarProductos() {
    $.ajax({
        url: '../../controllers/productos.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'todos' }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response !== "No se encontraron productos.") {
                mostrarProductos(response);
            } else {
                toastr.warning('No se encontraron productos.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al cargar los productos.');
        }
    });
}

$('#formNuevoProducto').submit(function(event) {
    event.preventDefault();

    if (submitting) {
        return; // Si ya se está enviando una solicitud, no permitir que se envíe otra
    }

    submitting = true;

    var nombre = $('#nombre_producto').val();
    var descripcion = $('#descripcion_producto').val();
    var precio = $('#precio_producto').val();
    var stock = $('#stock_producto').val();

    var data = {
        op: 'insertar',
        nombre: nombre,
        descripcion: descripcion,
        precio: precio,
        stock: stock
    };

    $.ajax({
        url: '../../controllers/productos.controllers.php',
        type: 'POST',
        data: JSON.stringify(data),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            if (response && response.error) {
                toastr.error("Error al insertar producto")
            } else {
                toastr.success("Inserción exitosa");
                $('#formNuevoProducto')[0].reset();
                $('#modalNuevoProducto').modal('hide'); // Resetear el formulario
                cargarProductos();
            }
        },
        complete: function() {
            submitting = false; // Restablecer el marcador después de completar la solicitud
        },
    });
});

$('#cuerpoProductos').on('click', '.btn-editar', function() {
    var idProducto = $(this).data('id');

    $.ajax({
        url: '../../controllers/productos.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'detalle', producto_id: idProducto }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
        },
        success: function(response) {
            if (response) {
                $('#producto_id').val(response.producto_id);
                $('#nombre_producto_editar').val(response.nombre);
                $('#descripcion_producto_editar').val(response.descripcion);
                $('#precio_producto_editar').val(response.precio);
                $('#stock_producto_editar').val(response.stock);

                // Open the modal
                $('#modalEditarProducto').modal('show');

                // Remove the loading indicator from the button
                $('.btn-editar').prop('disabled', false).html('Editar');
            } else {
                toastr.error('No se pudo obtener los detalles del producto.');
                $('.btn-editar').prop('disabled', false).html('Editar');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al obtener detalles del producto.');
            $('.btn-editar').prop('disabled', false).html('Editar');
        }
    });
});

$('#formEditarProducto').submit(function(event) {
    event.preventDefault();
    if (submitting) {
        return; // Si ya se está enviando una solicitud, no permitir que se envíe otra
    }

    submitting = true;

    var producto_id = $('#producto_id').val();
    var nombre = $('#nombre_producto_editar').val();
    var descripcion = $('#descripcion_producto_editar').val();
    var precio = $('#precio_producto_editar').val();
    var stock = $('#stock_producto_editar').val();

    if (!nombre || !descripcion || !precio || !stock) {
        toastr.warning('Por favor, complete todos los campos.');
        return;
    }

    $.ajax({
        url: '../../controllers/productos.controllers.php',
        type: 'POST',
        data: JSON.stringify({
            op: 'actualizar',
            producto_id: producto_id,
            nombre: nombre,
            descripcion: descripcion,
            precio: precio,
            stock: stock
        }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            $('#loading-indicator').show();
        },
        success: function(response) {
            console.log(response);  // Verifica la respuesta del servidor
            if (response && response.resultado === "ok") {
                cargarProductos();
                $('#modalEditarProducto').modal('hide');
                toastr.success('Producto actualizado con éxito.');
            } else {
                toastr.error('Error al actualizar producto: ' + (response.error || 'Respuesta inesperada del servidor'));
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

$('#cuerpoProductos').on('click', '.btn-eliminar', function() {
    var idProducto = $(this).data('id');

    Swal.fire({
        title: '¿Está seguro que desea eliminar este producto?',
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
                url: '../../controllers/productos.controllers.php',
                type: 'POST',
                data: JSON.stringify({
                    op: 'eliminar',
                    producto_id: idProducto
                }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response.resultado === "ok") {
                        cargarProductos();
                        Swal.fire(
                            '¡Eliminado!',
                            'El producto ha sido eliminado correctamente.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error',
                            'No se pudo eliminar el producto: ' + response.error,
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
    buscarProductos(nombre);
});

function buscarProductos(nombre) {
    $.ajax({
        url: '../../controllers/productos.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'buscarPorNombre', nombre: nombre }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                mostrarProductos(response);
            } else {
                var mensaje = '<tr><td colspan="5" class="text-center">No se encontraron productos con el nombre especificado.</td></tr>';
                $('#cuerpoProductos').html(mensaje);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al buscar productos.');
        }
    });
}

function mostrarProductos(productos) {
    var cuerpoProductos = $('#cuerpoProductos');
    cuerpoProductos.empty();

    // Ordenar los productos alfabéticamente por nombre
    productos.sort(function(a, b) {
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

    // Mostrar los productos ordenados en la tabla
    if ($.isArray(productos) && productos.length > 0) {
        $.each(productos, function(index, producto) {
            var fila = '<tr data-id="' + producto.producto_id + '">' +
                '<td>' + producto.nombre + '</td>' +
                '<td>' + producto.descripcion + '</td>' +
                '<td>' + producto.precio + '</td>' +
                '<td>' + producto.stock + '</td>' +
                '<td>' +
                '<button class="btn btn-sm btn-warning btn-editar" data-id="' + producto.producto_id + '">Editar</button>' +
                '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + producto.producto_id + '">Eliminar</button>' +
                '</td>' +
                '</tr>';
            cuerpoProductos.append(fila);
        });
    } else if ($.isPlainObject(productos)) {
        var fila = '<tr data-id="' + productos.producto_id + '">' +
            '<td>' + productos.nombre + '</td>' +
            '<td>' + productos.descripcion + '</td>' +
            '<td>' + productos.precio + '</td>' +
            '<td>' + productos.stock + '</td>' +
            '<td>' +
            '<button class="btn btn-sm btn-warning btn-editar" data-id="' + productos.producto_id + '">Editar</button>' +
            '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + productos.producto_id + '">Eliminar</button>' +
            '</td>' +
            '</tr>';
        cuerpoProductos.append(fila);
    } else {
        var mensaje = '<tr><td colspan="5" class="text-center">' + productos + '</td></tr>';
        cuerpoProductos.html(mensaje);
    }
}
