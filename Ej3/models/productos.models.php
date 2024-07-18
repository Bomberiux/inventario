<?php
require_once('../config/conexion.php');

class Clase_Productos
{
    public function todos()
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT * FROM productos";
            $resultado = mysqli_query($conexion, $consulta);
            
            if ($resultado === false) {
                throw new Exception(mysqli_error($conexion));
            }
            
            $productos = array();
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $productos[] = $fila;
            }
            
            return $productos;
        } catch (Exception $e) {
            error_log("Error en la consulta todos(): " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function insertar($nombre, $descripcion, $precio, $stock)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("ssdi", $nombre, $descripcion, $precio, $stock);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function actualizar($producto_id, $nombre, $descripcion, $precio, $stock)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "UPDATE productos SET nombre=?, descripcion=?, precio=?, stock=? WHERE producto_id=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("ssdii", $nombre, $descripcion, $precio, $stock, $producto_id);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function eliminar($producto_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "DELETE FROM productos WHERE producto_id=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("i", $producto_id);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar producto: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorId($producto_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT * FROM productos WHERE producto_id=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $producto_id);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $producto = $resultado->fetch_assoc();
                    return $producto;
                } else {
                    throw new Exception("No se encontró el producto.");
                }
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar producto por ID: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorNombre($nombre)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT * FROM productos WHERE nombre LIKE ?";
            $stmt = $conexion->prepare($consulta);
            $nombreBusqueda = "%" . $nombre . "%";
            $stmt->bind_param("s", $nombreBusqueda);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $productos = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $productos[] = $fila;
                }
                return $productos;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar productos por nombre: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }
}
?>
