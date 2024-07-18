<?php
$pagina_actual = 'Órdenes de Compra'; // Variable para indicar que estamos en la página de Órdenes de Compra
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once('../html/head.php') ?>
    <!-- Aquí incluir cualquier CSS adicional necesario -->
    <style>
        /* Estilos adicionales personalizados */
    </style>
</head>
<body>
    
    <div class="container">
        <!-- Botón para abrir el modal de nueva orden de compra -->
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <button id="btnNuevaOrden" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalNuevaOrden">Nueva Orden de Compra</button>
            </div>
        </div>

        <!-- Sección para mostrar todas las órdenes de compra -->
        <div class="row mb-3 justify-content-center">
            <div class="col-md-8">
                <div class="input-group">
                    <input type="text" id="buscarProducto" class="form-control" placeholder="Buscar por producto">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-8">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Cantidad</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoOrdenesCompra">
                        <!-- Ejemplo de cómo se puede generar dinámicamente una fila -->
    
                        <!-- Fin del ejemplo -->
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
                        <!-- Formulario de nueva orden de compra -->
                        <form id="formNuevaOrden">
                            <div class="form-group">
                                <label for="producto_id">Producto</label>
                                <input type="text" class="form-control" id="producto_id" name="producto_id" required>
                                <!-- Agregar lista de sugerencias si es necesario -->
                            </div>
                            <div class="form-group">
                                <label for="proveedor_id">Proveedor</label>
                                <input type="text" class="form-control" id="proveedor_id" name="proveedor_id" required>
                                <!-- Agregar lista de sugerencias si es necesario -->
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
        </div>

        <!-- Modal para eliminar orden de compra -->
        <div class="modal fade" id="modalEliminarOrden" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar Orden de Compra</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formEliminarOrden">
                            <input type="hidden" name="orden_id_eliminar" id="orden_id_eliminar">
                            <p>¿Está seguro de eliminar esta orden de compra?</p>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('../html/footer.php') ?>
    <?php require_once('../html/scripts.php') ?>

    <!-- Incluir aquí el archivo JavaScript necesario -->
    <script src="ordenes_compra.js"></script>

</body>
</html>
