<?php

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    session_start();

    require("vendor\autoload.php");
    require("conexion.php");

    // Conexión con Epayco
    $epayco = new Epayco\Epayco(array(
        "apiKey" => "2748d9ab9c7041e36711c19f4802c8cf",
        "privateKey" => "f668dd14c93aff3d78e8876a4634628e",
        "lenguage" => "ES",
        "test" => true
    ));
    //---------------------------------------------------------------

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    if(isset($_POST["membreCodi"])){

        $membreCod = $_POST["membreCodi"];
        $miembroToken = $_POST["userToken"];
        $tarjeToken = $_POST["userTarjeToken"];
        $userDocume = $_POST["userDocument"];
        $userDirecc = $_POST["userDirecc"];
        $userTelfF = $_POST["userTelf"];
        $userTelf = $_POST["userCelular"];
        $userIp = $_POST["userIp"];

        $sub = $epayco->subscriptions->create(array(
            "id_plan" => $membreCod,
            "customer" => $miembroToken,
            "token_card" => $tarjeToken,
            "doc_type" => "CC",
            "doc_number" => $userDocume,
            //Optional parameter: if these parameter it's not send, system get ePayco dashboard's url_confirmation
            "url_confirmation" => "https://ejemplo.com/confirmacion",
            "method_confirmation" => "POST"
        ));
        
        $sub3 = $epayco->subscriptions->charge(array(
            "id_plan" => $membreCod,
            "customer" => $miembroToken,
            "token_card" => $tarjeToken,
            "doc_type" => "CC",
            "doc_number" => $userDocume,
            "address" => $userDirecc,
            "phone"=> $userTelfF,
            "cell_phone"=> $userTelf,
            "ip" => $userIp,  // This is the client's IP, it is required
            "text"=> true
        ));

        echo json_encode($sub3, JSON_UNESCAPED_UNICODE);

    }

    if(isset($_POST["membreCodiPagoMem"])){

        $membreCod = $_POST["membreCodiPagoMem"];
        $miembroToken = $_POST["userToken"];
        $tarjeToken = $_POST["userTarjeToken"];
        $userDocume = $_POST["userDocument"];
        $userDirecc = $_POST["userDirecc"];
        $userTelfF = $_POST["userTelf"];
        $userTelf = $_POST["userCelular"];
        $userIp = $_POST["userIp"];

        $sub3 = $epayco->subscriptions->charge(array(
            "id_plan" => $membreCod,
            "customer" => $miembroToken,
            "token_card" => $tarjeToken,
            "doc_type" => "CC",
            "doc_number" => $userDocume,
            "address" => $userDirecc,
            "phone"=> $userTelfF,
            "cell_phone"=> $userTelf,
            "ip" => $userIp,  // This is the client's IP, it is required
            "text"=> true
        ));

        echo json_encode($sub3, JSON_UNESCAPED_UNICODE);
        
    }

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

?>