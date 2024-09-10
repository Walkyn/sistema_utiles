<?php
include '../conexion.php';

$fechaHoy = date('Y-m-d');
$inicioUltimos12Meses = date('Y-m-01', strtotime('-12 months', strtotime($fechaHoy)));

// Consulta para obtener las ventas de los últimos 12 meses
$query = "
    SELECT DATE_FORMAT(fecha_venta, '%Y-%m') as mes, COALESCE(SUM(total), 0) as total
    FROM ventas
    WHERE fecha_venta BETWEEN '$inicioUltimos12Meses' AND '$fechaHoy'
    GROUP BY DATE_FORMAT(fecha_venta, '%Y-%m')
    ORDER BY DATE_FORMAT(fecha_venta, '%Y-%m')
";

$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error en la consulta: ' . mysqli_error($conn));
}

$meses = [];
$totales = [];

// Inicializa los meses y totales con ceros
for ($i = 0; $i < 12; $i++) {
    $mesActual = date('Y-m', strtotime("-$i month", strtotime($fechaHoy)));
    $meses[] = date('M Y', strtotime($mesActual));
    $totales[] = 0;
}

// Ajusta los datos con los resultados de la consulta
while ($row = mysqli_fetch_assoc($result)) {
    $index = array_search(date('M Y', strtotime($row['mes'] . '-01')), $meses);
    if ($index !== false) {
        $totales[$index] = (float)$row['total'];
    }
}

// Devuelve los datos en formato JSON
echo json_encode([
    'meses' => array_reverse($meses),
    'totales' => array_reverse($totales)
]);
?>
