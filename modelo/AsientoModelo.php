<?php
require_once __DIR__ . '/Conexion.php';

class AsientoModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->conectar();
    }

    public function obtenerAsientosPorVuelo($vueloId) {
        $sql = "SELECT codigo_asiento, estado 
                FROM tb_asiento_vuelo 
                WHERE vuelo_id = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $vueloId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
