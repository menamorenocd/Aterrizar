<?php
require_once __DIR__ . '/../modelo/AsientoModelo.php';

class AsientoControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new AsientoModelo();
    }

    public function obtenerAsientosPorVuelo($vueloId) {
        return $this->modelo->obtenerAsientosPorVuelo($vueloId);
    }
}
?>
