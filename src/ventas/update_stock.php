<?php
// Incluir el archivo de conexión
include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}
// Establecer el tipo de contenido como JSON
header('Content-Type: application/json');

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

$ventaId = $data['ventaId'];
$products = $data['products'];

// Verificar que se haya recibido un ventaId
if (!isset($ventaId)) {
    echo json_encode(['success' => false, 'message' => 'ID de venta no proporcionado']);
    exit();
}

// Iniciar una transacción
$conn->begin_transaction();

try {
    // Actualizar stock para cada producto
    $stmt = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE codigo_producto = ?");

    foreach ($products as $product) {
        $stmt->bind_param('is', $product['cantidad'], $product['codigo']);
        $stmt->execute();
    }

    $stmt->close();
    
    // Confirmar transacción
    $conn->commit();

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Cerrar la conexión
$conn->close();
?>
