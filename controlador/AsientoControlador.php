<?php
require_once __DIR__ . '/../modelo/AsientoModelo.php';

class AsientoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new AsientoModelo();
    }

    /**
     * 🔹 Listar asientos de un vuelo
     */
    public function obtenerAsientosPorVuelo($vueloId) {
        return $this->modelo->obtenerAsientosPorVuelo($vueloId);
    }

    /**
     * 🔹 Verificar si asiento está disponible
     */
    public function verificarDisponible($vueloId, $codigoAsiento) {
        return $this->modelo->verificarDisponible($vueloId, $codigoAsiento);
    }

    /**
     * 🔹 Ocupar asiento
     */
    public function ocuparAsiento($vueloId, $codigoAsiento) {
        return $this->modelo->ocuparAsiento($vueloId, $codigoAsiento);
    }
}
?>
