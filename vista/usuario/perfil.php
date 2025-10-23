<?php
// =============================
// PERFIL DEL USUARIO - Aterriza
// =============================

// Iniciar sesión y verificar que el usuario esté autenticado
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

// Incluir encabezado y controlador
include '../header.php';
require_once __DIR__ . '/../../controlador/UsuarioControlador.php';

// Instanciar el controlador
$usuarioCtrl = new UsuarioControlador();
$idUsuario = $_SESSION['usuario']['id'];

// Obtener datos actuales del usuario
$datos = $usuarioCtrl->obtenerPerfil($idUsuario);

// Variables de mensaje (para mostrar éxito o error)
$mensaje = "";

// Procesar actualización si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre']);
    $correo = trim($_POST['correo']);
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    if ($usuarioCtrl->actualizarPerfil($idUsuario, $nombre, $correo, $password)) {
        $_SESSION['usuario']['nombre'] = $nombre;
        $mensaje = "<div class='ok'>✅ Perfil actualizado correctamente.</div>";

        // Actualizar los datos mostrados en pantalla
        $datos = $usuarioCtrl->obtenerPerfil($idUsuario);
    } else {
        $mensaje = "<div class='error'>❌ Error al actualizar el perfil. Inténtalo nuevamente.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi Perfil | Aterriza</title>

<style>
body {
    font-family:'Segoe UI',Arial,sans-serif;
    background:#f9fbfc;
    margin:0;
    padding:0;
}
.container {
    max-width:600px;
    margin:50px auto;
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}
h2 {
    text-align:center;
    color:#004aad;
    margin-bottom:20px;
}
form {
    display:flex;
    flex-direction:column;
    gap:15px;
}
label {
    font-weight:600;
    color:#333;
}
input {
    padding:10px;
    border:1px solid #ccc;
    border-radius:8px;
    font-size:15px;
}
button {
    background:#007bff;
    color:white;
    padding:10px;
    border:none;
    border-radius:8px;
    cursor:pointer;
    font-size:16px;
    transition:background 0.3s ease;
}
button:hover {
    background:#0056b3;
}
.ok {
    background:#d4edda;
    color:#155724;
    padding:10px;
    border-radius:8px;
    text-align:center;
    margin-bottom:15px;
}
.error {
    background:#f8d7da;
    color:#721c24;
    padding:10px;
    border-radius:8px;
    text-align:center;
    margin-bottom:15px;
}
a.volver {
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    background:#6c757d;
    color:white;
    padding:8px 14px;
    border-radius:6px;
}
a.volver:hover {
    background:#5a6268;
}
</style>
</head>

<body>
<div class="container">
    <h2>👤 Mi Perfil</h2>

    <?= $mensaje ?>

    <form method="POST" autocomplete="off">
        <label>Nombre completo:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($datos['nombre_usuario']) ?>" required>

        <label>Correo electrónico:</label>
        <input type="email" name="correo" value="<?= htmlspecialchars($datos['correo_usuario']) ?>" required>

        <label>Usuario:</label>
        <input type="text" value="<?= htmlspecialchars($datos['usuario_login']) ?>" disabled>

        <label>Nueva contraseña (opcional):</label>
        <input type="password" name="password" placeholder="Ingrese nueva contraseña si desea cambiarla">

        <button type="submit">💾 Guardar cambios</button>
    </form>

    <div style="text-align:center;">
        <a class="volver" href="panel_usuario.php">⬅️ Volver al panel</a>
    </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
