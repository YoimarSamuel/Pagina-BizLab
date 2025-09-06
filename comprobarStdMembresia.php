<?php

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    session_start();

    require("conexion.php");

    //----------------------------------------------------------------------------------------------------------------------------------
    // IP usuario

    $ipUser = "";

    function obtenerDataIp($ip){

        $url = 'http://ip-api.com/json/'.$ip;
    
        $response = file_get_contents($url);
    
        $data = json_decode($response, true);
        
        if($data['status'] == 'success'){
            return $data;
        }else{
            return null;
        }
    
    }
    
    $ipUser = $_SERVER['REMOTE_ADDR'];
    
    $resultadoIp = obtenerDataIp($ipUser);
    
    if($resultadoIp !== null){
    
        $ipUser = $_SERVER['REMOTE_ADDR']; 
    
    }else{
    
        $ipUser = "127.0.0.1";
    
    }

    //----------------------------------------------------------------------------------------------------------------------------------

//::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

    if(isset($_SESSION["iniciado"])){

        if($_SESSION["tipoUsuario"] == "Miembro"){

            if(isset($_POST["verifiMembresiaEstd"])){

                // Usuario Datos
                $resultadoUser = $conn->query(
                    "SELECT * FROM `bizlabDB`.`usuarios`
                    WHERE `usuarios`.`id_usuario` = ".intval($_SESSION["iniciado"]).";"
                );

                $usuario = $resultadoUser->fetch_assoc();
                //------------------------------------------------------------------------------------------------

                // Membresia Epayco del User ID
                $idMembresia = "";

                $resultadoMembresia = $conn->query(
                    "SELECT `membreIdEpayco`, `membreId`, `membreCodEpayco`, `membreCodigo` FROM `bizlabDB`.`membresiauser`
                    WHERE `membresiauser`.`membreUser` = ".intval($_SESSION["iniciado"]).";"
                );

                $idMembresia = $resultadoMembresia->fetch_assoc();
                //------------------------------------------------------------------------------------------------

                // Membresia
                $membresiaUser = "";

                $resultadoMembreDatos = $conn->query(
                    "SELECT * FROM `bizlabDB`.`membresias`
                    WHERE `membresias`.`id_membresia` = ".$idMembresia["membreId"].";"
                );

                $membresiaUser = $resultadoMembreDatos->fetch_assoc();

                $membreIVA = 
                    (intval($membresiaUser["membre_mensualidad"])*
                    (intval($membresiaUser["membre_iva"])/100));

                $membreDescu = 
                    (intval($membresiaUser["membre_mensualidad"])*
                    (intval($membresiaUser["membre_descuento"])/100));

                $membreTotal = 
                    intval($membresiaUser["membre_mensualidad"])+
                    (intval($membresiaUser["membre_mensualidad"])*
                    (intval($membresiaUser["membre_iva"])/100));
                
                $membreTotal = 
                    intval($membresiaUser["membre_mensualidad"])-
                    (intval($membresiaUser["membre_mensualidad"])*
                    (intval($membresiaUser["membre_descuento"])/100));
                
                //------------------------------------------------------------------------------------------------

            }

        }else{

            header("location:membresiasCliente.php");

        }

    }else{

        header("location:inicioSesion.php");

    }

?>

