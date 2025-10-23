<?php
require_once __DIR__ . '/Conexion.php';

class AsientoModelo {
    private $conn;

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    // 🔹 Obtiene todos los asientos de un vuelo
    public function obtenerAsientosPorVuelo($vueloId) {
        $stmt = $this->conn->prepare("SELECT * FROM tb_asiento_vuelo WHERE vuelo_id = ? ORDER BY fila, letra");
        $stmt->bind_param("i", $vueloId);
        $stmt->execute();
        $res = $stmt->get_result();
        $asientos = $res->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $asientos;
    }

    // 🔹 Contar compras hechas hoy por un usuario
    public function contarComprasHoy($usuarioId, $vueloId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS total FROM tb_asiento_vuelo WHERE usuario_id = ? AND vuelo_id = ? AND DATE(fecha_reserva) = CURDATE()");
        $stmt->bind_param("ii", $usuarioId, $vueloId);
        $stmt->execute();
        $total = $stmt->get_result()->fetch_assoc()['total'];
        $stmt->close();
        return $total;
    }

    // 🔹 Marcar un asiento como pagado
    public function marcarAsientoPagado($vueloId, $usuarioId, $codigo) {
        $stmt = $this->conn->prepare("UPDATE tb_asiento_vuelo SET estado = 'Pagado', usuario_id = ?, fecha_reserva = NOW() WHERE vuelo_id = ? AND codigo_asiento = ? AND estado = 'Disponible'");
        $stmt->bind_param("iis", $usuarioId, $vueloId, $codigo);
        $stmt->execute();
        $stmt->close();
    }
}
?>
