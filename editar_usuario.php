<?php
session_start();
include('conexion.php');
include('funciones.php');
include('Classes/dm.php');

if (isset($_SESSION['s_entidad'])){
    $entidad=$_SESSION['s_entidad'];
    $rol = $_SESSION["s_rol"];
    $action="nada";
    $action1="nada";
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
    $id_cliente = sqlValue($_POST["diencli"],"int");
    $cedula = sqlValue($_POST["duce"],"text");
    $cliente = sqlValue($_POST["encli"],"text");
    $entidad = sqlValue($_POST["ditien"],"int");
    $sube = sqlValue($_POST["subentidad"],"int");
    $nombre = sqlValue($_POST["bnom"],"text");
    $apellidos = sqlValue($_POST["elap"],"text");
    $direccion = sqlValue($_POST["redi"],"text");
    $telefono = sqlValue($_POST["lete"],"int");
    $email = sqlValue($_POST["iema"],"text");
    if (isset($_POST["dota"])){
        if ($_POST["dota"]=='on'){
            $estado=1;
        }else{
            $estado=0;
        }
    }else{
        $estado=0;
    }
    
    $action1="termino";
}
$sql="SELECT * FROM entidad where id_entidad=".$entidad;
$queEnt=@mysql_query($sql) or die (mysql_error());
$rsEnt=@mysql_fetch_assoc($queEnt);

if ($entidad==0){
$sql="SELECT a.id_cliente, a.cedula, a.cliente, c.id_entidad, c.entidad, d.id_sube, d.sube, a.nombre,a.apellidos, a.direccion, a.telefono, b.usuario, b.activo 
	FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
	inner join entidad c on a.id_entidad=c.id_entidad
	inner join subentidad d on a.id_sube=d.id_sube
	where a.id_cliente=".$id_cliente." and b.id_rol='3'";
}else{
$sql="SELECT a.id_cliente, a.cedula, a.cliente, c.id_entidad, c.entidad, d.id_sube, d.sube, a.nombre, a.apellidos, a.direccion, a.telefono, b.usuario, b.activo 
	FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
	inner join entidad c on a.id_entidad=c.id_entidad
	inner join subentidad d on a.id_sube=d.id_sube
	where a.id_cliente=".$id_cliente." and a.id_entidad=".$entidad." and b.id_rol='3'";
$sql_sube="SELECT * FROM subentidad where id_entidad=".$entidad;
}
$queUsu= mysql_query($sql);
$rsUsu = mysql_fetch_assoc($queUsu);
$id_entidad=$rsUsu['id_entidad'];
$sql_sube="SELECT * FROM subentidad where id_entidad=".$id_entidad;
$quesEnt=@mysql_query($sql_sube) or die (mysql_error());

$action="edit";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Editar Usuario : : </title>
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
            <li><a><?php echo "Editar Usuario"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmUsuario" action="/eu/">
        <label for="cedula">Cedula : </label>
        <input readonly="readonly" type="text" name="duce" value="<?php echo $rsUsu['cedula'];?>" required/>
        <br />
        <label for="cliente">Cliente : </label>
        <input type="text" name="encli" value="<?php echo $rsUsu['cliente'];?>" placeholder="Ingrese numero de cliente"/>
        <br />
        <label for="entidad">Entidad : </label>
        <input readonly="readonly" type="text" name="tien" value="<?php echo $rsUsu['entidad'];?>"/>
        <br />
        <label for="subentidad">Subentidad : </label>
        <select name="subentidad" title="Despliegue para seleccionar una nueva subentidad" required>
        <option value="<?php echo $rsUsu['id_sube'];?>"><?php echo $rsUsu['sube'];?></option>
        <?php
            while ($rssEnt=@mysql_fetch_assoc($quesEnt)){echo "<option value='".$rssEnt['id_sube']."'>".$rssEnt['sube']."</option>";}
        ?>
        </select>
        <br />
        <input type="hidden"/>
        <br /> 
        <label for="nombre">Nombres : </label>
        <input type="text" name="bnom" value="<?php echo $rsUsu['nombre'];?>" required/>
        <br />
        <label for="apellidos">Apellidos : </label>
        <input type="text" name="elap" value="<?php echo $rsUsu['apellidos'];?>" required/>
        <br />
        <label for="direccion">Direccion : </label>
        <input type="text" name="redi" value="<?php echo $rsUsu['direccion'];?>" />
        <br />
        <label for="telefono">Telefono : </label>
        <input type="text" name="lete" value="<?php echo $rsUsu['telefono'];?>"/>
        <br />
        <label for="usuario">Email : </label>
        <input type="text" name="iema" value="<?php echo $rsUsu['usuario'];?>" required/>
        <br />
        <label for="estado">Estado : </label>
        <?php if ($rsUsu['activo']==1) { ?>
            <input type="checkbox" name="dota" checked="checked"/>
        <?php } else { ?>
            <input type="checkbox" name="dota"/>
        <?php } ?>
        <br />
        <label for="bts">&nbsp;</label>
        <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
        <input type="hidden" name="diencli" value="<?php echo $rsUsu["id_cliente"];?>"/>
        <input type="hidden" name="ditien" value="<?php echo $rsUsu["id_entidad"];?>"/>
        <button type="submit">Guardar</button>
        <button type="reset">Limpiar</button>
        <?php if ($rol==1){?>
            <button type="button" onClick="location.href = '/lua/'">Cancelar</button>			 
        <?php }else{ ?>
            <button type="button" onClick="location.href = '/lu/'">Cancelar</button>
        <?php } ?>
    </form>
</body>
</html>

<?php  
//actualiza el cliente
if ($action1 == "termino"){
    $sql_clientes = "UPDATE clientes SET cliente=".$cliente.",id_entidad=".$entidad.",id_sube=".$sube.",nombre=".$nombre.",apellidos=".$apellidos.",direccion=".$direccion.",telefono=".$telefono.",email=".$email.",actualizado='".$hoy."' WHERE id_cliente=".$id_cliente;
    @mysql_query($sql_clientes) or die (mysql_error());
    //Busca el id_usuario en la tabla clientes
    $cliente_act="select id_usuario from clientes where id_cliente=".$id_cliente;
    $reg_cliente=@mysql_query($cliente_act) or die (mysql_error());
    $id_usucli=mysql_fetch_array($reg_cliente);
    $id_usuario=$id_usucli['id_usuario'];
    //Actualiza la tabla usuarios de acuerdo al id_usuario
    $sql_usuarios = "UPDATE usuarios SET cliente=".$cliente.",usuario=".$email.",activo='".$estado."',actualizado='".$hoy."' WHERE id_usuario=".$id_usuario;
    @mysql_query($sql_usuarios) or die (mysql_error());
    if ($rol==1){
        echo "<script> swal({title: 'Exito', text: 'Edición del usuario exitosa', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Exito', text: 'Edición del usuario exitosa', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
    }
}
?>

<script>
function redireccionar1(){
  window.location.href="/lua/";
}

function redireccionar2(){
  window.location.href="/lu/";
}
</script>