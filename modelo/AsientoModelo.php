<?php
require_once __DIR__ . '/Conexion.php';

class AsientoModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->conectar();
    }

    /**
     * 🔹 Obtener todos los asientos de un vuelo
     */
    public function obtenerAsientosPorVuelo($vueloId) {
        $sql = "SELECT codigo_asiento, estado 
                FROM tb_asiento_vuelo 
                WHERE vuelo_id = ? 
                ORDER BY codigo_asiento ASC";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $vueloId);
        $stmt->execute();
        $res = $stmt->get_result();
        return $res->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * 🔹 Verificar si un asiento está disponible
     */
    public function verificarDisponible($vueloId, $codigoAsiento) {
        $sql = "SELECT estado FROM tb_asiento_vuelo 
                WHERE vuelo_id = ? AND codigo_asiento = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $vueloId, $codigoAsiento);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();
        return isset($res['estado']) && $res['estado'] === 'Disponible';
    }

    /**
     * 🔹 Marcar un asiento como ocupado
     */
    public function ocuparAsiento($vueloId, $codigoAsiento) {
        $sql = "UPDATE tb_asiento_vuelo 
                SET estado = 'Ocupado' 
                WHERE vuelo_id = ? AND codigo_asiento = ?";
        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("is", $vueloId, $codigoAsiento);
        return $stmt->execute();
    }
}
?>
