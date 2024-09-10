<?php
include '../../conexion.php'; // Ajusta la ruta según la ubicación real

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}
session_start();
$userEmail = $_SESSION['userEmail']; // Obtener el correo del usuario desde la sesión

// Consultar la base de datos para obtener los datos del usuario
$query = "SELECT nombre, apellido, correo, imagen FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $userEmail);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Crear el nombre completo del usuario y reemplazar espacios con "+"
        $nombreCompleto = $user['nombre'] . ' ' . $user['apellido'];
        $nombreCompletoUrl = str_replace(' ', '+', $nombreCompleto);

        // Verificar si el usuario tiene una imagen
        $imageUrl = $user['imagen'] 
            ? '../uploads/' . $user['imagen'] 
            : 'https://ui-avatars.com/api/?name=' . $nombreCompletoUrl;

        echo json_encode([
            'nombre' => $user['nombre'],
            'apellido' => $user['apellido'],
            'correo' => $user['correo'],
            'image' => $imageUrl
        ]);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }
} else {
    echo json_encode(['error' => 'Error al recuperar los datos del usuario']);
}

$stmt->close();
$conn->close();
?>
