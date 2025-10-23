<?php include '../../includes/conexion_super.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resultados de vuelos</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="estilos.css">
</head>
<body style="background:#f8fafc;">

<div class="container py-5">
  <h3 class="text-primary fw-bold text-center mb-4">✈️ Resultados de vuelos disponibles</h3>

  <?php
  $origen = intval($_GET['origen']);
  $destino = intval($_GET['destino']);
  $tipo = $_GET['tipo'];
  $fecha_ida = $_GET['fecha_ida'] ?? '';
  $fecha_vuelta = $_GET['fecha_vuelta'] ?? '';

  $sql = "
    SELECT v.*, ar.nombre_aerolinea, c1.nombre_ciudad AS origen, c2.nombre_ciudad AS destino
    FROM tb_vuelo v
    JOIN tb_avion av ON v.avion_id = av.id_avion
    JOIN tb_aerolinea ar ON av.aerolinea_id = ar.id_aerolinea
    JOIN tb_ciudad c1 ON v.ciudad_origen_id = c1.id_ciudad
    JOIN tb_ciudad c2 ON v.ciudad_destino_id = c2.id_ciudad
    WHERE v.ciudad_origen_id = $origen AND v.ciudad_destino_id = $destino
  ";
  $vuelos = $conexion->query($sql);

  if ($vuelos->num_rows > 0):
  ?>
  <div class="row g-4">
    <?php while($v = $vuelos->fetch_assoc()): ?>
    <div class="col-md-4">
      <div class="card card-vuelo">
        <div class="card-body">
          <h5 class="card-title text-primary"><?= $v['nombre_aerolinea'] ?></h5>
          <p class="card-text">
            <strong>Origen:</strong> <?= $v['origen'] ?><br>
            <strong>Destino:</strong> <?= $v['destino'] ?><br>
            <strong>Fecha:</strong> <?= $v['fecha_salida'] ?><br>
            <strong>Hora:</strong> <?= $v['hora_salida'] ?><br>
            <strong>Precio:</strong> $<?= number_format($v['precio'], 0, ',', '.') ?>
          </p>
          <button class="btn btn-principal w-100" onclick="verDetalles(<?= $v['id_vuelo'] ?>, '<?= $tipo ?>')">
            Ver detalles
          </button>
        </div>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
  <?php else: ?>
  <div class="alert alert-warning text-center mt-5">
    No se encontraron vuelos con los criterios seleccionados.
  </div>
  <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="modalDetalle" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Detalles del vuelo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="contenidoModal">Cargando...</div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="funciones_vuelos.js"></script>
</body>
</html>
