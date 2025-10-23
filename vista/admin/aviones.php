<?php
include 'includes/header.php';
include 'includes/conexion_admin.php';

// Cargar listas
$modelos = $conexion->query("SELECT * FROM tb_modelo_avion");
$aerolineas = $conexion->query("SELECT * FROM tb_aerolinea");

// Crear
if (isset($_POST['agregar'])) {
    $codigo = trim($_POST['codigo']);
    $modelo = intval($_POST['modelo']);
    $aerolinea = intval($_POST['aerolinea']);
    $stmt = $conexion->prepare("INSERT INTO tb_avion (codigo_avion, modelo_id, aerolinea_id) VALUES (?, ?, ?)");
    $stmt->bind_param("sii", $codigo, $modelo, $aerolinea);
    $stmt->execute();
}

// Eliminar
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_avion WHERE id_avion = $id");
}

// Actualizar
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $codigo = trim($_POST['codigo']);
    $modelo = intval($_POST['modelo']);
    $aerolinea = intval($_POST['aerolinea']);
    $stmt = $conexion->prepare("UPDATE tb_avion SET codigo_avion=?, modelo_id=?, aerolinea_id=? WHERE id_avion=?");
    $stmt->bind_param("siii", $codigo, $modelo, $aerolinea, $id);
    $stmt->execute();
}

// Listar
$result = $conexion->query("SELECT a.*, m.nombre_modelo_avion, ae.nombre_aerolinea 
                            FROM tb_avion a
                            JOIN tb_modelo_avion m ON a.modelo_id = m.id_modelo_avion
                            JOIN tb_aerolinea ae ON a.aerolinea_id = ae.id_aerolinea
                            ORDER BY a.id_avion ASC");
?>
<h2>Gestión de Aviones</h2>
<form method="POST">
    <input type="text" name="codigo" placeholder="Código del avión" required>
    <select name="modelo" required>
        <option value="">Modelo...</option>
        <?php $modelos->data_seek(0); while ($m = $modelos->fetch_assoc()): ?>
        <option value="<?= $m['id_modelo_avion'] ?>"><?= $m['nombre_modelo_avion'] ?></option>
        <?php endwhile; ?>
    </select>
    <select name="aerolinea" required>
        <option value="">Aerolínea...</option>
        <?php $aerolineas->data_seek(0); while ($a = $aerolineas->fetch_assoc()): ?>
        <option value="<?= $a['id_aerolinea'] ?>"><?= $a['nombre_aerolinea'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="submit" name="agregar" value="Agregar">
</form>
<table>
<tr><th>ID</th><th>Código</th><th>Modelo</th><th>Aerolínea</th><th>Acciones</th></tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<form method="POST">
<td><?= $row['id_avion'] ?></td>
<td><input type="text" name="codigo" value="<?= htmlspecialchars($row['codigo_avion']) ?>"></td>
<td><?= htmlspecialchars($row['nombre_modelo_avion']) ?></td>
<td><?= htmlspecialchars($row['nombre_aerolinea']) ?></td>
<td>
    <input type="hidden" name="id" value="<?= $row['id_avion'] ?>">
    <input type="submit" name="editar" value="Actualizar">
    <a class="delete" href="?eliminar=<?= $row['id_avion'] ?>" onclick="return confirm('¿Eliminar este avión?')">Eliminar</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>
<?php include 'includes/footer.php'; ?>
