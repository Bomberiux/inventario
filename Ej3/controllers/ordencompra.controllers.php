<?php
require_once('../models/ordencompra.models.php');
require_once('../config/cors.php');

$ordenes_compra = new Clase_OrdenesCompra();
header('Content-Type: application/json'); 
// Establecer encabezado JSON desde el inicio

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['op'])) {
        switch ($data['op']) {
            case "todos":
                $datos = $ordenes_compra->todos();
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

                    try {
                        $resultado = $ordenes_compra->insertar($producto_id, $proveedor_id, $cantidad, $fecha);
                        if ($resultado === "ok") {
                            echo json_encode(["status" => "ok"]);
                        } else {
                            throw new Exception($resultado);
                        }
                    } catch (Exception $e) {
                        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
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

                    try {
                        $resultado = $ordenes_compra->actualizar($orden_id, $producto_id, $proveedor_id, $cantidad, $fecha);
                        if ($resultado === "ok") {
                            echo json_encode(["status" => "ok"]);
                        } else {
                            throw new Exception($resultado);
                        }
                    } catch (Exception $e) {
                        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Faltan parámetros para actualizar la orden de compra."]);
                }
                break;

            case 'eliminar':
                if (isset($data["orden_id"])) {
                    $orden_id = $data["orden_id"];
                    $resultado = $ordenes_compra->eliminar($orden_id);
                    if ($resultado === "ok") {
                        echo json_encode(["status" => "ok"]);
                    } else {
                        echo json_encode(["status" => "error", "message" => $resultado]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro 'orden_id' para eliminar la orden de compra."]);
                }
                break;

            case 'detalle':
                if (isset($data['orden_id'])) {
                    $orden_id = $data['orden_id'];
                    $resultado = $ordenes_compra->buscarPorId($orden_id);
                    if ($resultado) {
                        echo json_encode($resultado);
                    } else {
                        echo json_encode(["status" => "error", "message" => "No se encontró la orden de compra con id $orden_id"]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro 'orden_id'"]);
                }
                break;

            case 'buscarPorProducto':
                if (isset($data['nombre_producto'])) {
                    $producto_id = $data['nombre_producto'];
                    $ordenes_encontradas = $ordenes_compra->buscarPorProducto($nombre_producto);
                    if ($ordenes_encontradas !== false) {
                        echo json_encode($ordenes_encontradas);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Error al buscar órdenes de compra por producto."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro 'producto_id' para buscar órdenes de compra por producto."]);
                }
                break;

            case 'buscarPorProveedor':
                if (isset($data['nombre_proveedor'])) {
                    $proveedor_id = $data['nombre_proveedor'];
                    $ordenes_encontradas = $ordenes_compra->buscarPorProveedor($nombre_proveedor);
                    if ($ordenes_encontradas !== false) {
                        echo json_encode($ordenes_encontradas);
                    } else {
                        echo json_encode(["status" => "error", "message" => "Error al buscar órdenes de compra por proveedor."]);
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Falta el parámetro 'proveedor_id' para buscar órdenes de compra por proveedor."]);
                }
                break;

            default:
                echo json_encode(["status" => "error", "message" => "Operación no válida."]);
                break;
        }
    } else {
        echo json_encode(["status" => "error", "message" => "No se especificó la operación."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Método no permitido."]);
}
?>
