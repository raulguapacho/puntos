<?php
session_start();
include('conexion.php');
include('funciones.php');
include('Classes/dm.php');

if (isset($_SESSION['s_rol'])){
    $rol = $_SESSION["s_rol"];
    $action="nada";
}

if (isset($_GET['id'])){
    $id_cliente = getParam($_GET["id"],"-1");
    
    $sql="SELECT a.id_cliente, a.cedula, a.nombre, b.activo FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.id_cliente=".$id_cliente;
    $queUsu= mysql_query($sql) or die (mysql_error());
    $rsUsu = mysql_fetch_assoc($queUsu);
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
}

if ($action == "delete") {
    $id_cliente = sqlValue($_POST["encli"],"int");
    $nombre = sqlValue($_POST["bnom"],"text");
    
    $sql="SELECT a.id_cliente, a.cedula, a.nombre, b.activo FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.id_cliente=".$id_cliente;
    $queUsu= mysql_query($sql) or die (mysql_error());
    $rsUsu = mysql_fetch_assoc($queUsu);
    
    $sql_cliente="select id_usuario from clientes where id_cliente=".$id_cliente;
    $cliente_inact=@mysql_query($sql_cliente) or die (mysql_error());
    $reg_cliente=mysql_fetch_array($cliente_inact);
    $id_usuario=$reg_cliente['id_usuario'];
    $delete_cliente = "delete from clientes where id_cliente=".$id_cliente;
    $que_cliente=@mysql_query($delete_cliente) or die (mysql_error());
    $delete_usuario = "delete from usuarios WHERE id_usuario=".$id_usuario;
    $que_usuario=@mysql_query($delete_usuario) or die (mysql_error());
}

$action="delete";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : :  Elimimar Administrador : : </title>
    <script src="../js/modernizr.js"></script>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
    <dav>
       	<ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Eliminar Administrador"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmUsuario" action="/da/">
        <h2>Seguro desea eliminar este administrador:</h2>
        <label for="cedula">Cedula : </label>
	<input readonly="readonly" type="text" name="duce" value="<?php echo $rsUsu['cedula'];?>"/>
        <br />
        <label for="nombre">Nombre : </label>
        <input readonly="readonly"type="text" name="bnom" value="<?php echo $rsUsu['nombre'];?>"/>
        <br />
        <label for="estado">Estado : </label>
        <?php if ($rsUsu['activo']==1){?>
            <input disabled type="checkbox" name="taes" checked="checked"/></td> 
        <?php }else{ ?>
            <input disabled type="checkbox" name="taes"/></td>
        <?php } ?>
        <br />
	<label for="bts">&nbsp;</label>
	<input type="hidden" name="encli" value="<?php echo $rsUsu['id_cliente'];?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
	<input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
	<button type="submit">Aceptar</button>
        <button type="button" onClick="location.href ='/la/'">Cancelar</button>
    </form>
</body>
</html>

<?php
if ((isset($que_cliente)) and (isset($que_usuario))){
    echo "<script> swal({title: 'Exito', text: 'El Administrador ha sido eliminado', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
}
?>

<script>
function redireccionar(){
  window.location.href="/la/";
}
</script>