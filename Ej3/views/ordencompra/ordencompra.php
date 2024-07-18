<?php
$pagina_actual = 'Ordenes de Compra'; // Variable para indicar que estamos en la página de Órdenes de Compra
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once('../html/head.php') ?>
    <style>
        /* Estilos adicionales personalizados */
    </style>
</head>

<body>
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <button id="btnNuevaOrden" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalNuevaOrden">Nueva Orden de Compra</button>
            </div>
        </div>

        <div class="row mb-3 justify-content-center">
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar por producto">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" id="buscarProveedor" class="form-control" placeholder="Buscar por proveedor">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoOrdenesCompra">
                        <!-- Aquí se generarán dinámicamente las filas -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para registrar nueva orden de compra -->
        <div class="modal fade" id="modalNuevaOrden" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nueva Orden de Compra</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formNuevaOrden">
                            <div class="form-group">
                                <label for="producto_id">Producto</label>
                                <input type="text" class="form-control" id="producto_id" name="producto_id" required>
                                    <option value="">Seleccione un producto</option>
                                </input>
                            </div>
                            <div class="form-group">
                                <label for="proveedor_id">Proveedor</label>
                                <input type="text" class="form-control" id="proveedor_id" name="proveedor_id" required>
                                    <option value="">Seleccione un proveedor</option>
                                </input>
                            </div>
                            <div class="form-group">
                                <label for="cantidad">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha">Fecha</label>
                                <input type="date" class="form-control" id="fecha" name="fecha" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>

        <!-- Modal para editar orden de compra -->
        <div class="modal fade" id="modalEditarOrden" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Orden de Compra</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarOrden" method="post" action="procesar_edicion_orden.php">
                            <input type="hidden" id="orden_id" name="orden_id">
                            <div class="form-group">
                                <label for="producto_id_editar">Producto</label>
                                <select class="form-control" id="producto_id_editar" name="producto_id" required>
                                    <option value="">Seleccione un producto</option>
                                    <!-- Opciones dinámicas -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="proveedor_id_editar">Proveedor</label>
                                <select class="form-control" id="proveedor_id_editar" name="proveedor_id" required>
                                    <option value="">Seleccione un proveedor</option>
                                    <!-- Opciones dinámicas -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="cantidad_editar">Cantidad</label>
                                <input type="number" class="form-control" id="cantidad_editar" name="cantidad" required>
                            </div>
                            <div class="form-group">
                                <label for="fecha_editar">Fecha</label>
                                <input type="date" class="form-control" id="fecha_editar" name="fecha" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('../html/scripts.php') ?>
    <script src="ordencompra.js"></script>
</body>

</html>
