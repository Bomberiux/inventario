<?php
require_once('../config/conexion.php');

class Clase_OrdenesCompra
{
    private $conexion;

    public function __construct()
    {
        $con = new Clase_Conectar();
        $this->conexion = $con->Procedimiento_Conectar();
    }

    public function todos()
    {
        try {
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
                            
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
            }
            
            $stmt->execute();
            $resultado = $stmt->get_result();
            
            $ordenesCompra = array();
            while ($fila = $resultado->fetch_assoc()) {
                $ordenesCompra[] = $fila;
            }
            
            $stmt->close();
            return $ordenesCompra;
        } catch (Exception $e) {
            error_log("Error en la consulta todos() de ordenes-compra: " . $e->getMessage());
            return false;
        }
    }

    private function obtenerIdProductoPorNombre($nombre_producto)
    {
        $consulta = "SELECT producto_id FROM productos WHERE nombre = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de producto: " . $this->conexion->error);
        }
        $stmt->bind_param("s", $nombre_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $producto = $resultado->fetch_assoc();
        $stmt->close();
        return $producto['producto_id'] ?? null;
    }

    private function obtenerIdProveedorPorNombre($nombre_proveedor)
    {
        $consulta = "SELECT proveedor_id FROM proveedores WHERE nombre = ?";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            throw new Exception("Error en la preparación de la consulta de proveedor: " . $this->conexion->error);
        }
        $stmt->bind_param("s", $nombre_proveedor);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $proveedor = $resultado->fetch_assoc();
        $stmt->close();
        return $proveedor['proveedor_id'] ?? null;
    }

    public function insertar($nombre_producto, $nombre_proveedor, $cantidad, $fecha)
    {
        try {
            if (empty($nombre_producto) || empty($nombre_proveedor)) {
                throw new Exception("El nombre del producto o proveedor no puede estar vacío.");
            }

            $producto_id = $this->obtenerIdProductoPorNombre($nombre_producto);
            $proveedor_id = $this->obtenerIdProveedorPorNombre($nombre_proveedor);
            
            if (!$producto_id) {
                throw new Exception("El producto con nombre '$nombre_producto' no fue encontrado.");
            }
            if (!$proveedor_id) {
                throw new Exception("El proveedor con nombre '$nombre_proveedor' no fue encontrado.");
            }

            $consulta = "INSERT INTO ordenescompra (producto_id, proveedor_id, cantidad, fecha) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de inserción: " . $this->conexion->error);
            }
            $stmt->bind_param("ssis", $producto_id, $proveedor_id, $cantidad, $fecha);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                throw new Exception("Error al ejecutar la consulta de inserción: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar orden de compra: " . $e->getMessage());
            return false;
        }
    }

    public function actualizar($orden_id, $nombre_producto, $nombre_proveedor, $cantidad, $fecha)
    {
        try {
            if (empty($nombre_producto) || empty($nombre_proveedor)) {
                throw new Exception("El nombre del producto o proveedor no puede estar vacío.");
            }

            $producto_id = $this->obtenerIdProductoPorNombre($nombre_producto);
            $proveedor_id = $this->obtenerIdProveedorPorNombre($nombre_proveedor);
            
            if (!$producto_id) {
                throw new Exception("El producto con nombre '$nombre_producto' no fue encontrado.");
            }
            if (!$proveedor_id) {
                throw new Exception("El proveedor con nombre '$nombre_proveedor' no fue encontrado.");
            }

            $consulta = "UPDATE ordenescompra SET producto_id = ?, proveedor_id = ?, cantidad = ?, fecha = ? WHERE orden_id = ?";
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de actualización: " . $this->conexion->error);
            }
            $stmt->bind_param("ssiis", $producto_id, $proveedor_id, $cantidad, $fecha, $orden_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                throw new Exception("Error al ejecutar la consulta de actualización: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar orden de compra: " . $e->getMessage());
            return false;
        }
    }

    public function eliminar($orden_id)
    {
        try {
            $consulta = "DELETE FROM ordenescompra WHERE orden_id=?";
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de eliminación: " . $this->conexion->error);
            }
            $stmt->bind_param("i", $orden_id);
            
            if ($stmt->execute()) {
                $stmt->close();
                return true;
            } else {
                throw new Exception("Error al ejecutar la consulta de eliminación: " . $stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar orden de compra: " . $e->getMessage());
            return false;
        }
    }

    public function buscarPorProducto($nombre_producto)
    {
        try {
            $consulta = "SELECT oc.*, p.nombre AS nombre_producto, pr.nombre AS nombre_proveedor
                        FROM ordenescompra oc
                        INNER JOIN productos p ON oc.producto_id = p.producto_id
                        INNER JOIN proveedores pr ON oc.proveedor_id = pr.proveedor_id
                        WHERE p.nombre LIKE ?";
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
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
        }
    }

    public function buscarPorProveedor($nombre_proveedor)
    {
        try {
            $consulta = "SELECT oc.*, p.nombre AS nombre_producto, pr.nombre AS nombre_proveedor
                        FROM ordenescompra oc
                        INNER JOIN productos p ON oc.producto_id = p.producto_id
                        INNER JOIN proveedores pr ON oc.proveedor_id = pr.proveedor_id
                        WHERE pr.nombre LIKE ?";
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta: " . $this->conexion->error);
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
        }
    }
}
?>
