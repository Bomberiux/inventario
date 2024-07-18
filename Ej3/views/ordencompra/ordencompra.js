$('#producto').keyup(function() {
    var producto = $(this).val();
    if (producto.length === 0) {
        $('#producto_suggestions').hide(); // Ocultar la lista cuando el campo está vacío
        return; // Salir de la función temprano
    }
    $.ajax({
        url: '../../controllers/productos.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'autocompleteProductos', producto: producto }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            // Opcional: mostrar un indicador de carga o deshabilitar el campo de entrada
        },
        success: function(response) {
            if (response) {
                $('#producto_suggestions').empty();
                $.each(response, function(index, value) {
                    $('<li>').text(value).appendTo('#producto_suggestions');
                });
                $('#producto_suggestions').show();

                // Agregar evento click a los elementos <li>
                $('#producto_suggestions li').click(function() {
                    var selectedProduct = $(this).text();
                    $('#producto').val(selectedProduct);
                    $('#producto_suggestions').hide();
                });
            } else {
                console.error('Error:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

$('#proveedor').keyup(function() {
    var proveedor = $(this).val();
    if (proveedor.length === 0) {
        $('#proveedor_suggestions').hide(); // Ocultar la lista cuando el campo está vacío
        return; // Salir de la función temprano
    }
    $.ajax({
        url: '../../controllers/proveedores.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'autocompleteProveedores', proveedor: proveedor }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            // Opcional: mostrar un indicador de carga o deshabilitar el campo de entrada
        },
        success: function(response) {
            if (response) {
                $('#proveedor_suggestions').empty();
                $.each(response, function(index, value) {
                    $('<li>').text(value).appendTo('#proveedor_suggestions');
                });
                $('#proveedor_suggestions').show();

                // Agregar evento click a los elementos <li>
                $('#proveedor_suggestions li').click(function() {
                    var selectedProveedor = $(this).text();
                    $('#proveedor').val(selectedProveedor);
                    $('#proveedor_suggestions').hide();
                });
            } else {
                console.error('Error:', response);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
});

var submittingOrdenCompra = false;

cargarOrdenesCompra();

function cargarOrdenesCompra() {
    $.ajax({
        url: '../../controllers/ordencompra.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'todos' }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response !== "No se encontraron órdenes de compra.") {
                mostrarOrdenesCompra(response);
            } else {
                toastr.warning('No se encontraron órdenes de compra.');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.warning('Error al cargar las órdenes de compra.');
        }
    });
}

$('#formNuevaOrdenCompra').submit(function(e) {
    e.preventDefault();
    if (submittingOrdenCompra) {
        return;
    }

    submittingOrdenCompra = true;

    var producto = $('#producto').val();
    var proveedor = $('#proveedor').val();

    var datos = {
        op: 'insertar',
        producto: producto,
        proveedor: proveedor
    };

    $.ajax({
        type: 'POST',
        url: '../../controllers/ordencompra.controllers.php',
        data: JSON.stringify(datos),
        contentType: 'application/json',
        success: function(response) {
            console.log(response);
            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    toastr.error('Respuesta inválida del servidor.');
                    $('#formNuevaOrdenCompra')[0].reset();
                    cargarOrdenesCompra();
                    return;
                }
            }
            if (response && response.status === 'error' && response.message) {
                toastr.error(response.message);
                $('#formNuevaOrdenCompra')[0].reset();
                $('#producto_suggestions')[0].reset();
                $('#proveedor_suggestions')[0].reset();

            } else {
                toastr.success('Orden de compra registrada con éxito.');
                $('#formNuevaOrdenCompra')[0].reset();
                cargarOrdenesCompra();
                $('#producto_suggestions')[0].reset();
                $('#proveedor_suggestions')[0].reset();
            }
        },
        complete: function() {
            submittingOrdenCompra = false;
        },
        error: function(xhr, status, error) {
            console.error('Error al insertar orden de compra:', error);
            toastr.error('Error al insertar orden de compra.');
        }
    });
});

$('#cuerpoOrdenesCompra').on('click', '.btn-editar', function() {
    var idOrdenCompra = $(this).data('id');

    var $btn = $(this);
    $.ajax({
        url: '../../controllers/ordencompra.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'detalle', id_orden_compra: idOrdenCompra }),
        contentType: 'application/json',
        dataType: 'json',
        beforeSend: function() {
            $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
        },
        success: function(response) {
            if (response) {
                $('#id_orden_compra').val(response.id_orden_compra);
                $('#producto_editar').val(response.producto);
                $('#proveedor_editar').val(response.proveedor);

                $('#modalEditarOrdenCompra').modal('show');

                $btn.prop('disabled', false).html('Editar');
            } else {
                toastr.error('No se pudo obtener los detalles de la orden de compra.');
                $btn.prop('disabled', false).html('Editar');
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            toastr.error('Error al obtener detalles de la orden de compra.');
            $btn.prop('disabled', false).html('Editar');
        }
    });
});


$('#formEditarOrdenCompra').submit(function(e) {
    e.preventDefault();
    if (submittingOrdenCompra) {
        return;
    }

    submittingOrdenCompra = true;

    var id_orden_compra = $('#id_orden_compra').val();
    var producto = $('#producto_editar').val();
    var proveedor = $('#proveedor_editar').val();

    var datos = {
        op: 'actualizar',
        id_orden_compra: id_orden_compra,
        producto: producto,
        proveedor: proveedor
    };

    $.ajax({
        type: 'POST',
        url: '../../controllers/ordencompra.controllers.php',
        data: JSON.stringify(datos),
        contentType: 'application/json',
        success: function(response) {
            console.log(response);
            if (typeof response === 'string') {
                try {
                    response = JSON.parse(response);
                } catch (e) {
                    toastr.error('Respuesta inválida del servidor.');
                    $('#modalEditarOrdenCompra').modal('hide');
                    return;
                }
            }
            if (response && response.status === 'error' && response.message) {
                toastr.error(response.message);
            } else {
                toastr.success('Orden de compra actualizada con éxito.');
                $('#modalEditarOrdenCompra').modal('hide');
                cargarOrdenesCompra(); // Esta función debería cargar nuevamente las órdenes de compra en tu vista
            }
        },
        complete: function() {
            submittingOrdenCompra = false;
        },
        error: function(xhr, status, error) {
            console.error('Error al actualizar orden de compra:', error);
            toastr.error('Error al actualizar orden de compra.');
        }
    });
});


$('#cuerpoOrdenesCompra').on('click', '.btn-eliminar', function() {
    var idOrdenCompra = $(this).data('id');

    Swal.fire({
        title: '¿Está seguro que desea eliminar esta orden de compra?',
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
                url: '../../controllers/ordencompra.controllers.php',
                type: 'POST',
                data: JSON.stringify({
                    op: 'eliminar',
                    id_orden_compra: idOrdenCompra
                }),
                contentType: 'application/json',
                dataType: 'json',
                success: function(response) {
                    if (response && response.status === "ok") { // Verifica si la respuesta es "ok"
                        cargarOrdenesCompra();
                        Swal.fire(
                            '¡Eliminado!',
                            'La orden de compra ha sido eliminada correctamente.',
                            'success'
                        );
                    } else {
                        var errorMessage = response && response.message ? response.message : 'Error desconocido al eliminar.';
                        Swal.fire(
                            'Error',
                            'No se pudo eliminar la orden de compra: ' + errorMessage,
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

$('#buscarProducto').on('input', function() {
    var producto = $(this).val().trim();
    if (producto === '') {
        cargarOrdenesCompra();
    } else {
        buscarOrdenesCompraPorProducto(producto);
    }
});

function buscarOrdenesCompraPorProducto(producto) {
    $.ajax({
        url: '../../controllers/ordencompra.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'buscarPorProducto', producto: producto }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                mostrarOrdenesCompra(response);
            } else {
                var mensaje = '<tr><td colspan="4" class="text-center">No se encontraron órdenes de compra con el producto especificado.</td></tr>';
                $('#cuerpoOrdenesCompra').html(mensaje);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Error al buscar órdenes de compra.');
        }
    });
}

$('#buscarProveedor').on('input', function() {
    var proveedor = $(this).val().trim();
    if (proveedor === '') {
        cargarOrdenesCompra();
    } else {
        buscarOrdenesCompraPorProveedor(proveedor);
    }
});

function buscarOrdenesCompraPorProveedor(proveedor) {
    $.ajax({
        url: '../../controllers/ordencompra.controllers.php',
        type: 'POST',
        data: JSON.stringify({ op: 'buscarPorProveedor', proveedor: proveedor }),
        contentType: 'application/json',
        dataType: 'json',
        success: function(response) {
            if (response && response.length > 0) {
                mostrarOrdenesCompra(response);
            } else {
                var mensaje = '<tr><td colspan="4" class="text-center">No se encontraron órdenes de compra con el proveedor especificado.</td></tr>';
                $('#cuerpoOrdenesCompra').html(mensaje);
            }
        },
        error: function(xhr, status, error) {
            console.error(error);
            alert('Error al buscar órdenes de compra.');
        }
    });
}

function mostrarOrdenesCompra(ordenesCompra) {
    var cuerpoOrdenesCompra = $('#cuerpoOrdenesCompra');
    cuerpoOrdenesCompra.empty();

    if ($.isArray(ordenesCompra) && ordenesCompra.length > 0) {
        $.each(ordenesCompra, function(index, ordenCompra) {
            var fila = '<tr data-id="' + ordenCompra.id_orden_compra + '">' +
                '<td>' + ordenCompra.producto + '</td>' +
                '<td>' + ordenCompra.proveedor + '</td>' +
                '<td>' +
                '<button class="btn btn-sm btn-warning btn-editar" data-id="' + ordenCompra.id_orden_compra + '">Editar</button>' +
                '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + ordenCompra.id_orden_compra + '">Eliminar</button>' +
                '</td>' +
                '</tr>';
            cuerpoOrdenesCompra.append(fila);
        });
    } else {
        var mensaje = '<tr><td colspan="3" class="text-center">No se encontraron órdenes de compra.</td></tr>';
        cuerpoOrdenesCompra.html(mensaje);
    }
}
