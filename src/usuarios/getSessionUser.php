<?php
session_start();
header('Content-Type: application/json');

// Verifica si el usuario está autenticado
if (isset($_SESSION['userEmail'])) {
    echo json_encode(['email' => $_SESSION['userEmail']]);
} else {
    echo json_encode(['email' => null]);
}
?>
