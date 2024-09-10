<?php

include '../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

// Obtén la fecha desde el parámetro de la URL o usa la fecha actual por defecto
$fechaHoy = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

if (!DateTime::createFromFormat('Y-m-d', $fechaHoy)) {
    echo json_encode(['error' => 'Fecha proporcionada no es válida.']);
    exit;
}

// Calcula las fechas de inicio de la semana actual y la semana pasada
$inicioSemanaHoy = date('Y-m-d', strtotime('monday this week', strtotime($fechaHoy)));
$inicioSemanaAnterior = date('Y-m-d', strtotime('monday last week', strtotime($fechaHoy)));

// Define la consulta SQL para obtener las ventas de la semana actual y la semana pasada
$query = "
    SELECT
        COALESCE(SUM(CASE WHEN fecha_venta >= '$inicioSemanaHoy' AND fecha_venta <= '$fechaHoy' THEN total ELSE 0 END), 0) AS ventas_esta_semana,
        COALESCE(SUM(CASE WHEN fecha_venta >= '$inicioSemanaAnterior' AND fecha_venta < '$inicioSemanaHoy' THEN total ELSE 0 END), 0) AS ventas_semana_pasada
    FROM ventas
";

// Ejecuta la consulta
$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(['error' => 'Error en la consulta SQL.']);
    exit;
}

// Obtén los resultados
$data = mysqli_fetch_assoc($result);
$ventasEstaSemana = (float) $data['ventas_esta_semana'];
$ventasSemanaPasada = (float) $data['ventas_semana_pasada'];

// Calcula el porcentaje de cambio
$porcentajeCambio = ($ventasSemanaPasada > 0)
    ? (($ventasEstaSemana - $ventasSemanaPasada) / $ventasSemanaPasada) * 100
    : ($ventasEstaSemana > 0 ? 100 : 0);

// Devuelve los resultados como JSON
echo json_encode([
    'ventas_esta_semana' => $ventasEstaSemana,
    'ventas_semana_pasada' => $ventasSemanaPasada,
    'porcentaje_cambio' => $porcentajeCambio
]);
?>
