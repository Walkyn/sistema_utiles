<?php
include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

header('Content-Type: application/json');

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$sql = "SELECT id_venta, articulos, total, fecha_venta, hora_venta FROM ventas";
$result = $conn->query($sql);

$ventas = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Formatear el número de boleta
        $id_venta = $row['id_venta'];
        $boleta = 'B001-' . str_pad($id_venta, 6, '0', STR_PAD_LEFT);
        
        // Añadir la venta al array con el número de boleta
        $ventas[] = [
            'boleta' => $boleta,
            'articulos' => $row['articulos'],
            'total' => $row['total'],
            'fecha_venta' => $row['fecha_venta'],
            'hora_venta' => $row['hora_venta']
        ];
    }
}

echo json_encode($ventas);

$conn->close();
?>
