<?php
// Inicia sesión y verifica usuario
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../login.php");
    exit;
}

include '../header.php';
$nombre = htmlspecialchars($_SESSION['usuario']['nombre']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel de Usuario | Aterriza</title>
<style>
body{font-family:'Segoe UI',Arial;background:#f8fafc;margin:0;}
.container{max-width:1000px;margin:40px auto;background:#fff;padding:30px;border-radius:16px;
box-shadow:0 4px 12px rgba(0,0,0,0.1);}
h2{text-align:center;color:#004aad;margin-bottom:10px;}
.menu{display:flex;justify-content:center;flex-wrap:wrap;gap:20px;margin:20px 0;}
.menu a{background:#007bff;color:white;text-decoration:none;padding:12px 20px;border-radius:8px;transition:0.3s;}
.menu a:hover{background:#0056b3;}
</style>
</head>
<body>
<div class="container">
  <h2>👋 Bienvenido, <?= $nombre ?></h2>
  <p style="text-align:center;">Selecciona una opción para continuar:</p>

  <div class="menu">
    <a href="../index.php">✈️ Buscar vuelos</a>
    <a href="mis_vuelos.php">🎫 Mis vuelos</a>
    <a href="perfil.php">👤 Mi perfil</a>
  </div>
</div>

<?php include '../footer.php'; ?>
</body>
</html>
