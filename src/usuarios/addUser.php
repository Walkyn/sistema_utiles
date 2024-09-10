<?php  

header('Content-Type: application/json');  

include '../../conexion.php';

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {  

    $data = json_decode(file_get_contents('php://input'), true);  

    if (!$data) {  
        echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);  
        exit;  
    }  

    if (!isset($data['id_usuario'], $data['first-name'], $data['last-name'], $data['country'], $data['city'], $data['phone'], $data['email'], $data['password'], $data['role'])) {  
        echo json_encode(['success' => false, 'message' => 'Faltan campos.']);  
        exit;  
    }  

    $id_usuario = $data['id_usuario'];  
    $nombre = $data['first-name'];  
    $apellido = $data['last-name'];  
    $pais = $data['country'];  
    $ciudad = $data['city'];  
    $telefono = $data['phone'];  
    $correo = $data['email'];  
    $contrasena = $data['password'];  
    $rol = $data['role'];

    // Verificar si el ID de usuario ya existe en la base de datos
    $checkIdSql = "SELECT id_usuario FROM usuarios WHERE id_usuario = ?";
    if ($stmt = $conn->prepare($checkIdSql)) {
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'El ID de usuario ya existe.']);
            $stmt->close();
            exit;
        }

        $stmt->close();
    } else {  
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de verificación del ID de usuario.']);  
        exit;
    }

    // Verificar si el correo electrónico ya está en uso
    $checkEmailSql = "SELECT id_usuario FROM usuarios WHERE correo = ?";
    if ($stmt = $conn->prepare($checkEmailSql)) {
        $stmt->bind_param("s", $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'El correo electrónico ya está en uso.']);
            $stmt->close();
            exit;
        }

        $stmt->close();
    } else {  
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de verificación de correo.']);  
        exit;
    }

    $hashedContrasena = hash('sha256', $contrasena);  

    // Insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (id_usuario, nombre, apellido, pais, ciudad, telefono, correo, contrasena) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {  
        $stmt->bind_param("isssssss", $id_usuario, $nombre, $apellido, $pais, $ciudad, $telefono, $correo, $hashedContrasena);  

        if ($stmt->execute()) {  
            // Asignar el rol al usuario
            $sqlRole = "INSERT INTO usuarios_roles (id_usuario, id_rol) VALUES (?, ?)";
            if ($stmtRole = $conn->prepare($sqlRole)) {
                $stmtRole->bind_param("ii", $id_usuario, $rol);

                if ($stmtRole->execute()) {
                    echo json_encode(['success' => true, 'message' => 'Usuario registrado exitosamente.']);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Error al asignar el rol al usuario.']);
                }

                $stmtRole->close();
            } else {
                echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta de asignación de rol.']);
            }
        } else {  
            echo json_encode(['success' => false, 'message' => 'Error al registrar el usuario.']);  
        }  

        $stmt->close();  
    } else {  
        echo json_encode(['success' => false, 'message' => 'Error en la preparación de la consulta.']);  
    }  
} else {  
    echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido.']);  
}  

$conn->close();  
?>
