<?php
session_start();
require_once __DIR__ . '/../controlador/UsuarioControlador.php';
$controller = new UsuarioControlador();

$msg = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $msg = $controller->registrar(
        $_POST['nombre_usuario'],
        $_POST['apellido1'],
        $_POST['apellido2'],
        $_POST['genero'],
        $_POST['tipo_documeto'],
        $_POST['numero_documento'],
        $_POST['correo_usuario'],
        $_POST['usuario_login'],
        $_POST['password']
    );
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro - Sistema de Vuelos</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f2f2f2;
}
form {
    background: #fff;
    width: 320px;
    margin: 60px auto;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
input, select {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}
button {
    background: #28a745;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 5px;
    width: 100%;
    cursor: pointer;
}
button:hover {
    background: #1e7e34;
}
a {
    text-decoration: none;
    color: #007bff;
}
a:hover {
    text-decoration: underline;
}
</style>
</head>
<body>
<form method="POST">
    <h2>Registro de Usuario</h2>

    <?php if($msg): ?>
        <p style="color:red;"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <input type="text" name="nombre_usuario" placeholder="Nombre(s)" required>
    <input type="text" name="apellido1" placeholder="Primer apellido" required>
    <input type="text" name="apellido2" placeholder="Segundo apellido" required>

    <label for="genero">Género:</label>
    <select name="genero" id="genero" required>
        <option value="">Seleccione</option>
        <option value="Hombre">Hombre</option>
        <option value="Mujer">Mujer</option>
    </select>

    <label for="tipo_documeto">Tipo de documento:</label>
    <select name="tipo_documeto" id="tipo_documeto" required>
        <option value="">Seleccione</option>
        <option value="Cedula">Cédula</option>
        <option value="Targeta de identidad">Tarjeta de identidad</option>
        <option value="Pasaporte">Pasaporte</option>
        <option value="Otro">Otro</option>
    </select>

    <input type="text" name="numero_documento" placeholder="Número de documento" required>
    <input type="email" name="correo_usuario" placeholder="Correo electrónico" required>
    <input type="text" name="usuario_login" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>

    <button type="submit">Registrarse</button>
    <p><a href="login.php">¿Ya tienes cuenta? Inicia sesión</a></p>
</form>
</body>
</html>
