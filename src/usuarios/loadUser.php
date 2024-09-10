<?php
header('Content-Type: application/json');

// Obtener el correo del usuario desde la consulta GET
$correo = $_GET['correo'];

// Incluir archivo de conexión
include '../../conexion.php';

// Verificar si $conn está definido
if (!isset($conn)) {
    echo json_encode(['error' => 'Error de conexión a la base de datos']);
    exit;
}

// Preparar y ejecutar la consulta
$query = $conn->prepare('SELECT nombre, apellido, pais, ciudad, telefono, correo FROM usuarios WHERE correo = ?');
if ($query) {
    $query->bind_param('s', $correo);
    $query->execute();
    $result = $query->get_result();

    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode($user);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado']);
    }

    $query->close();
} else {
    echo json_encode(['error' => 'Error al preparar la consulta']);
}

$conn->close();
?>
