<?php
header('Content-Type: application/json');

include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}


$sql = "SELECT codigo_producto, descripcion, precio_compra, precio_venta, stock FROM productos";
// $sql = "SELECT descripcion FROM productos";

$result = $conn->query($sql);


if ($result) {
    $productos = [];
    while ($row = $result->fetch_assoc()) {
        $productos[] = $row;
    }
    echo json_encode($productos);
} else {
    echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
}

$conn->close();
?>
