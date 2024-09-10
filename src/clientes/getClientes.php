<?php
header('Content-Type: application/json');

include '../../conexion.php';

$sql = "SELECT id_cliente, nombre_apellido, correo, telefono FROM clientes";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $clientes = [];
    while ($row = $result->fetch_assoc()) {
        $clientes[] = $row;
    }
    echo json_encode($clientes);
} else {
    echo json_encode([]);
}

$conn->close();
?>
