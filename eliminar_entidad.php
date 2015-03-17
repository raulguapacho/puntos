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
	$id_entidad = getParam($_GET["id"],"-1");
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
}

if ($action == "delete"){
    $id_entidad = sqlValue($_POST['di'],"int");
    $sql_cliente="select id_cliente from clientes where id_entidad=".$id_entidad;
    $del_cliente=@mysql_query($sql_cliente) or die (mysql_error());
    $sql_sube="select id_sube from subentidad where id_entidad=".$id_entidad;
    $del_sube=@mysql_query($sql_sube) or die (mysql_error());
}

$sql="SELECT * FROM entidad WHERE id_entidad = ".$id_entidad;
$queEnt= mysql_query($sql) or die (mysql_error());
$rsEnt = mysql_fetch_assoc($queEnt);
$action="delete";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Eliminar Entidad : : </title>
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
            <li><a><?php echo "Eliminar Entidad"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmEntidad" action="/de/">
        <h2>¿Seguro desea eliminar esta entidad?</h2>
        <label for="Entidad">Entidad</label>
	<input type="text" readonly="readonly" name="tien" value="<?php echo $rsEnt["entidad"]; ?>"/>
        <br />
        <label for="bts">&nbsp;</label>
	<input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
	<input type="hidden" name="di" value="<?php echo $rsEnt["id_entidad"]; ?>" />
	<button type="submit">Aceptar</button>
        <button type="button" onClick="location.href = '/le/'">Cancelar</button>
    </form>
</body>
</html>

<?php
if (isset($del_cliente)){
    if (mysql_num_rows($del_cliente)>0){
        echo "<script> swal({title: 'Información', text: 'La entidad no puede ser eliminada, \\n tiene clientes asociados', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }elseif(mysql_num_rows($del_sube)>0){
	echo "<script> swal({title: 'Información', text: 'La entidad no puede ser eliminada, \\n tiene subentidades asociadas', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }else{		
	$sql="delete from entidad WHERE id_entidad=".$id_entidad;
	@mysql_query($sql) or die (mysql_error());
        echo "<script> swal({title: 'Exito', text: 'La entidad ha sido eliminada', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }
}
?>

<script>
function redireccionar(){
  window.location.href="/le/";
}
</script>