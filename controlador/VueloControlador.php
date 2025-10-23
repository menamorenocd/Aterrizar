<?php
require_once __DIR__ . '/../modelo/VueloModelo.php';

class VueloControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new VueloModelo();
    }

    // Lista todos los vuelos
    public function listarVuelos() {
        return $this->modelo->listarVuelos();
    }

    // Obtiene un vuelo específico
    public function obtenerVuelo($id) {
        return $this->modelo->obtenerVuelo($id);
    }
}
?>
