<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('../html/head.php') ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <style>
        body {
            background-color: #e0f7fa; /* Cambio de color de fondo */
        }
        .jumbotron {
            background-color: #ffffff; /* Cambio de color de fondo del jumbotron */
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            color: #00796b; /* Cambio de color de los enlaces de navegación */
        }
        .nav-link:hover {
            color: #004d40; /* Cambio de color de los enlaces de navegación al pasar el ratón */
        }
        .nav-item.active {
            background-color: #4db6ac; /* Cambio de color del fondo del ítem activo */
            border-radius: 10px;
        }
        .nav-item.active .nav-link {
            color: #ffffff; /* Cambio de color del texto del ítem activo */
        }
        .navbar-vertical {
            height: 100xp;
            position: fixed;
            left: 0;
            top: 0;
            width: 200px;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            margin-left: 220px; /* Espacio para la barra de navegación vertical */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-vertical">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav flex-column">
                <li class="nav-item <?php echo ($pagina_actual == 'Inicio') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../dashboard.php">Inicio <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item <?php echo ($pagina_actual == 'Productos') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../productos/productos.php">Productos</a>
                </li>
                <li class="nav-item <?php echo ($pagina_actual == 'Proveedor') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../proveedores/proveedores.php">Proveedor</a>
                </li>
                <li class="nav-item <?php echo ($pagina_actual == 'Orden De Compra') ? 'active' : ''; ?>">
                    <a class="nav-link" href="../ordencompra/ordencompra.php">Orden de Compra</a>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="jumbotron text-center">
            <h1>Bienvenidos!</h1>
            <p>Este es un proyecto realizado por Bomberiux.</p>
        </div>
        <!-- Contenido adicional según la página actual -->
    </div>

    <!-- Scripts al final del body -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
