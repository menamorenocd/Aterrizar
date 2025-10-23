<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Aterriza | Sistema de Vuelos</title>
<style>
/* ======== NAVBAR PRINCIPAL ======== */
body {
  margin:0;
  padding:0;
  font-family:'Segoe UI',Arial,sans-serif;
  background:#f5f7fa;
}

.navbar {
  display:flex;
  justify-content:space-between;
  align-items:center;
  background:#004aad;
  padding:12px 30px;
  color:white;
  box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

.nav-left a.logo {
  color:white;
  font-size:22px;
  font-weight:bold;
  text-decoration:none;
}

.nav-right {
  display:flex;
  align-items:center;
  gap:15px;
}

.nav-right span {
  font-size:15px;
}

.nav-right a {
  color:white;
  text-decoration:none;
  background:#007bff;
  padding:8px 14px;
  border-radius:6px;
  transition:background 0.3s ease;
  font-size:14px;
}

.nav-right a:hover {
  background:#0056b3;
}

/* ======== FOOTER ======== */
footer {
  background:#004aad;
  color:white;
  text-align:center;
  padding:15px 0;
  font-size:14px;
  position:relative;
  bottom:0;
  width:100%;
}

/* ======== RESPONSIVE ======== */
@media(max-width:700px){
  .navbar {
    flex-direction:column;
    align-items:flex-start;
  }
  .nav-right {
    margin-top:10px;
    flex-wrap:wrap;
  }
}
</style>
</head>
<body>

<nav class="navbar">
  <div class="nav-left">
    <a href="index.php" class="logo">✈️ Aterriza</a>
  </div>
  <div class="nav-right">
    <?php if (isset($_SESSION['nombre_usuario'])): ?>
      <span>👤 <?= htmlspecialchars($_SESSION['nombre_usuario']) ?></span>
      <a href="logout.php">Cerrar sesión</a>
    <?php else: ?>
      <a href="login.php">Iniciar sesión</a>
    <?php endif; ?>
  </div>
</nav>
