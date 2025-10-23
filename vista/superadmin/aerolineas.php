<?php include 'includes/header.php'; ?>
<?php include 'includes/conexion_super.php'; ?>

<?php
// --- CREAR AEROLÍNEA ---
if (isset($_POST['agregar'])) {
    $nombre = trim($_POST['nombre_aerolinea']);

    $stmt = $conexion->prepare("INSERT INTO tb_aerolinea (nombre_aerolinea) VALUES (?)");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
}

// --- ELIMINAR AEROLÍNEA ---
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conexion->query("DELETE FROM tb_aerolinea WHERE id_aerolinea = $id");
}

// --- ACTUALIZAR AEROLÍNEA ---
if (isset($_POST['editar'])) {
    $id = intval($_POST['id_aerolinea']);
    $nombre = trim($_POST['nombre_aerolinea']);

    $stmt = $conexion->prepare("UPDATE tb_aerolinea SET nombre_aerolinea = ? WHERE id_aerolinea = ?");
    $stmt->bind_param("si", $nombre, $id);
    $stmt->execute();
}

// --- OBTENER LISTA ---
$result = $conexion->query("SELECT * FROM tb_aerolinea ORDER BY id_aerolinea ASC");
?>

<main>
<header style="background:#fff; border-radius:10px; padding:20px 25px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
    <h1 style="margin:0; color:#2563eb;">Gestión de Aerolíneas</h1>
    <p style="color:#6b7280;">Administra las aerolíneas del sistema</p>
</header>

<section style="margin-top:30px;">
    <form method="POST" style="background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.05); display:grid; grid-template-columns: repeat(auto-fit,minmax(250px,1fr)); gap:12px;">
        <input type="text" name="nombre_aerolinea" placeholder="Nombre de Aerolínea" required>
        <button type="submit" name="agregar" style="background:#2563eb;color:#fff;padding:10px;border:none;border-radius:6px;cursor:pointer;font-weight:600;">Agregar</button>
    </form>
</section>

<section style="margin-top:35px;">
    <div style="overflow-x:auto; background:#fff; border-radius:10px; box-shadow:0 3px 10px rgba(0,0,0,0.05); padding:10px;">
        <table style="width:100%; border-collapse:collapse; min-width:600px;">
            <thead style="background:#2563eb; color:#fff;">
                <tr>
                    <th style="padding:14px; text-align:left;">ID</th>
                    <th style="padding:14px; text-align:left;">Nombre de Aerolínea</th>
                    <th style="padding:14px; text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr style="border-bottom:1px solid #f1f1f1;">
                    <form method="POST">
                        <td style="padding:10px; text-align:center; width:10%;"><?= $row['id_aerolinea'] ?></td>
                        <td style="padding:10px; width:60%;">
                            <input type="text" name="nombre_aerolinea" value="<?= htmlspecialchars($row['nombre_aerolinea']) ?>" required 
                                style="border:1px solid #ddd; border-radius:5px; padding:6px; width:100%;">
                        </td>
                        <td style="padding:10px; text-align:center; width:30%;">
                            <input type="hidden" name="id_aerolinea" value="<?= $row['id_aerolinea'] ?>">
                            <div style="display:flex; justify-content:center; gap:6px;">
                                <button name="editar" 
                                    style="background:#10b981;color:#fff;border:none;padding:7px 14px;border-radius:5px;cursor:pointer;font-size:14px;">
                                    Actualizar
                                </button>
                                <a href="?eliminar=<?= $row['id_aerolinea'] ?>" 
                                   onclick="return confirm('¿Eliminar esta aerolínea?')" 
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
