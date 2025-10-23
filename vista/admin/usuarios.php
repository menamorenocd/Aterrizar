<?php
include 'includes/header.php';
include 'includes/conexion_admin.php';

// Crear usuario
if (isset($_POST['agregar'])) {
    $nombre = $_POST['nombre'];
    $apellido1 = $_POST['apellido1'];
    $apellido2 = $_POST['apellido2'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rol = $_POST['rol'];
    $genero = $_POST['genero'];
    $tipo_doc = $_POST['tipo_doc'];
    $num_doc = $_POST['num_doc'];
    $stmt = $conexion->prepare("INSERT INTO tb_usuario (nombre_usuario, apellido1, apellido2, correo_usuario, usuario_login, password_hash, rol, genero, tipo_documeto, numero_documento) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $nombre, $apellido1, $apellido2, $correo, $usuario, $password, $rol, $genero, $tipo_doc, $num_doc);
    $stmt->execute();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_usuario WHERE id_usuario=$id");
}

// Editar rol
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $rol = $_POST['rol'];
    $stmt = $conexion->prepare("UPDATE tb_usuario SET rol=? WHERE id_usuario=?");
    $stmt->bind_param("si", $rol, $id);
    $stmt->execute();
}

// Listar
$result = $conexion->query("SELECT * FROM tb_usuario ORDER BY id_usuario ASC");
?>
<h2>Gestión de Usuarios</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre" required>
    <input type="text" name="apellido1" placeholder="Primer Apellido" required>
    <input type="text" name="apellido2" placeholder="Segundo Apellido" required>
    <input type="text" name="correo" placeholder="Correo" required>
    <input type="text" name="usuario" placeholder="Usuario" required>
    <input type="password" name="password" placeholder="Contraseña" required>
    <select name="rol" required>
        <option value="Usuario">Usuario</option>
        <option value="Administrador">Administrador</option>
        <option value="ADS">ADS</option>
    </select>
    <select name="genero" required>
        <option value="Hombre">Hombre</option>
        <option value="Mujer">Mujer</option>
    </select>
    <select name="tipo_doc" required>
        <option value="Cedula">Cédula</option>
        <option value="Targeta de identidad">Tarjeta de Identidad</option>
        <option value="Pasaporte">Pasaporte</option>
    </select>
    <input type="text" name="num_doc" placeholder="Número Documento" required>
    <input type="submit" name="agregar" value="Agregar">
</form>

<table>
<tr><th>ID</th><th>Nombre</th><th>Usuario</th><th>Correo</th><th>Rol</th><th>Acciones</th></tr>
<?php while ($u = $result->fetch_assoc()): ?>
<tr>
<form method="POST">
<td><?= $u['id_usuario'] ?></td>
<td><?= htmlspecialchars($u['nombre_usuario']." ".$u['apellido1']." ".$u['apellido2']) ?></td>
<td><?= htmlspecialchars($u['usuario_login']) ?></td>
<td><?= htmlspecialchars($u['correo_usuario']) ?></td>
<td>
    <select name="rol">
        <option <?= $u['rol']=="Usuario"?"selected":"" ?>>Usuario</option>
        <option <?= $u['rol']=="Administrador"?"selected":"" ?>>Administrador</option>
        <option <?= $u['rol']=="ADS"?"selected":"" ?>>ADS</option>
    </select>
</td>
<td>
    <input type="hidden" name="id" value="<?= $u['id_usuario'] ?>">
    <input type="submit" name="editar" value="Actualizar Rol">
    <a class="delete" href="?eliminar=<?= $u['id_usuario'] ?>" onclick="return confirm('¿Eliminar este usuario?')">Eliminar</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
