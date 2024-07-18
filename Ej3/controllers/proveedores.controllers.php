<?php
require_once('../models/proveedores.models.php');
require_once('../config/cors.php');

$proveedor = new Clase_Proveedores();
header('Content-Type: application/json'); // Establecer encabezado JSON desde el inicio

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtenemos el cuerpo de la solicitud POST
    $data = json_decode(file_get_contents('php://input'), true);

    // Verificamos que se haya enviado el parámetro 'op'
    if (isset($data['op'])) {
        switch ($data['op']) {
            case "todos":
                $datos = $proveedor->todos();
                if ($datos !== false) {
                    echo json_encode($datos);
                } else {
                    echo json_encode(array("error" => "No se encontraron proveedores."));
                }
                break;
            case "insertar":
                if (isset($data["nombre"], $data["direccion"], $data["telefono"], $data["email"])) {
                    $nombre = $data["nombre"];
                    $direccion = $data["direccion"];
                    $telefono = $data["telefono"];
                    $email = $data["email"];
                    $resultado = $proveedor->insertar($nombre, $direccion, $telefono, $email);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al insertar el proveedor: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para insertar el proveedor."));
                }
                break;
            case "actualizar":
                if (isset($data["proveedor_id"], $data["nombre"], $data["direccion"], $data["telefono"], $data["email"])) {
                    $proveedor_id = $data["proveedor_id"];
                    $nombre = $data["nombre"];
                    $direccion = $data["direccion"];
                    $telefono = $data["telefono"];
                    $email = $data["email"];
                    $resultado = $proveedor->actualizar($proveedor_id, $nombre, $direccion, $telefono, $email);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al actualizar el proveedor: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Faltan parámetros para actualizar el proveedor."));
                }
                break;
            case "eliminar":
                if (isset($data["proveedor_id"])) {
                    $proveedor_id = $data["proveedor_id"];
                    $resultado = $proveedor->eliminar($proveedor_id);
                    if ($resultado === "ok") {
                        echo json_encode(array("resultado" => "ok"));
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al eliminar el proveedor: " . $resultado));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro 'proveedor_id' para eliminar el proveedor."));
                }
                break;
            case "detalle":
                if (isset($data["proveedor_id"])) {
                    $proveedor_id = $data["proveedor_id"];
                    try {
                        $proveedorDetalle = $proveedor->buscarPorId($proveedor_id);
                        if ($proveedorDetalle) {
                            echo json_encode($proveedorDetalle);
                        } else {
                            echo json_encode(array("resultado" => "error", "error" => "No se encontró el proveedor."));
                        }
                    } catch (Exception $e) {
                        error_log("Error al obtener el detalle del proveedor: " . $e->getMessage());
                        echo json_encode(array("resultado" => "error", "error" => "Error al obtener el detalle del proveedor."));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro ID para obtener el detalle del proveedor."));
                }
                break;
            case "buscarPorNombre":
                if (isset($data["nombre"])) {
                    $nombre = $data["nombre"];
                    $proveedoresEncontrados = $proveedor->buscarPorNombre($nombre);
                    if ($proveedoresEncontrados !== false) {
                        echo json_encode($proveedoresEncontrados);
                    } else {
                        echo json_encode(array("resultado" => "error", "error" => "Error al buscar proveedores por nombre."));
                    }
                } else {
                    echo json_encode(array("resultado" => "error", "error" => "Falta el parámetro 'nombre' para buscar proveedores por nombre."));
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
