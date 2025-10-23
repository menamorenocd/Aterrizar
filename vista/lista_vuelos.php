<?php
session_start();
require_once __DIR__ . '/../controlador/VueloControlador.php';

$vueloCtrl = new VueloControlador();
$vuelos = $vueloCtrl->listarVuelos();

if (!isset($_SESSION['usuario'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Listado de Vuelos</title>
<style>
body {font-family:Arial;background:#f4f6f8;margin:20px;}
h1{text-align:center;color:#333;}
table{width:100%;border-collapse:collapse;background:white;box-shadow:0 0 10px rgba(0,0,0,0.1);}
th,td{padding:12px;border-bottom:1px solid #ddd;text-align:center;}
th{background:#007bff;color:white;}
tr:hover{background:#f1f1f1;}
button{background:#28a745;color:white;padding:8px 16px;border:none;border-radius:6px;cursor:pointer;}
button:hover{background:#218838;}
</style>
</head>
<body>

<h1>✈️ Vuelos Disponibles</h1>

<table>
<thead>
<tr>
<th>Código Vuelo</th>
<th>Origen</th>
<th>Destino</th>
<th>Fecha</th>
<th>Hora</th>
<th>Modelo Avión</th>
<th>Asientos Disponibles</th>
<th>Acción</th>
</tr>
</thead>
<tbody>
<?php if (empty($vuelos)): ?>
<tr><td colspan="8">No hay vuelos registrados.</td></tr>
<?php else: ?>
<?php foreach ($vuelos as $v): ?>
<tr>
<td><?= htmlspecialchars($v['codigo_vuelo']) ?></td>
<td><?= htmlspecialchars($v['origen']) ?></td>
<td><?= htmlspecialchars($v['destino']) ?></td>
<td><?= htmlspecialchars($v['fecha_salida']) ?></td>
<td><?= htmlspecialchars($v['hora_salida']) ?></td>
<td><?= htmlspecialchars($v['nombre_modelo_avion']) ?></td>
<td><?= htmlspecialchars($v['disponibles']) ?></td>
<td>
    <form action="compra_vuelo.php" method="GET">
        <input type="hidden" name="vuelo" value="<?= $v['id_vuelo'] ?>">
        <button type="submit">Comprar</button>
    </form>
</td>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>

</body>
</html>
