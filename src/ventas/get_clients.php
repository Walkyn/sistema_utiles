<?php
include '../../conexion.php';

header('Content-Type: application/json');

$query = "SELECT id_cliente, nombre_apellido FROM clientes";
$result = mysqli_query($conn, $query);

$clients = array();

while ($row = mysqli_fetch_assoc($result)) {
    $clients[] = $row;
}

mysqli_close($conn);

echo json_encode($clients);
?>
