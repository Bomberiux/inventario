$(document).ready(function() {
    // Cargar productos y proveedores al abrir el modal de nueva orden de compra
    $('#modalNuevaOrden').on('show.bs.modal', function() {
        cargarProductos("#producto_id");
        cargarProveedores("#proveedor_id");
    });

    // Cargar productos y proveedores al abrir el modal de editar orden de compra
    $('#modalEditarOrden').on('show.bs.modal', function() {
        cargarProductos("#producto_id_editar");
        cargarProveedores("#proveedor_id_editar");
    });

    // Autocompletado del nombre del producto
    $('#nombre_producto').keyup(function() {
        var nombre_producto = $(this).val();
        if (nombre_producto.length === 0) {
            $('#nombre_producto_suggestions').hide(); // Esconder la lista cuando el input está vacío
            return; // Salir de la función temprano
        }
        $.ajax({
            url: '../../controllers/ordencompra.controllers.php',
            type: 'POST',
            data: JSON.stringify({ op: 'autocompleteProductos', nombre_producto: nombre_producto }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response) {
                    $('#nombre_producto_suggestions').empty();
                    $.each(response, function(index, value) {
                        $('<li>').text(value).appendTo('#nombre_producto_suggestions');
                    });
                    $('#nombre_producto_suggestions').show();

                    // Agregar evento click a los elementos <li>
                    $('#nombre_producto_suggestions li').click(function() {
                        var selectedProducto = $(this).text();
                        $('#nombre_producto').val(selectedProducto);
                        $('#nombre_producto_suggestions').hide();
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

    // Variable para evitar envíos múltiples
    var submitting = false;

    // Cargar todas las órdenes de compra
    cargarOrdenesCompra();

    function cargarOrdenesCompra() {
        $.ajax({
            url: '../../controllers/ordencompra.controllers.php',
            type: 'POST',
            data: JSON.stringify({ op: 'todos' }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response !== "No se encontraron ordenes de compra.") {
                    mostrarOrdenesCompra(response);
                } else {
                    toastr.warning('No se encontraron ordenes de compra.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.warning('Error al cargar las ordenes de compra.');
            }
        });
    }

    // Enviar nueva orden de compra
    $('#formNuevaOrden').submit(function(e) {
        e.preventDefault();
        if (submitting) return;

        submitting = true;

        var nombre_producto = $('#nombre_producto').val();
        var cantidad = $('#cantidad').val();
        var nombre_proveedor = $('#nombre_proveedor').val(); // Agregado para capturar proveedor

        var datos = {
            op: 'insertar',
            nombre_producto: nombre_producto,
            cantidad: cantidad,
            nombre_proveedor: nombre_proveedor // Agregado para enviar a PHP
        };

        $.ajax({
            type: 'POST',
            url: '../../controllers/ordencompra.controllers.php',
            data: JSON.stringify(datos),
            contentType: 'application/json',
            success: function(response) {
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        toastr.error('Respuesta inválida del servidor.');
                        $('#formNuevaOrden')[0].reset();
                        cargarOrdenesCompra();
                        return;
                    }
                }
                if (response && response.status === 'error' && response.message) {
                    toastr.error(response.message);
                } else {
                    toastr.success('Orden de compra registrada con éxito.');
                    $('#formNuevaOrden')[0].reset();
                    cargarOrdenesCompra();
                }
            },
            complete: function() {
                submitting = false;
            },
            error: function(xhr, status, error) {
                console.error('Error al insertar orden de compra:', error);
                toastr.error('Error al insertar orden de compra.');
            }
        });
    });

    // Editar orden de compra
    $('#cuerpoOrdenesCompra').on('click', '.btn-editar', function() {
        var idOrden = $(this).data('id');
        var $btn = $(this);

        $.ajax({
            url: '../../controllers/ordencompra.controllers.php',
            type: 'POST',
            data: JSON.stringify({ op: 'detalle', id_orden: idOrden }),
            contentType: 'application/json',
            dataType: 'json',
            beforeSend: function() {
                $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cargando...');
            },
            success: function(response) {
                if (response) {
                    $('#id_orden').val(response.id_orden);
                    $('#nombre_producto_editar').val(response.nombre_producto);
                    $('#cantidad_editar').val(response.cantidad);
                    $('#nombre_proveedor_editar').val(response.nombre_proveedor); // Agregado para cargar proveedor

                    $('#modalEditarOrden').modal('show');
                } else {
                    toastr.error('No se pudo obtener los detalles de la orden de compra.');
                }
            },
            complete: function() {
                $btn.prop('disabled', false).html('Editar');
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.error('Error al obtener detalles de la orden de compra.');
                $btn.prop('disabled', false).html('Editar');
            }
        });
    });

    // Enviar formulario de edición de orden
    $('#formEditarOrden').submit(function(e) {
        e.preventDefault();
        if (submitting) return;

        submitting = true;

        var id_orden = $('#id_orden').val();
        var nombre_producto = $('#nombre_producto_editar').val();
        var cantidad = $('#cantidad_editar').val();
        var nombre_proveedor = $('#nombre_proveedor_editar').val(); // Agregado para capturar proveedor

        var datos = {
            op: 'actualizar',
            id_orden: id_orden,
            nombre_producto: nombre_producto,
            cantidad: cantidad,
            nombre_proveedor: nombre_proveedor // Agregado para enviar a PHP
        };

        $.ajax({
            type: 'POST',
            url: '../../controllers/ordencompra.controllers.php',
            data: JSON.stringify(datos),
            contentType: 'application/json',
            success: function(response) {
                if (typeof response === 'string') {
                    try {
                        response = JSON.parse(response);
                    } catch (e) {
                        toastr.error('Respuesta inválida del servidor.');
                        $('#modalEditarOrden').modal('hide');
                        return;
                    }
                }
                if (response && response.status === 'error' && response.message) {
                    toastr.error(response.message);
                } else {
                    toastr.success('Orden de compra actualizada con éxito.');
                    $('#modalEditarOrden').modal('hide');
                    cargarOrdenesCompra();
                }
            },
            complete: function() {
                submitting = false;
            },
            error: function(xhr, status, error) {
                console.error('Error al actualizar orden de compra:', error);
                toastr.error('Error al actualizar orden de compra.');
            }
        });
    });

    // Eliminar orden de compra
    $('#cuerpoOrdenesCompra').on('click', '.btn-eliminar', function() {
        var idOrden = $(this).data('id');

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
                        id_orden: idOrden
                    }),
                    contentType: 'application/json',
                    dataType: 'json',
                    success: function(response) {
                        if (response && response.status === "ok") {
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

    // Buscar órdenes de compra por nombre de producto
    $('#buscarNombreProducto').on('input', function() {
        var nombre_producto = $(this).val().trim();
        if (nombre_producto === '') {
            cargarOrdenesCompra();
        } else {
            buscarOrdenesCompraPorProducto(nombre_producto);
        }
    });

    function buscarOrdenesCompraPorProducto(nombre_producto) {
        $.ajax({
            url: '../../controllers/ordencompra.controllers.php',
            type: 'POST',
            data: JSON.stringify({ op: 'buscarPorProducto', nombre_producto: nombre_producto }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    mostrarOrdenesCompra(response);
                } else {
                    var mensaje = '<tr><td colspan="4" class="text-center">No se encontraron ordenes de compra con el nombre de producto especificado.</td></tr>';
                    $('#cuerpoOrdenesCompra').html(mensaje);
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('Error al buscar ordenes de compra.');
            }
        });
    }

    // Mostrar órdenes de compra en la tabla
    function mostrarOrdenesCompra(ordenesCompra) {
        var cuerpoOrdenesCompra = $('#cuerpoOrdenesCompra');
        cuerpoOrdenesCompra.empty();

        if ($.isArray(ordenesCompra) && ordenesCompra.length > 0) {
            $.each(ordenesCompra, function(index, ordenCompra) {
                var fila = '<tr data-id="' + ordenCompra.id_orden + '">' +
                    '<td>' + ordenCompra.nombre_producto + '</td>' +
                    '<td>' + ordenCompra.nombre_proveedor + '</td>' +
                    '<td>' + ordenCompra.cantidad + '</td>' +
                    '<td>' + ordenCompra.fecha + '</td>' +
                    '<td>' +
                    '<button class="btn btn-sm btn-warning btn-editar" data-id="' + ordenCompra.id_orden + '">Editar</button>' +
                    '<button class="btn btn-sm btn-danger btn-eliminar ms-2" data-id="' + ordenCompra.id_orden + '">Eliminar</button>' +
                    '</td>' +
                    '</tr>';
                cuerpoOrdenesCompra.append(fila);
            });
        } else {
            var mensaje = '<tr><td colspan="3" class="text-center">No se encontraron ordenes de compra.</td></tr>';
            cuerpoOrdenesCompra.html(mensaje);
        }
    }

    // Función para cargar productos en un select
    function cargarProductos(selector) {
        $.ajax({
            url: '../../controllers/productos.controllers.php',
            type: 'POST',
            data: JSON.stringify({ op: 'todos' }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $(selector).empty();
                    $.each(response, function(index, producto) {
                        $(selector).append('<option value="' + producto.id + '">' + producto.nombre + '</option>');
                    });
                } else {
                    toastr.warning('No se encontraron productos.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.warning('Error al cargar los productos.');
            }
        });
    }

    // Función para cargar proveedores en un select
    function cargarProveedores(selector) {
        $.ajax({
            url: '../../controllers/proveedores.controllers.php',
            type: 'POST',
            data: JSON.stringify({ op: 'todos' }),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                if (response && response.length > 0) {
                    $(selector).empty();
                    $.each(response, function(index, proveedor) {
                        $(selector).append('<option value="' + proveedor.id + '">' + proveedor.nombre + '</option>');
                    });
                } else {
                    toastr.warning('No se encontraron proveedores.');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                toastr.warning('Error al cargar los proveedores.');
            }
        });
    }
});
