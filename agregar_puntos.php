<?php
session_start();
include('conexion.php');
include('funciones.php');
include('Classes/dm.php');
include('entidad.php');

if (($_SESSION['s_entidade']>0) and ($_SESSION['s_sube']==0) and ($_SESSION['s_cliente']==0)){
    $entidad=$_SESSION['s_entidade'];
    $subentidad=0;
    $cliente=0;
}elseif (($_SESSION['s_entidade']>0) and ($_SESSION['s_sube']>0) and ($_SESSION['s_cliente']==0)){
    $entidad=$_SESSION['s_entidade'];
    $subentidad=$_SESSION['s_sube'];
    $cliente=0;
}elseif (($_SESSION['s_entidade']>0) and ($_SESSION['s_sube']>0) and ($_SESSION['s_cliente']>0)){
    $entidad=$_SESSION['s_entidade'];
    $subentidad=$_SESSION['s_sube'];
    $cliente=$_SESSION['s_cliente'];
}else{
    $entidad=0;
    $subentidad=0;
    $cliente=0;
}

if (isset($_SESSION['s_rol'])){
    $porcliente=0;
    $rol = $_SESSION["s_rol"];
    $action="nada";
}

if (isset($_GET['id'])){
    $porcliente=1;
    $id_cliente = getParam($_GET["id"],"-1");
    $nuevousu="select CONCAT(nombre,' ',apellidos) as nombre from clientes where id_cliente=".$id_cliente;
    $quenuevousu=@mysql_query($nuevousu) or die (mysql_error());
    $rsnuevousu=mysql_fetch_assoc($quenuevousu);
}

if (isset($_POST['lor'])){
    $rol = getParam($_POST["lor"],"int");
    $action = getParam($_POST["tiac"],"text");
    $hoy=date("Y-m-d");
}

