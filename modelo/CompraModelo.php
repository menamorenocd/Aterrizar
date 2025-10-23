<?php
require_once __DIR__ . '/Conexion.php';

class CompraModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->conectar();
    }

    /**
     * Registrar compra principal.
     * Ahora genera el registro y obtiene el número de factura automáticamente
     * gracias al trigger de la base de datos.
     */
    public function registrarCompra($vueloId, $usuarioId) {
        // Validar conexión y preparar la consulta
        $sql = "INSERT INTO tb_compra (vuelo_id, usuario_id, fecha_compra, estado)
                VALUES (?, ?, NOW(), 'Confirmada')";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            // Error al preparar la consulta
            return [
                'error' => true,
                'mensaje' => 'Error en la base de datos: ' . $this->conexion->error
            ];
        }

        $stmt->bind_param("ii", $vueloId, $usuarioId);

        if ($stmt->execute()) {
            $idCompra = $this->conexion->insert_id;

            // Consultar el número de factura generado automáticamente por el trigger
            $factura = null;
            $query = $this->conexion->prepare("SELECT numero_factura FROM tb_compra WHERE id_compra = ?");
            $query->bind_param("i", $idCompra);
            $query->execute();
            $query->bind_result($factura);
            $query->fetch();
            $query->close();

            return [
                'error' => false,
                'id_compra' => $idCompra,
                'numero_factura' => $factura
            ];
        }

        return [
            'error' => true,
            'mensaje' => '❌ No se pudo registrar la compra. ' . $stmt->error
        ];
    }

    /**
     * Registrar detalle de asiento comprado.
     */
    public function registrarDetalle($compraId, $vueloId, $codigoAsiento) {
        $sql = "INSERT INTO tb_detalle_compra (compra_id, vuelo_id, codigo_asiento)
                VALUES (?, ?, ?)";
        $stmt = $this->conexion->prepare($sql);

        if (!$stmt) {
            return false;
        }

        $stmt->bind_param("iis", $compraId, $vueloId, $codigoAsiento);
        return $stmt->execute();
    }
}
?>
