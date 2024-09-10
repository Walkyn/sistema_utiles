<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../conexion.php';

    // Recoge los datos del formulario
    $idCliente = $_POST['client-id'];
    $nombre = $_POST['client-name'];
    $correo = $_POST['client-email'];
    $telefono = $_POST['client-phone'];

    // Validar que los datos no estén vacíos
    if (empty($idCliente) || empty($nombre) || empty($correo) || empty($telefono)) {
        echo json_encode([
            'success' => false,
            'message' => 'Todos los campos son obligatorios.'
        ]);
        exit;
    }

    // Preparar y ejecutar la consulta SQL para actualizar los datos
    $query = "UPDATE clientes SET nombre_apellido = ?, correo = ?, telefono = ? WHERE id_cliente = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sssi', $nombre, $correo, $telefono, $idCliente);

    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Cliente actualizado con éxito.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Error al actualizar el cliente.'
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de solicitud no permitido.'
    ]);
}
?>