if ($action == "add") {
    $id_cliente=sqlValue($_POST["encli"],"int");
    $puntos = sqlValue($_POST["tpun"],"int");
    $partes = explode('/', $_POST['fvp']);
    $fec_ven_puntos = $partes[2].'-'.$partes[1].'-'.$partes[0];
    $fee = sqlValue($_POST["fee"],"float");

    //consulta id de usuario para el cliente
    $existcliente="select id_usuario from clientes where id_cliente=".$id_cliente;
    $quecliente=@mysql_query($existcliente) or die(mysql_error());
    $rscliente=  mysql_fetch_assoc($quecliente);
    $id_usuario=$rscliente['id_usuario'];
    
    //consulta el ultimo registro insertado tabla puntos
    $query_reg_puntos="select id, id_cliente, puntos, fec_ven_puntos, fee from puntos where id_cliente=".$id_cliente." order by id desc limit 1";
    $ultimo_reg_pun=@mysql_query($query_reg_puntos) or die(mysql_error());
    $reg_pun=  mysql_fetch_assoc($ultimo_reg_pun);
    $fec_exis_puntos=$reg_pun['fec_ven_puntos'];
}
$action="add";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> : : Agregar Puntos : : </title>
    <script src="../js/modernizr.js"></script>
    <link rel="stylesheet" type="text/css" href="../css/jquery-ui-1.7.2.custom.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
    <script>
        jQuery(function($){
            $.datepicker.regional['es'] = {
            closeText: 'Cerrar',
            prevText: '&#x3c;Ant',
            nextText: 'Sig&#x3e;',
            currentText: 'Hoy',
            monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio',
            'Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
            monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun',
            'Jul','Ago','Sep','Oct','Nov','Dic'],
            dayNames: ['Domingo','Lunes','Martes','Mi&eacute;rcoles','Jueves','Viernes','S&aacute;bado'],
            dayNamesShort: ['Dom','Lun','Mar','Mi&eacute;','Juv','Vie','S&aacute;b'],
            dayNamesMin: ['Do','Lu','Ma','Mi','Ju','Vi','S&aacute;'],
            weekHeader: 'Sm',
            dateFormat: 'dd/mm/yy',
            firstDay: 1,
            isRTL: false,
            showMonthAfterYear: false,
            yearSuffix: ''};
            $.datepicker.setDefaults($.datepicker.regional['es']);
        });    
        $(document).ready(function() {
           $("#datepicker").datepicker();
        });
    </script>
    <script>
        $(document).ready(function(){
            $("#cbotien").change(function() {
		var entidad = $(this).val();
		if(entidad > 0){var datos = {idEntidad : $(this).val()};
                    $.post("../subentidad.php", datos, function(subentidades) {     	
			var $comboSubentidades = $("#cbobesu");
                        $comboSubentidades.empty();
                        $comboSubentidades.append("<option value=0>Seleccione una Subentidad</option>");
                        $.each(subentidades, function(index, subentidad) {
                        $comboSubentidades.append("<option value=" + subentidad.id + ">" + subentidad.sube + "</option>");
                        });
                    }, 'json');
		}else{
                    var $comboSubentidades = $("#cbobesu");
	            $comboSubentidades.empty();
                    $comboSubentidades.append("<option>Seleccione una entidad</option>");
                    var $comboClientes = $("#cboencli");
                    $comboClientes.empty();
                    $comboClientes.append("<option>Seleccione una subentidad</option>");
		}
            });
            $("#cbobesu").change(function() {
                var subentidad = $(this).val();
                if(subentidad > 0){var datos = {idSubentidad : $(this).val()};
                        $.post("../clientes.php", datos, function(clientes) {     	
                        var $comboClientes = $("#cboencli");
                        $comboClientes.empty();
                        $comboClientes.append("<option value=0>Seleccione un Cliente</option>");
                        $.each(clientes, function(index, cliente) {
                        $comboClientes.append("<option value=" + cliente.id + ">" + cliente.nombre + "</option>");
                        });
                    }, 'json');
                }else{
                    var $comboClientes = $("#cboencli");
                    $comboClientes.empty();
                    $comboClientes.append("<option>Seleccione una subentidad</option>");
                }
            });
	});
    </script>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
    <dav>
       	<ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Agregar Puntos"; ?></a></li>
	</ul>
    </dav>
    <form method="post" id="frmPuntos" action="/admin_puntos/ap/">
    <?php if ($porcliente==0){
            if(($entidad==0)){
        ?>
        <label for="cbotien">Entidad : </label>
        <select id="cbotien" title="Despliegue para seleccionar una entidad">
        <option value="0">Seleccione una entidad</option>
        <?php
            $entidades = obtenerTodasLasEntidades();
            foreach ($entidades as $enti) {
                echo '<option value="'.$enti->id.'">'.utf8_encode($enti->entidad).'</option>';
            }
        ?>
        </select>
        <br />
        <br />
        <label for="cbobesu">Subentidad : </label>
        <select id="cbobesu" title="Despliegue para seleccionar una subentidad">
        <option value="0">Seleccione una entidad</option>
        </select>
        <br />
        <br />
        <label for="cboencli">Usuario : </label>
        <select id="cboencli" name="encli" title="Despliegue para seleccionar un cliente">
        <option value="0">Seleccione una subentidad</option>
        </select>
        <br />
        <br />
        <?php }elseif (($entidad>0) and ($subentidad==0)){ 
        $entisele="select entidad from entidad where id_entidad=".$entidad;
        $entisele=@mysql_query($entisele) or die (mysql_error());
        $entisele=  mysql_fetch_assoc($entisele);
        $subentis="select id_sube, sube from subentidad where id_entidad=".$entidad;
        $subentis=@mysql_query($subentis) or die (mysql_error());
        ?>
            <label for="tien">Entidad : </label>
            <input type="text" value="<?php echo $entisele['entidad']?>" readonly="readonly"/>
            <br/>
            <label for="cbosube">Subentidades : </label>
            <select id="cbobesu" title="Despliegue para seleccionar una subentidad">
            <option value="0">Seleccione una subentidad</option>
        <?php
            while ($rssent = mysql_fetch_assoc($subentis)){
                echo "<option value='".$rssent['id_sube']."'>".$rssent['sube']."</option>";
            }
        ?>
            </select>
            <br />
            <br/>
            <label for="cboencli">Usuario : </label>
            <select id="cboencli" name="encli" title="Despliegue para seleccionar un cliente">
            <option value="0">Seleccione una subentidad</option>
            </select>
            <br />
            <br />
        <?php }elseif (($entidad>0) and ($subentidad>0) and ($cliente==0)){
        $entisele="select id_entidad, entidad from entidad where id_entidad=".$entidad;
        $entisele=@mysql_query($entisele) or die (mysql_error());
        $entisele=  mysql_fetch_assoc($entisele);
        $subentis="select id_sube, sube from subentidad where id_sube=".$subentidad;
        $subentis=@mysql_query($subentis) or die (mysql_error());
        $subentis= mysql_fetch_assoc($subentis);
        $clisele="select id_cliente, CONCAT(nombre,' ',apellidos) as nombre from clientes "
                ."where id_entidad=".$entisele['id_entidad']." and id_sube=".$subentis['id_sube'];
        $clisele=@mysql_query($clisele) or die (mysql_error());
        ?>
            <label for="tien">Entidad : </label>
            <input type="text" value="<?php echo $entisele['entidad']?>" readonly="readonly"/>
            <br/>
            <label for="besu">Subentidad : </label>
            <input type="text" value="<?php echo $subentis['sube']?>" readonly="readonly"/>
            <br/>
            <label for="cboencli">Usuarios : </label>
            <select id="cboencli" name="encli" title="Despliegue para seleccionar un cliente">
            <option value="0">Seleccione un cliente</option>
        <?php
            while ($rscli = mysql_fetch_assoc($clisele)){
                echo "<option value='".$rscli['id_cliente']."'>".$rscli['nombre']."</option>";
            }
        ?>
            </select>
            <br />
            <br/>
        <?php }elseif (($entidad>0) and ($subentidad>0) and ($cliente>0)){
        $entisele="select entidad from entidad where id_entidad=".$entidad;
        $entisele=@mysql_query($entisele) or die (mysql_error());
        $entisele=  mysql_fetch_assoc($entisele);
        $subentis="select sube from subentidad where id_sube=".$subentidad;
        $subentis=@mysql_query($subentis) or die (mysql_error());
        $subentis= mysql_fetch_assoc($subentis);
        $clisele="select id_cliente, CONCAT(nombre,' ',apellidos) as nombre from clientes "
                ."where id_cliente=".$cliente;
        $clisele=@mysql_query($clisele) or die (mysql_error());
        $clisele=mysql_fetch_assoc($clisele);
        ?>
            <label for="tien">Entidad : </label>
            <input type="text" value="<?php echo $entisele['entidad']?>" readonly="readonly"/>
            <br/>
            <label for="besu">Subentidad : </label>
            <input type="text" value="<?php echo $subentis['sube']?>" readonly="readonly"/>
            <br/>
            <label for="encli">Cliente : </label>
            <input type="text" value="<?php echo $clisele['nombre']?>" readonly="readonly"/>
            <input type="hidden" name="encli" value="<?php echo $clisele['id_cliente']?>"/>
            <br/>
        <?php } ?>
    <?php }else{ ?>
        <label for="Usuario">Usuario : </label>
        <input type="text" value="<?php echo $rsnuevousu['nombre'];?>"/>
        <input type="hidden" name="encli" value="<?php echo $id_cliente;?>"/>
        <br />
    <?php } ?>
        <label for="Vence">Vencen : </label>
        <input type="text" id="datepicker" name="fvp" placeholder="Fecha de vencimiento de puntos" onBlur="valFec(this.value);"/>
        <br />
        <label for="puntos">Puntos : </label>
        <input type="text" id="tpun" name="tpun" placeholder="Ingrese numero de puntos del cliente"/>
        <br />
        <label for="fee">Fee : </label>
        <input type="text" name="fee" placeholder="Ingrese fee"/>
        <br />
        <label for="bts">&nbsp;</label>
        <input type="hidden" id="rol" name="lor" value="<?php echo $rol; ?>"/>
        <input type="hidden" id="action" name="tiac" value="<?php echo $action; ?>"/>
        <button type="submit">Guardar</button>
        <button type="reset">Limpiar</button>
        <button type="button" onClick="location.href = '/admin_puntos/lp/'">Cancelar</button>
        <br />
    </form>
