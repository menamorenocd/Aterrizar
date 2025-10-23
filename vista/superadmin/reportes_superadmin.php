<?php include 'includes/header.php'; ?>
<?php include 'includes/conexion_super.php'; ?>

<?php
// --- RESÚMENES ---
$totalVuelos = $conexion->query("SELECT COUNT(*) AS total FROM tb_vuelo")->fetch_assoc()['total'];
$totalAerol = $conexion->query("SELECT COUNT(*) AS total FROM tb_aerolinea")->fetch_assoc()['total'];
$totalAviones = $conexion->query("SELECT COUNT(*) AS total FROM tb_avion")->fetch_assoc()['total'];
$totalUsuarios = $conexion->query("SELECT COUNT(*) AS total FROM tb_usuario")->fetch_assoc()['total'];

// --- DATOS PARA GRÁFICAS ---
$vuelosPorAerolinea = $conexion->query("
    SELECT ar.nombre_aerolinea AS nombre, COUNT(v.id_vuelo) AS total
    FROM tb_vuelo v
    JOIN tb_avion a ON v.avion_id = a.id_avion
    JOIN tb_aerolinea ar ON a.aerolinea_id = ar.id_aerolinea
    GROUP BY ar.nombre_aerolinea
");

$vuelosPorCiudad = $conexion->query("
    SELECT c.nombre_ciudad AS nombre, COUNT(v.id_vuelo) AS total
    FROM tb_vuelo v
    JOIN tb_ciudad c ON v.ciudad_origen_id = c.id_ciudad
    GROUP BY c.nombre_ciudad
");
?>

<main style="padding:20px;">
<header style="background:#fff; border-radius:10px; padding:15px 20px; box-shadow:0 2px 8px rgba(0,0,0,0.05); display:flex; align-items:center; justify-content:space-between;">
    <h2 style="margin:0; color:#2563eb;">Dashboard General</h2>
    <small style="color:#6b7280;">Panel de control — Super Administrador</small>
</header>

<!-- Tarjetas de resumen -->
<section style="margin-top:25px; display:grid; grid-template-columns: repeat(auto-fit,minmax(180px,1fr)); gap:15px;">
    <div style="background:#2563eb; color:white; border-radius:10px; padding:15px; text-align:center;">
        <h3 style="margin:0; font-size:28px;"><?= $totalVuelos ?></h3>
        <p style="margin:0;">Vuelos</p>
    </div>
    <div style="background:#10b981; color:white; border-radius:10px; padding:15px; text-align:center;">
        <h3 style="margin:0; font-size:28px;"><?= $totalAerol ?></h3>
        <p style="margin:0;">Aerolíneas</p>
    </div>
    <div style="background:#f59e0b; color:white; border-radius:10px; padding:15px; text-align:center;">
        <h3 style="margin:0; font-size:28px;"><?= $totalAviones ?></h3>
        <p style="margin:0;">Aviones</p>
    </div>
    <div style="background:#dc2626; color:white; border-radius:10px; padding:15px; text-align:center;">
        <h3 style="margin:0; font-size:28px;"><?= $totalUsuarios ?></h3>
        <p style="margin:0;">Usuarios</p>
    </div>
</section>

<!-- Gráficas -->
<section style="margin-top:30px; display:grid; grid-template-columns: repeat(auto-fit,minmax(320px,1fr)); gap:20px;">
    <div style="background:#fff; padding:15px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
        <h4 style="margin-bottom:10px; color:#2563eb;">Vuelos por Aerolínea</h4>
        <canvas id="chartAerolineas" height="150"></canvas>
    </div>

    <div style="background:#fff; padding:15px; border-radius:10px; box-shadow:0 2px 6px rgba(0,0,0,0.05);">
        <h4 style="margin-bottom:10px; color:#2563eb;">Vuelos por Ciudad</h4>
        <canvas id="chartCiudades" height="150"></canvas>
    </div>
</section>

<footer style="text-align:center;margin-top:30px;color:#9ca3af;font-size:14px;">
    Panel Super Admin © <?= date('Y') ?>
</footer>
</main>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const dataAerolineas = {
    labels: [<?php
        $nombresA = [];
        $totalesA = [];
        $vuelosPorAerolinea->data_seek(0);
        while($row = $vuelosPorAerolinea->fetch_assoc()){
            $nombresA[] = $row['nombre'];
            $totalesA[] = $row['total'];
        }
        echo "'".implode("','", $nombresA)."'";
    ?>],
    datasets: [{
        label: 'Vuelos',
        data: [<?= implode(",", $totalesA) ?>],
        backgroundColor: ['#2563eb','#10b981','#f59e0b','#dc2626','#8b5cf6'],
        borderRadius: 6
    }]
};

const dataCiudades = {
    labels: [<?php
        $nombresC = [];
        $totalesC = [];
        $vuelosPorCiudad->data_seek(0);
        while($row = $vuelosPorCiudad->fetch_assoc()){
            $nombresC[] = $row['nombre'];
            $totalesC[] = $row['total'];
        }
        echo "'".implode("','", $nombresC)."'";
    ?>],
    datasets: [{
        label: 'Vuelos por Ciudad',
        data: [<?= implode(",", $totalesC) ?>],
        backgroundColor: ['#2563eb','#10b981','#f59e0b','#dc2626','#8b5cf6']
    }]
};

new Chart(document.getElementById('chartAerolineas'), {
    type: 'bar',
    data: dataAerolineas,
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

new Chart(document.getElementById('chartCiudades'), {
    type: 'doughnut',
    data: dataCiudades,
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>

<?php include 'includes/footer.php'; ?>
