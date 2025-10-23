<?php
include 'includes/header.php';
include 'includes/conexion_admin.php';

// Obtener listas de aviones y ciudades
$aviones = $conexion->query("SELECT id_avion, codigo_avion FROM tb_avion");
$ciudades = $conexion->query("SELECT id_ciudad, nombre_ciudad FROM tb_ciudad");

// Crear vuelo
if (isset($_POST['agregar'])) {
    $codigo = trim($_POST['codigo']);
    $avion = intval($_POST['avion']);
    $origen = intval($_POST['origen']);
    $destino = intval($_POST['destino']);
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $precio = floatval($_POST['precio']);

    if ($codigo && $avion && $origen && $destino && $fecha && $hora && $precio) {
        $stmt = $conexion->prepare("INSERT INTO tb_vuelo (codigo_vuelo, avion_id, ciudad_origen_id, ciudad_destino_id, fecha_salida, hora_salida, precio_base)
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("siiissd", $codigo, $avion, $origen, $destino, $fecha, $hora, $precio);
        $stmt->execute();
    }
}

// Eliminar vuelo
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_vuelo WHERE id_vuelo = $id");
}

// Editar vuelo
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $codigo = trim($_POST['codigo']);
    $fecha = $_POST['fecha'];
    $hora = $_POST['hora'];
    $precio = floatval($_POST['precio']);

    $stmt = $conexion->prepare("UPDATE tb_vuelo SET codigo_vuelo=?, fecha_salida=?, hora_salida=?, precio_base=? WHERE id_vuelo=?");
    $stmt->bind_param("sssdi", $codigo, $fecha, $hora, $precio, $id);
    $stmt->execute();
}

// Obtener lista de vuelos
$result = $conexion->query("
SELECT v.id_vuelo, v.codigo_vuelo, v.fecha_salida, v.hora_salida, v.precio_base,
       c1.nombre_ciudad AS origen, c2.nombre_ciudad AS destino, a.codigo_avion
FROM tb_vuelo v
JOIN tb_ciudad c1 ON v.ciudad_origen_id = c1.id_ciudad
JOIN tb_ciudad c2 ON v.ciudad_destino_id = c2.id_ciudad
JOIN tb_avion a ON v.avion_id = a.id_avion
ORDER BY v.id_vuelo ASC
");
?>

<h2>Gestión de Vuelos</h2>

<form method="POST">
    <input type="text" name="codigo" placeholder="Código de vuelo (ej: FL004)" required>
    <select name="avion" required>
        <option value="">Seleccione avión...</option>
        <?php while ($a = $aviones->fetch_assoc()): ?>
        <option value="<?= $a['id_avion'] ?>"><?= htmlspecialchars($a['codigo_avion']) ?></option>
        <?php endwhile; ?>
    </select>
    <select name="origen" required>
        <option value="">Ciudad origen...</option>
        <?php $ciudades->data_seek(0); while ($c = $ciudades->fetch_assoc()): ?>
        <option value="<?= $c['id_ciudad'] ?>"><?= htmlspecialchars($c['nombre_ciudad']) ?></option>
        <?php endwhile; ?>
    </select>
    <select name="destino" required>
        <option value="">Ciudad destino...</option>
        <?php $ciudades->data_seek(0); while ($c = $ciudades->fetch_assoc()): ?>
        <option value="<?= $c['id_ciudad'] ?>"><?= htmlspecialchars($c['nombre_ciudad']) ?></option>
        <?php endwhile; ?>
    </select>
    <input type="date" name="fecha" required>
    <input type="time" name="hora" required>
    <input type="number" name="precio" step="0.01" placeholder="Precio base" required>
    <input type="submit" name="agregar" value="Agregar">
</form>

<table>
<tr>
<th>ID</th><th>Código</th><th>Avión</th><th>Origen</th><th>Destino</th>
<th>Fecha</th><th>Hora</th><th>Precio</th><th>Acciones</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr>
<form method="POST">
<td><?= $row['id_vuelo'] ?></td>
<td><input type="text" name="codigo" value="<?= htmlspecialchars($row['codigo_vuelo']) ?>"></td>
<td><?= htmlspecialchars($row['codigo_avion']) ?></td>
<td><?= htmlspecialchars($row['origen']) ?></td>
<td><?= htmlspecialchars($row['destino']) ?></td>
<td><input type="date" name="fecha" value="<?= $row['fecha_salida'] ?>"></td>
<td><input type="time" name="hora" value="<?= $row['hora_salida'] ?>"></td>
<td><input type="number" step="0.01" name="precio" value="<?= $row['precio_base'] ?>"></td>
<td>
    <input type="hidden" name="id" value="<?= $row['id_vuelo'] ?>">
    <input type="submit" name="editar" value="Actualizar">
    <a class="delete" href="?eliminar=<?= $row['id_vuelo'] ?>" onclick="return confirm('¿Eliminar este vuelo?')">Eliminar</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>

<?php include 'includes/footer.php'; ?>
