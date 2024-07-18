<?php
require_once('../config/conexion.php');

class Clase_Proveedores
{
    public function todos()
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "SELECT * FROM proveedores";
            $resultado = mysqli_query($conexion, $consulta);
            
            if ($resultado === false) {
                throw new Exception(mysqli_error($conexion));
            }
            
            $proveedores = array();
            while ($fila = mysqli_fetch_assoc($resultado)) {
                $proveedores[] = $fila;
            }
            
            return $proveedores;
        } catch (Exception $e) {
            error_log("Error en la consulta todos(): " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function insertar($nombre, $direccion, $telefono)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "INSERT INTO proveedores (nombre, direccion, telefono) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("sss", $nombre, $direccion, $telefono);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al insertar proveedor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function actualizar($proveedor_id, $nombre, $direccion, $telefono)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "UPDATE proveedores SET nombre=?, direccion=?, telefono=? WHERE proveedor_id=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("sssi", $nombre, $direccion, $telefono, $proveedor_id);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al actualizar proveedor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function eliminar($proveedor_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();
            
            $consulta = "DELETE FROM proveedores WHERE proveedor_id=?";
            $stmt = $conexion->prepare($consulta);
            
            $stmt->bind_param("i", $proveedor_id);
            
            if ($stmt->execute()) {
                return "ok";
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al eliminar proveedor: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }

    public function buscarPorId($proveedor_id)
    {
        try {
            $con = new Clase_Conectar();
            $conexion = $con->Procedimiento_Conectar();

            $consulta = "SELECT * FROM proveedores WHERE proveedor_id=?";
            $stmt = $conexion->prepare($consulta);
            $stmt->bind_param("i", $proveedor_id);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                if ($resultado->num_rows === 1) {
                    $proveedor = $resultado->fetch_assoc();
                    return $proveedor;
                } else {
                    throw new Exception("No se encontró el proveedor.");
                }
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar proveedor por ID: " . $e->getMessage());
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

            $consulta = "SELECT * FROM proveedores WHERE nombre LIKE ?";
            $stmt = $conexion->prepare($consulta);
            $nombreBusqueda = "%" . $nombre . "%";
            $stmt->bind_param("s", $nombreBusqueda);

            if ($stmt->execute()) {
                $resultado = $stmt->get_result();
                $proveedores = array();
                while ($fila = $resultado->fetch_assoc()) {
                    $proveedores[] = $fila;
                }
                return $proveedores;
            } else {
                throw new Exception($stmt->error);
            }
        } catch (Exception $e) {
            error_log("Error al buscar proveedores por nombre: " . $e->getMessage());
            return false;
        } finally {
            if (isset($conexion)) {
                $conexion->close();
            }
        }
    }
}
?>
