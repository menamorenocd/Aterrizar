<?php include 'includes/header.php'; ?>
<h1>Panel de Administración del Sistema de Vuelos</h1>
<p>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']['nombre']) ?> (<?= $_SESSION['usuario']['rol'] ?>)</p>

<ul>
    <li><a href="aerolineas.php">Gestionar Aerolíneas</a></li>
    <li><a href="modelos.php">Gestionar Modelos de Avión</a></li>
    <li><a href="aviones.php">Gestionar Aviones</a></li>
    <li><a href="ciudades.php">Gestionar Ciudades</a></li>
    <li><a href="vuelos.php">Gestionar Vuelos</a></li>
    <li><a href="asientos.php">Gestionar Asientos</a></li>
    <li><a href="usuarios.php">Gestionar Usuarios</a></li>
    <li><a href="compras.php">Gestionar Compras</a></li>
</ul>

<?php include 'includes/footer.php'; ?>
