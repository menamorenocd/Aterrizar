<?php
session_start();
require_once __DIR__ . '/../controlador/VueloControlador.php';
require_once __DIR__ . '/../controlador/AsientoControlador.php';
require_once __DIR__ . '/../controlador/CompraControlador.php';

// ===============================
// VERIFICAR SESIÓN
// ===============================
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

// ===============================
// OBTENER VUELOS (GET params)
// ===============================
$vuelo_ida = $_GET['vuelo_ida'] ?? null;
$vuelo_regreso = $_GET['vuelo_regreso'] ?? null;

if (!$vuelo_ida && !$vuelo_regreso) {
    die("<h3 style='color:red;text-align:center;margin-top:40px;'>❌ No se especificó el vuelo.</h3>");
}

// ===============================
// DATOS DEL USUARIO
// ===============================
$usuarioId = $_SESSION['usuario']['id'] ?? $_SESSION['usuario']['id_usuario'] ?? null;

$vueloCtrl = new VueloControlador();
$asientoCtrl = new AsientoControlador();
$compraCtrl = new CompraControlador();

// ===============================
// OBTENER DATOS DE LOS VUELOS
// ===============================
$vueloIda = $vuelo_ida ? $vueloCtrl->obtenerVueloPorId($vuelo_ida) : null;
$vueloRegreso = $vuelo_regreso ? $vueloCtrl->obtenerVueloPorId($vuelo_regreso) : null;

$asientosIda = $vuelo_ida ? $asientoCtrl->obtenerAsientosPorVuelo($vuelo_ida) : [];
$asientosRegreso = $vuelo_regreso ? $asientoCtrl->obtenerAsientosPorVuelo($vuelo_regreso) : [];

// ===============================
// OBTENER CONFIGURACIÓN DE AVIÓN
// ===============================
function obtenerConfigModelo($vuelo, $conexion, $idVuelo) {
    if (!empty($vuelo['filas_modelo_avion']) && !empty($vuelo['columnas_modelo_avion'])) {
        return [
            'filas' => (int)$vuelo['filas_modelo_avion'],
            'columnas' => (int)$vuelo['columnas_modelo_avion']
        ];
    }

    $sql = "SELECT m.filas_modelo_avion AS filas, m.columnas_modelo_avion AS columnas
            FROM tb_vuelo v
            JOIN tb_avion a ON v.avion_id = a.id_avion
            JOIN tb_modelo_avion m ON a.modelo_id = m.id_modelo_avion
            WHERE v.id_vuelo = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $idVuelo);
    $stmt->execute();
    $res = $stmt->get_result();
    $data = $res->fetch_assoc();
    $stmt->close();

    return [
        'filas' => $data['filas'] ?? 30,
        'columnas' => $data['columnas'] ?? 6
    ];
}

$conexion = new mysqli("localhost", "root", "", "sistema_vuelos");
$conexion->set_charset("utf8mb4");

$configIda = $vueloIda ? obtenerConfigModelo($vueloIda, $conexion, $vuelo_ida) : ['filas'=>30,'columnas'=>6];
$configRegreso = $vueloRegreso ? obtenerConfigModelo($vueloRegreso, $conexion, $vuelo_regreso) : ['filas'=>30,'columnas'=>6];

