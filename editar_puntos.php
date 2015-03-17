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
    $id_puntos = getParam($_GET["id"],"-1");
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "edit") {
    $id_puntos = sqlValue($_POST["di"],"int");
    $puntos = sqlValue($_POST["tpun"],"int");
	
    $sql_puntos="UPDATE puntos SET puntos=".$puntos.",actualizado=".$hoy." WHERE id=".$id_puntos;
    $que_puntos=@mysql_query($sql_puntos) or die (mysql_error());
    
    include('conexionpuntos.php');
    $sql_customer_points="UPDATE ps_customer_points SET points=".$puntos.",actualizado=".$hoy." WHERE id=".$id_puntos;
    @mysql_query($sql_customer_points) or die (mysql_error());
    
    include('conexion.php');
}
$puntos="select id, id_cliente, puntos, fec_ven_puntos from puntos where id=".$id_puntos;
$quepuntos=@mysql_query($puntos) or die (mysql_error());
$rs_puntos=@mysql_fetch_assoc($quepuntos);

$cliente_edi="select CONCAT(nombre,' ',apellidos) as nombre from clientes where id_cliente=".$rs_puntos['id_cliente'];
$quecliente=@mysql_query($cliente_edi) or die (mysql_error());
$rscliente=@mysql_fetch_assoc($quecliente);

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
            <li><a><?php echo "Editar Puntos"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmPuntos" action="/ep/">
        <label for="cliente">Usuario : </label>
        <input type="text" value="<?php echo $rscliente['nombre'];?>" readonly="readonly"/>
        <br />
        <label for="Vence">Vencen : </label>
        <input type="text" value="<?php echo $rs_puntos['fec_ven_puntos'];?>" readonly="readonly"/>
        <br />
        <label for="puntos">Puntos : </label>
        <input type="text" name="tpun" value="<?php echo $rs_puntos['puntos'];?>" required/>
        <br />
        <label for="bts">&nbsp;</label>
        <input type="hidden" name="di" value="<?php echo $rs_puntos['id']; ?>"/>
        <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
        <button type="submit">Aceptar</button>
        <button type="button" onClick="location.href = '/lp/'">Cancelar</button>
        <br />
    </form>
</body>
</html>

<?php
if (isset($que_puntos)){
    echo "<script> swal({title: 'Exito', text: 'Puntos del usuario editados exitosamente', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
}
?>

<script>
function redireccionar(){
  window.location.href="/lp/";
}
</script>