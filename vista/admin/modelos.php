<?php
include 'includes/header.php';
include 'includes/conexion_admin.php';

// Crear
if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre']);
    $filas = intval($_POST['filas']);
    $columnas = intval($_POST['columnas']);
    $stmt = $conexion->prepare("INSERT INTO tb_modelo_avion (nombre_modelo_avion, filas_modelo_avion, columnas_modelo_avion) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $nombre, $filas, $columnas);
    $stmt->execute();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_modelo_avion WHERE id_modelo_avion = $id");
}

// Actualizar
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $nombre = trim($_POST['nombre']);
    $filas = intval($_POST['filas']);
    $columnas = intval($_POST['columnas']);
    $stmt = $conexion->prepare("UPDATE tb_modelo_avion SET nombre_modelo_avion=?, filas_modelo_avion=?, columnas_modelo_avion=? WHERE id_modelo_avion=?");
    $stmt->bind_param("siii", $nombre, $filas, $columnas, $id);
    $stmt->execute();
}

// Listar
$result = $conexion->query("SELECT * FROM tb_modelo_avion ORDER BY id_modelo_avion ASC");
?>
<h2>Gestión de Modelos de Avión</h2>
<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre del modelo" required>
    <input type="number" name="filas" placeholder="Filas" required>
    <input type="number" name="columnas" placeholder="Columnas" required>
    <input type="submit" name="agregar" value="Agregar">
</form>
<table>
<tr><th>ID</th><th>Modelo</th><th>Filas</th><th>Columnas</th><th>Acciones</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<form method="POST">
<td><?= $row['id_modelo_avion'] ?></td>
<td><input type="text" name="nombre" value="<?= htmlspecialchars($row['nombre_modelo_avion']) ?>"></td>
<td><input type="number" name="filas" value="<?= $row['filas_modelo_avion'] ?>"></td>
<td><input type="number" name="columnas" value="<?= $row['columnas_modelo_avion'] ?>"></td>
<td>
    <input type="hidden" name="id" value="<?= $row['id_modelo_avion'] ?>">
    <input type="submit" name="editar" value="Actualizar">
    <a class="delete" href="?eliminar=<?= $row['id_modelo_avion'] ?>" onclick="return confirm('¿Eliminar este modelo?')">Eliminar</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>
<?php include 'includes/footer.php'; ?>


