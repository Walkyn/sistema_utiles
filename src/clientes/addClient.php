<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include '../../conexion.php';

    // Obtener los datos del formulario
    $id_cliente = $_POST['client-id'];
    $nombre_apellido = $_POST['client-name'];
    $correo = $_POST['client-email'];
    $telefono = $_POST['client-phone'];

    $response = array('success' => false, 'message' => '');

    $sql_check = "SELECT id_cliente FROM clientes WHERE id_cliente = ?";
    if ($stmt_check = $conn->prepare($sql_check)) {
        $stmt_check->bind_param("i", $id_cliente);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $response['message'] = 'El ID de cliente proporcionado ya está en uso.';
        } else {
            // Preparar la consulta SQL para insertar los datos
            $sql_insert = "INSERT INTO clientes (id_cliente, nombre_apellido, correo, telefono) 
                            VALUES (?, ?, ?, ?)";

            if ($stmt_insert = $conn->prepare($sql_insert)) {
                $stmt_insert->bind_param("isss", $id_cliente, $nombre_apellido, $correo, $telefono);

                try {
                    // Ejecutar la consulta
                    $stmt_insert->execute();
                    $response['success'] = true;
                    $response['message'] = 'Cliente registrado exitosamente';
                } catch (mysqli_sql_exception $e) {
                    $response['message'] = $e->getMessage();
                }

                $stmt_insert->close();
            } else {
                $response['message'] = 'Error en la preparación de la consulta de inserción: ' . $conn->error;
            }
        }

        $stmt_check->close();
    } else {
        $response['message'] = 'Error en la preparación de la consulta de verificación: ' . $conn->error;
    }

    $conn->close();

    echo json_encode($response);
} else {
    echo json_encode(array('success' => false, 'message' => 'Método no permitido'));
}
?>
