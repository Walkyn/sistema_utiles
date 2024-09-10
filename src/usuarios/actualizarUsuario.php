<?php
include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

// Obtener datos del formulario
$id_usuario = $_POST['user-id'];
$nombre = $_POST['user-name'];
$apellido = $_POST['user-lastname'];
$pais = $_POST['user-country'];
$ciudad = $_POST['user-city'];
$telefono = $_POST['user-phone'];
$correo = $_POST['user-email'];
$original_email = $_POST['original-email'] ?? '';
$password = $_POST['user-password'] ?? '';
$rol = $_POST['role'] ?? '';

// Verificar si el correo electrónico está en uso
$query = "SELECT correo FROM usuarios WHERE correo = ? AND id_usuario <> ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $correo, $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está en uso.']);
    exit();
}

// Preparar la consulta de actualización
if ($password) {
    $query = "UPDATE usuarios SET nombre = ?, apellido = ?, pais = ?, ciudad = ?, telefono = ?, correo = ?, contrasena = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $passwordHash = hash('sha256', $password);
    $stmt->bind_param('sssssssi', $nombre, $apellido, $pais, $ciudad, $telefono, $correo, $passwordHash, $id_usuario);
} else {
    $query = "UPDATE usuarios SET nombre = ?, apellido = ?, pais = ?, ciudad = ?, telefono = ?, correo = ? WHERE id_usuario = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssi', $nombre, $apellido, $pais, $ciudad, $telefono, $correo, $id_usuario);
}

if ($stmt->execute()) {
    // Actualizar el rol del usuario
    $queryRole = "INSERT INTO usuarios_roles (id_usuario, id_rol) VALUES (?, ?) 
                  ON DUPLICATE KEY UPDATE id_rol = VALUES(id_rol)";
    $stmtRole = $conn->prepare($queryRole);
    $stmtRole->bind_param('ii', $id_usuario, $rol);

    if ($stmtRole->execute()) {
        echo json_encode(['success' => true, 'message' => 'Usuario actualizado correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el rol del usuario.']);
    }

    $stmtRole->close();
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el usuario.']);
}

$stmt->close();
$conn->close();
?>
