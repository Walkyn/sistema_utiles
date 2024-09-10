<?php

include 'conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT id_usuario, contrasena FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = [];

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row['contrasena'];

        // Cifrar la contraseña ingresada usando SHA-256
        $hashedPassword = hash('sha256', $password);

        // Comparar la contraseña cifrada ingresada con la almacenada
        if ($hashedPassword === $stored_password) {
            // Iniciar sesión para el nuevo usuario
            $_SESSION['id_usuario'] = $row['id_usuario'];
            $_SESSION['userEmail'] = $email;

            $response['status'] = 'success';
            $response['message'] = 'Inicio de sesión exitoso';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Correo o contraseña incorrectos';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Correo o contraseña incorrectos';
    }

    echo json_encode($response);

    $stmt->close();
    $conn->close();
}
