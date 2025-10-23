<?php
session_start();
require_once __DIR__ . '/../controlador/VueloControlador.php';
require_once __DIR__ . '/../controlador/AsientoControlador.php';
require_once __DIR__ . '/../controlador/CompraControlador.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$vueloId = $_GET['vuelo'] ?? null;
if (!$vueloId) {
    die("<h3>❌ No se especificó el vuelo.</h3>");
}

$usuarioId = $_SESSION['usuario']['id_usuario'];

$vueloCtrl = new VueloControlador();
$asientoCtrl = new AsientoControlador();
$compraCtrl = new CompraControlador();

$vuelo = $vueloCtrl->obtenerVuelo($vueloId);
$asientos = $asientoCtrl->obtenerAsientosPorVuelo($vueloId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asientos'])) {
    $asientosSeleccionados = $_POST['asientos'];
    $resultado = $compraCtrl->procesarCompra($vueloId, $usuarioId, $asientosSeleccionados);

    if ($resultado['error']) {
        echo "<script>alert('{$resultado['mensaje']}');</script>";
    } else {
        $_SESSION['ultimo_vuelo'] = $vueloId;
        $_SESSION['asientos_comprados'] = $asientosSeleccionados;
        header("Location: pago_exitoso.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Compra de Asientos</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:20px;}
h1,h2{text-align:center;}
.avion{display:grid;justify-content:center;gap:5px;}
.fila{display:flex;justify-content:center;gap:5px;}
.asiento{width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;}
.disponible{background:#28a745;color:white;}
.ocupado{background:#dc3545;color:white;cursor:not-allowed;}
.seleccionado{background:#ffc107;color:black;}
button{margin-top:20px;background:#007bff;color:white;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;}
button:hover{background:#0056b3;}
</style>
</head>
<body>

<h1>Compra de Asientos</h1>
<h2><?= htmlspecialchars($vuelo['codigo_vuelo']) ?> - <?= htmlspecialchars($vuelo['origen']) ?> → <?= htmlspecialchars($vuelo['destino']) ?></h2>
<p style="text-align:center;">Modelo: <?= htmlspecialchars($vuelo['nombre_modelo_avion']) ?> | Avión: <?= htmlspecialchars($vuelo['codigo_avion']) ?></p>

<form method="POST">
<div class="avion">
<?php
$filas = $vuelo['filas_modelo_avion'];
$columnas = $vuelo['columnas_modelo_avion'];
$letras = range('A', chr(64 + $columnas));

for ($f = 1; $f <= $filas; $f++):
    echo "<div class='fila'>";
    foreach ($letras as $l):
        $codigo = $f . $l;
        $estado = 'Disponible';
        foreach ($asientos as $a) {
            if ($a['codigo_asiento'] === $codigo) {
                $estado = $a['estado'];
                break;
            }
        }
        $class = ($estado === 'Disponible') ? 'disponible' : 'ocupado';
        $disabled = ($estado !== 'Disponible') ? 'disabled' : '';
        echo "<label class='asiento $class'>
                <input type='checkbox' name='asientos[]' value='$codigo' style='display:none;' $disabled>
                $codigo
              </label>";
    endforeach;
    echo "</div>";
endfor;
?>
</div>

<div style="text-align:center;">
    <button type="submit">Comprar Asientos</button>
</div>
</form>

<script>
const asientos = document.querySelectorAll('.asiento');
asientos.forEach(a => {
    a.addEventListener('click', () => {
        if(a.classList.contains('ocupado')) return;
        const check = a.querySelector('input');
        if (check.checked) {
            check.checked = false;
            a.classList.remove('seleccionado');
        } else {
            const seleccionados = document.querySelectorAll('.asiento.seleccionado').length;
            if (seleccionados >= 5) {
                alert('Solo puedes seleccionar hasta 5 asientos por día.');
                return;
            }
            check.checked = true;
            a.classList.add('seleccionado');
        }
    });
});
</script>

</body>
</html>
