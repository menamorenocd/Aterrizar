<?php
session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['usuario']['rol'] !== 'Administrador' && $_SESSION['usuario']['rol'] !== 'ADS')) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel Administrativo - Sistema de Vuelos</title>
<style>
body {font-family: Arial, sans-serif; background: #f0f2f5; margin: 0;}
nav {
    background: #007bff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 25px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
}
nav .menu a {
    color: white;
    text-decoration: none;
    margin-right: 20px;
    font-weight: bold;
}
nav .menu a:hover {
    text-decoration: underline;
}
.container {
    background: white;
    margin: 30px auto;
    width: 90%;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}
th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}
th {
    background: #007bff;
    color: white;
}
input, select {
    padding: 8px;
    margin: 5px 0;
}
input[type=submit], button {
    background: #007bff;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 8px 12px;
    cursor: pointer;
}
input[type=submit]:hover, button:hover {
    background: #0056b3;
}
a.delete {
    color: red;
    text-decoration: none;
}
a.delete:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<nav>
    <div class="menu">
        <a href="panel_admin.php"> Inicio</a>
        <a href="aerolineas.php"> Aerolíneas</a>
        <a href="modelos.php"> Modelos</a>
        <a href="aviones.php"> Aviones</a>
        <a href="ciudades.php"> Ciudades</a>
        <a href="vuelos.php"> Vuelos</a>
        <a href="asientos.php"> Asientos</a>
        <!-- <a href="usuarios.php"> Usuarios</a> -->
        <a href="compras.php"> Compras</a>
    </div>
    <div>
        <a href="../../controlador/logout.php" style="color:white;"> Cerrar sesión</a>
    </div>
</nav>
<div class="container">    
