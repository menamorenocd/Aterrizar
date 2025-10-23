<?php
include 'header.php';
require_once __DIR__ . '../../controlador/VueloControlador.php';

// ===============================
// Conexión base de datos
// ===============================
$conexion = new mysqli("localhost", "root", "", "sistema_vuelos");
$conexion->set_charset("utf8mb4");

// ===============================
// Obtener ciudades para los select
// ===============================
$ciudades = $conexion->query("SELECT * FROM tb_ciudad ORDER BY nombre_ciudad ASC");

// ===============================
// Variables del formulario
// ===============================
$tipo_vuelo = $_GET['tipo_vuelo'] ?? 'ida';
$origen = $_GET['origen'] ?? '';
$destino = $_GET['destino'] ?? '';
$fecha_ida = $_GET['fecha_ida'] ?? '';
$fecha_regreso = $_GET['fecha_regreso'] ?? '';

$resultados_ida = [];
$resultados_regreso = [];

// ===============================
// Búsqueda de vuelos
// ===============================
if ($origen && $destino && $fecha_ida) {
  // ---- Vuelos de ida ----
  $rangoInicio = date('Y-m-d', strtotime($fecha_ida . ' -2 days'));
  $rangoFin = date('Y-m-d', strtotime($fecha_ida . ' +2 days'));

  $stmt = $conexion->prepare("
        SELECT v.*, c1.nombre_ciudad AS origen, c2.nombre_ciudad AS destino,
               m.nombre_modelo_avion,
               (SELECT COUNT(*) FROM tb_asiento_vuelo a WHERE a.vuelo_id=v.id_vuelo AND a.estado='Disponible') AS disponibles
        FROM tb_vuelo v
        INNER JOIN tb_ciudad c1 ON v.ciudad_origen_id=c1.id_ciudad
        INNER JOIN tb_ciudad c2 ON v.ciudad_destino_id=c2.id_ciudad
        INNER JOIN tb_avion av ON v.avion_id=av.id_avion
        INNER JOIN tb_modelo_avion m ON av.modelo_id=m.id_modelo_avion
        WHERE v.ciudad_origen_id=? AND v.ciudad_destino_id=? 
          AND v.fecha_salida BETWEEN ? AND ?
        ORDER BY v.fecha_salida ASC
    ");
  $stmt->bind_param("iiss", $origen, $destino, $rangoInicio, $rangoFin);
  $stmt->execute();
  $resultados_ida = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  $stmt->close();

  // ---- Vuelos de regreso ----
  if ($tipo_vuelo === 'ida_y_regreso' && $fecha_regreso) {
    $rangoInicioReg = date('Y-m-d', strtotime($fecha_regreso . ' -2 days'));
    $rangoFinReg = date('Y-m-d', strtotime($fecha_regreso . ' +2 days'));
    $stmt2 = $conexion->prepare("
            SELECT v.*, c1.nombre_ciudad AS origen, c2.nombre_ciudad AS destino,
                   m.nombre_modelo_avion,
                   (SELECT COUNT(*) FROM tb_asiento_vuelo a WHERE a.vuelo_id=v.id_vuelo AND a.estado='Disponible') AS disponibles
            FROM tb_vuelo v
            INNER JOIN tb_ciudad c1 ON v.ciudad_origen_id=c1.id_ciudad
            INNER JOIN tb_ciudad c2 ON v.ciudad_destino_id=c2.id_ciudad
            INNER JOIN tb_avion av ON v.avion_id=av.id_avion
            INNER JOIN tb_modelo_avion m ON av.modelo_id=m.id_modelo_avion
            WHERE v.ciudad_origen_id=? AND v.ciudad_destino_id=? 
              AND v.fecha_salida BETWEEN ? AND ?
            ORDER BY v.fecha_salida ASC
        ");
    $stmt2->bind_param("iiss", $destino, $origen, $rangoInicioReg, $rangoFinReg);
    $stmt2->execute();
    $resultados_regreso = $stmt2->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt2->close();
  }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Buscar Vuelos | Aterriza</title>
  <style>
    body {
      font-family: 'Segoe UI', Arial, sans-serif;
      background: #f8fafc;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 950px;
      margin: 50px auto;
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #004aad;
      margin-bottom: 10px;
    }

    p {
      text-align: center;
      color: #555;
    }

    form {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 12px;
      margin: 25px 0;
    }

    select,
    input[type="date"] {
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 8px;
      width: 180px;
      font-size: 15px;
    }

    button {
      background: #007bff;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-size: 15px;
    }

    button:hover {
      background: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
      background: #fff;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    th,
    td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid #eee;
    }

    th {
      background: #007bff;
      color: white;
    }

    tr:hover {
      background: #f9f9f9;
    }

    fieldset {
      border: none;
      text-align: center;
      margin-top: 10px;
    }

    .compra-multiple {
      text-align: center;
      margin-top: 25px;
    }

    .btn-compra {
      background: #28a745;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      text-decoration: none;
      font-size: 16px;
      cursor: pointer;
    }

    .btn-compra:hover {
      background: #218838;
    }

    a.btn-ver {
      background: #17a2b8;
      color: white;
      padding: 8px 14px;
      border-radius: 6px;
      text-decoration: none;
    }

    a.btn-ver:hover {
      background: #138496;
    }
  </style>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const tipoVuelo = document.querySelectorAll('input[name="tipo_vuelo"]');
      const fechaIda = document.querySelector('input[name="fecha_ida"]');
      const fechaRegreso = document.querySelector('input[name="fecha_regreso"]');
      const hoy = new Date().toISOString().split("T")[0];
      fechaIda.min = hoy;
      fechaRegreso.min = hoy;

      tipoVuelo.forEach(el => {
        el.addEventListener('change', () => {
          if (el.value === 'ida_y_regreso') {
            fechaRegreso.disabled = false;
          } else {
            fechaRegreso.disabled = true;
            fechaRegreso.value = '';
          }
        });
      });

      fechaIda.addEventListener('change', () => {
        fechaRegreso.min = fechaIda.value;
      });
    });
  </script>
</head>

<body>
  <div class="container">
    <h2>✈️ Sistema de vuelos Aterriza</h2>
    <p>Busca vuelos de ida o ida y regreso, selecciona tus asientos y compra fácilmente.</p>

    <form method="GET" action="">
      <fieldset>
        <label><input type="radio" name="tipo_vuelo" value="ida" <?= $tipo_vuelo === 'ida' ? 'checked' : '' ?>> Solo ida</label>
        <label><input type="radio" name="tipo_vuelo" value="ida_y_regreso" <?= $tipo_vuelo === 'ida_y_regreso' ? 'checked' : '' ?>> Ida y regreso</label>
      </fieldset>

      <select name="origen" required>
        <option value="">Origen</option>
        <?php
        $ciudades->data_seek(0);
        while ($c = $ciudades->fetch_assoc()): ?>
          <option value="<?= $c['id_ciudad'] ?>" <?= ($origen == $c['id_ciudad']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['nombre_ciudad']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <?php $ciudades2 = $conexion->query("SELECT * FROM tb_ciudad ORDER BY nombre_ciudad ASC"); ?>
      <select name="destino" required>
        <option value="">Destino</option>
        <?php while ($c = $ciudades2->fetch_assoc()): ?>
          <option value="<?= $c['id_ciudad'] ?>" <?= ($destino == $c['id_ciudad']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['nombre_ciudad']) ?>
          </option>
        <?php endwhile; ?>
      </select>

      <input type="date" name="fecha_ida" value="<?= htmlspecialchars($fecha_ida) ?>" required>
      <input type="date" name="fecha_regreso" value="<?= htmlspecialchars($fecha_regreso) ?>" <?= $tipo_vuelo === 'ida_y_regreso' ? '' : 'disabled' ?>>
      <button type="submit">Buscar vuelos</button>
    </form>

    <?php if ($origen && $destino && $fecha_ida): ?>
      <form method="GET" action="compra_vuelo.php" id="form-compra">
        <h3>✈️ Resultados de vuelo de ida</h3>
        <?php if (empty($resultados_ida)): ?>
          <p style="text-align:center;color:#777;">No se encontraron vuelos de ida.</p>
        <?php else: ?>
          <table>
            <thead>
              <tr>
                <th></th>
                <th>Código</th>
                <th>Origen</th>
                <th>Destino</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Modelo</th>
                <th>Disponibles</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($resultados_ida as $v): ?>
                <tr>
                  <td><input type="radio" name="vuelo_ida" value="<?= $v['id_vuelo'] ?>" required></td>
                  <td><?= htmlspecialchars($v['codigo_vuelo']) ?></td>
                  <td><?= htmlspecialchars($v['origen']) ?></td>
                  <td><?= htmlspecialchars($v['destino']) ?></td>
                  <td><?= htmlspecialchars($v['fecha_salida']) ?></td>
                  <td><?= htmlspecialchars($v['hora_salida']) ?></td>
                  <td><?= htmlspecialchars($v['nombre_modelo_avion']) ?></td>
                  <td><?= htmlspecialchars($v['disponibles']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>

        <?php if ($tipo_vuelo === 'ida_y_regreso'): ?>
          <h3>🔁 Resultados de vuelo de regreso</h3>
          <?php if (empty($resultados_regreso)): ?>
            <p style="text-align:center;color:#777;">No se encontraron vuelos de regreso.</p>
          <?php else: ?>
            <table>
              <thead>
                <tr>
                  <th></th>
                  <th>Código</th>
                  <th>Origen</th>
                  <th>Destino</th>
                  <th>Fecha</th>
                  <th>Hora</th>
                  <th>Modelo</th>
                  <th>Disponibles</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($resultados_regreso as $v): ?>
                  <tr>
                    <td><input type="radio" name="vuelo_regreso" value="<?= $v['id_vuelo'] ?>"></td>
                    <td><?= htmlspecialchars($v['codigo_vuelo']) ?></td>
                    <td><?= htmlspecialchars($v['origen']) ?></td>
                    <td><?= htmlspecialchars($v['destino']) ?></td>
                    <td><?= htmlspecialchars($v['fecha_salida']) ?></td>
                    <td><?= htmlspecialchars($v['hora_salida']) ?></td>
                    <td><?= htmlspecialchars($v['nombre_modelo_avion']) ?></td>
                    <td><?= htmlspecialchars($v['disponibles']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          <?php endif; ?>
        <?php endif; ?>

        <div class="compra-multiple">
          <button class="btn-compra" type="submit">Comprar vuelo<?= ($tipo_vuelo === 'ida_y_regreso') ? 's' : '' ?></button>
          <a href="lista_vuelos.php" class="btn-ver">Ver más vuelos</a>
        </div>
      </form>
    <?php endif; ?>
  </div>

  <?php include 'footer.php'; ?>
</body>

</html>