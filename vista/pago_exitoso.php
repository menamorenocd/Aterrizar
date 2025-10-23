<?php
session_start();
require_once __DIR__ . '/../controlador/VueloControlador.php';

// ===============================
// VERIFICAR SESIÓN
// ===============================
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$usuario = $_SESSION['usuario'];

// ===============================
// VUELOS Y ASIENTOS DESDE SESIÓN
// ===============================
$vueloIda = $_SESSION['vuelo_ida'] ?? $_SESSION['ultimo_vuelo'] ?? null;
$vueloRegreso = $_SESSION['vuelo_regreso'] ?? null;
$asientosIda = $_SESSION['asientos_ida'] ?? $_SESSION['asientos_comprados'] ?? [];
$asientosRegreso = $_SESSION['asientos_regreso'] ?? [];

if (!$vueloIda && !$vueloRegreso) {
    echo "<h3>⚠️ No se encontraron datos de compra.</h3>";
    echo "<a href='lista_vuelos.php'><button>Volver a vuelos</button></a>";
    exit();
}

// ===============================
// CONTROLADOR DE VUELOS
// ===============================
$vueloCtrl = new VueloControlador();
$vueloDataIda = $vueloIda ? $vueloCtrl->obtenerVueloPorId($vueloIda) : null;
$vueloDataRegreso = $vueloRegreso ? $vueloCtrl->obtenerVueloPorId($vueloRegreso) : null;

