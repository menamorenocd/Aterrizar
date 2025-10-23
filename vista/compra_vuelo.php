<?php
session_start();

// Aquí definimos la clase VueloControlador directamente
class VueloControlador {
    public function obtenerVuelo($id) {
        // Simulación de vuelos
        $vuelos = [
            1 => [
                'codigo_vuelo' => 'AV123',
                'origen' => 'Bogotá',
                'destino' => 'Medellín',
                'fecha_salida' => '2025-11-01',
                'hora_salida' => '08:00',
                'nombre_modelo_avion' => 'Boeing 737',
                'filas_modelo_avion' => 10,
                'columnas_modelo_avion' => 6
            ],
            2 => [
                'codigo_vuelo' => 'AV456',
                'origen' => 'Medellín',
                'destino' => 'Bogotá',
                'fecha_salida' => '2025-11-05',
                'hora_salida' => '17:00',
                'nombre_modelo_avion' => 'Airbus A320',
                'filas_modelo_avion' => 12,
                'columnas_modelo_avion' => 6
            ]
        ];

        return $vuelos[$id] ?? null;
    }
}

// Simulación de AsientoControlador
class AsientoControlador {
    public function obtenerAsientosPorVuelo($vueloId) {
        // Simulamos algunos asientos ocupados
        if ($vueloId == 1) {
            return [
                ['codigo_asiento' => '1A', 'estado' => 'Ocupado'],
                ['codigo_asiento' => '2B', 'estado' => 'Ocupado'],
            ];
        } elseif ($vueloId == 2) {
            return [
                ['codigo_asiento' => '3C', 'estado' => 'Ocupado'],
                ['codigo_asiento' => '4D', 'estado' => 'Ocupado'],
            ];
        }
        return [];
    }
}

// Simulación de CompraControlador
class CompraControlador {
    public function procesarCompra($usuarioId, $vueloIdaId, $asientosIda, $vueloRegresoId = null, $asientosRegreso = []) {
        // Aquí simplemente devolvemos éxito
        return [
            'error' => false,
            'mensaje' => 'Compra realizada con éxito'
        ];
    }
}

// Verificamos sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$vueloIdaId = $_GET['vuelo_ida'] ?? null;
$vueloRegresoId = $_GET['vuelo_regreso'] ?? null;

if (!$vueloIdaId) {
    die("<h3>❌ No se especificó el vuelo de ida.</h3>");
}

$usuarioId = $_SESSION['usuario']['id'];

$vueloCtrl = new VueloControlador();
$asientoCtrl = new AsientoControlador();
$compraCtrl = new CompraControlador();

// Aquí usamos el método definido arriba
$vueloIda = $vueloCtrl->obtenerVuelo($vueloIdaId);
if (!$vueloIda) {
    echo "<h3 style='color:red;text-align:center;'>❌ Vuelo no encontrado. <a href='index.php'>Volver</a></h3>";
    exit;
}
$asientosIda = $asientoCtrl->obtenerAsientosPorVuelo($vueloIdaId);

