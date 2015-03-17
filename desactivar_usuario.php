<?php
session_start();
include('conexion.php');
include('funciones.php');
include('Classes/dm.php');

if (isset($_SESSION['s_entidad'])){
    $entidad=$_SESSION['s_entidad'];
    $rol = $_SESSION["s_rol"];
    $action="nada";
}

if (isset($_GET["id"])){
	$id_cliente= getParam($_GET["id"],"-1");
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
}

if ($action == "deactivate") {
    $id_usuario = sqlValue($_POST["ario"],"int");
    $sql_usuarios = "update usuarios set activo=0 WHERE id_usuario=".$id_usuario;
    $que_usuario=@mysql_query($sql_usuarios) or die (mysql_error());
}

$sql="SELECT a.id_usuario, a.cedula, a.nombre, a.apellidos, a.id_entidad, a.id_sube, b.activo 
      FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.id_cliente=".$id_cliente;
$queUsu = @mysql_query($sql) or die (mysql_error());
$rsUsu = mysql_fetch_assoc($queUsu);

$sql_ent="select entidad from entidad where id_entidad=".$rsUsu['id_entidad'];
$queEnt=@mysql_query($sql_ent) or die (mysql_error());
$rsEnt=mysql_fetch_assoc($queEnt);
$sql_sube="select sube from subentidad where id_sube=".$rsUsu['id_sube'];
$quesEnt=@mysql_query($sql_sube) or die (mysql_error());
$rssEnt=mysql_fetch_assoc($quesEnt);
$action="deactivate";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : :  Desactivar Usuario : :</title>
    <script src="../js/modernizr.js"></script>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
    <dav>
       	<ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
             <li><a><?php echo "Desactivar Usuario"; ?></a></li>
	</ul>
    </dav>
<form method="post" id="frmUsuario" action="/du/">
    <h3>Seguro desea inactivar este usuario:</h3>
    <label for="cedula">Cedula : </label>
    <input readonly="readonly" type="text" id="cedula" name="cedula" value="<?php echo $rsUsu['cedula'];?>"/>
    <br />
    <label for="nombre">Nombres : </label>
    <input readonly="readonly"type="text" id="nombre" name="nombre" value="<?php echo $rsUsu['nombre'];?>"/>
    <br />
    <label for="apellidos">Apellidos : </label>
    <input readonly="readonly"type="text" id="apellidos" name="apellidos" value="<?php echo $rsUsu['apellidos'];?>"/>
    <br />
    <label for="estado">Estado : </label>
    <?php if ($rsUsu['activo']==1){?>
        <input disabled type="checkbox" name="estado" checked="checked"></td> 
    <?php }else{ ?>
	<input disabled type="checkbox" name="estado"></td>
    <?php } ?>
    <br />
    <label for="bts">&nbsp;</label>
    <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
    <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
    <input type="hidden" name="ario" value="<?php echo $rsUsu["id_usuario"];?>"/>
    <button type="submit">Aceptar</button>
    <?php if ($rol==1){?>
	<button type="button" onClick="location.href = '/lua/'">Cancelar</button>			 
    <?php }else{ ?>
       	<button type="button" onClick="location.href = '/lu/'">Cancelar</button>
    <?php } ?>
</form>
</body>
</html>

<?php
if (isset($que_usuario)){
    if ($rol==1){
        echo "<script> swal({title: 'Exito', text: 'El usuario ha sido desactivado', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Exito', text: 'El usuario ha sido desactivado', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
    }
}

if ($rsUsu['activo']==0){
    if ($rol==1){
        echo "<script> swal({title: 'Información', text: 'El usuario se encuentra inactivo', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Información', text: 'El usuario se encuentra inactivo', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
    }
}
?>

<script>
function redireccionar1(){
    window.location.href='/lua/';
}

function redireccionar2(){
    window.location.href='/lu/';
}
</script>