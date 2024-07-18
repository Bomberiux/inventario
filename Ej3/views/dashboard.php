<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Librería</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
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
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav flex-column">
            <li class="nav-item active">
                <a class="nav-link" href="#">Inicio<span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./productos/productos.php">Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./proveedores/proveedores.php">Proveedores</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="./ordencompra/ordencompra.php">Orden de Compra</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container">
    <div class="jumbotron text-center">
        <h1>Bienvenidos !</h1>
        <p></p>
    </div>

    <div class="jumbotron text-center">
        <img src="/PruebaParcial/public/img/Dashboard.jpg" alt="Dashboard Image" style="max-width: 44%; height: auto;">
    </div>

    <?php require_once('./html/footer.php') ?>
    <?php require_once('./html/scripts.php') ?>
</div>
</body>
</html>
