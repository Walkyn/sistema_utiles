<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_db";

// Crear conexión
$conn = null;
try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
        exit;
    }
    mysqli_set_charset($conn, "utf8");

} catch (Exception $error) {
    echo json_encode(["error" => "Conexión fallida: " . $error->getMessage()]);
    exit;
}



/*




$servername = "www.walkyngamingyt.com";
$username = "capa_admin";
$password = "C18042000b_*";
$dbname = "capa_sistema";

$conn = null;
try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Verificar la conexión
    if ($conn->connect_error) {
        echo json_encode(["error" => "Conexión fallida: " . $conn->connect_error]);
        exit;
    }
    mysqli_set_charset($conn, "utf8");

} catch(Exception $error) {
    echo json_encode(["error" => "Conexión fallida: " . $error->getMessage()]);
    exit;
}
    ?>
*/
