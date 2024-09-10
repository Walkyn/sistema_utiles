<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../conexion.php';

    // Obtener los datos del formulario
    $codigo = $_POST['codigo'];
    $descripcion = $_POST['descripcion'];
    $precioCompra = $_POST['precioCompra'];
    $precioVenta = $_POST['precioVenta'];
    $stock = $_POST['existencia'];

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO productos (codigo_producto, descripcion, precio_compra, precio_venta, stock) 
            VALUES (?, ?, ?, ?, ?)";

    $response = array('success' => false, 'message' => '');

    // Preparar la declaración
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param("ssddi", $codigo, $descripcion, $precioCompra, $precioVenta, $stock);

        try {
            // Ejecutar la consulta
            $stmt->execute();
            $response['success'] = true;
            $response['message'] = 'Producto agregado exitosamente';
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                $response['message'] = 'El código del producto ya está registrado.';
            } else {
                $response['message'] = $e->getMessage();
            }
        }

        $stmt->close();
    } else {
        $response['message'] = 'Error en la preparación de la consulta: ' . $conn->error;
    }

    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(array('success' => false, 'message' => 'Método no permitido'));
}
?>
