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
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "edit") {
    $id_cliente = sqlValue($_POST["encli"],"int");
    $cedula = sqlValue($_POST["duce"],"text");
    $entidad = sqlValue($_POST["tien"],"int");
    $nombre = sqlValue($_POST["bnom"],"text");
    $direccion = sqlValue($_POST["redi"],"text");
    $telefono = sqlValue($_POST["lete"],"int");
    $email = sqlValue($_POST["aime"],"text");
    if (isset($_POST['taes'])){$estado=1;}
    else{$estado=0;}
	
    $sql_clientes="UPDATE clientes SET id_entidad=".$entidad.",nombre=".$nombre.",direccion=".$direccion.",telefono=".$telefono.",email=".$email.",actualizado='".$hoy."' WHERE id_cliente=".$id_cliente;
    $que_cliente=@mysql_query($sql_clientes) or die (mysql_error());
    $cliente_act="select id_usuario from clientes where id_cliente=".$id_cliente;
    $reg_cliente=@mysql_query($cliente_act) or die (mysql_error());
    $id_usucli=mysql_fetch_array($reg_cliente);
    $id_usuario=$id_usucli['id_usuario'];
    $sql_usuarios = "UPDATE usuarios SET usuario=".$email.",activo=".$estado.",actualizado='".$hoy."' WHERE id_usuario=".$id_usuario;
    $que_usuario=@mysql_query($sql_usuarios) or die (mysql_error());
}
	
$sql="SELECT * FROM entidad";
$queEnt=@mysql_query($sql) or die (mysql_error());

$sql="SELECT a.id_cliente, a.cedula, a.id_entidad, c.entidad, a.nombre, a.direccion, a.telefono, b.usuario, b.activo 
      FROM clientes a inner join usuarios b on a.cedula=b.cedula
      inner join entidad c on a.id_entidad=c.id_entidad
      where a.id_cliente=".$id_cliente." and b.id_rol='2'";
$queUsu= mysql_query($sql);
$rsUsu = mysql_fetch_assoc($queUsu);
$action="edit";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Editar Administrador : : </title>
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
            <li><a><?php echo "Editar Administrador"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmUsuario" action="/ea/">
        <label for="cedula">Cedula : </label>
        <input readonly="readonly" type="text" id="cedula" placeholder="Identificación:" name="duce" value="<?php echo $rsUsu['cedula'];?>"/>
        <br />
        <label for="Entidad">Entidad</label>
        <input readonly="readonly" type="text" placeholder="Entidad" value="<?php echo $rsUsu['entidad'];?>"/>
        <br />
        <label for="nombre">Nombre : </label>
        <input type="text" id="nombre" name="bnom" placeholder="Nombres y apellidos" value="<?php echo $rsUsu['nombre'];?>" required/>
        <br />
        <label for="direccion">Direccion : </label>
        <input type="text" id="direccion" placeholder="Dirección" name="redi" value="<?php echo $rsUsu['direccion'];?>"/>
        <br />
        <label for="telefono">Telefono : </label>
        <input type="text" id="telefono" placeholder="Teléfono" name="lete" value="<?php echo $rsUsu['telefono'];?>"/>
        <br />
        <label for="usuario">Email : </label>
        <input type="text" id="email" placeholder="Email" name="aime" value="<?php echo $rsUsu['usuario'];?>" required/>
        <br />
        <label for="estado">Estado : </label>
        <?php if ($rsUsu['activo']==1){?>
            <input align="left" name="taes" type="checkbox" title="Check para activar, uncheck para inactivar" checked="CHECKED">
        <?php }else{ ?>
            <input align="left" type="checkbox" name="taes" title="Check para activar, uncheck para inactivar">
        <?php } ?>
        <br />
        <label for="bts">&nbsp;</label>
        <input type="hidden" name="encli" value="<?php echo $rsUsu['id_cliente'];?>"/>
        <input type="hidden" name="tien" value="<?php echo $rsUsu['id_entidad'];?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
        <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <button type="submit">Guardar</button>
        <button type="button" onClick="location.href = '/la/'">Cancelar</button>
    </form>
</body>
</html>

<?php
if ((isset($que_cliente)) and (isset($que_usuario))) {
    echo "<script> swal({title: 'Exito', text: 'Edición del administrador exitosa', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
}
?>

<script>
function redireccionar(){
  window.location.href="/la/";
}
</script>