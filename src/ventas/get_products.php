<?php
// get_products.php

header('Content-Type: application/json');

include '../../conexion.php';

// Consultar productos
$sql = "SELECT codigo_producto, descripcion, precio_venta, stock FROM productos";
$result = $conn->query($sql);

$products = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();

echo json_encode($products);
?>
