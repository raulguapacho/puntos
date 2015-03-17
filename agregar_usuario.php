<?php
session_start();
include('conexion.php');
include('entidad.php');
include('funciones.php');
include('Classes/dm.php');

if (isset($_SESSION['s_rol'])){
    $enti=$_SESSION['s_entidad'];
    $rol = $_SESSION["s_rol"];
    $action="nada";
    $id_cliente=0;
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "add") {
    $cedula = sqlValue($_POST["duce"],"int");
    $cliente = sqlValue($_POST["encli"],"text");
    if ($enti==0){$entidad = sqlValue($_POST["tien1"],"int");}
    else{$entidad = sqlValue($_POST["tien"],"int");}
    $sube = sqlValue($_POST["sube"],"int");
    $nombre = sqlValue($_POST["bnom"],"text");
    $apellidos = sqlValue($_POST["elap"],"text");
    $direccion = sqlValue($_POST["redi"],"text");
    $telefono = sqlValue($_POST["lete"],"int");
    $email = sqlValue($_POST["iema"],"text");

    $busca_cliente="SELECT id_cliente FROM clientes where cedula=".$cedula." and id_entidad=".$entidad." and id_sube=".$sube;
    $encuentra_cliente=@mysql_query($busca_cliente) or die (mysql_error());
    $total_clientes=@mysql_num_rows($encuentra_cliente);
}
include('conexion.php');
$sql="SELECT * FROM entidad where id_entidad=".$enti;
$queEnt=@mysql_query($sql) or die (mysql_error());
$rsEnt=@mysql_fetch_assoc($queEnt);
$sql="SELECT * FROM subentidad where id_entidad=".$enti;
$quesEnt=@mysql_query($sql) or die (mysql_error());
$action="add";
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Agregar Usuario : : </title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="../Classes/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="../Classes/jquery-ui-1.8.23.custom.min.js"></script>
    <script type="text/javascript" src="../Classes/jquery.ui.datepicker-es.js"></script>
    <script>
        $(document).ready(function(){
            $("#cboEntidades").change(function() {
		var entidad = $(this).val();
		if(entidad > 0){var datos = {idEntidad : $(this).val()};
                	$.post("../subentidad.php", datos, function(subentidades) {     	
			var $comboSubentidades = $("#cboSubentidades");
                        $comboSubentidades.empty();
                        $.each(subentidades, function(index, subentidad) {
                        $comboSubentidades.append("<option value=" + subentidad.id + ">" + subentidad.sube + "</option>");
                        });
                    }, 'json');
		}else{
                    var $comboSubentidades = $("#cboSubentidades");
	            $comboSubentidades.empty();
                    $comboSubentidades.append("<option>Seleccione una entidad</option>");
		}
            });
	}); 
    </script>
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
            <li><a><?php echo "Agregar Usuario"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmUsuario" action="/au/">
        <label for="cedula">Cedula</label>
        <input type="text" name="duce" placeholder="Ingrese Cedula" required/>
        <br />
        <label for="cliente">Cliente</label>
        <input type="text" name="encli" placeholder="Ingrese numero de cliente"/>
        <br />
        <?php if ($enti==0){?>
            <label for="cboEntidades">Entidad : </label> 
            <select id="cboEntidades" name="tien1" title="Despliegue para seleccionar una nueva entidad">
            <option value="0">Seleccione una entidad</option>
            <?php
                $entidades = obtenerTodasLasEntidades();
                foreach ($entidades as $enti) { echo '<option value="'.$enti->id.'">'.utf8_encode($enti->entidad).'</option>';}
            ?>
            </select>
            <br />
            <br /> 
            <label for="cboSubentidades">Subentidad : </label>
            <select id="cboSubentidades" name="sube" title="Despliegue para seleccionar una nueva subentidad">
            <option value="0">Seleccione una entidad</option>
            </select>
        <?php }else{?>        
            <label for="entidad">Entidad</label>
            <input readonly="readonly" type="text" id="entidad" name="entidad" value="<?php echo $rsEnt['entidad'];?>"/>
            <br />         
            <label for="subentidad">Subentidad</label>
            <select name="sube" title="Despliegue para seleccionar una nueva subentidad" required>
            <option value="">Seleccionar</option>
        <?php
            while ($rssEnt=@mysql_fetch_assoc($quesEnt)){echo "<option value='".$rssEnt['id_sube']."'>".$rssEnt['sube']."</option>";}
        } ?>
        </select>
        <br /> 
        <input type="hidden">  
        <br />          
        <label for="nombre">Nombre</label>
        <input type="text" name="bnom" style="text-transform:uppercase;" placeholder="Ingrese Nombres" required/>
        <br />
        <label for="apellidos">Apellidos</label>
        <input type="text" name="elap" style="text-transform:uppercase;" placeholder="Ingrese Apellidos" required/>
        <br />
        <label for="direccion">Direccion</label>
        <input type="text" name="redi" placeholder="Ingrese dirección"/>
        <br />
        <label for="telefono">Telefono</label>
        <input type="text" name="lete" placeholder="Ingrese telefono"/>
        <br />
        <label for="usuario">Email</label>
        <input type="email" name="iema" placeholder="Ingrese email" required/>
        <br />
        <label for="bts">&nbsp;</label>
        <input type="hidden" name="tien" value="<?php echo $rsEnt["id_entidad"];?>"/>
        <input type="hidden" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" name="tiac" value="<?php echo $action; ?>"/>
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
if (isset($total_clientes)){
    if ($total_clientes == 0){
	//inserta registro tabla clientes
	$sql_cliente = "insert into clientes (cedula, cliente, id_entidad, id_sube, nombre, apellidos, direccion, telefono, email, creado) 
		        values (".$cedula.",".$cliente.",".$entidad.",".$sube.",".$nombre.",".$apellidos.",".$direccion.",".$telefono.",".$email.",'".$hoy."')";
        @mysql_query($sql_cliente) or die (mysql_error());
        
	//inserta registro tabla usuarios
	$sql_usuario = "insert into usuarios (cedula, cliente, id_rol, usuario, activo, creado)
		        values (".$cedula.",".$cliente.",3,".$email.",0,'".$hoy."')";
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
	$query_reg_cli="Select id_cliente, id_entidad, id_sube, nombre, apellidos, email from clientes order by id_cliente DESC LIMIT 1";
	$ultimo_reg_cli=@mysql_query($query_reg_cli) or die (mysql_error());
	$reg_cli=mysql_fetch_array($ultimo_reg_cli);
	$id_cliente=$reg_cli['id_cliente'];
	$id_ent=$reg_cli['id_entidad'];
	$id_sube=$reg_cli['id_sube'];
	$nombre=$reg_cli['nombre'];
	$apellidos=$reg_cli['apellidos'];
	$email=$reg_cli['email'];
	
	//Actualiza registro tabla clientes con el id del usuario
	$actualiza_cliente="update clientes SET id_usuario=".$id_usuario." where id_cliente=".$id_cliente;
	@mysql_query($actualiza_cliente) or die (mysql_error());
		
	//determinar valor del punto para entidad - subentidad
	$sql_valpunto="select val_punto from subentidad where id_entidad=".$id_ent." and id_sube=".$id_sube;
	$query_valpunto=@mysql_query($sql_valpunto) or die (mysql_error());
	$rs_valpunto=mysql_fetch_assoc($query_valpunto);
	$val_punto=$rs_valpunto['val_punto'];
		
	include ('conexionpuntos.php');
	//inserta registro tabla ps_customer de la BD puntos
	$pass=md5('I3mLxuxZo1zSFjdCAcWhubRk9Gu3ZbTxtTdnrPSxX7zJYRu2x0IuZZDW'.$cedula);
	$secure = md5(uniqid(rand(), true));
	$last_passwd_gen = date('Y-m-d H:i:s');
	$newsletter_date_add = date('Y-m-d H:i:s');
	$date_add = date('Y-m-d H:i:s');
	$date_upd = date('Y-m-d H:i:s');
				
	$sql_customer="INSERT INTO ps_customer (id_customer, id_shop_group, id_shop, id_gender, id_default_group, id_lang, id_risk, company, siret, ape, firstname, lastname, email, passwd, last_passwd_gen, birthday, newsletter, ip_registration_newsletter, newsletter_date_add, optin, website, outstanding_allow_amount, show_public_prices, max_payment_days, secure_key, note, active, is_guest, deleted, date_add, date_upd, id_entidad, id_sube, val_punto) 
                       VALUES(".$id_usuario.", 1, 1, 1, 3, 1, 0, '', '', '','".$nombre."','".$apellidos."','".$email."','".$pass."', '".$last_passwd_gen."', NULL, 1, '', '".$newsletter_date_add."', 1, '', 0.000000, 0, 0, '".$secure."', '', 0, 0, 0, '".$date_add."', '".$date_upd."', '".$id_ent."','".$id_sube."','".$val_punto."')";
	$query_puntos=@mysql_query($sql_customer) or die (mysql_error());

	//inserta registro tabla ps_customer_group de la BD puntos
	$sql_group="INSERT INTO ps_customer_group (id_customer, id_group) VALUES (".$id_usuario.", '3')";
	$query_group=@mysql_query($sql_group) or die (mysql_error());
        
        include ('conexion.php');
        
	if ($rol==1){
            /*echo "<script>
                    swal({
                        title: 'Pregunta?',
                        text: 'Usuario creado satisfactoriamente, \\n ¿Desea Agregar puntos para este usuario?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#DD6B55',
                        cancelButtonText: 'No',
                        confirmButtonText: 'Si',
                        closeOnConfirm: false,
                        closeOnCancel: false
                    },
                    function(isConfirm){
                        if (isConfirm) {
                            redireccionar2(".$id_cliente.");
                        }else{
                            redireccionar();
                        }
                    });
                 </script>";*/
            echo "<script> swal({title: 'Información', text: 'Usuario creado satisfactoriamente', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
	}else{
            echo "<script> swal({title: 'Información', text: 'Usuario creado satisfactoriamente', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
	}
    }else{
	if ($rol==1){
            echo "<script> swal({title: 'Información', text: 'Este usuario ya existe para esta Entidad y Subentidad', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
	}else{
            echo "<script> swal({title: 'Información', text: 'Este usuario ya existe para esta Entidad y Subentidad', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
	}
    }
}
?>

<script>
function redireccionar(){
    window.location.href='/lua/';
}

function redireccionar2(cliente){
    direccion='/ap/';
    window.location.href=direccion+cliente;
}

function redireccionar1(){
    window.location.href="/lu/";
}
</script>