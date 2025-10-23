<?php
session_start();
require_once __DIR__ . '/../controlador/VueloControlador.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];
$vueloId = $_SESSION['ultimo_vuelo'] ?? null;
$asientos = $_SESSION['asientos_comprados'] ?? [];

if (!$vueloId || empty($asientos)) {
    echo "<h3>⚠️ No se encontraron datos de compra.</h3>";
    echo "<a href='lista_vuelos.php'><button>Volver a vuelos</button></a>";
    exit();
}

$vueloCtrl = new VueloControlador();
$vuelo = $vueloCtrl->obtenerVuelo($vueloId);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Pago Exitoso - Ticket</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:40px;}
.card{max-width:500px;margin:auto;background:white;padding:20px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.2);}
h2{text-align:center;color:#28a745;}
ul{list-style:none;padding:0;}
li{margin:5px 0;}
button{background:#007BFF;color:white;border:none;padding:10px 15px;border-radius:6px;cursor:pointer;}
button:hover{background:#0056b3;}
</style>
</head>
<body>
<div class="card">
<h2>✅ Pago Exitoso</h2>
<p>Gracias, <b><?= htmlspecialchars($usuario['nombre_usuario']) ?></b>.</p>
<p>Tu compra ha sido registrada correctamente.</p>

<h3>🛫 Detalles del vuelo</h3>
<ul>
<li><b>Código:</b> <?= htmlspecialchars($vuelo['codigo_vuelo']) ?></li>
<li><b>Origen:</b> <?= htmlspecialchars($vuelo['origen']) ?></li>
<li><b>Destino:</b> <?= htmlspecialchars($vuelo['destino']) ?></li>
<li><b>Fecha:</b> <?= htmlspecialchars($vuelo['fecha_salida']) ?></li>
<li><b>Hora:</b> <?= htmlspecialchars($vuelo['hora_salida']) ?></li>
</ul>

<h3>💺 Asientos comprados</h3>
<ul>
<?php foreach ($asientos as $a): ?>
<li><?= htmlspecialchars($a) ?></li>
<?php endforeach; ?>
</ul>

<p style="text-align:center;margin-top:20px;">
<a href="lista_vuelos.php"><button>Volver a vuelos</button></a>
</p>
</div>
</body>
</html>
