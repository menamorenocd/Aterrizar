<?php include 'includes/header.php'; ?>
<?php include 'includes/conexion_super.php'; ?>

<?php
// --- CREAR VUELO ---
if (isset($_POST['agregar'])) {
    $codigo = trim($_POST['codigo_vuelo']);
    $avion = intval($_POST['avion_id']);
    $origen = intval($_POST['ciudad_origen_id']);
    $destino = intval($_POST['ciudad_destino_id']);
    $fecha = $_POST['fecha_salida'];
    $hora = $_POST['hora_salida'];
    $precio = floatval($_POST['precio_base']);

    $stmt = $conexion->prepare("INSERT INTO tb_vuelo (codigo_vuelo, avion_id, ciudad_origen_id, ciudad_destino_id, fecha_salida, hora_salida, precio_base) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("siiissd", $codigo, $avion, $origen, $destino, $fecha, $hora, $precio);
    $stmt->execute();

    header("Location: vuelos.php?agregado=1");
    exit;
}

// --- ELIMINAR VUELO ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_vuelo WHERE id_vuelo = $id");
    header("Location: vuelos.php?eliminado=1");
    exit;
}

// --- ACTUALIZAR VUELO ---
if (isset($_POST['editar'])) {
    $id = intval($_POST['id_vuelo']);
    $codigo = trim($_POST['codigo_vuelo']);
    $avion = intval($_POST['avion_id']);
    $origen = intval($_POST['ciudad_origen_id']);
    $destino = intval($_POST['ciudad_destino_id']);
    $fecha = $_POST['fecha_salida'];
    $hora = $_POST['hora_salida'];
    $precio = floatval($_POST['precio_base']);

    $stmt = $conexion->prepare("UPDATE tb_vuelo SET codigo_vuelo=?, avion_id=?, ciudad_origen_id=?, ciudad_destino_id=?, fecha_salida=?, hora_salida=?, precio_base=? WHERE id_vuelo=?");
    $stmt->bind_param("siiissdi", $codigo, $avion, $origen, $destino, $fecha, $hora, $precio, $id);
    $stmt->execute();

    header("Location: vuelos.php?actualizado=1");
    exit;
}

