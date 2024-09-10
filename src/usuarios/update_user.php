<?php
header('Content-Type: application/json');

// Conectar a la base de datos con MySQLi
include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}
// Obtener los datos enviados en formato JSON
$data = json_decode(file_get_contents('php://input'), true);

$id_usuario = $data['id_usuario'];
$firstName = $data['firstName'];
$lastName = $data['lastName'];
$country = $data['country'];
$city = $data['city'];
$phone = $data['phone'];
$email = $data['email'];
$password = !empty($data['password']) ? hash('sha256', $data['password']) : null;

// Verificar si el correo ya existe en la base de datos, excepto para el usuario actual
$query_check = "SELECT COUNT(*) as count FROM usuarios WHERE correo = ? AND id_usuario != ?";
$stmt_check = $conn->prepare($query_check);
$stmt_check->bind_param("si", $email, $id_usuario);
$stmt_check->execute();
$result = $stmt_check->get_result()->fetch_assoc();
$existingEmailCount = $result['count'];

if ($existingEmailCount > 0) {
    echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está en uso.']);
    exit;
}

if (empty($firstName) || empty($lastName)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, complete todos los campos requeridos.']);
    exit;
}

$query = "UPDATE usuarios SET nombre = ?, apellido = ?, pais = ?, ciudad = ?, telefono = ?, correo = ?";

$params = [$firstName, $lastName, $country, $city, $phone, $email];

if ($password) {
    $query .= ", contrasena = ?";
    $params[] = $password;
}

$query .= " WHERE id_usuario = ?";
$params[] = $id_usuario;

// Preparar la consulta
$stmt = $conn->prepare($query);
if ($stmt === false) {
    echo json_encode(['success' => false, 'message' => 'Error al preparar la consulta.']);
    exit;
}

// Vincular los parámetros
$types = str_repeat('s', count($params) - 1) . 'i';
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'No se pudo actualizar los datos.']);
}

$stmt->close();
$conn->close();
?>
