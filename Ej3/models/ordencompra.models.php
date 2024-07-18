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
                        JOIN 
                            productos p ON oc.producto_id = p.producto_id
                        JOIN 
                            proveedores pr ON oc.proveedor_id = pr.proveedor_id";
                            
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            $ordenesCompra = array();
            while ($fila = $resultado->fetch_assoc()) {
                $ordenesCompra[] = $fila;
            }
            
            $stmt->close();
            $conexion->close();
            
            return $ordenesCompra;  // Devuelve el array de órdenes de compra
        } catch (Exception $e) {
            error_log("Error en la consulta todos() de ordenes-compra: " . $e->getMessage());
            return false;
        }
    }

    public function insertar($producto_id, $proveedor_id, $cantidad, $fecha)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
    
            // Insertar la orden de compra
            $consulta = "INSERT INTO ordenescompra (producto_id, proveedor_id, cantidad, fecha) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de inserción: " . $conexion->error);
            }
            $stmt->bind_param("iiis", $producto_id, $proveedor_id, $cantidad, $fecha);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;  // Devuelve true cuando la inserción es exitosa
            } else {
                throw new Exception("Error al ejecutar la consulta de inserción: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar orden de compra: " . $e->getMessage());
            return false;
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
    
            // Actualizar la orden de compra
            $consulta = "UPDATE ordenescompra SET producto_id = ?, proveedor_id = ?, cantidad = ?, fecha = ? WHERE orden_id = ?";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de actualización: " . $conexion->error);
            }
            $stmt->bind_param("iiisi", $producto_id, $proveedor_id, $cantidad, $fecha, $orden_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;  // Devuelve true cuando la actualización es exitosa
            } else {
                throw new Exception("Error al ejecutar la consulta de actualización: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar orden de compra: " . $e->getMessage());
            return false;
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
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de eliminación: " . $conexion->error);
            }
            $stmt->bind_param("i", $orden_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;  // Devuelve true cuando la eliminación es exitosa
            } else {
                throw new Exception("Error al ejecutar la consulta de eliminación: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar orden de compra: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorProducto($nombre_producto)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
    
            $consulta = "SELECT oc.*, p.nombre AS nombre_producto, pr.nombre AS nombre_proveedor
                         FROM ordenescompra oc
                         INNER JOIN productos p ON oc.producto_id = p.producto_id
                         INNER JOIN proveedores pr ON oc.proveedor_id = pr.proveedor_id
                         WHERE p.nombre LIKE ?";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            $productoBusqueda = "%" . $nombre_producto . "%";
            $stmt->bind_param("s", $productoBusqueda);
    
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $ordenes_compra = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $ordenes_compra[] = $fila;
                }
                $stmt->close();
                return $ordenes_compra;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar órdenes por producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorProveedor($nombre_proveedor)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
    
            $consulta = "SELECT oc.*, p.nombre AS nombre_producto, pr.nombre AS nombre_proveedor
                         FROM ordenescompra oc
                         INNER JOIN productos p ON oc.producto_id = p.producto_id
                         INNER JOIN proveedores pr ON oc.proveedor_id = pr.proveedor_id
                         WHERE pr.nombre LIKE ?";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            $proveedorBusqueda = "%" . $nombre_proveedor . "%";
            $stmt->bind_param("s", $proveedorBusqueda);
    
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $ordenes_compra = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $ordenes_compra[] = $fila;
                }
                $stmt->close();
                return $ordenes_compra;
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar órdenes por proveedor: " . $e->getMessage());
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
    
            $consulta = "SELECT oc.*, p.nombre AS nombre_producto, pr.nombre AS nombre_proveedor
                         FROM ordenescompra oc
                         INNER JOIN productos p ON oc.producto_id = p.producto_id
                         INNER JOIN proveedores pr ON oc.proveedor_id = pr.proveedor_id
                         WHERE oc.orden_id = ?";
            $stmt = $conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $conexion->error);
            }
            $stmt->bind_param("i", $orden_id);
    
            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $orden_compra = $resultado->fetch_assoc();
                    $stmt->close();
                    return $orden_compra;
                } else {
                    throw new Exception("No se encontró la orden de compra.");
                }
            } else {
                throw new Exception("Error al ejecutar la consulta: " . $stmt->error);
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
