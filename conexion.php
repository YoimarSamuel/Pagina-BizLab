<?php

    // $conn = new mysqli("localhost", "bizlabadmin", "bizlabadminpass", "bizlabDB");
    $conn = new mysqli("localhost:3306", "root", "D@n!#l!ll01188390", "bizlabDB");

    if($conn->connect_error){
        die("Hubo un fallo en la conexión". $conn->$connect_error);
    };
    
?>