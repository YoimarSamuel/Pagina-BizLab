<?php
    include("conexion.php");

    session_start();

    if (!isset($_SESSION['iniciado'])) {
        header("Location: inicioSesion.php");
        exit; 
    }

    $userCorreo = $_SESSION['iniciado'];

    $queryUser = "SELECT * FROM usuarios WHERE user_correo = '$userCorreo'";
    $resultUser = $conn->query($queryUser);

    $userName = "";
    $membreNombre = "";
    $membreDescrip = "";
    $membreBeneficios = "";

    if ($resultUser && $resultUser->num_rows > 0) {
        $userRow = $resultUser->fetch_assoc();
        $userName = $userRow['user_nombre'];
        $userMembresia = $userRow['user_membresia'];

        $queryMembresia = "SELECT * FROM membresias WHERE id_membresia = $userMembresia";
        $resultMembresia = $conn->query($queryMembresia);

        if ($resultMembresia && $resultMembresia->num_rows > 0) {
            $membresiaRow = $resultMembresia->fetch_assoc();
            $membreNombre = $membresiaRow['membre_nombre'];
            $membreDescrip = $membresiaRow['membre_descrip'];
            $membreBeneficios = $membresiaRow['membre_beneficios'];
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Membresia Usuario</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" type="x-icon" href="images/favicon_bizclub.svg">
        <link rel="stylesheet" href="estilos/membresias.css">
    </head>
    <body class="body">
        <header class="header">
        </header>
        <main class="main">
            <div class="contenedor_todo">
                <div class="contenedor_base1">
                    <div class="base1_info1">
                        <img src="images/Sala_reuniones.jpg" alt="Sala de reuniones">
                    </div>
                    <div class="info2">
                        <div class="membresia_info">
                            <h3>BENEFICIOS DEL USUARIO</h3>
                            <p><?php echo $membreBeneficios; ?></p>
                        </div>
                        <div class="info_contenedor">
                            <div class="contenedor1"></div>
                            <div class="contenedor1"></div>
                            <div class="contenedor1"></div>
                        </div>
                    </div>
                </div>

                <div class="contenedor_base2">
                    <div class="base2_info1">
                        <h3><?php echo $userName; ?><?php echo $membreNombre; ?></h3>
                    </div>
                    <div class="base2_info2">
                        <div class="descripcion_info">
                            <p><?php echo $membreDescrip; ?></p>
                        </div>
                        <div class="contenedor_inferior">
                            <div class="contenedor_extra1">
                                <div class="contenedor"></div>
                                <div class="contenedor"></div>
                                <div class="contenedor"></div>
                            </div>
                            <div class="contenedor_extra2">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer">
        </footer>
    </body>
</html>