</body>
</html>

<?php
if (isset($fec_ven_puntos)){
    if ($fec_ven_puntos>$hoy){
        if($fec_ven_puntos>$fec_exis_puntos){
            //inserta registro tabla puntos
            $sql_puntos = "insert into puntos (id_cliente, id_usuario, puntos, fec_ven_puntos, fee, creado, activo) values ('".$id_cliente."','".$id_usuario."','".$puntos."','".$fec_ven_puntos."','".$fee."','".$hoy."','1')";
            @mysql_query($sql_puntos) or die (mysql_error());
            include('conexionpuntos.php');
            $sql_points="INSERT INTO ps_customer_points (id_customer, points, fec_ven_puntos, fee, creado, activo) VALUES (".$id_usuario.",".$puntos.",'".$fec_ven_puntos."',".$fee.",".$hoy.",'1')";
            @mysql_query($sql_points) or die (mysql_error());
            $inserta=1;
            include('conexion.php');
            echo "<script> swal({title: 'Exito', text: 'Puntos del cliente creado satisfactoriamente', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
        }else{
            echo "<script> swal({title: 'Información', text: 'Existen puntos con vencimiento superior', type: 'info', confirmButtonText: 'Aceptar', timer: 2000});"
                ."document.getElementById('datepicker').style.borderColor='red';</script>";
        }
    }else{
        echo "<script> swal({title: 'Información', text: 'Vencimiento debe ser mayor a hoy', type: 'info', confirmButtonText: 'Aceptar', timer: 2000});"
            ."document.getElementById('datepicker').style.borderColor='red';</script>";
    }
}
?>

<script>
function redireccionar(){
  window.location.href="/admin_puntos/lp/";
}
</script>