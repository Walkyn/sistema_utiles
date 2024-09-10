<?php

include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}
header('Content-Type: application/json');

$id_cliente = isset($_POST['id_cliente']) ? intval($_POST['id_cliente']) : 0;

$response = array();

if ($id_cliente > 0) {
    // Preparar la consulta para eliminar el cliente
    $stmt = $conn->prepare("DELETE FROM clientes WHERE id_cliente = ?");
    $stmt->bind_param("i", $id_cliente);

    // Ejecutar la consulta y verificar el resultado
    if ($stmt->execute()) {
        // Verificar si se eliminó alguna fila
        if ($stmt->affected_rows > 0) {
            $response['status'] = 'success';
            $response['message'] = 'Cliente eliminado correctamente.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'El cliente no existe.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error al eliminar el cliente.';
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'ID de cliente no válido.';
}

$conn->close();

echo json_encode($response);
?>
