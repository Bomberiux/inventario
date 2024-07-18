<?php
require_once('../models/ordencompra.models.php');
require_once('../config/cors.php');

$ordencompra = new Clase_OrdenesCompra();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['op'])) {
        switch ($data['op']) {
            case "todos":
                $datos = $ordencompra->todos();
                if ($datos !== false) {
                    echo json_encode($datos);
                } else {
                    echo json_encode(["status" => "error", "message" => "No se encontraron órdenes de compra."]);
                }
                break;

            case 'insertar':
                if (isset($data['producto_id'], $data['proveedor_id'], $data['cantidad'], $data['fecha'])) {
                    $producto_id = $data['producto_id'];
                    $proveedor_id = $data['proveedor_id'];
                    $cantidad = $data['cantidad'];
                    $fecha = $data['fecha'];
                    
                    $resultado = $ordencompra->insertar($producto_id, $proveedor_id, $cantidad, $fecha);
                    if ($resultado === true) {
                        echo json_encode(["status" => "ok"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => $resultado]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Faltan parámetros para insertar la orden de compra."]);
                }
                break;

            case 'actualizar':
                if (isset($data['orden_id'], $data['producto_id'], $data['proveedor_id'], $data['cantidad'], $data['fecha'])) {
                    $orden_id = $data['orden_id'];
                    $producto_id = $data['producto_id'];
                    $proveedor_id = $data['proveedor_id'];
                    $cantidad = $data['cantidad'];
                    $fecha = $data['fecha'];
                    
                    $resultado = $ordencompra->actualizar($orden_id, $producto_id, $proveedor_id, $cantidad, $fecha);
                    if ($resultado === true) {
                        echo json_encode(["status" => "ok"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => $resultado]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Faltan parámetros para actualizar la orden de compra."]);
                }
                break;

            case 'eliminar':
                if (isset($data["orden_id"])) {
                    $resultado = $ordencompra->eliminar($data["orden_id"]);
                    if ($resultado === true) {
                        echo json_encode(["status" => "ok"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => $resultado]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro ID para eliminar la orden de compra."]);
                }
                break;

            case "buscarPorProducto":
                if (isset($data['producto'])) {
                    $ordenes_encontradas = $ordencompra->buscarPorProducto($data['producto']);
                    if ($ordenes_encontradas !== false) {
                        echo json_encode($ordenes_encontradas);
                    } else {
                        echo json_encode(["status" => "error", "message" => "No se encontraron órdenes de compra para el producto especificado."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro de búsqueda para el producto."]);
                }
                break;

            case "buscarPorProveedor":
                if (isset($data['proveedor'])) {
                    $ordenes_encontradas = $ordencompra->buscarPorProveedor($data['proveedor']);
                    if ($ordenes_encontradas !== false) {
                        echo json_encode($ordenes_encontradas);
                    } else {
                        echo json_encode(["status" => "error", "message" => "No se encontraron órdenes de compra para el proveedor especificado."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro de búsqueda para el proveedor."]);
                }
                break;

            case "buscarPorId":
                if (isset($data['orden_id'])) {
                    $orden_encontrada = $ordencompra->buscarPorId($data['orden_id']);
                    if ($orden_encontrada !== false) {
                        echo json_encode($orden_encontrada);
                    } else {
                        echo json_encode(["status" => "error", "message" => "No se encontró la orden de compra especificada."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro de búsqueda para la orden de compra."]);
                }
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Operación no válida."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se especificó ninguna operación."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>
