<?php
include 'includes/header.php';
include 'includes/conexion_admin.php';

// Obtener listas
$usuarios = $conexion->query("SELECT id_usuario, nombre_usuario FROM tb_usuario");
$vuelos = $conexion->query("SELECT id_vuelo, codigo_vuelo FROM tb_vuelo");

// Registrar nueva compra
if (isset($_POST['agregar'])) {
    $usuario = intval($_POST['usuario']);
    $vuelo = intval($_POST['vuelo']);
    $fecha = $_POST['fecha'];

    if ($usuario && $vuelo && $fecha) {
        $stmt = $conexion->prepare("INSERT INTO tb_control_compra (usuario_id, vuelo_id, fecha_compra) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $usuario, $vuelo, $fecha);
        $stmt->execute();
    }
}

// Eliminar compra
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_control_compra WHERE id_compra = $id");
}

// Listar compras
$result = $conexion->query("
SELECT c.id_compra, u.nombre_usuario, v.codigo_vuelo, c.fecha_compra
FROM tb_control_compra c
JOIN tb_usuario u ON c.usuario_id = u.id_usuario
JOIN tb_vuelo v ON c.vuelo_id = v.id_vuelo
ORDER BY c.id_compra ASC
");
?>

<h2>Gestión de Compras</h2>

<form method="POST" style="margin-bottom:20px;">
    <select name="usuario" required>
        <option value="">Seleccione usuario...</option>
        <?php while ($u = $usuarios->fetch_assoc()): ?>
        <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre_usuario']) ?></option>
        <?php endwhile; ?>
    </select>

    <select name="vuelo" required>
        <option value="">Seleccione vuelo...</option>
        <?php while ($v = $vuelos->fetch_assoc()): ?>
        <option value="<?= $v['id_vuelo'] ?>">Vuelo <?= htmlspecialchars($v['codigo_vuelo']) ?></option>
        <?php endwhile; ?>
    </select>

    <input type="datetime-local" name="fecha" required>
    <input type="submit" name="agregar" value="Registrar Compra">
</form>

<table>
<tr><th>ID</th><th>Usuario</th><th>Vuelo</th><th>Fecha Compra</th><th>Acciones</th></tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<td><?= $row['id_compra'] ?></td>
<td><?= htmlspecialchars($row['nombre_usuario']) ?></td>
<td><?= htmlspecialchars($row['codigo_vuelo']) ?></td>
<td><?= htmlspecialchars($row['fecha_compra']) ?></td>
<td>
    <a class="delete" href="?eliminar=<?= $row['id_compra'] ?>" onclick="return confirm('¿Eliminar esta compra?')">Eliminar</a>
</td>
</tr>
<?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
