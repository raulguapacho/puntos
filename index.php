<?php
session_start();
if (isset($_SESSION['ex'])){
    $i=$_SESSION['ex'];
}else{
    $_SESSION['ex']=1;
    $i=1;
    $ex="/ex/";
    header("Location:".$ex);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> : : Ingreso de usuarios : : </title>
<?php if ($i==0){?>
    <link rel="stylesheet" href="/css/sweet-alert.css"/>
<?php }else{ ?>
    <link rel="stylesheet" href="../css/estilo.css">
<?php } ?>
</head>
<body>
    <div id="img">
        <a><img src="../images/aquiva.png" alt="puntos" width="248" height="88"/></a>
    </div>
    <div id="contenedor">
	<h3>Administración del sitio</h3>
	<form action="/vu/" method="post">
            <div id="cliente" class="caja" style="display:none">
		<h2><input type="text" name="cliente" placeholder="Ingrese Id : "/></h2>
            </div>
            <div id="cedula" class="caja" style="display:none">
		<h2><input type="text" name="cedula" placeholder="Ingrese Cedula : "/></h2>
            </div>
            <div id="email" class="caja">
		<h2><input type="email" name="email" placeholder="Ingrese Email : " required/></h2>
            </div>
            <div id="resto" class="caja">
		<h2><input type="password" name="clave" placeholder="Contraseña : " required/></h2>
		<h2><img src="../captcha/captcha.php" border="0" /><h2>
		<h2><input type="text" name="code" placeholder="Ingrese Captcha : " width="25" required/></h2>
                <h2><button type="submit" name="btn_enviar">Ingresar </button></h2>
		<h2>________________________________</h2>
            </div>
	</form>		
	<p>¿Aún no has activado tu cuenta? <a href="/in/">clic aquí</a></p>
    </div>
</body>
</html>