// ===============================
// PROCESAR COMPRA (POST)
// ===============================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asientosSeleccionadosIda = $_POST['asientos_ida'] ?? [];
    $asientosSeleccionadosRegreso = $_POST['asientos_regreso'] ?? [];

    $error = false;

    if ($vuelo_ida && !empty($asientosSeleccionadosIda)) {
        $resultadoIda = $compraCtrl->procesarCompra($vuelo_ida, $usuarioId, $asientosSeleccionadosIda);
        if ($resultadoIda['error']) $error = true;
    }

    if ($vuelo_regreso && !empty($asientosSeleccionadosRegreso)) {
        $resultadoRegreso = $compraCtrl->procesarCompra($vuelo_regreso, $usuarioId, $asientosSeleccionadosRegreso);
        if ($resultadoRegreso['error']) $error = true;
    }

    if ($error) {
        echo "<script>alert('❌ Hubo un problema al procesar la compra. Revisa tus asientos.');</script>";
    } else {
        // Guardar información para mostrar en pago_exitoso.php
        $_SESSION['vuelo_ida'] = $vuelo_ida;
        $_SESSION['vuelo_regreso'] = $vuelo_regreso;
        $_SESSION['asientos_ida'] = $asientosSeleccionadosIda;
        $_SESSION['asientos_regreso'] = $asientosSeleccionadosRegreso;

        header("Location: pago_exitoso.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Selección de Asientos | Aterriza</title>
<style>
body { font-family:'Segoe UI',Arial,sans-serif; background:#eef2f7; margin:0; padding:0; }
.container { max-width:1200px; margin:40px auto; background:#fff; padding:30px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08); }
h1,h2{ text-align:center; color:#004aad; }
.avion { position:relative; margin:30px auto; background:#dfe9f3; border-radius:80px 80px 20px 20px; padding:30px 50px; width:fit-content; box-shadow:inset 0 0 12px rgba(0,0,0,0.06); }
.pasillo { width:40px; }
.fila { display:flex; justify-content:center; margin:6px 0; gap:8px; }
.asiento { width:46px; height:46px; border-radius:8px; display:flex; align-items:center; justify-content:center; cursor:pointer; font-weight:600; font-size:13px; transition:0.15s; box-shadow:0 2px 4px rgba(0,0,0,0.12); }
.disponible { background:#28a745; color:white; }
.ocupado { background:#dc3545; color:white; cursor:not-allowed; }
.seleccionado { background:#ffc107; color:black; }
.legend { text-align:center; margin-top:18px; color:#333; font-size:14px; }
button { display:block; margin:26px auto; background:#007bff; color:white; border:none; padding:12px 26px; border-radius:8px; cursor:pointer; font-size:16px; }
button:hover { background:#0056b3; }
.vuelo-info { text-align:center; background:#eef7ff; padding:10px; border-radius:8px; margin-bottom:12px; }
.section { margin-top:22px; }
</style>
</head>
<body>
<div class="container">
    <h1>🪑 Selección de Asientos</h1>
    <p style="text-align:center;color:#555;">Selecciona hasta <strong>5 asientos por vuelo</strong>. Haz clic sobre el asiento dentro del avión.</p>

    <form method="POST">
        <!-- VUELO DE IDA -->
        <?php if ($vueloIda): ?>
        <div class="section">
            <div class="vuelo-info">
                <h2>✈️ Vuelo de ida</h2>
                <p><strong><?= htmlspecialchars($vueloIda['origen'] ?? '') ?></strong> → <strong><?= htmlspecialchars($vueloIda['destino'] ?? '') ?></strong>
                | Fecha: <?= htmlspecialchars($vueloIda['fecha_salida'] ?? '') ?> | Hora: <?= htmlspecialchars($vueloIda['hora_salida'] ?? '') ?></p>
            </div>
            <div class="avion" id="avionIda">
                <?php
                $filas = (int)$configIda['filas'];
                $columnas = (int)$configIda['columnas'];
                $letras = range('A', chr(64 + $columnas));

                for ($f = 1; $f <= $filas; $f++):
                    echo "<div class='fila'>";
                    foreach ($letras as $index => $l):
                        if ($index === (int)ceil($columnas/2)) echo "<div class='pasillo'></div>";
                        $codigo = $f . $l;
                        $estado = 'Disponible';
                        foreach ($asientosIda as $a) {
                            if (($a['codigo_asiento'] ?? '') === $codigo) {
                                $estado = $a['estado'];
                                break;
                            }
                        }
                        $class = ($estado === 'Disponible') ? 'disponible' : 'ocupado';
                        $disabled = ($estado !== 'Disponible') ? 'disabled' : '';
                        echo "<label class='asiento $class'><input type='checkbox' name='asientos_ida[]' value='$codigo' style='display:none;' $disabled>$codigo</label>";
                    endforeach;
                    echo "</div>";
                endfor;
                ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- VUELO DE REGRESO -->
        <?php if ($vueloRegreso): ?>
        <div class="section">
            <div class="vuelo-info">
                <h2>🔁 Vuelo de regreso</h2>
                <p><strong><?= htmlspecialchars($vueloRegreso['origen'] ?? '') ?></strong> → <strong><?= htmlspecialchars($vueloRegreso['destino'] ?? '') ?></strong>
                | Fecha: <?= htmlspecialchars($vueloRegreso['fecha_salida'] ?? '') ?> | Hora: <?= htmlspecialchars($vueloRegreso['hora_salida'] ?? '') ?></p>
            </div>
            <div class="avion" id="avionRegreso">
                <?php
                $filas = (int)$configRegreso['filas'];
                $columnas = (int)$configRegreso['columnas'];
                $letras = range('A', chr(64 + $columnas));

                for ($f = 1; $f <= $filas; $f++):
                    echo "<div class='fila'>";
                    foreach ($letras as $index => $l):
                        if ($index === (int)ceil($columnas/2)) echo "<div class='pasillo'></div>";
                        $codigo = $f . $l;
                        $estado = 'Disponible';
                        foreach ($asientosRegreso as $a) {
                            if (($a['codigo_asiento'] ?? '') === $codigo) {
                                $estado = $a['estado'];
                                break;
                            }
                        }
                        $class = ($estado === 'Disponible') ? 'disponible' : 'ocupado';
                        $disabled = ($estado !== 'Disponible') ? 'disabled' : '';
                        echo "<label class='asiento $class'><input type='checkbox' name='asientos_regreso[]' value='$codigo' style='display:none;' $disabled>$codigo</label>";
                    endforeach;
                    echo "</div>";
                endfor;
                ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="legend">
            <span style="display:inline-block;width:14px;height:14px;background:#28a745;margin-right:6px;border-radius:3px;"></span> Disponible
            <span style="display:inline-block;width:14px;height:14px;background:#dc3545;margin:0 12px;border-radius:3px;"></span> Ocupado
            <span style="display:inline-block;width:14px;height:14px;background:#ffc107;margin:0 12px;border-radius:3px;"></span> Seleccionado
        </div>

        <button type="submit">💳 Confirmar compra</button>
    </form>
</div>

<script>
document.querySelectorAll('.avion').forEach(avion => {
    avion.addEventListener('click', function(e) {
        const target = e.target.closest('.asiento');
        if (!target) return;
        if (target.classList.contains('ocupado')) return;
        const checkbox = target.querySelector('input');
        const seleccionados = this.querySelectorAll('.asiento.seleccionado').length;
        if (checkbox.checked) {
            checkbox.checked = false;
            target.classList.remove('seleccionado');
        } else {
            if (seleccionados >= 5) {
                alert('⚠️ Solo puedes seleccionar hasta 5 asientos por vuelo.');
                return;
            }
            checkbox.checked = true;
            target.classList.add('seleccionado');
        }
    });
});
</script>
</body>
</html>
