<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

include '../header.php';
require_once __DIR__ . '/../../modelo/UsuarioModelo.php';

$modelo = new UsuarioModelo();
$idUsuario = $_SESSION['usuario']['id'];

// Obtener datos del usuario actual
$datos = $modelo->obtenerUsuarioPorId($idUsuario);

// Procesar actualización
$mensaje = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $password = !empty($_POST['password']) ? sha1($_POST['password']) : $datos['password'];

    if ($modelo->actualizarPerfil($idUsuario, $nombre, $correo, $password)) {
        $_SESSION['usuario']['nombre'] = $nombre;
        $mensaje = "<div class='ok'>✅ Perfil actualizado correctamente.</div>";
    } else {
        $mensaje = "<div class='error'>❌ Error al actualizar.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi perfil | Aterriza</title>
<style>
body{font-family:'Segoe UI',Arial;background:#f9fbfc;margin:0;}
.container{max-width:600px;margin:40px auto;background:#fff;padding:30px;border-radius:16px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);}
h2{text-align:center;color:#004aad;}
form{display:flex;flex-direction:column;gap:15px;margin-top:20px;}
input{padding:10px;border:1px solid #ccc;border-radius:8px;font-size:15px;}
button{background:#007bff;color:white;padding:10px;border:none;border-radius:8px;cursor:pointer;}
button:hover{background:#0056b3;}
.ok{background:#d4edda;color:#155724;padding:10px;border-radius:6px;margin-bottom:10px;}
.error{background:#f8d7da;color:#721c24;padding:10px;border-radius:6px;margin-bottom:10px;}
</style>
</head>
<body>
<div class="container">
  <h2>👤 Mi perfil</h2>
  <?= $mensaje ?>
  <form method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($datos['nombre_usuario']) ?>" required>

    <label>Correo:</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($datos['correo']) ?>" required>

    <label>Contraseña (dejar vacío si no desea cambiarla):</label>
    <input type="password" name="password" placeholder="Nueva contraseña">

    <button type="submit">Guardar cambios</button>
  </form>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
