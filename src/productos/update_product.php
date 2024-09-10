<?php
include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    die("No se pudo conectar a la base de datos: " . mysqli_connect_error());
}

// Obtener datos del formulario
$codigo = isset($_POST['codigo']) ? $_POST['codigo'] : '';
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
$precioCompra = isset($_POST['precioCompra']) ? $_POST['precioCompra'] : '';
$precioVenta = isset($_POST['precioVenta']) ? $_POST['precioVenta'] : '';
$existencia = isset($_POST['existencia']) ? $_POST['existencia'] : '';

$response = [];

if ($codigo && $descripcion && $precioCompra && $precioVenta && $existencia) {
    // Preparar consulta para verificar si el producto ya existe
    $sql_check = "SELECT codigo_producto FROM productos WHERE codigo_producto = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $codigo);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // El producto existe, realizar una actualización
        $sql_update = "UPDATE productos SET descripcion = ?, precio_compra = ?, precio_venta = ?, stock = ? WHERE codigo_producto = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sddis", $descripcion, $precioCompra, $precioVenta, $existencia, $codigo);

        if ($stmt_update->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'El producto se actualizó correctamente.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al actualizar el producto: ' . $stmt_update->error;
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'El producto con el código especificado no existe.';
    }

    $stmt_check->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Por favor, complete todos los campos.';
}

// Cerrar la conexión
$conn->close();

// Devolver respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
