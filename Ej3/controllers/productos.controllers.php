<?php
require_once('../models/productos.models.php');
require_once('../config/cors.php');

$producto = new Clase_Productos();
header('Content-Type: application/json'); // Establecer encabezado JSON desde el inicio

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos el cuerpo de la solicitud POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificamos que se haya enviado el parámetro 'op'
    if (isset($data['op'])) {
        switch ($data['op']) {
            case "todos":
                $datos = $producto->todos();
                if ($datos !== false) {
                    echo json_encode($datos);
                } else {
                    echo json_encode(array("error" => "No se encontraron productos."));
                }
                break;
            case "insertar":
                if (isset($data["nombre"], $data["descripcion"], $data["precio"], $data["stock"])) {
                    $nombre = $data["nombre"];
                    $descripcion = $data["descripcion"];
                    $precio = $data["precio"];
                    $stock = $data["stock"];
                    $resultado = $producto->insertar($nombre, $descripcion, $precio, $stock);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al insertar el producto: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para insertar el producto."));
                }
                break;
            case "actualizar":
                if (isset($data["producto_id"], $data["nombre"], $data["descripcion"], $data["precio"], $data["stock"])) {
                    $producto_id = $data["producto_id"];
                    $nombre = $data["nombre"];
                    $descripcion = $data["descripcion"];
                    $precio = $data["precio"];
                    $stock = $data["stock"];
                    $resultado = $producto->actualizar($producto_id, $nombre, $descripcion, $precio, $stock);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al actualizar el producto: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para actualizar el producto."));
                }
                break;
            case "eliminar":
                if (isset($data["producto_id"])) {
                    $producto_id = $data["producto_id"];
                    $resultado = $producto->eliminar($producto_id);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al eliminar el producto: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro 'producto_id' para eliminar el producto."));
                }
                break;
            case "detalle":
                if (isset($data["producto_id"])) {
                    $producto_id = $data["producto_id"];
                    try {
                        $productoDetalle = $producto->buscarPorId($producto_id);
                        if ($productoDetalle) {
                            echo json_encode($productoDetalle);
                        } else {
                            echo json_encode(array("resultado" => "error", "error" => "No se encontró el producto."));
                        }
                    } catch (Exception $e) {
                        error_log("Error al obtener el detalle del producto: " . $e->getMessage());
                        echo json_encode(array("resultado" => "error", "error" => "Error al obtener el detalle del producto."));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro ID para obtener el detalle del producto."));
                }
                break;
            case "buscarPorNombre":
                if (isset($data["nombre"])) {
                    $nombre = $data["nombre"];
                    $productosEncontrados = $producto->buscarPorNombre($nombre);
                    if ($productosEncontrados !== false) {
                        echo json_encode($productosEncontrados);
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al buscar productos por nombre."));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro 'nombre' para buscar productos por nombre."));
                }
                break;
            default:
                echo json_encode(array("resultado" => "error", "error" => "Operación no válida."));
                break;
        }
    } else {
        echo json_encode(array("resultado" => "error", "error" => "No se especificó la operación."));
    }
} else {
    echo json_encode(array("resultado" => "error", "error" => "Método no permitido."));
}
?>
