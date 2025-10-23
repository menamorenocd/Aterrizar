<?php include 'includes/header.php'; ?>
<?php include 'includes/conexion_super.php'; ?>

<?php
$totalUsuarios = $conexion->query("SELECT COUNT(*) AS total FROM tb_usuario")->fetch_assoc()['total'];
$totalVuelos = $conexion->query("SELECT COUNT(*) AS total FROM tb_vuelo")->fetch_assoc()['total'];
$totalCompras = $conexion->query("SELECT COUNT(*) AS total FROM tb_control_compra")->fetch_assoc()['total'];
$totalAerolíneas = $conexion->query("SELECT COUNT(*) AS total FROM tb_aerolinea")->fetch_assoc()['total'];

$nombreUsuario = $_SESSION['usuario']['nombre_usuario']
    ?? $_SESSION['usuario']['usuario_login']
    ?? $_SESSION['usuario']['correo_usuario']
    ?? 'Super Admin';
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Super Admin - Sistema de Vuelos</title>
<style>
:root {
    --color-primario: #2563eb;
    --color-secundario: #f3f4f6;
    --color-texto: #1f2937;
    --color-blanco: #ffffff;
    --color-gris: #9ca3af;
    --color-rojo: #dc2626;
}
body {
    margin: 0;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background-color: var(--color-secundario);
    color: var(--color-texto);
    display: flex;
    min-height: 100vh;
    overflow: hidden;
}
nav {
    width: 240px;
    background: var(--color-primario);
    color: var(--color-blanco);
    display: flex;
    flex-direction: column;
    padding: 25px 0;
    box-shadow: 2px 0 10px rgba(0,0,0,0.05);
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
}
nav h2 {
    text-align: center;
    font-size: 20px;
    margin-bottom: 25px;
    letter-spacing: 0.5px;
}
nav a {
    color: #e5e7eb;
    padding: 12px 25px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.25s ease;
    font-size: 15px;
}
nav a:hover {
    background: rgba(255, 255, 255, 0.1);
    padding-left: 30px;
    color: #fff;
}
nav .logout {
    margin-top: auto;
    background: var(--color-rojo);
    color: #fff;
    text-align: center;
    padding: 12px;
    font-weight: bold;
    cursor: pointer;
}
main {
    flex: 1;
    margin-left: 240px;
    padding: 40px;
    overflow-y: auto;
}
header {
    background-color: var(--color-blanco);
    border-radius: 10px;
    padding: 25px 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
header h1 {
    margin: 0;
    font-size: 24px;
    color: var(--color-primario);
}
header p {
    margin-top: 5px;
    color: var(--color-gris);
}
.stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 25px;
    margin-top: 40px;
}
.card {
    background: var(--color-blanco);
    border-radius: 12px;
    padding: 25px 20px;
    text-align: center;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.card:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.1);
}
.card h3 {
    margin-bottom: 10px;
    font-weight: 500;
    color: var(--color-gris);
}
.card p {
    font-size: 34px;
    font-weight: bold;
    color: var(--color-primario);
}
footer {
    text-align: center;
    margin-top: 40px;
    color: var(--color-gris);
    font-size: 14px;
}
</style>
</head>
<body>

<nav>
    <h2>🛫 Super Admin</h2>
    <a href="panel_superadmin.php">🏠 Dashboard</a>
    <a href="usuarios.php">👤 Usuarios</a>
    <a href="aerolineas.php">✈️ Aerolíneas</a>
    <a href="vuelos.php">🗓️ Vuelos</a>
    <a href="reportes_superadmin.php">📊 Reportes</a>
    
    <div class="logout" onclick="window.location.href='../logout.php'">Cerrar sesión</div>
</nav>

<main>
    <header>
        <h1>Panel de Control del Super Administrador</h1>
        <p>Bienvenido, <?= htmlspecialchars($nombreUsuario) ?> 👋</p>
    </header>

    <section class="stats">
        <div class="card">
            <h3>Usuarios Registrados</h3>
            <p><?= $totalUsuarios ?></p>
        </div>
        <div class="card">
            <h3>Vuelos Activos</h3>
            <p><?= $totalVuelos ?></p>
        </div>
        <div class="card">
            <h3>Compras Realizadas</h3>
            <p><?= $totalCompras ?></p>
        </div>
        <div class="card">
            <h3>Aerolíneas Registradas</h3>
            <p><?= $totalAerolíneas ?></p>
        </div>
    </section>

    <footer>
        Sistema de Gestión de Vuelos © <?= date('Y') ?> - Panel Super Admin
    </footer>
</main>

</body>
</html>
