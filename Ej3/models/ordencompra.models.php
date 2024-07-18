<?php
require_once('../config/conexion.php');

class Clase_OrdenesCompra
{

    public function todos()
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT 
                            oc.orden_id,
                            oc.producto_id,
                            p.nombre AS nombre_producto,
                            oc.proveedor_id,
                            pr.nombre AS nombre_proveedor,
                            oc.cantidad,
                            oc.fecha
                        FROM 
                            ordenescompra oc
                        LEFT JOIN 
                            productos p ON oc.producto_id = p.producto_id
                        LEFT JOIN 
                            proveedores pr ON oc.proveedor_id = pr.proveedor_id";
                            
            $stmt = $conexion->prepare($consulta);
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            $ordenesCompra = array();
            while ($fila = $resultado->fetch_assoc()) {
                $ordenesCompra[] = $fila;
            }
            
            $stmt->close();
            $conexion->close();
            
            return $ordenesCompra;
        } catch (Exception $e) {
            error_log("Error en la consulta todos() de ordenes de compra: " . $e->getMessage());
            return false;
        }
    }

    public function insertar($producto_id, $proveedor_id, $cantidad, $fecha)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "INSERT INTO ordenescompra (producto_id, proveedor_id, cantidad, fecha) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de inserción de orden de compra: " . $conexion->error);
            }
            $stmt->bind_param("iiis", $producto_id, $proveedor_id, $cantidad, $fecha);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar orden de compra: " . $e->getMessage());
            return "Error al insertar la orden de compra: " . $e->getMessage();
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function actualizar($orden_id, $producto_id, $proveedor_id, $cantidad, $fecha)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "UPDATE ordenescompra SET producto_id = ?, proveedor_id = ?, cantidad = ?, fecha = ? WHERE orden_id = ?";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de actualización de orden de compra: " . $conexion->error);
            }
            $stmt->bind_param("iiisi", $producto_id, $proveedor_id, $cantidad, $fecha, $orden_id);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar orden de compra: " . $e->getMessage());
            return "Error al actualizar la orden de compra: " . $e->getMessage();
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function eliminar($orden_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "DELETE FROM ordenescompra WHERE orden_id=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $orden_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                $conexion->close();
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar orden de compra: " . $e->getMessage());
            return "Error al eliminar la orden de compra: " . $e->getMessage();
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorProducto($producto_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT * FROM ordenescompra WHERE producto_id=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $producto_id);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $ordenesCompra = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $ordenesCompra[] = $fila;
                }
                return $ordenesCompra;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar órdenes de compra por producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorProveedor($proveedor_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT * FROM ordenescompra WHERE proveedor_id=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $proveedor_id);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $ordenesCompra = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $ordenesCompra[] = $fila;
                }
                return $ordenesCompra;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar órdenes de compra por proveedor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorId($orden_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT * FROM ordenescompra WHERE orden_id=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $orden_id);
            
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $ordenCompra = $resultado->fetch_assoc();
                    return $ordenCompra;
                } else {
                    throw new Exception("No se encontró la orden de compra con ID $orden_id");
                }
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar orden de compra por ID: " . $e->getMessage());
            return false;
        } finally {
            if (isset($stmt)) {
                $stmt->close();
            }
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }
}
?>
