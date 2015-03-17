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
	$id_sube= getParam($_GET["id"],"-1");
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "deactivate") {
    $id_sube = sqlValue($_POST["besu"],"int");
    $sql_subes = "update subentidad set activa=0, actualizada='".$hoy."' WHERE id_sube=".$id_sube;
    $que_sube=@mysql_query($sql_subes) or die (mysql_error());
    $sql = "select a.id_usuario, a.activo from usuarios a inner join clientes b on a.id_usuario=b.id_usuario "
         . "where b.id_sube=".$id_sube;
    $qsql=mysql_query($sql) or die (mysql_error());    
    while ($rssql=  mysql_fetch_assoc($qsql)){
        if ($rssql['activo']==1){
            $sql_up="update usuarios set activo=0, actualizado=".$hoy." where id_usuario=".$rssql['id_usuario'];
            mysql_query($sql_up) or die (mysql_error());
            include('conexionpuntos.php');
            $sql_usuarios="update ps_customer set active=0 where id_customer=".$rssql['id_usuario'];
            mysql_query($sql_usuarios) or die (mysql_error());
            include('conexion.php');
        }
    }
}

$sql="SELECT * FROM subentidad WHERE id_sube=".$id_sube;
$quesEnt= mysql_query($sql) or die (mysql_error());
$rssEnt = mysql_fetch_assoc($quesEnt);
$action="deactivate";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : :  Desactivar Subentidad : :</title>
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
             <li><a><?php echo "Desactivar Subentidad"; ?></a></li>
	</ul>
    </dav>
<form method="post" id="frmUsuario" action="/is/">
    <h3>Seguro desea inactivar esta Subentidad:</h3>
    <label for="Subentidad">Subentidad : </label>
    <input readonly="readonly" type="text" name="besu" value="<?php echo $rssEnt['sube'];?>"/>
    <br />
    <label for="val_punto">Valor Punto : </label>
    <input type="text" name="topun" value="<?php echo $rssEnt['val_punto'];?>"/></td> 
    <br />
    <label for="estado">Estado : </label>
    <?php if ($rssEnt['activa']==1){?>
    <input disabled type="checkbox" name="estado" checked="checked"/></td> 
    <?php }else{ ?>
	<input disabled type="checkbox" name="estado"/></td>
    <?php } ?>
    <br />
    <label for="bts">&nbsp;</label>
    <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
    <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
    <input type="hidden" name="besu" value="<?php echo $rssEnt["id_sube"];?>"/>
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
if (isset($que_sube)){
    if ($rol==1){
        echo "<script> swal({title: 'Exito', text: 'La Subentidad ha sido desactivada', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Exito', text: 'La subentidad ha sido desactivada', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
    }
}elseif ($rssEnt['activa']==0){
    if ($rol==1){
        echo "<script> swal({title: 'Información', text: 'La subentidad se encuentra inactiva', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Información', text: 'La subentidad se encuentra inactiva', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
    }
}
?>

<script>
function redireccionar1(){
    window.location.href='/lsa/';
}

function redireccionar2(){
    window.location.href='/ls/';
}
</script>