<!DOCTYPE html>
<html lang="en" id="comprobarMembreHTML">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago de Mensualidad - Membresía BizClub</title>
    <link rel="shortcut icon" type="x-icon" href="images/favicon_bizclub.svg">
    <link rel="stylesheet" href="estilos/comprobarMembresia.css">

    <input type="hidden" value="<?php echo $idMembresia["membreIdEpayco"]; ?>" id="inO_idMembreUser">
    <input type="hidden" value="<?php echo $idMembresia["membreCodEpayco"]; ?>" id="inO_idMembreCodigoEpayco">
    <input type="hidden" value="<?php echo $idMembresia["membreCodigo"]; ?>" id="inO_idMembreCodigo">
    <input type="hidden" value="<?php echo $usuario["user_codigoEpayco"]; ?>" id="inO_idUserCodigoEpayco">
    <input type="hidden" value="<?php echo $ipUser; ?>" id="inO_ipUser">
    <input type="hidden" value="<?php echo $membresiaUser["membre_mensualidad"]; ?>" id="inO_mensualidadMembre">
    <input type="hidden" value="<?php echo $membresiaUser["membre_iva"]; ?>" id="inO_ivaPorcenMembre">
    <input type="hidden" value="<?php echo $membreIVA; ?>" id="inO_ivaCantMembre">
    <input type="hidden" value="<?php echo $membresiaUser["membre_descuento"]; ?>" id="inO_descuPorcenMembre">
    <input type="hidden" value="<?php echo $membresiaUser["membre_nombre"]; ?>" id="inO_nombreMembre">
    <input type="hidden" value="<?php echo $membreDescu; ?>" id="inO_descuCantMembre">
    <input type="hidden" value="<?php echo $membreTotal; ?>" id="inO_totalMembre">
    <input type="hidden" value="<?php echo $usuario["id_usuario"]; ?>" id="inO_userId">
    <input type="hidden" value="<?php echo $usuario["user_membresia"]; ?>" id="inO_userIdMembresia">
    <input type="hidden" value="<?php echo $usuario["user_nombre"]; ?>" id="inO_userNombre">
    <input type="hidden" value="<?php echo $usuario["user_apellido"]; ?>" id="inO_userApellido">
    <input type="hidden" value="<?php echo $usuario["user_correo"]; ?>" id="inO_userEmail">
    <input type="hidden" value="<?php echo $usuario["user_telefono"]; ?>" id="inO_userTelefF">
    <input type="hidden" value="<?php echo $usuario["user_celular"]; ?>" id="inO_userCelular">
    <input type="hidden" value="<?php echo $usuario["user_documento"]; ?>" id="inO_userDocumento">

    <form 
        method="post"
        action="mensualidadPagoFacEmail.php"
        id="formCreaFacMensualidad"
        name="formCreaFacMensualidad"
    >
        <input type="hidden" value="" name="codMembresiaEmail" id="inFormO_codMembresiaEmail">
        <input type="hidden" value="" name="fechaCreaFac" id="inFormO_fechaCreaFac">
        <input type="hidden" value="" name="fechaCaducaFac" id="inFormO_fechaCaducaFac">
        <input type="hidden" value="" name="facNumPedido" id="inFormO_facNumPedido">
        <input type="hidden" value="" name="mensuaMembresia" id="inFormO_mensuaMembresia">
        <input type="hidden" value="" name="ivaCantMembresia" id="inFormO_ivaCantMembresia">
        <input type="hidden" value="" name="descuCantMembresia" id="inFormO_descuCantMembresia">
        <input type="hidden" value="" name="totalMembresia" id="inFormO_totalMembresia">
        <input type="hidden" value="" name="horaCreaFac" id="inFormO_horaCreaFac">
        <input type="hidden" value="" name="serieFac" id="inFormO_serieFac">
        <input type="hidden" value="" name="codigoFac" id="inFormO_codigoFac">
        <input type="hidden" value="" name="membreNombre" id="inFormO_membreNombre">
        <input type="hidden" value="" name="respuestaEpay" id="inFormO_respuestaEpay">
        <input type="hidden" value="" name="motivoEpay" id="inFormO_motivoEpay">
    </form>

