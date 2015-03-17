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

if (isset($_GET['id'])){
    $id_sube = getParam($_GET['id'],"-1");
}

if (isset($_POST['lor'])){
    $id_sube = getParam($_POST['di'],"int");
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
}

if ($action == "delete"){
    $id_sube = sqlValue($_POST['di'],"int");
    $sql_cliente="select id_cliente from clientes where id_sube=".$id_sube;
    $del_cliente=@mysql_query($sql_cliente) or die (mysql_error());
}

$sql="SELECT * FROM subentidad WHERE id_sube = ".$id_sube;
$quesEnt= mysql_query($sql) or die (mysql_error());
$rssEnt = mysql_fetch_assoc($quesEnt);
$query="SELECT id_entidad, entidad FROM entidad where id_entidad=".$entidad;
$queEnt=@mysql_query($query) or die (mysql_error());
$rstit=mysql_fetch_assoc($queEnt);
$action="delete";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Eliminar Subentidad : : </title>
    <script src="../js/modernizr.js"></script>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
<?php if ($entidad>0){?>
    <dav>
       	<ul>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a>Eliminar Subentidad</a></li>
	</ul>
    </dav>
<?php }else{ ?>
	<dav>
       	<ul>
            <li><a>Administración de Tienda</a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a>Eliminar subentidad</a></li>
	</ul>
	</dav>
<?php } ?>
    <form method="post" id="frmsEntidad" action="/ds/">
        <h3>Seguro desea eliminar esta subentidad?</h3>
        <label for="subentidad">Subentidad</label>
	<input type="text" readonly="readonly" name="besu" value="<?php echo $rssEnt["sube"]; ?>" />
        <br />
        <label for="bts">&nbsp;</label>
	<button type="submit">Aceptar</button>
        <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
        <input type="hidden" name="di" value="<?php echo $rssEnt["id_sube"]; ?>" />
	<?php if ($rol==1){?>
            <button type="button" onClick="location.href = '/lsa/'">Cancelar</button>			 
	<?php }else{ ?>
            <button type="button" onClick="location.href = '/ls/'">Cancelar</button>
	<?php } ?>
	</form>
    </body>
</html>

<?php
if (isset($del_cliente)){
    if (mysql_num_rows($del_cliente)>0){
	if ($rol==1){
            echo "<script> swal({title: 'Error', text: 'La subentidad no puede ser eliminada, \\n tiene clientes asociados', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
	}else{
            echo "<script> swal({title: 'Error', text: 'La subentidad no puede ser eliminada, \\n tiene clientes asociados', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
	}
    }else{
        $sql="delete from subentidad WHERE id_sube=".$id_sube;
	@mysql_query($sql) or die (mysql_error());
	if ($rol==1){
            echo "<script> swal({title: 'Exito', text: 'La Subentidad ha sido eliminada', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
        }else{
            echo "<script> swal({title: 'Exito', text: 'La Subentidad ha sido eliminada', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
	}
    }
}
?>

<script>
function redireccionar1(){
  window.location.href="/lsa/";
}

function redireccionar2(){
  window.location.href="/ls/";
}
</script>