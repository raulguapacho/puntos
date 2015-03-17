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
    $hoy=date("Y-m-d");
}

if ($action == "edit") {
    $id_entidad = sqlValue($_POST["di"],"int");
    $entidad = sqlValue($_POST["tien"],"text");
    if (isset($_POST["dota"])){
        if ($_POST["dota"]=='on'){
            $estado=1;
        }else{
            $estado=0;
        }
    }else{
        $estado=0;
    }
	
    $busca_entidad="SELECT id_entidad FROM entidad where entidad=".$entidad." and activa=".$estado;
    $encuentra_entidad=@mysql_query($busca_entidad);
    $total_entidades=@mysql_num_rows($encuentra_entidad);
}

$sql="SELECT * FROM entidad WHERE id_entidad = ".$id_entidad;
$queEnt= @mysql_query($sql);
$rsEnt = mysql_fetch_assoc($queEnt);
$action="edit";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Editar entidad : : </title>
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
            <li><a><?php echo "Editar Entidad"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmEntidad" action="/ee/">
	<label for="Entidad">Entidad : </label>
	<input type="text" readonly="readonly" name="tien" value="<?php echo $rsEnt["entidad"]; ?>" style="text-transform:uppercase;" required/>
	<br />
        <label for="Estado">Estado : </label>
        <?php if ($rsEnt['activa']==1) { ?>
            <input type="checkbox" name="dota" checked="checked" disabled/>
        <?php } else { ?>
            <input type="checkbox" name="dota"/>
        <?php } ?>
        <br />
	<label for="bts">&nbsp;</label>
	<button type="submit">Guardar</button>
	<input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
	<input type="hidden" name="di" value="<?php echo $rsEnt["id_entidad"]; ?>" />
        <button type="button" onClick="location.href = '/le/'">Cancelar</button>
		
    </form>
</body>
</html>

<?php
if (isset($total_entidades)){
    if ($total_entidades == 0){
        $sql = "UPDATE entidad SET entidad=".$entidad.",activa=".$estado.", actualizada='".$hoy."' WHERE id_entidad=".$id_entidad;
        @mysql_query($sql) or die (mysql_error());
        if ($estado==1){
            $sql = "select a.id_usuario, a.cedula, a.clave from usuarios a inner join clientes b on a.id_usuario=b.id_usuario "
                 . "where b.id_entidad=".$id_entidad;
            $qsql=mysql_query($sql) or die (mysql_error());    
            while ($rssql=  mysql_fetch_assoc($qsql)){
                $cedula=md5(md5($rssql['cedula']).sha1($rssql['cedula']));
                if ($cedula!=$rssql['clave']){
                    $sql_up="update usuarios set activo=1, actualizado='".$hoy."' where id_usuario=".$rssql['id_usuario'];
                    mysql_query($sql_up) or die (mysql_error());
                    include('conexionpuntos.php');
                    $sql_usuarios="update ps_customer set active=1 where id_customer=".$rssql['id_usuario'];
                    mysql_query($sql_usuarios) or die (mysql_error());
                    include('conexion.php');
                }
            }
        }
        echo "<script> swal({title: 'Exito', text: 'Edición de la entidad exitosa', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Información', text: 'No se realizo ningun cambio', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }
}
?>

<script>
function redireccionar(){
  window.location.href="/le/";
}
</script>