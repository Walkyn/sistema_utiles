<?php
include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

header('Content-Type: application/json');

// Obtener datos del cuerpo de la solicitud
$data = json_decode(file_get_contents('php://input'), true);

$clientId = $data['clientId'];
$subTotal = $data['subTotal'];
$descuento = $data['descuento'];
$total = $data['total'];
$articulos = $data['articulos'];
$products = $data['products'];
$fechaVenta = $data['fechaVenta'];
$horaVenta = $data['horaVenta'];

// Iniciar la transacción
$conn->begin_transaction();

try {
    // Insertar en la tabla de ventas
    $stmt = $conn->prepare("INSERT INTO ventas (id_cliente, fecha_venta, hora_venta, subtotal, descuento, total, articulos) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    // Asegúrate de que todos los parámetros están definidos y usar 'd' para decimal
    $stmt->bind_param('issdddi', $clientId, $fechaVenta, $horaVenta, $subTotal, $descuento, $total, $articulos);

    // Ejecutar la consulta
    $stmt->execute();
    $ventaId = $stmt->insert_id;
    $stmt->close();

    // Insertar detalles de la venta en la tabla detalles_ventas
    $stmt = $conn->prepare("INSERT INTO detalles_ventas (id_venta, codigo_producto, descripcion, precio, cantidad) VALUES (?, ?, ?, ?, ?)");

    foreach ($products as $product) {
        // Usar 'i' para integer, 's' para string, y 'd' para double (decimal)
        $stmt->bind_param('issdi', $ventaId, $product['codigo'], $product['descripcion'], $product['precio'], $product['cantidad']);
        $stmt->execute();
    }

    $stmt->close();

    // Confirmar transacción
    $conn->commit();

    // Devolver respuesta con éxito y el ID de la venta
    echo json_encode(['success' => true, 'ventaId' => $ventaId]);
} catch (Exception $e) {
    // Revertir transacción en caso de error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Cerrar la conexión
$conn->close();
?>