// ===============================
// GENERAR NÚMERO DE FACTURA
// ===============================
if (!isset($_SESSION['factura_numero'])) {
    $fecha = date('Ymd');
    $rand = str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
    $_SESSION['factura_numero'] = "FA-$fecha-$rand";
}
$numeroFactura = $_SESSION['factura_numero'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket de Vuelo - Aterriza Airlines</title>
<style>
body {
    font-family: 'Poppins', Arial, sans-serif;
    background: #eef2f7;
    margin: 0;
    padding: 40px;
}
.ticket {
    background: #fff;
    max-width: 850px;
    margin: auto;
    border-radius: 16px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    overflow: hidden;
}
.header {
    background: linear-gradient(90deg, #004aad, #007bff);
    color: white;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header h2 {
    margin: 0;
    font-size: 24px;
}
.header span {
    font-size: 14px;
    opacity: 0.8;
}
.ticket-body {
    display: flex;
    flex-direction: column;
    padding: 30px 40px;
}
.section {
    border-top: 1px dashed #ddd;
    padding-top: 20px;
    margin-top: 20px;
}
.section:first-of-type {
    border-top: none;
    margin-top: 0;
    padding-top: 0;
}
.flight-info {
    display: flex;
    justify-content: space-between;
    flex-wrap: wrap;
}
.flight-info div {
    width: 48%;
    margin-bottom: 10px;
}
.flight-info b {
    color: #004aad;
}
.asientos {
    margin-top: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.asiento {
    background: #007bff;
    color: white;
    padding: 6px 12px;
    border-radius: 6px;
    font-weight: bold;
    font-size: 14px;
}
.asiento:hover {
    background: #0056b3;
}
.footer {
    text-align: center;
    padding: 20px;
    background: #f9f9f9;
    border-top: 1px solid #eee;
    font-size: 13px;
    color: #666;
}
button {
    background: #004aad;
    color: white;
    border: none;
    padding: 12px 25px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 15px;
    font-weight: bold;
    margin: 15px 5px;
}
button:hover {
    background: #0066d2;
}
.actions {
    text-align: center;
    margin-top: 25px;
}
.boarding-pass {
    border: 2px dashed #ccc;
    border-radius: 12px;
    margin-top: 20px;
    padding: 20px;
    background: #fafafa;
    position: relative;
}
.boarding-pass h3 {
    margin: 0 0 10px;
    color: #004aad;
}
.passenger {
    font-weight: bold;
    color: #333;
}
.qr {
    position: absolute;
    top: 15px;
    right: 25px;
}
.qr img {
    width: 80px;
    opacity: 0.8;
}
.factura {
    text-align: right;
    color: #555;
    font-size: 14px;
}
</style>
</head>
<body>

<div class="ticket">
    <div class="header">
        <div>
            <h2>🎫 Aterriza Airlines</h2>
            <span>Ticket de vuelo electrónico</span>
        </div>
        <div class="factura">
            <b>Factura:</b> <?= htmlspecialchars($numeroFactura) ?><br>
            <small><?= date('d/m/Y H:i') ?></small>
        </div>
    </div>

    <div class="ticket-body">

        <p><b>Pasajero:</b> <span class="passenger"><?= htmlspecialchars($usuario['nombre'] ?? $usuario['nombre_usuario']) ?></span></p>

        <!-- VUELO DE IDA -->
        <?php if ($vueloDataIda): ?>
        <div class="boarding-pass">
            <div class="qr"><img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode($numeroFactura) ?>" alt="QR"></div>
            <h3>🛫 Vuelo de Ida</h3>
            <div class="flight-info">
                <div><b>Código:</b> <?= htmlspecialchars($vueloDataIda['codigo_vuelo']) ?></div>
                <div><b>Fecha:</b> <?= htmlspecialchars($vueloDataIda['fecha_salida']) ?></div>
                <div><b>Origen:</b> <?= htmlspecialchars($vueloDataIda['origen']) ?></div>
                <div><b>Destino:</b> <?= htmlspecialchars($vueloDataIda['destino']) ?></div>
                <div><b>Hora:</b> <?= htmlspecialchars($vueloDataIda['hora_salida']) ?></div>
                <div><b>Avión:</b> <?= htmlspecialchars($vueloDataIda['nombre_modelo_avion'] ?? 'N/A') ?></div>
            </div>
            <div>
                <b>Asientos Asignados:</b>
                <div class="asientos">
                    <?php if (!empty($asientosIda)): ?>
                        <?php foreach ($asientosIda as $a): ?>
                            <span class="asiento"><?= htmlspecialchars($a) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span>No se registraron asientos.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- VUELO DE REGRESO -->
        <?php if ($vueloDataRegreso): ?>
        <div class="boarding-pass">
            <div class="qr"><img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=<?= urlencode($numeroFactura . '-R') ?>" alt="QR"></div>
            <h3>🔁 Vuelo de Regreso</h3>
            <div class="flight-info">
                <div><b>Código:</b> <?= htmlspecialchars($vueloDataRegreso['codigo_vuelo']) ?></div>
                <div><b>Fecha:</b> <?= htmlspecialchars($vueloDataRegreso['fecha_salida']) ?></div>
                <div><b>Origen:</b> <?= htmlspecialchars($vueloDataRegreso['origen']) ?></div>
                <div><b>Destino:</b> <?= htmlspecialchars($vueloDataRegreso['destino']) ?></div>
                <div><b>Hora:</b> <?= htmlspecialchars($vueloDataRegreso['hora_salida']) ?></div>
                <div><b>Avión:</b> <?= htmlspecialchars($vueloDataRegreso['nombre_modelo_avion'] ?? 'N/A') ?></div>
            </div>
            <div>
                <b>Asientos Asignados:</b>
                <div class="asientos">
                    <?php if (!empty($asientosRegreso)): ?>
                        <?php foreach ($asientosRegreso as $a): ?>
                            <span class="asiento"><?= htmlspecialchars($a) ?></span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span>No se registraron asientos.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <div class="actions">
            <button onclick="window.print()">🖨️ Imprimir Ticket</button>
            <a href="lista_vuelos.php"><button type="button">✈️ Ver más vuelos</button></a>
        </div>
    </div>

    <div class="footer">
        © <?= date('Y') ?> Aterriza Airlines — Todos los derechos reservados<br>
        Este ticket electrónico es válido únicamente para el pasajero indicado y no es transferible.
    </div>
</div>

</body>
</html>
