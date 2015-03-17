<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <link rel="stylesheet" href="../css/nav.css">
</head>
<body>
<?php
session_start();
if( md5( $_POST['code'] ) != $_SESSION['key'] ) {
    echo "<script> swal({title: 'Error', text: 'Texto de imagen errado', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
}else{
    include('Classes/nuevo_usuario.php');
    if (isset($_POST['btn_enviar'])){
	if (($_POST['cliente']!='') && ($_POST['clave']!='')){
            $datosUsuario = new activaUsuario($_POST['cliente'],$_POST['cedula'],$_POST['email'],$_POST['clave']);
            $datosUsuario->activar_cliente();
	}elseif (($_POST['cedula']!='') && ($_POST['clave']!='')){
            $datosUsuario = new activaUsuario($_POST['cliente'],$_POST['cedula'],$_POST['email'],$_POST['clave']);
            $datosUsuario->activar_cedula();
	}elseif (($_POST['email']!='') && ($_POST['clave']!='')){
            $datosUsuario = new activaUsuario($_POST['cliente'],$_POST['cedula'],$_POST['email'],$_POST['clave']);
            $datosUsuario->activar_email();
	}
    }else{
    	$_SESSION["ex"] = 1;
        echo "<script> swal({title: 'Error', text: 'Error, no es posible procesar los datos en este momento', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
	}
}
?>
</body>

<script>
function redireccionar(){
  window.location.href="/in/";
}
</script>