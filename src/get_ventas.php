<?php

include '../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

// Obtén la fecha desde el parámetro de la URL
$fechaHoy = $_GET['fecha'] ?? null;

// Verificar si se proporciona la fecha en la URL
if (!$fechaHoy) {
    echo json_encode(['error' => 'No se proporcionó una fecha en la URL.']);
    exit;
}

// Calcula la fecha de ayer basada en la fecha proporcionada
$fechaAyer = date('Y-m-d', strtotime('-1 day', strtotime($fechaHoy)));

// Consulta para obtener los ingresos de hoy y ayer
$query = "
    SELECT
        COALESCE(SUM(CASE WHEN fecha_venta = '$fechaHoy' THEN total ELSE 0 END), 0) AS ingresos_hoy,
        COALESCE(SUM(CASE WHEN fecha_venta = '$fechaAyer' THEN total ELSE 0 END), 0) AS ingresos_ayer
    FROM ventas
";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

// Calcula el porcentaje de cambio
$ingresosHoy = (float)$data['ingresos_hoy'];
$ingresosAyer = (float)$data['ingresos_ayer'];
$porcentajeCambio = $ingresosAyer > 0 ? (($ingresosHoy - $ingresosAyer) / $ingresosAyer) * 100 : ($ingresosHoy > 0 ? 100 : 0);

// Devuelve los resultados como JSON
echo json_encode([
    'ingresos_hoy' => $ingresosHoy,
    'ingresos_ayer' => $ingresosAyer,
    'porcentaje_cambio' => $porcentajeCambio
]);
?>
