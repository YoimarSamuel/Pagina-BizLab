<?php
include("conexion.php");

session_start();
if (!isset($_SESSION['iniciado'])) {
    header("Location: inicioSesion.php");
    exit; 
}

$userCorreo = $_SESSION['iniciado'];
$query = "SELECT id_Factura, estadoFactura FROM facturas";
$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    $facturaEstado = [];
    while ($row = $result->fetch_assoc()) {
        $facturaEstado[] = $row;
    }
    echo json_encode($facturaEstado);
} else {
    echo json_encode([]);
}

?>