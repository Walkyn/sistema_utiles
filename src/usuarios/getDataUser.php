<?php
session_start();
include '../../conexion.php';

header('Content-Type: application/json');

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];

    // Consulta SQL para obtener los datos del usuario
    $sql = "SELECT id_usuario, nombre, apellido, pais, ciudad, telefono, correo FROM usuarios WHERE id_usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id_usuario);
    $stmt->execute();
    $stmt->bind_result($id_usuario, $nombre, $apellido, $pais, $ciudad, $telefono, $correo);
    $stmt->fetch();

    $user_data = [
        'id_usuario' => $id_usuario,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'pais' => $pais,
        'ciudad' => $ciudad,
        'telefono' => $telefono,
        'correo' => $correo
    ];

    echo json_encode($user_data);

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'Usuario no autenticado']);
}
?>
