<?php
include '../../conexion.php';

header('Content-Type: application/json');

if (!$conn) {
    die(json_encode(array('error' => 'Error de conexión a la base de datos.')));
}

// Obtiene el número máximo de boleta actual
$query = "SELECT MAX(id_venta) AS max_id FROM ventas";
$result = mysqli_query($conn, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    $next_id = $row['max_id'] + 1;
} else {
    $next_id = 1;
}

$formatted_id = str_pad($next_id, 6, "0", STR_PAD_LEFT);
$invoice_number = "B001-" . $formatted_id;

echo json_encode(array('invoice_number' => $invoice_number));

mysqli_close($conn);
?>
