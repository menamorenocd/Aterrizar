<?php
require_once __DIR__ . '/Conexion.php';

class CompraModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->conectar();
    }

    /**
     * Registrar compra principal
     */
    public function registrarCompra($vueloId, $usuarioId) {
        $sql = "INSERT INTO tb_compra (vuelo_id, usuario_id, fecha_compra, estado)
                VALUES (?, ?, NOW(), 'Confirmada')";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            return [
                'error' => true,
                'mensaje' => 'Error en SQL: ' . $this->conexion->error
            ];
        }

        $stmt->bind_param("ii", $vueloId, $usuarioId);
        if ($stmt->execute()) {
            $idCompra = $this->conexion->insert_id;

            // Consultar el número de factura generado por el trigger
            $factura = null;
            $q = $this->conexion->prepare("SELECT numero_factura FROM tb_compra WHERE id_compra = ?");
            $q->bind_param("i", $idCompra);
            $q->execute();
            $q->bind_result($factura);
            $q->fetch();
            $q->close();

            return [
                'error' => false,
                'id_compra' => $idCompra,
                'numero_factura' => $factura
            ];
        } else {
            return [
                'error' => true,
                'mensaje' => 'Error al registrar compra: ' . $stmt->error
            ];
        }
    }

    /**
     * Registrar detalle de asiento comprado
     */
    public function registrarDetalle($compraId, $vueloId, $codigoAsiento) {
        $sql = "INSERT INTO tb_detalle_compra (compra_id, vuelo_id, codigo_asiento)
                VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("iis", $compraId, $vueloId, $codigoAsiento);
        return $stmt->execute();
    }

    /**
     * Marcar asientos como ocupados
     */
    public function ocuparAsiento($vueloId, $codigoAsiento) {
        $sql = "UPDATE tb_asiento_vuelo SET estado='Ocupado'
                WHERE vuelo_id=? AND codigo_asiento=?";
        $stmt = $this->conexion->prepare($sql);
        if (!$stmt) return false;
        $stmt->bind_param("is", $vueloId, $codigoAsiento);
        return $stmt->execute();
    }
}
?>
