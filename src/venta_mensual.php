<?php

include '../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

// Obtén la fecha desde el parámetro de la URL, o usa la fecha actual por defecto
$fechaHoy = $_GET['fecha'] ?? date('Y-m-d');

if (!DateTime::createFromFormat('Y-m-d', $fechaHoy)) {
    echo json_encode(['error' => 'Fecha proporcionada no es válida.']);
    exit;
}

// Calcula el inicio del mes actual y el mes pasado usando la fecha proporcionada
$inicioMesActual = date('Y-m-01', strtotime($fechaHoy));
$inicioMesPasado = date('Y-m-01', strtotime('first day of previous month', strtotime($fechaHoy)));
$finMesPasado = date('Y-m-t', strtotime('last day of previous month', strtotime($fechaHoy)));

// Consulta para obtener las ventas del mes actual y del mes pasado
$query = "
    SELECT
        COALESCE(SUM(CASE WHEN fecha_venta >= '$inicioMesActual' AND fecha_venta <= '$fechaHoy' THEN total ELSE 0 END), 0) AS ventas_mes_actual,
        COALESCE(SUM(CASE WHEN fecha_venta >= '$inicioMesPasado' AND fecha_venta <= '$finMesPasado' THEN total ELSE 0 END), 0) AS ventas_mes_pasado
    FROM ventas
";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Calcula el porcentaje de cambio
$ventasMesActual = (float)$data['ventas_mes_actual'];
$ventasMesPasado = (float)$data['ventas_mes_pasado'];
$porcentajeCambio = $ventasMesPasado > 0
    ? (($ventasMesActual - $ventasMesPasado) / $ventasMesPasado) * 100
    : ($ventasMesActual > 0 ? 100 : 0);

// Devuelve los resultados como JSON
echo json_encode([
    'ventas_mes_actual' => $ventasMesActual,
    'ventas_mes_pasado' => $ventasMesPasado,
    'porcentaje_cambio' => $porcentajeCambio
]);
?>
