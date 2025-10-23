<?php
class Conexion {
    private $host = "localhost";
    private $usuario = "root";
    private $password = "";
    private $base_datos = "sistema_vuelos";
    public $conexion;

    public function conectar() {
        $this->conexion = new mysqli($this->host, $this->usuario, $this->password, $this->base_datos);
        if ($this->conexion->connect_error) {
            die("Error de conexión: " . $this->conexion->connect_error);
        }
        $this->conexion->set_charset("utf8mb4");
        return $this->conexion;
    }
}
?>
