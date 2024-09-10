<?php
include '../../conexion.php'; // Ajusta la ruta según la ubicación real

// Verificar la conexión
if (!$conn) {
    echo json_encode(['error' => 'No se pudo conectar a la base de datos.']);
    exit;
}
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profileImage']['tmp_name'];
        $fileName = $_FILES['profileImage']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Directorio para guardar la imagen
        $uploadFileDir = '../uploads/';
        $destPath = $uploadFileDir . $fileName;

        // Verificar si la carpeta de uploads existe, de lo contrario crearla
        if (!file_exists($uploadFileDir)) {
            mkdir($uploadFileDir, 0755, true);
        }

        // Mover el archivo al directorio deseado
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Actualizar la imagen del usuario en la base de datos
            $userEmail = $_SESSION['userEmail'];
            $query = "UPDATE usuarios SET imagen = ? WHERE correo = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('ss', $fileName, $userEmail);

            if ($stmt->execute()) {
                echo json_encode(['success' => true, 'message' => 'Imagen subida y actualizada con éxito']);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar la imagen en la base de datos']);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'error' => 'No se pudo mover el archivo']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'No se seleccionó ningún archivo o error al subir']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Método de solicitud inválido']);
}
?>
