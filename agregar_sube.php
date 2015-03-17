<?php
session_start();
include('conexion.php');
include('funciones.php');
include('Classes/dm.php');

if (isset($_SESSION['s_entidad'])){
    $entidad=$_SESSION['s_entidad'];
    $rol = $_SESSION["s_rol"];
    $action="nada";
}else{
    $entidad=0;
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
    if ($rol==1){
        $entidad= getParam($_POST["tien"],"int");
    }
    $sube = sqlValue($_POST["besu"],"text");
    $val_punto = sqlValue($_POST["topun"],"float");
    $busca_sube="SELECT * FROM subentidad where id_entidad=".$entidad." and sube=".$sube;
    $encuentra_sube=@mysql_query($busca_sube) or die (mysql_error()); 
    $total_subes=@mysql_num_rows($encuentra_sube);
}

if ($entidad>0){
    $query="SELECT id_entidad, entidad FROM entidad where id_entidad=".$entidad;
    $queEnt=@mysql_query($query) or die (mysql_error());
    $rstit=mysql_fetch_assoc($queEnt);
    //$titulo="Agregar Subentidad, Entidad ".$rstit['entidad'];
}else{
    $query="SELECT id_entidad, entidad FROM entidad where id_entidad>0";
    $queEnt=@mysql_query($query) or die (mysql_error());
}
$action="add";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title> : : Agregar Subentidad : : </title>
    <script src="../js/modernizr.js"></script>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <link rel="stylesheet" href="../css/nav.css">
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
<?php if ($entidad>0){?>
    <dav>
        <ul>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Agregar subentidad";?></a></li>
        </ul>
    </dav>
<?php }else{ ?>
    <dav>
        <ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Agregar subentidad";?></a></li>
        </ul>
    </dav>
<?php } ?>
<form method="post" id="frmsubentidad" action="/as/">
<?php if ($entidad==0){ ?>
	<label for="Entidad">Entidad : </label>
	<select name="tien">
	<option value="">Seleccionar</option>
<?php   while ($rsEnt=mysql_fetch_assoc($queEnt)){echo "<option value='".$rsEnt['id_entidad']."'>".$rsEnt['entidad']."</option>";}?>
	</select>
	<br />
	<input type="hidden">
	<br />
<?php } ?>
	<label for="Subentidad">Subentidad : </label>
	<input type="text" name="besu" placeholder="Ingrese subentidad" style="text-transform:uppercase;" required/>
        <br />
        <label for="val_punto">Valor punto : </label>
	<input type="text" name="topun" placeholder="Valor Punto" required/>
	<br />
	<label for="bts">&nbsp;</label>
<?php if ($entidad>0){ ?>
        <input type="hidden" name="tien" value="<?php echo $entidad; ?>"/>
<?php } ?>
       	<input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
	<button type="submit">Guardar</button>
	<button type="reset">Limpiar</button>
<?php if ($rol==1){?>
        <button type="button" onClick="location.href = '/lsa/'">Cancelar</button>			 
<?php }else{ ?>
        <button type="button" onClick="location.href = '/ls/'">Cancelar</button>
<?php } ?>
</form>
</body>
</html>

<?php
if (isset($total_subes)){
    if ($total_subes == 0){
	$sql = "insert into subentidad (id_entidad, sube, val_punto, activa, creada) values (".$entidad.",".$sube.",".$val_punto.", '1','".$hoy."')";
	@mysql_query($sql) or die (mysql_error());
	if ($rol==1){
            echo "<script> swal({title: 'Exito', text: 'Subentidad creada satisfactoriamente', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
	}else{
            echo "<script> swal({title: 'Exito', text: 'Subentidad creada satisfactoriamente', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
	}
    }else{
        if ($rol==1){
            echo "<script> swal({title: 'Error', text: 'Error, esta Subentidad ya existe', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
        }else{
            echo "<script> swal({title: 'Error', text: 'Error, esta Subentidad ya existe', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
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