<?php
require_once __DIR__ . '/Conexion.php';

class VueloModelo {
    private $conexion;

    public function __construct() {
        $this->conexion = (new Conexion())->conectar();
    }

    /** =============================
     *  Listar todos los vuelos
     *  ============================= */
    public function listarVuelos() {
        $sql = "SELECT v.*, 
                       c1.nombre_ciudad AS origen, 
                       c2.nombre_ciudad AS destino,
                       m.nombre_modelo_avion,
                       (SELECT COUNT(*) 
                        FROM tb_asiento_vuelo a 
                        WHERE a.vuelo_id=v.id_vuelo 
                          AND a.estado='Disponible') AS disponibles
                FROM tb_vuelo v
                INNER JOIN tb_ciudad c1 ON v.ciudad_origen_id=c1.id_ciudad
                INNER JOIN tb_ciudad c2 ON v.ciudad_destino_id=c2.id_ciudad
                INNER JOIN tb_avion av ON v.avion_id=av.id_avion
                INNER JOIN tb_modelo_avion m ON av.modelo_id=m.id_modelo_avion
                ORDER BY v.fecha_salida ASC";

        $result = $this->conexion->query($sql);
        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    /** =============================
     *  Obtener vuelo por ID
     *  ============================= */
    public function obtenerVueloPorId($idVuelo) {
        $sql = "SELECT v.*, 
                       c1.nombre_ciudad AS origen, 
                       c2.nombre_ciudad AS destino,
                       m.nombre_modelo_avion,
                       (SELECT COUNT(*) 
                        FROM tb_asiento_vuelo a 
                        WHERE a.vuelo_id=v.id_vuelo 
                          AND a.estado='Disponible') AS disponibles
                FROM tb_vuelo v
                INNER JOIN tb_ciudad c1 ON v.ciudad_origen_id=c1.id_ciudad
                INNER JOIN tb_ciudad c2 ON v.ciudad_destino_id=c2.id_ciudad
                INNER JOIN tb_avion av ON v.avion_id=av.id_avion
                INNER JOIN tb_modelo_avion m ON av.modelo_id=m.id_modelo_avion
                WHERE v.id_vuelo = ?";

        $stmt = $this->conexion->prepare($sql);
        $stmt->bind_param("i", $idVuelo);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }
}
?>
