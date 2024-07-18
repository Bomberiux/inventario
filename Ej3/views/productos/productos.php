<?php
$pagina_actual = 'Productos'; // Indica que estamos en la página de Productos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once('../html/head.php') ?>
    <title>Administración de Productos - Tu Sitio</title>
</head>
<body>
    <div class="container">
        <!-- Botón para abrir el modal de nuevo producto -->
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <button id="btnNuevoProducto" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalNuevoProducto">Nuevo Producto</button>
            </div>
        </div>

        <!-- Sección para mostrar todos los productos -->
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <h2>Lista de Productos</h2>
                <div class="input-group mb-3">
                    <input type="text" id="inputBuscar" class="form-control" placeholder="Buscar por nombre...">
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoProductos">
                        <!-- Aquí se mostrarán los productos -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para registrar nuevo producto -->
        <div class="modal fade" id="modalNuevoProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario de nuevo producto -->
                        <form id="formNuevoProducto">
                            <div class="form-group">
                                <label for="nombre_producto">Nombre del Producto</label>
                                <input type="text" class="form-control" id="nombre_producto" name="nombre_producto" required pattern="[A-Za-z\sáéíóúñÁÉÍÓÚÜüÑñ]+" title="Solo se permiten letras y espacios" maxlength="30">
                            </div>
                            <div class="form-group">
                                <label for="descripcion_producto">Descripción</label>
                                <input type="text" class="form-control" id="descripcion_producto" name="descripcion_producto" required>
                            </div>
                            <div class="form-group">
                                <label for="precio_producto">Precio</label>
                                <input type="number" class="form-control" id="precio_producto" name="precio_producto" required>
                            </div>
                            <div class="form-group">
                                <label for="stock_producto">Stock</label>
                                <input type="number" class="form-control" id="stock_producto" name="stock_producto" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar productos -->
        <div class="modal fade" id="modalEditarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formEditarProducto" novalidate>
                        <div class="modal-body">
                            <input type="hidden" id="producto_id" name="producto_id">
                            <div class="form-group">
                                <label for="nombre_producto_editar">Nombre del Producto</label>
                                <input type="text" class="form-control" id="nombre_producto_editar" name="nombre_producto_editar" required>
                                <div class="invalid-feedback">Por favor, ingrese un nombre válido para el producto.</div>
                            </div>
                            <div class="form-group">
                                <label for="descripcion_producto_editar">Descripción</label>
                                <input type="text" class="form-control" id="descripcion_producto_editar" name="descripcion_producto_editar" required>
                            </div>
                            <div class="form-group">
                                <label for="precio_producto_editar">Precio</label>
                                <input type="number" class="form-control" id="precio_producto_editar" name="precio_producto_editar" required>
                            </div>
                            <div class="form-group">
                                <label for="stock_producto_editar">Stock</label>
                                <input type="number" class="form-control" id="stock_producto_editar" name="stock_producto_editar" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para eliminar productos -->
        <div class="modal fade" id="modalEliminarProducto" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar Producto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formEliminarProducto">
                            <input type="hidden" id="producto_id_eliminar" name="producto_id_eliminar">
                            <p>¿Está seguro de eliminar este producto?</p>
                            <button type="submit" class="btn btn-primary">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php require_once('../html/footer.php') ?>
    <?php require_once('../html/scripts.php') ?>

    <script src="productos.js"></script>

    <script>
        // Tu código JavaScript aquí
    </script>
</body>
</html>
