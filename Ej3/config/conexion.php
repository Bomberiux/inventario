<?php

// POO
class Clase_Conectar
{
    public $conexion;
    protected $db;
    private $server = "localhost";
    private $usu = "root";
    private $clave = "";  // Password for root user
    private $base = "inventario";

    public function Procedimiento_Conectar()
    {
        // Mostrar todos los errores
        error_reporting(E_ALL);
        
        $this->conexion = mysqli_connect($this->server, $this->usu, $this->clave, $this->base);
        if (!$this->conexion) {
            die("Error al conectarse con MySQL: " . mysqli_connect_error());
        }

        mysqli_query($this->conexion, "SET NAMES 'utf8'");
        
        $this->db = mysqli_select_db($this->conexion, $this->base);
        if (!$this->db) {
            die("Error conexión con la base de datos: " . mysqli_error($this->conexion));
        }

        return $this->conexion;
    }
}

// Crear una instancia y probar la conexión
$conectar = new Clase_Conectar();
$conectar->Procedimiento_Conectar();
?>
