<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Si no está autenticado, devuelve una respuesta JSON
    echo json_encode(['success' => false]);
} else {
    // Si está autenticado, devuelve una respuesta JSON
    echo json_encode(['success' => true]);
}
?>
