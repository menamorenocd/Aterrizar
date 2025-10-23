<?php
require_once __DIR__ . '/../modelo/AsientoModelo.php';
require_once __DIR__ . '/../modelo/VueloModelo.php';

class CompraControlador {
    private $asientoModelo;
    private $vueloModelo;

    public function __construct() {
        $this->asientoModelo = new AsientoModelo();
        $this->vueloModelo = new VueloModelo();
    }

    // Procesa la compra de los asientos seleccionados
    public function procesarCompra($vueloId, $usuarioId, $asientosSeleccionados) {
        $comprasHoy = $this->asientoModelo->contarComprasHoy($usuarioId, $vueloId);
        $nuevasCompras = count($asientosSeleccionados);

        if ($comprasHoy + $nuevasCompras > 5) {
            return [
                'error' => true,
                'mensaje' => "❌ Has alcanzado el límite de 5 asientos comprados en este vuelo hoy. Intenta nuevamente en 24 horas."
            ];
        }

        foreach ($asientosSeleccionados as $codigo) {
            $this->asientoModelo->marcarAsientoPagado($vueloId, $usuarioId, $codigo);
        }

        return [
            'error' => false,
            'mensaje' => "✅ Compra realizada con éxito."
        ];
    }
}
?>
