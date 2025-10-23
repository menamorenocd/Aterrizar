<?php
require_once __DIR__ . '/../modelo/CompraModelo.php';
require_once __DIR__ . '/../modelo/AsientoModelo.php';

class CompraControlador {
    private $modelo;
    private $asientoModelo;

    public function __construct() {
        $this->modelo = new CompraModelo();
        $this->asientoModelo = new AsientoModelo();
    }

    /**
     * Procesar la compra de uno o varios asientos.
     * @param int $vueloId
     * @param int $usuarioId
     * @param array $asientosSeleccionados
     * @return array con ['error'=>bool, 'mensaje'=>string]
     */
    public function procesarCompra($vueloId, $usuarioId, $asientosSeleccionados) {
        if (empty($asientosSeleccionados)) {
            return ['error' => true, 'mensaje' => 'No seleccionaste ningún asiento.'];
        }

        $compraExitosa = false;

        // Iniciar una compra principal
        $compraId = $this->modelo->registrarCompra($vueloId, $usuarioId);
        if (!$compraId) {
            return ['error' => true, 'mensaje' => 'No se pudo crear la compra en la base de datos.'];
        }

        // Registrar cada asiento
        foreach ($asientosSeleccionados as $codigo) {
            $disponible = $this->asientoModelo->verificarDisponible($vueloId, $codigo);

            if (!$disponible) {
                return ['error' => true, 'mensaje' => "El asiento $codigo ya fue ocupado."];
            }

            // Cambiar estado a “Ocupado” y registrar asiento en detalle
            $this->asientoModelo->ocuparAsiento($vueloId, $codigo);
            $this->modelo->registrarDetalle($compraId, $vueloId, $codigo);
            $compraExitosa = true;
        }

        if ($compraExitosa) {
            return ['error' => false, 'mensaje' => 'Compra realizada correctamente.'];
        } else {
            return ['error' => true, 'mensaje' => 'No se pudo registrar la compra.'];
        }
    }
}
