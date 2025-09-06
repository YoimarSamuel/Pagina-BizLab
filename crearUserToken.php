<?php

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

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
    //-----------------------------------------------------------------

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

//:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    if(isset($_POST["tokenTarjetaNewUser"])){

        $tokenTarjeta = $_POST["tokenTarjetaNewUser"];
        $nombreUser = $_POST["nombreUser"];
        $apellidoUser = $_POST["apellidoUser"];
        $emailUser = $_POST["emailUser"];
        $ciudadUser = $_POST["ciudadUser"];
        $direccUser = $_POST["direccUser"];
        $telfFijoUser = $_POST["telfFijoUser"];
        $celularUser = $_POST["celularUser"];

        $clienteEpayco = "";
        $clienteData = "";
        
        //Creando un nuevo customer EPAYCO
        $clienteEpayco = $epayco->customer->create(array(
            "token_card" => $tokenTarjeta,
            "name" => $nombreUser,
            "last_name" => $apellidoUser, //This parameter is optional
            "email" => $emailUser,
            "default" => true,
            //Optional parameters: These parameters are important when validating the credit card transaction
            "city" => $ciudadUser,
            "address" => $direccUser,
            "phone" => $telfFijoUser,
            "cell_phone"=> $celularUser,
        ));

        // var_dump($clienteEpayco);
        
        $clienteData = $clienteEpayco->{"data"};
        $clienteData2 = $clienteData->{"customerId"};

        echo json_encode($clienteData2, JSON_UNESCAPED_UNICODE);

    }

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

?>