$vueloRegreso = null;
$asientosRegreso = [];
if ($vueloRegresoId) {
    $vueloRegreso = $vueloCtrl->obtenerVuelo($vueloRegresoId);
    $asientosRegreso = $asientoCtrl->obtenerAsientosPorVuelo($vueloRegresoId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asientosSeleccionadosIda = $_POST['asientos_ida'] ?? [];
    $asientosSeleccionadosRegreso = $_POST['asientos_regreso'] ?? [];

    $resultado = $compraCtrl->procesarCompra(
        $usuarioId, 
        $vueloIdaId, 
        $asientosSeleccionadosIda,
        $vueloRegresoId,
        $asientosSeleccionadosRegreso
    );

    if ($resultado['error']) {
        echo "<script>alert('{$resultado['mensaje']}');</script>";
    } else {
        $_SESSION['ultima_compra'] = [
            'vuelo_ida_id' => $vueloIdaId,
            'asientos_ida' => $asientosSeleccionadosIda,
            'vuelo_regreso_id' => $vueloRegresoId,
            'asientos_regreso' => $asientosSeleccionadosRegreso
        ];
        header("Location: pago_exitoso.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Compra de Asientos</title>
<style>
body{font-family:Arial;background:#f4f6f8;margin:20px;}
h1,h2,h3{text-align:center;}
.vuelo-section{background:white;padding:20px;margin:20px auto;max-width:800px;border-radius:10px;box-shadow:0 2px 8px rgba(0,0,0,0.1);}
.avion{display:grid;justify-content:center;gap:5px;margin:20px 0;}
.fila{display:flex;justify-content:center;gap:5px;}
.asiento{width:40px;height:40px;border-radius:8px;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:12px;}
.disponible{background:#28a745;color:white;}
.ocupado{background:#dc3545;color:white;cursor:not-allowed;}
.seleccionado{background:#ffc107;color:black;}
.btn-container{text-align:center;margin-top:20px;}
button{background:#007bff;color:white;border:none;padding:12px 30px;border-radius:8px;cursor:pointer;font-size:16px;}
button:hover{background:#0056b3;}
.leyenda{display:flex;justify-content:center;gap:20px;margin:15px 0;font-size:14px;}
.leyenda-item{display:flex;align-items:center;gap:5px;}
.leyenda-box{width:20px;height:20px;border-radius:4px;}
</style>
</head>
<body>

<h1>✈️ Compra de Asientos</h1>

<form method="POST">
    <!-- VUELO DE IDA -->
    <div class="vuelo-section">
        <h2>🛫 Vuelo de Ida</h2>
        <h3><?= htmlspecialchars($vueloIda['codigo_vuelo']) ?> - <?= htmlspecialchars($vueloIda['origen']) ?> → <?= htmlspecialchars($vueloIda['destino']) ?></h3>
        <p style="text-align:center;">
            Fecha: <?= htmlspecialchars($vueloIda['fecha_salida']) ?> | 
            Hora: <?= htmlspecialchars($vueloIda['hora_salida']) ?> | 
            Modelo: <?= htmlspecialchars($vueloIda['nombre_modelo_avion']) ?>
        </p>

        <div class="leyenda">
            <div class="leyenda-item"><div class="leyenda-box disponible"></div> Disponible</div>
            <div class="leyenda-item"><div class="leyenda-box ocupado"></div> Ocupado</div>
            <div class="leyenda-item"><div class="leyenda-box seleccionado"></div> Seleccionado</div>
        </div>

        <div class="avion" id="avion-ida">
        <?php
        $filas = $vueloIda['filas_modelo_avion'];
        $columnas = $vueloIda['columnas_modelo_avion'];
        $letras = range('A', chr(64 + $columnas));

        for ($f = 1; $f <= $filas; $f++):
            echo "<div class='fila'>";
            foreach ($letras as $l):
                $codigo = $f . $l;
                $estado = 'Disponible';
                foreach ($asientosIda as $a) {
                    if ($a['codigo_asiento'] === $codigo) {
                        $estado = $a['estado'];
                        break;
                    }
                }
                $class = ($estado === 'Disponible') ? 'disponible' : 'ocupado';
                $disabled = ($estado !== 'Disponible') ? 'disabled' : '';
                echo "<label class='asiento $class' data-grupo='ida'>
                        <input type='checkbox' name='asientos_ida[]' value='$codigo' style='display:none;' $disabled>
                        $codigo
                      </label>";
            endforeach;
            echo "</div>";
        endfor;
        ?>
        </div>
    </div>

    <!-- VUELO DE REGRESO (si existe) -->
    <?php if ($vueloRegreso): ?>
    <div class="vuelo-section">
        <h2>🛬 Vuelo de Regreso</h2>
        <h3><?= htmlspecialchars($vueloRegreso['codigo_vuelo']) ?> - <?= htmlspecialchars($vueloRegreso['origen']) ?> → <?= htmlspecialchars($vueloRegreso['destino']) ?></h3>
        <p style="text-align:center;">
            Fecha: <?= htmlspecialchars($vueloRegreso['fecha_salida']) ?> | 
            Hora: <?= htmlspecialchars($vueloRegreso['hora_salida']) ?> | 
            Modelo: <?= htmlspecialchars($vueloRegreso['nombre_modelo_avion']) ?>
        </p>

        <div class="leyenda">
            <div class="leyenda-item"><div class="leyenda-box disponible"></div> Disponible</div>
            <div class="leyenda-item"><div class="leyenda-box ocupado"></div> Ocupado</div>
            <div class="leyenda-item"><div class="leyenda-box seleccionado"></div> Seleccionado</div>
        </div>

        <div class="avion" id="avion-regreso">
        <?php
        $filasReg = $vueloRegreso['filas_modelo_avion'];
        $columnasReg = $vueloRegreso['columnas_modelo_avion'];
        $letrasReg = range('A', chr(64 + $columnasReg));

        for ($f = 1; $f <= $filasReg; $f++):
            echo "<div class='fila'>";
            foreach ($letrasReg as $l):
                $codigo = $f . $l;
                $estado = 'Disponible';
                foreach ($asientosRegreso as $a) {
                    if ($a['codigo_asiento'] === $codigo) {
                        $estado = $a['estado'];
                        break;
                    }
                }
                $class = ($estado === 'Disponible') ? 'disponible' : 'ocupado';
                $disabled = ($estado !== 'Disponible') ? 'disabled' : '';
                echo "<label class='asiento $class' data-grupo='regreso'>
                        <input type='checkbox' name='asientos_regreso[]' value='$codigo' style='display:none;' $disabled>
                        $codigo
                      </label>";
            endforeach;
            echo "</div>";
        endfor;
        ?>
        </div>
    </div>
    <?php endif; ?>

    <div class="btn-container">
        <button type="submit">💳 Comprar Asientos Seleccionados</button>
    </div>
</form>

<script>
const asientos = document.querySelectorAll('.asiento');
asientos.forEach(a => {
    a.addEventListener('click', () => {
        if(a.classList.contains('ocupado')) return;
        
        const grupo = a.dataset.grupo;
        const check = a.querySelector('input');
        
        if (check.checked) {
            check.checked = false;
            a.classList.remove('seleccionado');
        } else {
            const seleccionadosGrupo = document.querySelectorAll(`.asiento.seleccionado[data-grupo="${grupo}"]`).length;
            if (seleccionadosGrupo >= 5) {
                alert('Solo puedes seleccionar hasta 5 asientos por vuelo.');
                return;
            }
            check.checked = true;
            a.classList.add('seleccionado');
        }
    });
});
</script>

</body>
</html>
