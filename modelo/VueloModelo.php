<?php
require_once __DIR__ . '/Conexion.php';

class VueloModelo {
    private $conn;

    public function __construct() {
        $this->conn = (new Conexion())->conectar();
    }

    // 🔹 Lista todos los vuelos y genera asientos automáticamente si no existen
    public function listarVuelos() {
        $sql = "SELECT 
                    v.id_vuelo,
                    v.codigo_vuelo,
                    v.fecha_salida,
                    v.hora_salida,
                    v.precio_base,
                    co.nombre_ciudad AS origen,
                    cd.nombre_ciudad AS destino,
                    a.codigo_avion,
                    ar.nombre_aerolinea,
                    ma.nombre_modelo_avion,
                    ma.filas_modelo_avion,
                    ma.columnas_modelo_avion
                FROM tb_vuelo v
                JOIN tb_avion a ON v.avion_id = a.id_avion
                JOIN tb_modelo_avion ma ON a.modelo_id = ma.id_modelo_avion
                JOIN tb_aerolinea ar ON a.aerolinea_id = ar.id_aerolinea
                JOIN tb_ciudad co ON v.ciudad_origen_id = co.id_ciudad
                JOIN tb_ciudad cd ON v.ciudad_destino_id = cd.id_ciudad";

        $res = $this->conn->query($sql);
        $vuelos = $res ? $res->fetch_all(MYSQLI_ASSOC) : [];

        foreach ($vuelos as &$vuelo) {
            $this->generarAsientosSiNoExisten($vuelo['id_vuelo']);
            $vuelo['disponibles'] = $this->contarAsientosDisponibles($vuelo['id_vuelo']);
        }

        return $vuelos;
    }

    // 🔹 Genera asientos automáticamente según el modelo del avión
    private function generarAsientosSiNoExisten($vueloId) {
        $check = $this->conn->prepare("SELECT COUNT(*) AS total FROM tb_asiento_vuelo WHERE vuelo_id = ?");
        $check->bind_param("i", $vueloId);
        $check->execute();
        $total = $check->get_result()->fetch_assoc()['total'];
        $check->close();

        if ($total > 0) return;

        $sql = "SELECT ma.filas_modelo_avion, ma.columnas_modelo_avion
                FROM tb_vuelo v
                JOIN tb_avion a ON v.avion_id = a.id_avion
                JOIN tb_modelo_avion ma ON a.modelo_id = ma.id_modelo_avion
                WHERE v.id_vuelo = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $vueloId);
        $stmt->execute();
        $modelo = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$modelo) return;

        $filas = (int)$modelo['filas_modelo_avion'];
        $columnas = (int)$modelo['columnas_modelo_avion'];
        $letras = range('A', chr(64 + $columnas));

        $insert = $this->conn->prepare("
            INSERT INTO tb_asiento_vuelo (vuelo_id, fila, letra, codigo_asiento, clase, estado)
            VALUES (?, ?, ?, ?, 'Económica', 'Disponible')
        ");

        for ($f = 1; $f <= $filas; $f++) {
            foreach ($letras as $l) {
                $codigo = $f . $l;
                $insert->bind_param("iiss", $vueloId, $f, $l, $codigo);
                $insert->execute();
            }
        }

        $insert->close();
    }

    // 🔹 Contar asientos disponibles
    private function contarAsientosDisponibles($vueloId) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) AS disp FROM tb_asiento_vuelo WHERE vuelo_id = ? AND estado = 'Disponible'");
        $stmt->bind_param("i", $vueloId);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return (int)$row['disp'];
    }

    // 🔹 Obtener información de un vuelo
    public function obtenerVuelo($id) {
        $sql = "SELECT v.*, 
                       co.nombre_ciudad AS origen, 
                       cd.nombre_ciudad AS destino,
                       a.codigo_avion,
                       ma.nombre_modelo_avion,
                       ma.filas_modelo_avion,
                       ma.columnas_modelo_avion
                FROM tb_vuelo v
                JOIN tb_avion a ON v.avion_id = a.id_avion
                JOIN tb_modelo_avion ma ON a.modelo_id = ma.id_modelo_avion
                JOIN tb_ciudad co ON v.ciudad_origen_id = co.id_ciudad
                JOIN tb_ciudad cd ON v.ciudad_destino_id = cd.id_ciudad
                WHERE v.id_vuelo = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $vuelo = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $vuelo;
    }
}
?>
