<?php
include '../../conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar si se ha enviado el código del producto
    if (isset($_POST['codigo_producto'])) {
        $codigoProducto = $_POST['codigo_producto'];

        // Preparar la consulta SQL para eliminar el producto
        $sql = "DELETE FROM productos WHERE codigo_producto = ?";

        // Usar una sentencia preparada para evitar inyecciones SQL
        if ($stmt = $conn->prepare($sql)) {
            // Vincular el código del producto a la consulta
            $stmt->bind_param('s', $codigoProducto);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Producto eliminado exitosamente.'
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Error al eliminar el producto.'
                ];
            }

            // Cerrar la declaración
            $stmt->close();
        } else {
            $response = [
                'status' => 'error',
                'message' => 'No se pudo preparar la consulta.'
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'No se recibió un código de producto válido.'
        ];
    }
} else {
    $response = [
        'status' => 'error',
        'message' => 'Método de solicitud no válido.'
    ];
}

// Cerrar la conexión
$conn->close();

// Enviar la respuesta como JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
