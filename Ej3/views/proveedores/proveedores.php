<?php
$pagina_actual = 'Proveedores'; // Indica que estamos en la página de Proveedores
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <?php require_once('../html/head.php') ?>
    <title>Administración de Proveedores - Tu Sitio</title>
</head>
<body>
    <div class="container">
        <!-- Botón para abrir el modal de nuevo proveedor -->
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <button id="btnNuevoProveedor" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalNuevoProveedor">Nuevo Proveedor</button>
            </div>
        </div>

        <!-- Sección para mostrar todos los proveedores -->
        <div class="row mt-4">
            <div class="col-md-8 offset-md-2">
                <h2>Lista de Proveedores</h2>
                <div class="input-group mb-3">
                    <input type="text" id="inputBuscar" class="form-control" placeholder="Buscar por nombre...">
                </div>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cuerpoProveedores">
                        <!-- Aquí se mostrarán los proveedores -->
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal para registrar nuevo proveedor -->
        <div class="modal fade" id="modalNuevoProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Nuevo Proveedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario de nuevo proveedor -->
                        <form id="formNuevoProveedor">
                            <div class="form-group">
                                <label for="nombre_proveedor">Nombre del Proveedor</label>
                                <input type="text" class="form-control" id="nombre_proveedor" name="nombre_proveedor" required>
                            </div>
                            <div class="form-group">
                                <label for="direccion_proveedor">Dirección del Proveedor</label>
                                <input type="text" class="form-control" id="direccion_proveedor" name="direccion_proveedor" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono_proveedor">Teléfono del Proveedor</label>
                                <input type="text" class="form-control" id="telefono_proveedor" name="telefono_proveedor" required>
                            </div>
                            <div class="form-group">
                                <label for="email_proveedor">Email del Proveedor</label>
                                <input type="email" class="form-control" id="email_proveedor" name="email_proveedor" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar proveedores -->
        <div class="modal fade" id="modalEditarProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Editar Proveedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="formEditarProveedor" novalidate>
                        <div class="modal-body">
                            <input type="hidden" id="id_proveedor" name="id_proveedor">
                            <div class="form-group">
                                <label for="nombre_proveedor_editar">Nombre del Proveedor</label>
                                <input type="text" class="form-control" id="nombre_proveedor_editar" name="nombre_proveedor_editar" required>
                            </div>
                            <div class="form-group">
                                <label for="direccion_proveedor_editar">Dirección del Proveedor</label>
                                <input type="text" class="form-control" id="direccion_proveedor_editar" name="direccion_proveedor_editar" required>
                            </div>
                            <div class="form-group">
                                <label for="telefono_proveedor_editar">Teléfono del Proveedor</label>
                                <input type="text" class="form-control" id="telefono_proveedor_editar" name="telefono_proveedor_editar" required>
                            </div>
                            <div class="form-group">
                                <label for="email_proveedor_editar">Email del Proveedor</label>
                                <input type="email" class="form-control" id="email_proveedor_editar" name="email_proveedor_editar" required>
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

        <!-- Modal para eliminar proveedores -->
        <div class="modal fade" id="modalEliminarProveedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Eliminar Proveedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formEliminarProveedor">
                            <input type="hidden" id="id_proveedor_eliminar" name="id_proveedor_eliminar">
                            <p>¿Está seguro de eliminar este proveedor?</p>
                            <button type="submit" class="btn btn-primary">Eliminar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once('../html/footer.php') ?>
    <?php require_once('../html/scripts.php') ?>

    <script src="proveedores.js"></script>

    <script>
        // Tu código JavaScript aquí
    </script>
</body>
</html>
