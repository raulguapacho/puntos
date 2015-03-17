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
    $id_sube = getParam($_GET["id"],"-1");
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "add") {
    $cedula = sqlValue($_POST["duce"],"int");
    $entidad = sqlValue($_POST["tien"],"int");
    $nombre = sqlValue($_POST["bnom"],"text");
    $direccion = sqlValue($_POST["redi"],"text");
    $telefono = sqlValue($_POST["lete"],"int");
    $email = sqlValue($_POST["aime"],"text");
	
    $busca_admin_ent="SELECT a.id_cliente FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.id_entidad=".$entidad." and b.id_rol=2";
    $encuentra_admin_ent=@mysql_query($busca_admin_ent) or die (mysql_error());
    $total_admin_ent=@mysql_num_rows($encuentra_admin_ent);
}
$sql="SELECT * FROM entidad where id_entidad>0";
$queEnt=@mysql_query($sql) or die (mysql_error());
$action="add";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title> : : Agregar Administrador : : </title>
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
            <li><a><?php echo "Agregar Administrador"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmAdmin" action="/aa/">
        <label for="cedula">Cedula : </label>
        <input type="text" id="cedula" placeholder="Numero de identificación" name="duce" required/>
        <br />
        <label for="entidad">Entidad : </label>
        <select name="tien" title="Despliegue para seleccionar una Entidad" required>
        <option value="">Seleccionar</option>
        <?php
        while ($rsEnt=@mysql_fetch_assoc($queEnt)){echo "<option value='".$rsEnt['id_entidad']."'>".$rsEnt['entidad']."</option>";}
        ?>
        </select>
        <br /> 
        <input type="hidden">
        <br /> 
        <label for="nombre">Nombre : </label>
        <input type="text" id="nombre" placeholder="Nombres y apellidos" style="text-transform:uppercase;" name="bnom" required/>
        <br />
        <label for="direccion">Direccion : </label>
        <input type="text" id="direccion" placeholder="Dirección" name="redi"/>
        <br />
        <label for="telefono">Telefono : </label>
        <input type="text" id="telefono" placeholder="Teléfono" name="lete"/>
        <br />
        <label for="usuario">Email : </label>
        <input type="email" id="email" placeholder="Correo electrónico" name="aime" required/>
        <br />
        <label for="bts">&nbsp;</label>
        <input type="hidden" id="rol" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" id="action" name="tiac" value="<?php echo $action; ?>"/>
        <button type="submit">Guardar</button>
        <button type="reset">Limpiar</button>
        <button type="button" onClick="location.href = '/la/'">Cancelar</button>
    </form>
</body>
</html>

<?php
if (isset($total_admin_ent)){
    if ($total_admin_ent == 0){
	$busca_admin="SELECT a.id_cliente FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.cedula=".$cedula." and b.id_rol=2";
	$encuentra_admin=@mysql_query($busca_admin) or die (mysql_error());
	$total_admin=@mysql_num_rows($encuentra_admin);	
	if ($total_admin==0){
            //inserta registro tabla clientes
            $sql_cliente = "insert into clientes (cedula, id_entidad, nombre, direccion, telefono, email, creado) 
			    values (".$cedula.",".$entidad.",".$nombre.",".$direccion.",".$telefono.",".$email.",'".$hoy."')";
            @mysql_query($sql_cliente) or die (mysql_error());
            //inserta registro tabla usuarios
            $sql_usuario = "insert into usuarios (cedula, id_rol, usuario, activo, creado)
                            values (".$cedula.",2,".$email.",0,'".$hoy."')";
            @mysql_query($sql_usuario) or die (mysql_error());
            //consulta el ultimo registro insertado tabla usuarios
            $query_reg_usu="Select id_usuario from usuarios order by id_usuario DESC LIMIT 1";
            $ultimo_reg_usu=@mysql_query($query_reg_usu) or die (mysql_error());
            $reg_usu=@mysql_fetch_array($ultimo_reg_usu);
            $id_usuario=$reg_usu['id_usuario'];
            //Actualiza registro tabla usuarios Clave la misma cedula
            $clave=md5(md5($cedula).sha1($cedula));
            $actualiza_usuario="update usuarios SET clave='".$clave."' WHERE id_usuario=".$id_usuario;
            @mysql_query($actualiza_usuario) or die (mysql_error());
            //consulta el ultimo registro insertado tabla clientes
            $query_reg_cli="Select id_cliente from clientes order by id_cliente DESC LIMIT 1";
            $ultimo_reg_cli=@mysql_query($query_reg_cli) or die (mysql_error());
            $reg_cli=mysql_fetch_array($ultimo_reg_cli);
            $id_cliente=$reg_cli['id_cliente'];
            //Actualiza registro tabla clientes con el id del usuario
            $actualiza_cliente="update clientes SET id_usuario=".$id_usuario." where id_cliente=".$id_cliente;
            @mysql_query($actualiza_cliente) or die (mysql_error());
            echo "<script> swal({title: 'Exito', text: 'Administrador creado satisfactoriamente', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
	}else{
            echo "<script> swal({title: 'Error', text: 'Error, ya existe este administrador', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
	}
    }else{
        echo "<script> swal({title: 'Información', text: 'Error, ya existe Administrador para esta entidad', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
    }
}
?>

<script>
function redireccionar(){
  window.location.href="/la/";
}
</script>