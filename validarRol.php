<?php
include 'conexion.php';
// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}
session_start();

header('Content-Type: application/json');

// Crear la respuesta inicial
$response = ['status' => 'success'];

if (!isset($_SESSION['id_usuario'])) {
    $response['status'] = 'error';
    $response['message'] = 'Debes iniciar sesión para acceder a esta página.';
} else {
    $userId = $_SESSION['id_usuario'];

    $query = "
        SELECT ur.id_rol
        FROM usuarios_roles ur
        WHERE ur.id_usuario = ?
    ";

    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        
        // Obtener el resultado
        $result = $stmt->get_result();
        
        // Verificar si hay resultados
        if ($row = $result->fetch_assoc()) {
            $roleId = $row['id_rol'];

            if ($roleId !== 1) {
                $response['status'] = 'error';
                $response['message'] = 'No tienes acceso a este modulo.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Rol no encontrado para el usuario.';
        }
        
        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Error en la consulta a la base de datos: ' . $conn->error;
    }
}

echo json_encode($response);

$conn->close();
?>
