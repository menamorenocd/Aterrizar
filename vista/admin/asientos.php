<?php
include 'includes/header.php';
include 'includes/conexion_admin.php';

// Obtener listas
$vuelos = $conexion->query("SELECT id_vuelo, codigo_vuelo FROM tb_vuelo");
$usuarios = $conexion->query("SELECT id_usuario, nombre_usuario FROM tb_usuario");

// Crear asiento
if (isset($_POST['agregar'])) {
    $vuelo = intval($_POST['vuelo']);
    $fila = intval($_POST['fila']);
    $letra = strtoupper(trim($_POST['letra']));
    $codigo = trim($_POST['codigo']);
    $clase = $_POST['clase'];
    $estado = $_POST['estado'];
    $usuario = !empty($_POST['usuario']) ? intval($_POST['usuario']) : null;

    $stmt = $conexion->prepare("INSERT INTO tb_asiento_vuelo (vuelo_id, fila, letra, codigo_asiento, clase, estado, usuario_id, fecha_reserva)
                                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iissssi", $vuelo, $fila, $letra, $codigo, $clase, $estado, $usuario);
    $stmt->execute();
}

// Eliminar asiento
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_asiento_vuelo WHERE id_asiento = $id");
}

// Editar asiento
if (isset($_POST['editar'])) {
    $id = intval($_POST['id']);
    $fila = intval($_POST['fila']);
    $letra = strtoupper(trim($_POST['letra']));
    $codigo = trim($_POST['codigo']);
    $clase = $_POST['clase'];
    $estado = $_POST['estado'];
    $usuario = !empty($_POST['usuario']) ? intval($_POST['usuario']) : null;

    $stmt = $conexion->prepare("UPDATE tb_asiento_vuelo 
                                SET fila=?, letra=?, codigo_asiento=?, clase=?, estado=?, usuario_id=? 
                                WHERE id_asiento=?");
    $stmt->bind_param("issssii", $fila, $letra, $codigo, $clase, $estado, $usuario, $id);
    $stmt->execute();
}

// Obtener lista de asientos con vuelos
$result = $conexion->query("
SELECT a.id_asiento, a.fila, a.letra, a.codigo_asiento, a.clase, a.estado, a.fecha_reserva, 
       v.id_vuelo, v.codigo_vuelo, u.nombre_usuario
FROM tb_asiento_vuelo a
JOIN tb_vuelo v ON a.vuelo_id = v.id_vuelo
LEFT JOIN tb_usuario u ON a.usuario_id = u.id_usuario
ORDER BY v.codigo_vuelo ASC, a.fila ASC, a.letra ASC
");
?>

<h2>Gestión de Asientos</h2>

<!-- Formulario para agregar asiento -->
<form method="POST" style="margin-bottom:20px;">
    <select name="vuelo" required>
        <option value="">Seleccione vuelo...</option>
        <?php while ($v = $vuelos->fetch_assoc()): ?>
        <option value="<?= $v['id_vuelo'] ?>">Vuelo <?= htmlspecialchars($v['codigo_vuelo']) ?></option>
        <?php endwhile; ?>
    </select>
    <input type="number" name="fila" placeholder="Fila" required>
    <input type="text" name="letra" placeholder="Letra" maxlength="1" required>
    <input type="text" name="codigo" placeholder="Código (ej: 12A)" required>
    <select name="clase" required>
        <option value="Económica">Económica</option>
        <option value="Ejecutiva">Ejecutiva</option>
        <option value="Primera">Primera</option>
    </select>
    <select name="estado" required>
        <option value="Disponible">Disponible</option>
        <option value="Pagado">Pagado</option>
    </select>
    <select name="usuario">
        <option value="">Sin usuario</option>
        <?php while ($u = $usuarios->fetch_assoc()): ?>
        <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nombre_usuario']) ?></option>
        <?php endwhile; ?>
    </select>
    <input type="submit" name="agregar" value="Agregar">
</form>

<!-- FILTRO POR VUELO -->
<div style="background:#f8f9fa; padding:15px; border-radius:10px; margin-bottom:15px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">
    <h3 style="margin-top:0;">✈️ Filtrar asientos por vuelo</h3>
    <select id="filtroVuelo" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:8px; font-size:14px;">
        <option value="">Mostrar todos los vuelos</option>
        <?php
        // Reiniciar el puntero y listar vuelos en el filtro
        $vuelos->data_seek(0);
        while ($v = $vuelos->fetch_assoc()):
        ?>
        <option value="<?= $v['codigo_vuelo'] ?>">Vuelo <?= htmlspecialchars($v['codigo_vuelo']) ?></option>
        <?php endwhile; ?>
    </select>
</div>

<!-- TABLA DE ASIENTOS -->
<table id="tablaAsientos">
<tr>
<th>ID</th><th>Vuelo</th><th>Fila</th><th>Letra</th><th>Código</th><th>Clase</th>
<th>Estado</th><th>Usuario</th><th>Fecha Reserva</th><th>Acciones</th>
</tr>

<?php while ($row = $result->fetch_assoc()): ?>
<tr data-vuelo="<?= htmlspecialchars($row['codigo_vuelo']) ?>">
<form method="POST">
<td><?= $row['id_asiento'] ?></td>
<td><?= htmlspecialchars($row['codigo_vuelo']) ?></td>
<td><input type="number" name="fila" value="<?= $row['fila'] ?>"></td>
<td><input type="text" name="letra" value="<?= htmlspecialchars($row['letra']) ?>" maxlength="1"></td>
<td><input type="text" name="codigo" value="<?= htmlspecialchars($row['codigo_asiento']) ?>"></td>
<td><input type="text" name="clase" value="<?= htmlspecialchars($row['clase']) ?>"></td>
<td>
    <select name="estado">
        <option <?= $row['estado']=="Disponible"?"selected":"" ?>>Disponible</option>
        <option <?= $row['estado']=="Pagado"?"selected":"" ?>>Pagado</option>
    </select>
</td>
<td><?= htmlspecialchars($row['nombre_usuario'] ?? '—') ?></td>
<td><?= $row['fecha_reserva'] ?? '—' ?></td>
<td>
    <input type="hidden" name="id" value="<?= $row['id_asiento'] ?>">
    <input type="submit" name="editar" value="Actualizar">
    <a class="delete" href="?eliminar=<?= $row['id_asiento'] ?>" onclick="return confirm('¿Eliminar este asiento?')">Eliminar</a>
</td>
</form>
</tr>
<?php endwhile; ?>
</table>

<script>
// FILTRO PROFESIONAL POR VUELO
const filtroVuelo = document.getElementById("filtroVuelo");
const filas = document.querySelectorAll("#tablaAsientos tr[data-vuelo]");

filtroVuelo.addEventListener("change", () => {
    const valor = filtroVuelo.value.trim();
    filas.forEach(fila => {
        fila.style.display = (!valor || fila.dataset.vuelo === valor) ? "" : "none";
    });
});
</script>

<?php include 'includes/footer.php'; ?>
