<?php
include 'includes/header.php';
include 'includes/conexion_admin.php';

// Crear
if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre']);
    $stmt = $conexion->prepare("INSERT INTO tb_ciudad (nombre_ciudad) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_ciudad WHERE id_ciudad = $id");
}

// Editar
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $stmt = $conexion->prepare("UPDATE tb_ciudad SET nombre_ciudad=? WHERE id_ciudad=?");
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
}

$result = $conexion->query("SELECT * FROM tb_ciudad ORDER BY id_ciudad ASC");
?>
<h2>Gestión de Ciudades</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre de la ciudad" required>
    <input type="submit" name="agregar" value="Agregar">
</form>

<table>
<tr><th>ID</th><th>Ciudad</th><th>Acciones</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<form method="POST">
<td><?= $row['id_ciudad'] ?></td>
<td><input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre_ciudad']) ?>"></td>
<td>
    <input type="hidden" name="id" value="<?= $row['id_ciudad'] ?>">
    <input type="submit" name="editar" value="Actualizar">
    <a class="delete" href="?eliminar=<?= $row['id_ciudad'] ?>" onclick="return confirm('¿Eliminar esta ciudad?')">Eliminar</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>
<?php include 'includes/footer.php'; ?>
