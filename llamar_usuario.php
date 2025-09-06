<?php
include("conexion.php");

session_start();
if (!isset($_SESSION['iniciado'])) {
    header("Location: inicioSesion.php");
    exit; 
}

$userCorreo = $_SESSION['iniciado'];
$query = "SELECT id_usuario, user_estado FROM usuarios";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $userStates = [];
    while ($row = $result->fetch_assoc()) {
        $userStates[] = $row;
    }
    echo json_encode($userStates);
} else {
    echo json_encode([]);
}
?>
