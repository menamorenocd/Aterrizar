<?php include 'includes/header.php'; ?>
<?php include 'includes/conexion_super.php'; ?>

<?php
// --- CREAR USUARIO ---
if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre_usuario']);
    $apellido1 = trim($_POST['apellido1']);
    $apellido2 = trim($_POST['apellido2']);
    $correo = trim($_POST['correo_usuario']);
    $usuario = trim($_POST['usuario_login']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];

    $stmt = $conexion->prepare("
        INSERT INTO tb_usuario 
        (nombre_usuario, apellido1, apellido2, correo_usuario, usuario_login, password_hash, rol) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssss", $nombre, $apellido1, $apellido2, $correo, $usuario, $password, $rol);
    $stmt->execute();
}

// --- ELIMINAR USUARIO ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_usuario WHERE id_usuario = $id");
}

// --- ACTUALIZAR USUARIO ---
if (isset($_POST['editar'])) {
    $id = intval($_POST['id_usuario']);
    $nombre = trim($_POST['nombre_usuario']);
    $apellido1 = trim($_POST['apellido1']);
    $apellido2 = trim($_POST['apellido2']);
    $correo = trim($_POST['correo_usuario']);
    $usuario = trim($_POST['usuario_login']);
    $rol = $_POST['rol'];

    $stmt = $conexion->prepare("
        UPDATE tb_usuario 
        SET nombre_usuario=?, apellido1=?, apellido2=?, correo_usuario=?, usuario_login=?, rol=? 
        WHERE id_usuario=?
    ");
    $stmt->bind_param("ssssssi", $nombre, $apellido1, $apellido2, $correo, $usuario, $rol, $id);
    $stmt->execute();
}

// --- OBTENER LISTA ---
$result = $conexion->query("SELECT * FROM tb_usuario ORDER BY id_usuario ASC");
?>

<main>
<header style="background:#fff; border-radius:10px; padding:20px 25px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
    <h1 style="margin:0; color:#2563eb;">Gestión de Usuarios</h1>
    <p style="color:#6b7280;">Administra los usuarios del sistema</p>
</header>

<section style="margin-top:30px;">
    <form method="POST" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05); display:grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap:12px;">
        <input type="text" name="nombre_usuario" placeholder="Nombre" required>
        <input type="text" name="apellido1" placeholder="Primer Apellido" required>
        <input type="text" name="apellido2" placeholder="Segundo Apellido" required>
        <input type="email" name="correo_usuario" placeholder="Correo" required>
        <input type="text" name="usuario_login" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <select name="rol" required>
            <option value="Usuario">Usuario</option>
            <option value="Administrador">Administrador</option>
            <option value="ADS">Super Administrador</option>
        </select>
        <button type="submit" name="agregar" style="background:#2563eb;color:#fff;padding:10px;border:none;border-radius:6px;cursor:pointer;font-weight:600;">Agregar</button>
    </form>
</section>

<section style="margin-top:35px;">
    <div style="overflow-x:auto; background:#fff; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.05); padding:10px;">
        <table style="width:100%; border-collapse:collapse; min-width:1100px;">
            <thead style="background:#2563eb; color:#fff;">
                <tr>
                    <th style="padding:14px; text-align:left;">ID</th>
                    <th style="padding:14px; text-align:left;">Nombre</th>
                    <th style="padding:14px; text-align:left;">Apellidos</th>
                    <th style="padding:14px; text-align:left;">Correo</th>
                    <th style="padding:14px; text-align:left;">Usuario</th>
                    <th style="padding:14px; text-align:left;">Rol</th>
                    <th style="padding:14px; text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr style="border-bottom:1px solid #f1f1f1;">
                    <form method="POST">
                        <td style="padding:10px; text-align:center; width:5%;"><?= $row['id_usuario'] ?></td>
                        <td style="padding:10px; width:15%;">
                            <input type="text" name="nombre_usuario" value="<?= htmlspecialchars($row['nombre_usuario']) ?>" required 
                                style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100%;">
                        </td>
                        <td style="padding:10px; width:18%;">
                            <div style="display:flex; gap:5px;">
                                <input type="text" name="apellido1" value="<?= htmlspecialchars($row['apellido1']) ?>" 
                                    style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100%;" required>
                                <input type="text" name="apellido2" value="<?= htmlspecialchars($row['apellido2']) ?>" 
                                    style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100%;" required>
                            </div>
                        </td>
                        <td style="padding:10px; width:22%;">
                            <input type="email" name="correo_usuario" value="<?= htmlspecialchars($row['correo_usuario']) ?>" required 
                                style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100%;">
                        </td>
                        <td style="padding:10px; width:13%;">
                            <input type="text" name="usuario_login" value="<?= htmlspecialchars($row['usuario_login']) ?>" required 
                                style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100%;">
                        </td>
                        <td style="padding:10px; width:10%;">
                            <select name="rol" style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100%;">
                                <option <?= $row['rol'] === 'Usuario' ? 'selected' : '' ?>>Usuario</option>
                                <option <?= $row['rol'] === 'Administrador' ? 'selected' : '' ?>>Administrador</option>
                                <option <?= $row['rol'] === 'ADS' ? 'selected' : '' ?>>Super Administrador</option>
                            </select>
                        </td>
                        <td style="padding:10px; text-align:center; width:15%;">
    <input type="hidden" name="id_usuario" value="<?= $row['id_usuario'] ?>">
    <div style="display:flex; justify-content:center; gap:6px;">
        <button name="editar" 
            style="background:#10b981;color:#fff;border:none;padding:7px 14px;border-radius:5px;cursor:pointer;font-size:14px;">
            Actualizar
        </button>
        <a href="?eliminar=<?= $row['id_usuario'] ?>" 
           onclick="return confirm('¿Eliminar este usuario?')" 
           style="background:#dc2626;color:#fff;padding:7px 14px;border-radius:5px;text-decoration:none;font-size:14px;">
           Eliminar
        </a>
    </div>
</td>
                    </form>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</section>

<footer style="text-align:center;margin-top:40px;color:#9ca3af;">Panel Super Admin © <?= date('Y') ?></footer>
</main>

<?php include 'includes/footer.php'; ?>
