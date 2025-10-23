<?php include '../../includes/conexion_super.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Buscar Vuelos | Sistema de Vuelos Cris</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="estilos.css">
</head>
<body>

<div class="buscador-contenedor">
  <div class="buscador-caja">
    <h2 class="text-primary fw-bold text-center mb-3">✈️ Encuentra tu vuelo ideal</h2>
    <p class="text-muted text-center mb-4">Selecciona tu origen, destino y tipo de viaje</p>

    <form id="formBusqueda" method="GET" action="buscar_vuelos.php" class="row g-3 justify-content-center">
      <div class="col-md-3">
        <label class="form-label">Desde</label>
        <select name="origen" class="form-select" required>
          <option value="">Selecciona ciudad</option>
          <?php
          $ciudades = $conexion->query("SELECT * FROM tb_ciudad ORDER BY nombre_ciudad");
          while($c = $ciudades->fetch_assoc()){
              echo "<option value='{$c['id_ciudad']}'>{$c['nombre_ciudad']}</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Hacia</label>
        <select name="destino" class="form-select" required>
          <option value="">Selecciona ciudad</option>
          <?php
          $ciudades->data_seek(0);
          while($c = $ciudades->fetch_assoc()){
              echo "<option value='{$c['id_ciudad']}'>{$c['nombre_ciudad']}</option>";
          }
          ?>
        </select>
      </div>

      <div class="col-md-3">
        <label class="form-label">Tipo de vuelo</label>
        <select name="tipo" id="tipo" class="form-select" onchange="mostrarFechas()" required>
          <option value="">Selecciona tipo</option>
          <option value="ida">Solo ida</option>
          <option value="ida_vuelta">Ida y vuelta</option>
        </select>
      </div>

      <div class="col-md-3 fecha" id="fechaIdaDiv" style="display:none;">
        <label class="form-label">Fecha de salida</label>
        <input type="date" name="fecha_ida" class="form-control">
      </div>

      <div class="col-md-3 fecha" id="fechaVueltaDiv" style="display:none;">
        <label class="form-label">Fecha de regreso</label>
        <input type="date" name="fecha_vuelta" class="form-control">
      </div>

      <div class="col-md-12 text-center mt-3">
        <button class="btn btn-principal px-4 py-2">Buscar vuelos</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="funciones_vuelos.js"></script>
</body>
</html>
