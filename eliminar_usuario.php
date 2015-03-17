<?php
session_start();
include('conexion.php');
include('funciones.php');
include('Classes/dm.php');

if (isset($_SESSION['s_rol'])){
    $rol = $_SESSION["s_rol"];
    $action="nada";
}

if (isset($_GET["id"])){
    $id_cliente= getParam($_GET["id"],"-1");
    
    $sql="SELECT a.id_cliente, a.id_usuario, a.id_entidad, a.id_sube, a.cedula, a.nombre, a.apellidos, b.activo 
          FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.id_cliente=".$id_cliente;
    $queUsu = @mysql_query($sql) or die (mysql_error());
    $rsUsu = mysql_fetch_assoc($queUsu);
    $sql_ent="select entidad from entidad where id_entidad=".$rsUsu['id_entidad'];
    $queEnt=@mysql_query($sql_ent) or die (mysql_error());
    $rsEnt=mysql_fetch_assoc($queEnt);
    $sql_sube="select sube from subentidad where id_sube=".$rsUsu['id_sube'];
    $quesEnt=@mysql_query($sql_sube) or die (mysql_error());
    $rssEnt=mysql_fetch_assoc($quesEnt);
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
}

if ($action == "delete") {
    $id_usuario = sqlValue($_POST["ausu"],"int");
    $id_cliente = sqlValue($_POST["diencli"],"int");
    
    $sql="SELECT a.id_cliente, a.id_usuario, a.id_entidad, a.id_sube, a.cedula, a.nombre, a.apellidos, b.activo 
          FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.id_cliente=".$id_cliente;
    $queUsu = @mysql_query($sql) or die (mysql_error());
    $rsUsu = mysql_fetch_assoc($queUsu);
    $sql_ent="select entidad from entidad where id_entidad=".$rsUsu['id_entidad'];
    $queEnt=@mysql_query($sql_ent) or die (mysql_error());
    $rsEnt=mysql_fetch_assoc($queEnt);
    $sql_sube="select sube from subentidad where id_sube=".$rsUsu['id_sube'];
    $quesEnt=@mysql_query($sql_sube) or die (mysql_error());
    $rssEnt=mysql_fetch_assoc($quesEnt);
    
    $sql_clientes="delete from clientes where id_cliente=".$id_cliente;
    $que_cliente=@mysql_query($sql_clientes) or die (mysql_error());
    $sql_usuarios = "delete from usuarios WHERE id_usuario=".$id_usuario;
    $que_usuario=@mysql_query($sql_usuarios) or die (mysql_error());
}

$action="delete";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : :  Inactivar Usuario</title>
    <script src="../js/modernizr.js"></script>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link  rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
    <dav>
       	<ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Eliminar Usuario"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmUsuario" action="/du/">
    	<h3>Seguro desea eliminar este usuario?</h3>
        <label for="cedula">Cedula : </label>
	<input readonly="readonly" type="text" name="cedula" value="<?php echo $rsUsu['cedula'];?>"/>
        <br />
        <label for="nombre">Nombre : </label>
        <input readonly="readonly"type="text" name="nombre" value="<?php echo $rsUsu['nombre'];?>"/>
        <br />
	<label for="apellidos">Apellidos : </label>
        <input readonly="readonly"type="text" name="apellidos" value="<?php echo $rsUsu['apellidos'];?>"/>
        <br />
	<label for="bts">&nbsp;</label>
       	<input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
        <input type="hidden" name="ausu" value="<?php echo $rsUsu["id_usuario"];?>"/>
	<input type="hidden" name="diencli" value="<?php echo $rsUsu["id_cliente"];?>"/>
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
if ((isset($que_cliente)) and (isset($que_usuario))){
    if ($rol==1){
        echo "<script> swal({title: 'Exito', text: 'El usuario ha sido eliminado', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Exito', text: 'El usuario ha sido eliminado', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
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