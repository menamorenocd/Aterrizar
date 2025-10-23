<?php
session_start();
require_once __DIR__ . '/../controlador/VueloControlador.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$vueloCtrl = new VueloControlador();

// ------------------------------
// Obtener vuelos de ida y regreso
// ------------------------------
$vuelo_ida = $_GET['vuelo_ida'] ?? null;
$vuelo_regreso = $_GET['vuelo_regreso'] ?? null;

if (!$vuelo_ida && !$vuelo_regreso) {
    echo "<p style='color:red; text-align:center; margin-top:40px;'>❌ No se especificaron vuelos.</p>";
    echo "<p style='text-align:center;'><a href='index.php'>⬅️ Volver</a></p>";
    exit;
}

// Obtener detalles de los vuelos
$info_ida = $vuelo_ida ? $vueloCtrl->obtenerVueloPorId($vuelo_ida) : null;
$info_regreso = $vuelo_regreso ? $vueloCtrl->obtenerVueloPorId($vuelo_regreso) : null;

// Guardar en sesión temporal
$_SESSION['vuelo_ida'] = $vuelo_ida;
$_SESSION['vuelo_regreso'] = $vuelo_regreso;
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Seleccionar Asientos | Aterriza</title>
<style>
body {
    font-family:'Segoe UI',Arial,sans-serif;
    background:#f5f7fa;
    margin:0;
    padding:0;
}
.container {
    max-width:1100px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
h2, h3 {
    text-align:center;
    color:#004aad;
}
.vuelo-info {
    text-align:center;
    margin-bottom:20px;
    padding:10px;
    background:#e9f2ff;
    border-radius:10px;
}
.asientos {
    display:grid;
    grid-template-columns:repeat(6, 1fr);
    gap:10px;
    justify-items:center;
    margin:20px auto;
    max-width:500px;
}
.asiento {
    width:40px;
    height:40px;
    border-radius:6px;
    text-align:center;
    line-height:40px;
    font-weight:bold;
    background:#d6d8d9;
    color:#333;
    cursor:pointer;
    transition:all 0.2s ease;
}
.asiento.disponible:hover { background:#007bff; color:white; }
.asiento.seleccionado { background:#28a745; color:white; }
.asiento.ocupado { background:#dc3545; color:white; cursor:not-allowed; }
.btn {
    display:inline-block;
    background:#007bff;
    color:white;
    padding:10px 20px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    margin:15px;
}
.btn:hover { background:#0056b3; }
.volver { background:#6c757d; }
.volver:hover { background:#5a6268; }
</style>
</head>

<body>
<div class="container">
    <h2>🪑 Selecciona tus asientos</h2>
    <p style="text-align:center;color:#555;">Haz clic en los asientos disponibles para seleccionarlos. Puedes elegir hasta 5 por vuelo.</p>

    <form method="POST" action="procesar_compra.php">
        <!-- ======== VUELO DE IDA ======== -->
        <?php if ($info_ida): ?>
        <div class="vuelo-info">
            <h3>✈️ Vuelo de ida</h3>
            <p><strong><?= htmlspecialchars($info_ida['origen']) ?> → <?= htmlspecialchars($info_ida['destino']) ?></strong> | 
               Fecha: <?= htmlspecialchars($info_ida['fecha_salida']) ?> | 
               Hora: <?= htmlspecialchars($info_ida['hora_salida']) ?></p>
        </div>

        <div class="asientos" id="asientosIda"></div>
        <input type="hidden" name="asientos_ida" id="asientos_ida">
        <?php endif; ?>

        <!-- ======== VUELO DE REGRESO ======== -->
        <?php if ($info_regreso): ?>
        <div class="vuelo-info">
            <h3>🔁 Vuelo de regreso</h3>
            <p><strong><?= htmlspecialchars($info_regreso['origen']) ?> → <?= htmlspecialchars($info_regreso['destino']) ?></strong> | 
               Fecha: <?= htmlspecialchars($info_regreso['fecha_salida']) ?> | 
               Hora: <?= htmlspecialchars($info_regreso['hora_salida']) ?></p>
        </div>

        <div class="asientos" id="asientosRegreso"></div>
        <input type="hidden" name="asientos_regreso" id="asientos_regreso">
        <?php endif; ?>

        <div style="text-align:center;">
            <button type="submit" class="btn">💳 Confirmar compra</button>
            <a href="index.php" class="btn volver">⬅️ Cancelar</a>
        </div>
    </form>
</div>

<script>
// ===============================
// Generador visual de asientos
// ===============================
function generarAsientos(containerId, inputHidden) {
    const contenedor = document.getElementById(containerId);
    const seleccionados = [];
    for (let i = 1; i <= 30; i++) {
        const asiento = document.createElement('div');
        asiento.classList.add('asiento', 'disponible');
        asiento.textContent = i;
        asiento.addEventListener('click', () => {
            if (asiento.classList.contains('ocupado')) return;
            if (asiento.classList.contains('seleccionado')) {
                asiento.classList.remove('seleccionado');
                const index = seleccionados.indexOf(i);
                if (index > -1) seleccionados.splice(index, 1);
            } else {
                if (seleccionados.length >= 5) {
                    alert('⚠️ Solo puedes seleccionar hasta 5 asientos por vuelo.');
                    return;
                }
                asiento.classList.add('seleccionado');
                seleccionados.push(i);
            }
            document.getElementById(inputHidden).value = seleccionados.join(',');
        });
        contenedor.appendChild(asiento);
    }
}

// Generar los dos mapas (si existen)
<?php if ($info_ida): ?>generarAsientos('asientosIda', 'asientos_ida');<?php endif; ?>
<?php if ($info_regreso): ?>generarAsientos('asientosRegreso', 'asientos_regreso');<?php endif; ?>
</script>
</body>
</html>
