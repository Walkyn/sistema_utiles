<?php
session_start();
include '../../conexion.php';

header('Content-Type: application/json');

// Verificar si la conexión se realizó correctamente
if (!$conn) {
    die("Conexión fallida: " . mysqli_connect_error());
}

// Consulta SQL para obtener datos específicos
$sql = "SELECT nombre, apellido, telefono, correo, imagen FROM usuarios";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuarios = array();
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
    echo json_encode($usuarios);
} else {
    echo json_encode(array());
}

$conn->close();
?>
