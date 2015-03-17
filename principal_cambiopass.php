<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8">
    <title> : : Cambio contraseña : :</title>
    <script src="../js/modernizr.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css"/>
    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <script src="../js/script.js"></script>
    <link rel="stylesheet" href="../css/nav.css"/>
		<!--[if IE]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
        <!--// Si se utiliza ie7, se cargan estos archivos css -->        
        <!--[if IE 7]>
        		<link rel="stylesheet" href="css/ie.css">
            	<link rel="stylesheet" href="css/ie7.css">
        <![endif]-->
    <link href="../css/validacion.css" rel="stylesheet"/>
    <link href="../css/estilo.css" rel="stylesheet"/>
</head>
<?php
session_start();
include ('conexion.php');
if ((isset($_SESSION['s_email'])) and (isset($_SESSION['s_cedulae']))){
    $email=$_SESSION['s_email'];
    $cedulae=$_SESSION["s_cedulae"] ;
    if (isset($_SESSION['s_entidad'])){
    	$entidad=$_SESSION["s_entidad"] ;
    }
    if (isset($_POST['entidad'])){
    	$entidad=$_POST["entidad"] ;
    }
}
$sql_usuario_act="select b.clave from clientes a inner join usuarios b on a.id_usuario=b.id_usuario 
		   where a.cedula=".$cedulae." and a.email='".$email."' and a.id_entidad=".$entidad;
$usuario_act=@mysql_query($sql_usuario_act) or die (mysql_error());
$cla_usuario=mysql_fetch_assoc($usuario_act);
$vecla=$cla_usuario['clave'];
if (isset($_SESSION['s_vecla'])){
    if ($vecla!=$_SESSION['s_vecla']){
    	$_SESSION = array();
        echo "<script> swal({title: 'Error', text: 'Contraseña Incorrecta para esta Entidad - Subentidad', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }
}
?>
<body class="no-js">
    <script>
	var el = document.getElementsByTagName("body")[0];
	el.className = "";
    </script>
    <noscript>
		<!--[if IE]>
        <link rel="stylesheet" href="css/ie.css">
        <![endif]-->
    </noscript>
    <nav id="topNav">
        <ul>
            <li><a>Bienvenid@ <?php echo $_SESSION['s_usuario'] ?></a></li>
	</ul>
    </nav>		
    <!--<div id="img">
    	<a><img src="../images/aquiva.png" alt="puntos" position="relative" width="248" height="88"/></a>
    </div>-->
    <div id="contenedor">
	<h2>Cambio de contraseña</h2>
	<h2>________________________________</h2>
	<form action="/cp/" method="post">
            <ul>
                <div class="input-group">
                    <input type="password" class="form-control" id="pswd" name="pswd" placeholder="Nueva Contraseña" required/>
                    <span id="show-hide-passwd" action="hide" class="input-group-addon glyphicon glyphicon glyphicon-eye-open"></span>
                </div>
                <div class="input-group">
                    <input type="password" class="form-control" id="confirma" placeholder="Confirmar Contraseña" required disabled/>
                    <span id="show-hide-passwd2" action="hide" class="input-group-addon glyphicon glyphicon glyphicon-eye-open"></span>
                </div>
                <h2>________________________________</h2>
            <?php if (isset($_SESSION['s_cliente'])){
                $cliente=$_SESSION['s_cliente'];?>
                <input type="hidden" name="cliente" value=<?php echo $cliente ?> />
            <?php }elseif (isset($_SESSION['s_cedula'])){
                $cedula=$_SESSION['s_cedula'];?>
                <input type="hidden" name="cedula" value=<?php echo $cedula ?> />
            <?php }elseif ((isset($_SESSION['s_email'])) and (isset($_SESSION['s_cedulae']))){
                $email=$_SESSION['s_email'];
                $cedulae=$_SESSION["s_cedulae"] ;
                if ((isset($_SESSION['s_entidad'])) and (isset($_SESSION['s_sube']))){
                    $entidad=$_SESSION["s_entidad"] ;
                    $sube=$_SESSION["s_sube"];
                }
                if ((isset($_POST['entidad'])) and (isset($_POST['sube']))){
                    $entidad=$_POST["entidad"] ;
                    $sube=$_POST["sube"];
                }
            ?>
            <input type="hidden" name="email" value=<?php echo $email ?> />
            <input type="hidden" name="cedulae" value=<?php echo $cedulae ?> />
            <input type="hidden" name="entidad" value=<?php echo $entidad ?> />
            <?php } ?>
            </ul>
            <button id="aceptar" type="submit" disabled>Aceptar</button>
            <button type="button" onClick="location.href='/in/'">Cancelar</button>
            
	</form>
        <div id="pswd_info">
            <h3>La contraseña debe contener:</h3>
            <ul>
		<li id="letter" class="invalid">Al menos <strong>un caracter</strong></li>
		<li id="capital" class="invalid">Al menos <strong>una letra mayuscula</strong></li>
		<li id="number" class="invalid">Al menos <strong>un numeró</strong></li>
		<li id="length" class="invalid">Tener por lo <strong>menos 8 caracteres</strong></li>
                <li id="especial" class="invalid">Al menos <strong>un caracter especial</strong></li>   
            </ul>
	</div>
    </div>
</body>
</html>
    
<script>
function redireccionar(){
  window.location.href="/ex/";
}
</script>