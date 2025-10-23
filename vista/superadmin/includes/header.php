<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'ADS') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Super Admin</title>
<style>
:root {
    --color-primario: #2563eb;
    --color-blanco: #ffffff;
    --color-texto: #e5e7eb;
    --color-hover: rgba(255,255,255,0.15);
    --color-rojo: #dc2626;
}

body {
    margin: 0;
    font-family: 'Inter', 'Segoe UI', sans-serif;
    background-color: #f3f4f6;
    color: #1f2937;
    display: flex;
    min-height: 100vh;
}

nav {
    width: 240px;
    background: var(--color-primario);
    color: var(--color-blanco);
    display: flex;
    flex-direction: column;
    padding-top: 25px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
}

nav h2 {
    text-align: center;
    font-size: 20px;
    font-weight: 600;
    margin-bottom: 25px;
    color: var(--color-blanco);
}

nav a {
    color: var(--color-texto);
    padding: 12px 25px;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    transition: all 0.25s ease;
}

nav a:hover {
    background: var(--color-hover);
    color: var(--color-blanco);
    padding-left: 30px;
}

nav .logout {
    margin-top: auto;
    background: var(--color-rojo);
    color: var(--color-blanco);
    text-align: center;
    padding: 12px;
    font-weight: bold;
    cursor: pointer;
    border-top: 1px solid rgba(255,255,255,0.2);
}

main {
    flex: 1;
    margin-left: 240px;
    padding: 30px;
}
</style>
</head>
<body>

<nav>
    <h2>🛫 Super Admin</h2>
    <a href="../superadmin/panel_super_admin.php">🏠 Dashboard</a>
    <a href="../superadmin/usuarios.php">👤 Usuarios</a>
    <a href="../superadmin/aerolineas.php">✈️ Aerolíneas</a>
    <a href="../superadmin/vuelos.php">🗓️ Vuelos</a>  
    <a href="../superadmin/reportes_superadmin.php">📊 Reportes</a>
    <div class="logout" onclick="window.location.href='../logout.php'">Cerrar sesión</div>
</nav>

<main>
