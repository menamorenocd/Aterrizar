<?php
session_start();
require_once __DIR__ . '/../controlador/UsuarioControlador.php';
$controlador = new UsuarioControlador();
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = $controlador->login($_POST['usuario_login'], $_POST['password']);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión - Aterriza</title>
<style>
body { background:#f2f2f2; font-family:Arial; }
form {
  background:white; width:300px; margin:100px auto; padding:20px;
  border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1);
}
input { width:100%; padding:10px; margin:8px 0; }
button {
  background:#007BFF; color:white; padding:10px; border:none;
  border-radius:5px; width:100%; cursor:pointer;
}
button:hover { background:#0056b3; }
</style>
</head>
<body>
<form method="POST">
<h2>Iniciar Sesión</h2>
<?php if($mensaje): ?><p style="color:red;"><?= $mensaje ?></p><?php endif; ?>
<input type="text" name="usuario_login" placeholder="Usuario" required>
<input type="password" name="password" placeholder="Contraseña" required>
<button type="submit">Entrar</button>
<p><a href="registro.php">¿No tienes cuenta? Regístrate</a></p>
</form>
</body>
</html>