// --- OBTENER LISTAS ---
$vuelos = $conexion->query("
    SELECT v.*, a.codigo_avion, ao.nombre_aerolinea, c1.nombre_ciudad AS origen, c2.nombre_ciudad AS destino
    FROM tb_vuelo v
    JOIN tb_avion a ON v.avion_id = a.id_avion
    JOIN tb_aerolinea ao ON a.aerolinea_id = ao.id_aerolinea
    JOIN tb_ciudad c1 ON v.ciudad_origen_id = c1.id_ciudad
    JOIN tb_ciudad c2 ON v.ciudad_destino_id = c2.id_ciudad
    ORDER BY v.id_vuelo ASC
");

$aviones = $conexion->query("
    SELECT a.id_avion, CONCAT(a.codigo_avion, ' - ', ar.nombre_aerolinea) AS avion
    FROM tb_avion a
    JOIN tb_aerolinea ar ON a.aerolinea_id = ar.id_aerolinea
");

$ciudades = $conexion->query("SELECT id_ciudad, nombre_ciudad FROM tb_ciudad ORDER BY nombre_ciudad ASC");
?>

<main>
<header style="background:#fff; border-radius:10px; padding:20px 25px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
    <h1 style="margin:0; color:#2563eb;">Gestión de Vuelos</h1>
    <p style="color:#6b7280;">Administra los vuelos del sistema</p>
</header>

<section style="margin-top:30px;">
    <form method="POST" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05); display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:12px;">
        <input type="text" name="codigo_vuelo" placeholder="Código de vuelo" required>
        <select name="avion_id" required>
            <option value="">Seleccionar avión</option>
            <?php while ($a = $aviones->fetch_assoc()): ?>
                <option value="<?= $a['id_avion'] ?>"><?= htmlspecialchars($a['avion']) ?></option>
            <?php endwhile; ?>
        </select>
        <select name="ciudad_origen_id" required>
            <option value="">Ciudad origen</option>
            <?php
            $ciudades->data_seek(0);
            while ($c = $ciudades->fetch_assoc()): ?>
                <option value="<?= $c['id_ciudad'] ?>"><?= htmlspecialchars($c['nombre_ciudad']) ?></option>
            <?php endwhile; ?>
        </select>
        <select name="ciudad_destino_id" required>
            <option value="">Ciudad destino</option>
            <?php
            $ciudades->data_seek(0);
            while ($c = $ciudades->fetch_assoc()): ?>
                <option value="<?= $c['id_ciudad'] ?>"><?= htmlspecialchars($c['nombre_ciudad']) ?></option>
            <?php endwhile; ?>
        </select>
        <input type="date" name="fecha_salida" required>
        <input type="time" name="hora_salida" required>
        <input type="number" step="0.01" name="precio_base" placeholder="Precio base" required>
        <button type="submit" name="agregar" style="background:#2563eb;color:#fff;padding:10px;border:none;border-radius:6px;cursor:pointer;font-weight:600;">Agregar</button>
    </form>
</section>

<section style="margin-top:35px;">
    <div style="overflow-x:auto; background:#fff; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.05); padding:10px;">
        <table style="width:100%; border-collapse:collapse; min-width:1000px;">
            <thead style="background:#2563eb; color:#fff;">
                <tr>
                    <th style="padding:14px;">ID</th>
                    <th style="padding:14px;">Código</th>
                    <th style="padding:14px;">Avión</th>
                    <th style="padding:14px;">Origen</th>
                    <th style="padding:14px;">Destino</th>
                    <th style="padding:14px;">Fecha</th>
                    <th style="padding:14px;">Hora</th>
                    <th style="padding:14px;">Precio</th>
                    <th style="padding:14px; text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($v = $vuelos->fetch_assoc()): ?>
                <tr style="border-bottom:1px solid #f1f1f1;">
                    <form method="POST">
                        <td style="padding:10px; text-align:center;"><?= $v['id_vuelo'] ?></td>
                        <td><input type="text" name="codigo_vuelo" value="<?= htmlspecialchars($v['codigo_vuelo']) ?>" style="width:100%; border:1px solid #ddd; border-radius:5px; padding:6px;"></td>
                        <td>
                            <select name="avion_id" style="width:100%; border:1px solid #ddd; border-radius:5px; padding:6px;">
                                <?php
                                $aviones2 = $conexion->query("
                                    SELECT a.id_avion, CONCAT(a.codigo_avion, ' - ', ar.nombre_aerolinea) AS avion
                                    FROM tb_avion a
                                    JOIN tb_aerolinea ar ON a.aerolinea_id = ar.id_aerolinea
                                ");
                                while ($a = $aviones2->fetch_assoc()): ?>
                                    <option value="<?= $a['id_avion'] ?>" <?= $a['id_avion'] == $v['avion_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($a['avion']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td>
                            <select name="ciudad_origen_id" style="width:100%; border:1px solid #ddd; border-radius:5px; padding:6px;">
                                <?php
                                $ciudades2 = $conexion->query("SELECT id_ciudad, nombre_ciudad FROM tb_ciudad");
                                while ($c = $ciudades2->fetch_assoc()): ?>
                                    <option value="<?= $c['id_ciudad'] ?>" <?= $c['id_ciudad'] == $v['ciudad_origen_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_ciudad']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td>
                            <select name="ciudad_destino_id" style="width:100%; border:1px solid #ddd; border-radius:5px; padding:6px;">
                                <?php
                                $ciudades3 = $conexion->query("SELECT id_ciudad, nombre_ciudad FROM tb_ciudad");
                                while ($c = $ciudades3->fetch_assoc()): ?>
                                    <option value="<?= $c['id_ciudad'] ?>" <?= $c['id_ciudad'] == $v['ciudad_destino_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($c['nombre_ciudad']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </td>
                        <td><input type="date" name="fecha_salida" value="<?= $v['fecha_salida'] ?>" style="border:1px solid #ddd; border-radius:5px; padding:6px;"></td>
                        <td><input type="time" name="hora_salida" value="<?= $v['hora_salida'] ?>" style="border:1px solid #ddd; border-radius:5px; padding:6px;"></td>
                        <td><input type="number" step="0.01" name="precio_base" value="<?= $v['precio_base'] ?>" style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100px;"></td>
                        <td style="text-align:center;">
                            <input type="hidden" name="id_vuelo" value="<?= $v['id_vuelo'] ?>">
                            <div style="display:flex; justify-content:center; gap:6px;">
                                <button name="editar" style="background:#10b981;color:#fff;border:none;padding:7px 14px;border-radius:5px;cursor:pointer;font-size:14px;">Actualizar</button>
                                <a href="?eliminar=<?= $v['id_vuelo'] ?>" onclick="return confirm('¿Eliminar este vuelo?')" style="background:#dc2626;color:#fff;padding:7px 14px;border-radius:5px;text-decoration:none;font-size:14px;">Eliminar</a>
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

<?php if(isset($_GET['agregado']) || isset($_GET['actualizado']) || isset($_GET['eliminado'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
<?php if(isset($_GET['agregado'])): ?>
Swal.fire({ icon: 'success', title: '¡Vuelo agregado!', text: 'El vuelo se ha registrado correctamente.', confirmButtonColor: '#2563eb' });
<?php elseif(isset($_GET['actualizado'])): ?>
Swal.fire({ icon: 'success', title: '¡Actualizado!', text: 'Los datos del vuelo fueron actualizados.', confirmButtonColor: '#2563eb' });
<?php elseif(isset($_GET['eliminado'])): ?>
Swal.fire({ icon: 'success', title: '¡Eliminado!', text: 'El vuelo fue eliminado correctamente.', confirmButtonColor: '#2563eb' });
<?php endif; ?>
</script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
