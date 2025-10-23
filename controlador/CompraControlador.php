<?php
require_once __DIR__ . '/../modelo/CompraModelo.php';

class CompraControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new CompraModelo();
    }

    public function procesarCompra($vueloId, $usuarioId, $asientosSeleccionados) {
        if (empty($asientosSeleccionados)) {
            return ['error' => true, 'mensaje' => 'No se seleccionaron asientos.'];
        }

        // Registrar compra principal
        $compra = $this->modelo->registrarCompra($vueloId, $usuarioId);
        if ($compra['error']) {
            return $compra;
        }

        $compraId = $compra['id_compra'];
        $factura = $compra['numero_factura'];

        // Registrar cada asiento y marcarlo ocupado
        foreach ($asientosSeleccionados as $codigoAsiento) {
            $this->modelo->registrarDetalle($compraId, $vueloId, $codigoAsiento);
            $this->modelo->ocuparAsiento($vueloId, $codigoAsiento);
        }

        return [
            'error' => false,
            'mensaje' => 'Compra registrada correctamente.',
            'id_compra' => $compraId,
            'numero_factura' => $factura
        ];
    }
}
?>