</head>
<body class="body">
    <main class="main">
        <div class="contenedorGene">
            <div class="btnsDivTipoPagoGene">
                <button class="buttonTP buttonSelected">Tarjeta de Crédito</button>
                <!-- <button class="buttonTP buttonNoSele">Cuenta Bancaria</button> -->
            </div>
            <div class="baseTipoPagoGene">
                <div id="divEstadoCuenta" class="divEstadoCuenta divEstadoCuenta-V">
                    <span class="spanEstadoCuenta">Estado de la cuenta</span>
                </div>
                <div class="pagoTDCBase">
                    <span class="spanTipoPagoGene">Tarjeta de Crédito</span>
                    <div class="datosTDCDiv">
                        <div class="divNumTDC">
                            <span>Número de la tarjeta</span>
                            <input type="text" value="" id="in_numTDC" maxlength="30">
                        </div>
                        <div class="divFechasTDC">
                            <span class="fechaVenci">Fecha de Vencimiento</span>
                            <div class="divMesVTDC">
                                <span>Mes</span>
                                <select id="in_mesVTDC">
                                    <option selected disabled>Sin Dato</option>
                                    <option>01</option>
                                    <option>02</option>
                                    <option>03</option>
                                    <option>04</option>
                                    <option>05</option>
                                    <option>06</option>
                                    <option>07</option>
                                    <option>08</option>
                                    <option>09</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                </select>
                            </div>
                            <div class="divAnioVTDC">
                                <span>Año</span>
                                <input type="text" value="" id="in_anioVTDC" maxlength="4">
                            </div>
                        </div>
                        <div class="numCVVTDCDiv">
                            <span>CVC</span>
                            <input type="text" maxlength="4" id="in_numCVVTDC">
                        </div>
                    </div>
                    <div class="erroresRespuDiv">
                        <span class="spanError"></span>
                    </div>
                </div>
            </div>
            <div class="datosPagoDivGene">
                <div class="divMensualidad">
                    <span>Mensualidad</span>
                    <span><?php echo $membresiaUser["membre_mensualidad"]; ?> COP</span>
                </div>
                <div class="divIva">
                    <span>Iva</span>
                    <span><?php echo $membresiaUser["membre_iva"]; ?> COP</span>
                </div>
                <div class="divDescu">
                    <span>Descuento</span>
                    <span><?php echo $membresiaUser["membre_descuento"]; ?> COP</span>
                </div>
                <div class="divTotal">
                    <span>Total</span>
                    <span><?php echo $membreTotal; ?> COP</span>
                </div>
            </div>
            <div class="btnsDivGenePagarMensu">
                <button id="btn_volver" class="buttonVolver buttonVolver-D">Volver al Inicio</button>
                <button id="btn_pagarMensualidad" class="buttonPagarMensu buttonPagarMensu-B" disabled>Pagar Mensualidad</button>
            </div>
        </div>
    </main>
    <footer class="footer">
        <div class="flexContainerEnlaces">
            <a target="_blank" href="https://wa.me/+573044138809">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0,0,256,256">
                    <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M25,2c-12.69047,0 -23,10.30953 -23,23c0,4.0791 1.11869,7.88588 2.98438,11.20898l-2.94727,10.52148c-0.09582,0.34262 -0.00241,0.71035 0.24531,0.96571c0.24772,0.25536 0.61244,0.35989 0.95781,0.27452l10.9707,-2.71875c3.22369,1.72098 6.88165,2.74805 10.78906,2.74805c12.69047,0 23,-10.30953 23,-23c0,-12.69047 -10.30953,-23 -23,-23zM25,4c11.60953,0 21,9.39047 21,21c0,11.60953 -9.39047,21 -21,21c-3.72198,0 -7.20788,-0.97037 -10.23828,-2.66602c-0.22164,-0.12385 -0.48208,-0.15876 -0.72852,-0.09766l-9.60742,2.38086l2.57617,-9.19141c0.07449,-0.26248 0.03851,-0.54399 -0.09961,-0.7793c-1.84166,-3.12289 -2.90234,-6.75638 -2.90234,-10.64648c0,-11.60953 9.39047,-21 21,-21zM16.64258,13c-0.64104,0 -1.55653,0.23849 -2.30859,1.04883c-0.45172,0.48672 -2.33398,2.32068 -2.33398,5.54492c0,3.36152 2.33139,6.2621 2.61328,6.63477h0.00195v0.00195c-0.02674,-0.03514 0.3578,0.52172 0.87109,1.18945c0.5133,0.66773 1.23108,1.54472 2.13281,2.49414c1.80347,1.89885 4.33914,4.09336 7.48633,5.43555c1.44932,0.61717 2.59271,0.98981 3.45898,1.26172c1.60539,0.5041 3.06762,0.42747 4.16602,0.26563c0.82216,-0.12108 1.72641,-0.51584 2.62109,-1.08203c0.89469,-0.56619 1.77153,-1.2702 2.1582,-2.33984c0.27701,-0.76683 0.41783,-1.47548 0.46875,-2.05859c0.02546,-0.29156 0.02869,-0.54888 0.00977,-0.78711c-0.01897,-0.23823 0.0013,-0.42071 -0.2207,-0.78516c-0.46557,-0.76441 -0.99283,-0.78437 -1.54297,-1.05664c-0.30567,-0.15128 -1.17595,-0.57625 -2.04883,-0.99219c-0.8719,-0.41547 -1.62686,-0.78344 -2.0918,-0.94922c-0.29375,-0.10568 -0.65243,-0.25782 -1.16992,-0.19922c-0.51749,0.0586 -1.0286,0.43198 -1.32617,0.87305c-0.28205,0.41807 -1.4175,1.75835 -1.76367,2.15234c-0.0046,-0.0028 0.02544,0.01104 -0.11133,-0.05664c-0.42813,-0.21189 -0.95173,-0.39205 -1.72656,-0.80078c-0.77483,-0.40873 -1.74407,-1.01229 -2.80469,-1.94727v-0.00195c-1.57861,-1.38975 -2.68437,-3.1346 -3.0332,-3.7207c0.0235,-0.02796 -0.00279,0.0059 0.04687,-0.04297l0.00195,-0.00195c0.35652,-0.35115 0.67247,-0.77056 0.93945,-1.07812c0.37854,-0.43609 0.54559,-0.82052 0.72656,-1.17969c0.36067,-0.71583 0.15985,-1.50352 -0.04883,-1.91797v-0.00195c0.01441,0.02867 -0.11288,-0.25219 -0.25,-0.57617c-0.13751,-0.32491 -0.31279,-0.74613 -0.5,-1.19531c-0.37442,-0.89836 -0.79243,-1.90595 -1.04102,-2.49609v-0.00195c-0.29285,-0.69513 -0.68904,-1.1959 -1.20703,-1.4375c-0.51799,-0.2416 -0.97563,-0.17291 -0.99414,-0.17383h-0.00195c-0.36964,-0.01705 -0.77527,-0.02148 -1.17773,-0.02148zM16.64258,15c0.38554,0 0.76564,0.0047 1.08398,0.01953c0.32749,0.01632 0.30712,0.01766 0.24414,-0.01172c-0.06399,-0.02984 0.02283,-0.03953 0.20898,0.40234c0.24341,0.57785 0.66348,1.58909 1.03906,2.49023c0.18779,0.45057 0.36354,0.87343 0.50391,1.20508c0.14036,0.33165 0.21642,0.51683 0.30469,0.69336v0.00195l0.00195,0.00195c0.08654,0.17075 0.07889,0.06143 0.04883,0.12109c-0.21103,0.41883 -0.23966,0.52166 -0.45312,0.76758c-0.32502,0.37443 -0.65655,0.792 -0.83203,0.96484c-0.15353,0.15082 -0.43055,0.38578 -0.60352,0.8457c-0.17323,0.46063 -0.09238,1.09262 0.18555,1.56445c0.37003,0.62819 1.58941,2.6129 3.48438,4.28125c1.19338,1.05202 2.30519,1.74828 3.19336,2.2168c0.88817,0.46852 1.61157,0.74215 1.77344,0.82227c0.38438,0.19023 0.80448,0.33795 1.29297,0.2793c0.48849,-0.05865 0.90964,-0.35504 1.17773,-0.6582l0.00195,-0.00195c0.3568,-0.40451 1.41702,-1.61513 1.92578,-2.36133c0.02156,0.0076 0.0145,0.0017 0.18359,0.0625v0.00195h0.00195c0.0772,0.02749 1.04413,0.46028 1.90625,0.87109c0.86212,0.41081 1.73716,0.8378 2.02148,0.97852c0.41033,0.20308 0.60422,0.33529 0.6543,0.33594c0.00338,0.08798 0.0068,0.18333 -0.00586,0.32813c-0.03507,0.40164 -0.14243,0.95757 -0.35742,1.55273c-0.10532,0.29136 -0.65389,0.89227 -1.3457,1.33008c-0.69181,0.43781 -1.53386,0.74705 -1.8457,0.79297c-0.9376,0.13815 -2.05083,0.18859 -3.27344,-0.19531c-0.84773,-0.26609 -1.90476,-0.61053 -3.27344,-1.19336c-2.77581,-1.18381 -5.13503,-3.19825 -6.82031,-4.97266c-0.84264,-0.8872 -1.51775,-1.71309 -1.99805,-2.33789c-0.4794,-0.62364 -0.68874,-0.94816 -0.86328,-1.17773l-0.00195,-0.00195c-0.30983,-0.40973 -2.20703,-3.04868 -2.20703,-5.42578c0,-2.51576 1.1685,-3.50231 1.80078,-4.18359c0.33194,-0.35766 0.69484,-0.41016 0.8418,-0.41016z"></path></g></g>
                </svg>
            </a>
            <a target="_blank" href="https://mail.google.com/mail/u/2/#inbox?compose=CllgCJvqsKdQQMdxJNjVPkmWPBMFLLCWFrQTnkZGftqFKTpBSdjcztwknBlHlNZrQNsPFdFPcSq">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0,0,256,256">
                    <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M43.75391,6.40234c-1.2177,-0.05335 -2.45911,0.31055 -3.48242,1.06641l-2.74609,2.02734l-12.52539,9.25977l-12.4082,-9.17187c-0.17292,-0.16897 -0.4012,-0.26957 -0.64258,-0.2832h0.25l-2.46484,-1.82422c-1.02397,-0.75773 -2.26723,-1.12367 -3.48633,-1.07031c-1.2191,0.05336 -2.4131,0.52522 -3.33984,1.43945c-1.17726,1.16068 -1.9082,2.78413 -1.9082,4.56445v3.43359c-0.01457,0.09777 -0.01457,0.19715 0,0.29492v23.36133c0,1.92119 1.57881,3.5 3.5,3.5h7.5c0.55226,-0.00006 0.99994,-0.44774 1,-1v-16.62695l11.40625,8.43164c0.353,0.26043 0.8345,0.26043 1.1875,0l11.40625,-8.43164v16.62695c0.00006,0.55226 0.44774,0.99994 1,1h7.5c1.92119,0 3.5,-1.57881 3.5,-3.5v-23.38086c0.01129,-0.08622 0.01129,-0.17355 0,-0.25977v-3.44922c0,-1.75846 -0.70954,-3.37437 -1.87109,-4.53711c-0.03357,-0.03357 -0.04482,-0.04287 -0.03125,-0.0293c-0.00194,-0.00196 -0.0039,-0.00391 -0.00586,-0.00586c-0.92656,-0.91221 -2.12019,-1.3822 -3.33789,-1.43555zM43.64453,8.40039c0.7563,0.02965 1.48952,0.3165 2.04492,0.86328c0.01891,0.01867 0.03272,0.03277 0.02344,0.02344c0.79645,0.79726 1.28711,1.9015 1.28711,3.12305v3.08594l-8,5.91211v-10.4082c0.00042,-0.0339 -0.00088,-0.0678 -0.00391,-0.10156l2.46289,-1.82031c0.00065,0 0.0013,0 0.00195,0c0.64864,-0.47915 1.42729,-0.70739 2.18359,-0.67773zM6.35742,8.40625c0.75715,-0.02964 1.53847,0.19746 2.1875,0.67773l2.45898,1.81836c-0.00289,0.03247 -0.0042,0.06506 -0.00391,0.09766v10.4082l-8,-5.91211v-3.08594c0,-1.23567 0.50176,-2.3413 1.3125,-3.14062c0.55526,-0.54776 1.28777,-0.83364 2.04492,-0.86328zM37,12.37109v10.51563l-12,8.86914l-12,-8.86914v-10.51367l11.40625,8.43164c0.353,0.26043 0.8345,0.26043 1.1875,0zM3,17.98242l8,5.91406v17.10352h-6.5c-0.84081,0 -1.5,-0.65919 -1.5,-1.5zM47,17.98242v21.51758c0,0.84081 -0.65919,1.5 -1.5,1.5h-6.5v-17.10352z"></path></g></g>
                </svg>
            </a>
            <a target="_blank" href="https://www.instagram.com/bizclubworld?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==">
                <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="40" height="40" viewBox="0,0,256,256">
                    <g fill="#ffffff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(5.12,5.12)"><path d="M16,3c-7.16752,0 -13,5.83248 -13,13v18c0,7.16752 5.83248,13 13,13h18c7.16752,0 13,-5.83248 13,-13v-18c0,-7.16752 -5.83248,-13 -13,-13zM16,5h18c6.08648,0 11,4.91352 11,11v18c0,6.08648 -4.91352,11 -11,11h-18c-6.08648,0 -11,-4.91352 -11,-11v-18c0,-6.08648 4.91352,-11 11,-11zM37,11c-1.10457,0 -2,0.89543 -2,2c0,1.10457 0.89543,2 2,2c1.10457,0 2,-0.89543 2,-2c0,-1.10457 -0.89543,-2 -2,-2zM25,14c-6.06329,0 -11,4.93671 -11,11c0,6.06329 4.93671,11 11,11c6.06329,0 11,-4.93671 11,-11c0,-6.06329 -4.93671,-11 -11,-11zM25,16c4.98241,0 9,4.01759 9,9c0,4.98241 -4.01759,9 -9,9c-4.98241,0 -9,-4.01759 -9,-9c0,-4.98241 4.01759,-9 9,-9z"></path></g></g>
                </svg>
            </a>
        </div>
        <div class="flexContainerLogo">
            <svg class="footer_logoBizSvg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 76.43 39.84"><g id="Capa_2" data-name="Capa 2"><g id="Capa_1-2" data-name="Capa 1"><path d="M72.55,19.72c-.51-.49-.49-.79-.1-1.27a9.71,9.71,0,0,0,1.65-3A11.54,11.54,0,0,0,63.13,0Q41.55,0,19.94,0a19.89,19.89,0,0,0-.68,39.76c7.53.15,15.07,0,22.61,0v0H64.24a12.61,12.61,0,0,0,3.91-.44C76.92,36.52,79.26,26.12,72.55,19.72ZM65.42,33.37a13.3,13.3,0,0,1-1.55.08H20.32A13.18,13.18,0,0,1,8.84,27.68,12.85,12.85,0,0,1,7.69,14.15C10,9.38,13.91,6.54,19.22,6.49c14.72-.15,29.44-.07,44.16,0a5.05,5.05,0,0,1,5,5.09,5.13,5.13,0,0,1-4.91,5.18c-.4,0-.8,0-1.2,0l-38.53,0A3.21,3.21,0,0,0,20.39,20a3.26,3.26,0,0,0,3.42,3.23q20.52,0,41-.06a5.11,5.11,0,0,1,.57,10.2Z"/></g></g></svg>
            <div class="footer_copyrightContent">
                Copyright​ © 2024 BizLab SAS 
            </div>
        </div>
    </footer> 
    <script src="scripts\app1.js"></script> 
</body>
</html>