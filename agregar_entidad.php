<?php
session_start();
include('conexion.php');
include('funciones.php');
include('Classes/dm.php');

if (isset($_SESSION['s_rol'])){
    $rol = $_SESSION["s_rol"];
    $action="nada";
}

/*if (isset($_GET['id'])){
	$id_sube = getParam($_GET["id"],"-1");
}*/

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "add") {
    $entidad = sqlValue($_POST["tien"],"text");
    $busca_entidad="SELECT * FROM entidad where entidad=".$entidad;
    $encuentra_entidad=@mysql_query($busca_entidad);
    $total_entidades=@mysql_num_rows($encuentra_entidad);
}
$action="add";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title> : : Agregar entidad : : </title>
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
            <li><a><?php echo "Agregar Entidad"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmEntidad" action="/ae/">
	<label for="Entidad">Entidad</label>
	<input type="text" id="daden" name="tien" style="text-transform:uppercase;" placeholder="Ingrese entidad" required/>
	<br />
	<label for="bts">&nbsp;</label>
	<input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
	<button type="submit">Guardar</button>
	<button type="reset">Limpiar</button>
        <button type="button" onClick="location.href = '/le/'">Cancelar</button>
    </form>
</body>
</html>

<?php
if (isset($total_entidades)){
    if ($total_entidades==0){
        $sql = "insert into entidad (entidad, creada, activa) values (".$entidad.",'".$hoy."', '1')";
        @mysql_query($sql);
        echo "<script> swal({title: 'Exito', text: 'Entidad creada satisfactoriamente', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }else{
        echo "<script> swal({title: 'Error', text: 'Error, Esta entidad ya existe', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }
}
?>

<script>
function redireccionar(){
  window.location.href="/le/";
}
</script>