<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
</head>
<body>
<?php
include('conexion.php');
$nuevopass=md5(md5($_POST['pswd']).sha1($_POST['pswd']));
if (isset($_POST['cliente'])){
    $cliente=$_POST['cliente'];
    $activar_cliente=@mysql_query("update usuarios set activo='1', clave='".$nuevopass."' where cliente='".$cliente."'");
    echo "<script> swal({title: 'Error', text: 'Cambio de contraseña exitoso', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
}elseif (isset($_POST['cedula'])){
    $activar_cedula=@mysql_query("update usuarios set activo='1', clave='".$nuevopass."' where cedula='".$cedula."'"); 
    echo "<script> swal({title: 'Error', text: 'Cambio de contraseña exitoso', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
}elseif ((isset($_POST['email'])) and (isset($_POST['cedulae']))){
    //Recibir variables que llegan por post
    $email=$_POST['email'];$cedulae=$_POST['cedulae'];$entidade=$_POST['entidad'];
    //Determinar id de usuario
    $sql_usuario="select a.id_usuario, b.id_rol from clientes a inner join usuarios b on a.id_usuario=b.id_usuario 
		  where a.email='".$email."' and a.cedula=".$cedulae." and a.id_entidad=".$entidade;
    $usuario=@mysql_query($sql_usuario) or die (mysql_error());
    $usuario_log=mysql_fetch_assoc($usuario);
    $id_usuario=$usuario_log["id_usuario"];
    //verificar si la contraseña fue cambiada
    $sqlpass_usuario="select clave from usuarios where id_usuario=".$id_usuario;
    $pass_usuario=@mysql_query($sqlpass_usuario) or die (mysql_error());
    $rspass=mysql_fetch_assoc($pass_usuario);
    if ($rspass['clave']==$nuevopass){
        echo "<script> swal({title: 'Información', text: 'Debe establecer una contraseña diferente a la anterior', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
    }else{
        session_start();
        $_SESSION = array();
        $activar_email=@mysql_query("update usuarios set activo='1', clave='".$nuevopass."' where usuario='".$email."' and id_usuario=".$id_usuario);
        if ($usuario_log['id_rol']==3){
            include ('conexionpuntos.php');
            $nuevopass=md5('I3mLxuxZo1zSFjdCAcWhubRk9Gu3ZbTxtTdnrPSxX7zJYRu2x0IuZZDW'.$_POST['pswd']);
            $activar="update ps_customer set active='1', passwd='".$nuevopass."' where email='".$email."' and id_customer=".$id_usuario;
            $activar_email=@mysql_query($activar) or die (mysql_error());
            include ('conexion.php');
            echo "<script> swal({title: 'Exito', text: 'Cambio de contraseña exitoso', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
        }else{
            echo "<script> swal({title: 'Exito', text: 'Cambio de contraseña exitoso', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar3()', 2000)</script>";
        }
    }
}
?>
</body>
</html>
    
<script>
function redireccionar(){
  window.location.href="/in/";
}

function redireccionar1(){
  window.location.href="/pcp/";
}

function redireccionar2(){
  window.location.href="http://puntos.com/";
}

function redireccionar3(){
  window.location.href="/ex/";
}
</script>