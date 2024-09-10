<?php
// Incluye el archivo de conexión a la base de datos
include '../../conexion.php';
session_start(); // Iniciar sesión

// Verifica si la conexión se estableció correctamente
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Verifica si la sesión está iniciada
if (!isset($_SESSION['userEmail'])) {
    echo json_encode(['success' => false, 'message' => 'No se ha iniciado sesión.']);
    exit;
}

// Obtiene el correo del usuario en sesión
$sessionUserEmail = $_SESSION['userEmail'];

if (isset($_POST['correo'])) {
    // Obtiene el correo del usuario desde la solicitud POST
    $correo = $_POST['correo'];

    // Verifica si el usuario intenta eliminar su propia cuenta
    if ($correo === $sessionUserEmail) {
        echo json_encode([
            'success' => false,
            'message' => 'No se puede eliminar la cuenta mientras la sesión esté activa.'
        ]);
        exit;
    }

    // Prepara la consulta SQL para eliminar el usuario
    $sql = "DELETE FROM usuarios WHERE correo = ?";

    // Prepara la sentencia
    if ($stmt = $conn->prepare($sql)) {
        // Asocia los parámetros
        $stmt->bind_param("s", $correo);

        // Ejecuta la sentencia
        if ($stmt->execute()) {
            // Responde con éxito si el usuario se elimina correctamente
            echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente.']);
        } else {
            // Responde con un mensaje de error si hay un problema al ejecutar la consulta
            echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario.']);
        }

        // Cierra la sentencia
        $stmt->close();
    } else {
        // Responde con un mensaje de error si hay un problema al preparar la consulta
        echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    }
} else {
    // Responde con un mensaje de error si no se recibe un correo
    echo json_encode(['success' => false, 'message' => 'No se recibió un correo válido.']);
}

// Cierra la conexión
$conn->close();
