<?php
include __DIR__ . '/includes/header.php';
include __DIR__ . '/includes/conexion_admin.php';

// Crear aerolínea
if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre']);
    if ($nombre !== '') {
        $stmt = $conexion->prepare("INSERT INTO tb_aerolinea (nombre_aerolinea) VALUES (?)");
        $stmt->bind_param("s", $nombre);
        $stmt->execute();
        echo "<script>alert('Aerolínea agregada correctamente'); window.location='aerolineas.php';</script>";
    }
}

// Eliminar aerolínea
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_aerolinea WHERE id_aerolinea = $id");
    echo "<script>alert('Aerolínea eliminada correctamente'); window.location='aerolineas.php';</script>";
}

// Actualizar aerolínea
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $stmt = $conexion->prepare("UPDATE tb_aerolinea SET nombre_aerolinea=? WHERE id_aerolinea=?");
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
    echo "<script>alert('Aerolínea actualizada correctamente'); window.location='aerolineas.php';</script>";
}

// Obtener lista
$result = $conexion->query("SELECT * FROM tb_aerolinea ORDER BY id_aerolinea ASC");
?>
<h2>Gestión de Aerolíneas</h2>

<!-- Formulario para agregar -->
<form method="POST" style="margin-bottom:20px;">
    <input type="text" name="nombre" placeholder="Nombre de la aerolínea" required>
    <input type="submit" name="agregar" value="Agregar Aerolínea">
</form>

<!-- Tabla de aerolíneas -->
<table>
<tr><th>ID</th><th>Nombre</th><th>Acciones</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<form method="POST">
    <td><?= $row['id_aerolinea'] ?></td>
    <td><input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre_aerolinea']) ?>"></td>
    <td>
        <input type="hidden" name="id" value="<?= $row['id_aerolinea'] ?>">
        <input type="submit" name="editar" value="Actualizar">
        <a class="delete" href="?eliminar=<?= $row['id_aerolinea'] ?>" onclick="return confirm('¿Eliminar esta aerolínea?')">Eliminar</a>
    </td>
</form>
</tr>
<?php endwhile; ?>
</table>

<?php include __DIR__ . '/includes/footer.php'; ?>
