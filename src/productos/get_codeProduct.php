<?php
header('Content-Type: application/json');

include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

$codigo_producto = $_GET['codigo_producto'] ?? '';

if ($codigo_producto) {
    $sql = $conn->prepare("SELECT codigo_producto, descripcion, precio_compra, precio_venta, stock FROM productos WHERE codigo_producto = ?");
    $sql->bind_param('s', $codigo_producto);
    $sql->execute();
    $result = $sql->get_result();
    
    if ($result) {
        $producto = $result->fetch_assoc();
        echo json_encode($producto);
    } else {
        echo json_encode(['error' => 'Error en la consulta: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Código de producto no proporcionado.']);
}

$conn->close();
?>
