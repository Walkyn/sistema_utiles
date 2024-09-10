<?php
include '../conexion.php';

$fechaHoy = date('Y-m-d');
$inicioUltimos10Dias = date('Y-m-d', strtotime('-10 days', strtotime($fechaHoy)));

// Consulta para obtener las ventas de los últimos 10 días
$query = "
    SELECT DATE(fecha_venta) as fecha, COALESCE(SUM(total), 0) as total
    FROM ventas
    WHERE fecha_venta BETWEEN '$inicioUltimos10Dias' AND '$fechaHoy'
    GROUP BY DATE(fecha_venta)
    HAVING total > 0
    ORDER BY DATE(fecha_venta) ASC
";
$result = mysqli_query($conn, $query);

$fechas = [];
$totales = [];

// Ajusta los datos con los resultados de la consulta
while ($row = mysqli_fetch_assoc($result)) {
    $fechas[] = date('d-m', strtotime($row['fecha']));
    $totales[] = (float)$row['total'];
}

// No es necesario generar fechas vacías aquí

echo json_encode([
    'fechas' => $fechas,
    'totales' => $totales
]);
?>
