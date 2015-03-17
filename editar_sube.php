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
	$id_sube = getParam($_GET["id"],"-1");
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "edit") {
    $id_sube = getParam($_POST['di'],"int");
    $sube = getParam($_POST['besu'],"int");
    $entidad = sqlValue($_POST["tien"],"int");
    $id_sube = sqlValue($_POST["di"],"int");
    $val_punto = sqlValue($_POST["topun"],"float");
    if (isset($_POST["dota"])){
        if ($_POST["dota"]=="on"){
            $activo=1;
        }else{
            $activo=0;
        }
    }else{
        $activo=0;
    }
    $busca_sube="select id_sube from subentidad where sube='".$sube."' and activa=".$activo;
    $encuentra_sube=@mysql_query ($busca_sube) or die (mysql_error());
    $total_subes=  mysql_num_rows($encuentra_sube);
}

$sql="SELECT * FROM subentidad WHERE id_sube=".$id_sube;
$quesEnt= mysql_query($sql) or die (mysql_error());
$rssEnt = mysql_fetch_assoc($quesEnt);
$action="edit";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Editar Subentidad : : </title>
    <script src="../js/modernizr.js"></script>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
<?php if ($rol==2){?>
    <dav>
       	<ul>
        	<li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
		<li><a><?php echo "Editar subentidad";?></a></li>
	</ul>
    </dav>
<?php }else{ ?>
    <dav>
       	<ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Editar subentidad";?></a></li>
	</ul>
    </dav>
<?php } ?>
    <form method="post" id="frmsubentidad" action="/es/">
        <label for="subentidad">Subentidad : </label>
	<input type="text" readonly="readonly" name="besu" value="<?php echo $rssEnt["sube"]; ?>"/>
	<br />
        <label for="val_punto">Valor Punto : </label>
	<input type="text" name="topun" value="<?php echo $rssEnt["val_punto"]; ?>"/>
	<br />
        <label for="estado">Estado : </label>
        <?php if ($rssEnt['activa']==1){?>
            <input disabled type="checkbox" name="dota" checked="checked"/></td> 
        <?php }else{ ?>
            <input type="checkbox" name="dota"/></td>
        <?php } ?>
        <br />
	<label for="bts">&nbsp;</label>
	<button type="submit">Guardar</button>
        <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tien" value="<?php echo $entidad; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
	<input type="hidden" name="di" value="<?php echo $rssEnt["id_sube"]; ?>" />
        <?php if ($rol==1){?>
            <button type="button" onClick="location.href = '/lsa/'">Cancelar</button>			 
	<?php }else{ ?>
            <button type="button" onClick="location.href = 'ls/'">Cancelar</button>
	<?php } ?>
    </form>
</body>
</html>

<?php
if (isset($total_subes)){
    if ($total_subes==0){
        $sql = "UPDATE subentidad SET sube='".$sube."',val_punto=".$val_punto.", activa=".$activo.", actualizada='".$hoy."' WHERE id_sube=".$id_sube;
        $quesql=@mysql_query($sql) or die (mysql_error());
        if ($activo==1){
            $sql = "select a.id_usuario, a.cedula, a.clave from usuarios a inner join clientes b on a.id_usuario=b.id_usuario "
                 . "where b.id_sube=".$id_sube;
            $qsql=mysql_query($sql) or die (mysql_error());    
            while ($rssql=  mysql_fetch_assoc($qsql)){
                $cedula=md5(md5($rssql['cedula']).sha1($rssql['cedula']));
                if ($cedula!=$rssql['clave']){
                    $sql_up="update usuarios set activo=1, actualizado=".$hoy." where id_usuario=".$rssql['id_usuario'];
                    mysql_query($sql_up) or die (mysql_error());
                    include('conexionpuntos.php');
                    $sql_usuarios="update ps_customer set active=1 where id_customer=".$rssql['id_usuario'];
                    mysql_query($sql_usuarios) or die (mysql_error());
                    include('conexion.php');
                }
            }
        }
        if ($rol==1){
            echo "<script> swal({title: 'Exito', text: 'Edición de la subentidad exitosa', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
        }else{
            echo "<script> swal({title: 'Exito', text: 'Edición de la subentidad exitosa', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
        }
    }else{
        if ($rol==1){
            echo "<script> swal({title: 'Exito', text: 'No se realizaron cambios', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
        }else{
            echo "<script> swal({title: 'Exito', text: 'No se realizaron cambios', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
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