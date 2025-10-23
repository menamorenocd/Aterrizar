<?php
require_once __DIR__ . '/../modelo/VueloModelo.php';

class VueloControlador {
    private $modelo;

    public function __construct() {
        $this->modelo = new VueloModelo();
    }

    /** =============================
     *  Listar todos los vuelos
     *  ============================= */
    public function listarVuelos() {
        return $this->modelo->listarVuelos();
    }

    /** =============================
     *  Obtener vuelo por ID
     *  ============================= */
    public function obtenerVueloPorId($idVuelo) {
        return $this->modelo->obtenerVueloPorId($idVuelo);
    }
}
?>
