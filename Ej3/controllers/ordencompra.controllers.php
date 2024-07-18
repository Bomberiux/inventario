<?php
require_once('../models/ordencompra.models.php');
require_once('../config/cors.php');

$ordencompra = new Clase_OrdenesCompra();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['op'])) {
        switch ($data['op']) {
            case 'todos':
                $datos = $ordencompra->todos();
                echo json_encode($datos !== false ? $datos : [
                    "status" => "error",
                    "message" => "No se encontraron órdenes de compra."
                ]);
                break;

            case 'insertar':
                if (isset($data['nombre_producto'], $data['nombre_proveedor'], $data['cantidad'], $data['fecha'])) {
                    $nombre_producto = $data['nombre_producto'];
                    $nombre_proveedor = $data['nombre_proveedor'];
                    $cantidad = (int) $data['cantidad'];
                    $fecha = $data['fecha']; // Asegúrate de que el formato de la fecha sea correcto

                    try {
                        $resultado = $ordencompra->insertarOrdenCompra($nombre_producto, $nombre_proveedor, $cantidad, $fecha);
                        if ($resultado === "ok") {
                            echo json_encode(["status" => "ok"]);
                        } else {
                            throw new Exception($resultado);
                        }
                    } catch (Exception $e) {
                        echo json_encode([
                            "status" => "error",
                            "message" => $e->getMessage()
                        ]);
                    }
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Faltan parámetros para insertar la orden de compra."
                    ]);
                }
                break;

            case 'actualizar':
                if (isset($data['orden_id'], $data['producto_id'], $data['proveedor_id'], $data['cantidad'], $data['fecha'])) {
                    $resultado = $ordencompra->actualizar(
                        $data['orden_id'],
                        $data['producto_id'],
                        $data['proveedor_id'],
                        $data['cantidad'],
                        $data['fecha']
                    );
                    echo json_encode($resultado === "ok" ? ["status" => "ok"] : [
                        "status" => "error",
                        "message" => $resultado
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Faltan parámetros para actualizar la orden de compra."
                    ]);
                }
                break;

            case 'eliminar':
                if (isset($data['orden_id'])) {
                    $resultado = $ordencompra->eliminar($data['orden_id']);
                    echo json_encode($resultado === "ok" ? ["status" => "ok"] : [
                        "status" => "error",
                        "message" => $resultado
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Falta el parámetro ID para eliminar la orden de compra."
                    ]);
                }
                break;

            case 'buscarPorProducto':
                if (isset($data['producto'])) {
                    $ordenes_encontradas = $ordencompra->buscarPorProducto($data['producto']);
                    echo json_encode($ordenes_encontradas !== false ? $ordenes_encontradas : [
                        "status" => "error",
                        "message" => "No se encontraron órdenes de compra para el producto especificado."
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Falta el parámetro de búsqueda para el producto."
                    ]);
                }
                break;

            case 'buscarPorProveedor':
                if (isset($data['proveedor'])) {
                    $ordenes_encontradas = $ordencompra->buscarPorProveedor($data['proveedor']);
                    echo json_encode($ordenes_encontradas !== false ? $ordenes_encontradas : [
                        "status" => "error",
                        "message" => "No se encontraron órdenes de compra para el proveedor especificado."
                    ]);
                } else {
                    echo json_encode([
                        "status" => "error",
                        "message" => "Falta el parámetro de búsqueda para el proveedor."
                    ]);
                }
                break;

            default:
                echo json_encode([
                    "status" => "error",
                    "message" => "Operación no válida."
                ]);
        }
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "No se especificó ninguna operación."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método no permitido."
    ]);
}
?>
