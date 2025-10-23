<?php
include '../../includes/conexion_super.php';
$id = intval($_GET['id']);
$tipo = $_GET['tipo'];

$q = $conexion->query("
  SELECT v.*, ar.nombre_aerolinea, c1.nombre_ciudad AS origen, c2.nombre_ciudad AS destino
  FROM tb_vuelo v
  JOIN tb_avion av ON v.avion_id = av.id_avion
  JOIN tb_aerolinea ar ON av.aerolinea_id = ar.id_aerolinea
  JOIN tb_ciudad c1 ON v.ciudad_origen_id = c1.id_ciudad
  JOIN tb_ciudad c2 ON v.ciudad_destino_id = c2.id_ciudad
  WHERE v.id_vuelo = $id
");
$d = $q->fetch_assoc();
?>
<div>
  <h5 class="text-primary"><?= $d['nombre_aerolinea'] ?></h5>
  <p><strong>Vuelo:</strong> <?= $d['codigo_vuelo'] ?><br>
     <strong>Origen:</strong> <?= $d['origen'] ?><br>
     <strong>Destino:</strong> <?= $d['destino'] ?><br>
     <strong>Fecha:</strong> <?= $d['fecha_salida'] ?><br>
     <strong>Hora:</strong> <?= $d['hora_salida'] ?><br>
     <strong>Precio:</strong> $<?= number_format($d['precio'], 0, ',', '.') ?></p>

  <?php if($tipo == 'ida_vuelta'): ?>
    <hr>
    <p class="text-muted">🛬 Este vuelo es parte de un viaje ida y vuelta. Selecciona también el vuelo de regreso en los resultados.</p>
  <?php endif; ?>

  <button class="btn btn-success w-100 mt-3">Reservar vuelo</button>
</div>
