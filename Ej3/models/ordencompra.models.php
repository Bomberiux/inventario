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
            
            $ordenesCompra = [];
            while ($fila = $resultado->fetch_assoc()) {
                $ordenesCompra[] = $fila;
            }
            
            $stmt->close();
            return $ordenesCompra;
        } catch (Exception $e) {
            error_log("Error en la consulta todos() de ordenescompra: " . $e->getMessage());
            return false;
        }
    }

    private function obtenerIdPorNombre($tabla, $nombre_columna, $nombre)
    {
        try {
            $consulta = "SELECT id FROM $tabla WHERE $nombre_columna = ?";
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de $tabla: " . $this->conexion->error);
            }
            $stmt->bind_param("s", $nombre);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $registro = $resultado->fetch_assoc();
            $stmt->close();
            return $registro['id'] ?? null;
        } catch (Exception $e) {
            error_log("Error al obtener el ID por nombre en $tabla: " . $e->getMessage());
            return null;
        }
    }

    public function obtenerIdProductoPorNombre($nombre_producto)
    {
        return $this->obtenerIdPorNombre('productos', 'nombre', $nombre_producto);
    }

    public function obtenerIdProveedorPorNombre($nombre_proveedor)
    {
        return $this->obtenerIdPorNombre('proveedores', 'nombre', $nombre_proveedor);
    }

    public function insertarOrdenCompra($nombre_producto, $nombre_proveedor, $cantidad, $fecha)
    {
        try {
            // Obtener el id_producto basado en el nombre_producto
            $consulta_producto = "SELECT producto_id FROM productos WHERE nombre = ?";
            $stmt_producto = $this->conexion->prepare($consulta_producto);
            if (!$stmt_producto) {
                throw new Exception("Error en la preparación de la consulta del producto: " . $this->conexion->error);
            }
            $stmt_producto->bind_param("s", $nombre_producto);
            $stmt_producto->execute();
            $stmt_producto->bind_result($producto_id);
            
            if (!$stmt_producto->fetch()) {
                throw new Exception("El producto '$nombre_producto' no fue encontrado.");
            }
            $stmt_producto->close();
            
            // Obtener el id_proveedor basado en el nombre_proveedor
            $consulta_proveedor = "SELECT proveedor_id FROM proveedores WHERE nombre = ?";
            $stmt_proveedor = $this->conexion->prepare($consulta_proveedor);
            if (!$stmt_proveedor) {
                throw new Exception("Error en la preparación de la consulta del proveedor: " . $this->conexion->error);
            }
            $stmt_proveedor->bind_param("s", $nombre_proveedor);
            $stmt_proveedor->execute();
            $stmt_proveedor->bind_result($proveedor_id);
            
            if (!$stmt_proveedor->fetch()) {
                throw new Exception("El proveedor '$nombre_proveedor' no fue encontrado.");
            }
            $stmt_proveedor->close();
            
            // Insertar la orden de compra con los ids obtenidos
            $consulta = "INSERT INTO ordenescompra (producto_id, proveedor_id, cantidad, fecha) VALUES (?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($consulta);
            if (!$stmt) {
                throw new Exception("Error en la preparación de la consulta de orden de compra: " . $this->conexion->error);
            }
            $stmt->bind_param("iiis", $producto_id, $proveedor_id, $cantidad, $fecha);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar la orden de compra: " . $e->getMessage());
            return "Error al insertar la orden de compra: " . $e->getMessage();
        }
    }

    public function actualizar($orden_id, $nombre_producto, $nombre_proveedor, $cantidad, $fecha)
    {
        try {
            if (empty($nombre_producto) || empty($nombre_proveedor) || empty($cantidad) || empty($fecha)) {
                throw new Exception("Todos los campos deben ser llenados.");
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
                $ordenes_compra = [];
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
                $ordenes_compra = [];
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
