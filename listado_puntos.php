<?php
//session_start();
include('inactividad.php');
include('conexion.php');
include('Classes/dm.php');
include('funciones.php');
include('entidad.php');

if (isset($_POST['pag'])){
    $paginar=$_POST['pag'];
}else{
    $paginar=1;
}

if (isset($_SESSION['s_rol'])){
    if ($_SESSION['s_rol']==1){
        if ((array_key_exists('s_entidade', $_SESSION)) and !(array_key_exists('s_sube', $_SESSION)) and !(array_key_exists('s_cliente', $_SESSION))){
            $entidad=$_SESSION['s_entidade'];
        }elseif ((array_key_exists('s_entidade', $_SESSION)) and (array_key_exists('s_sube', $_SESSION)) and !(array_key_exists('s_cliente', $_SESSION))){
            $entidad=$_SESSION['s_entidade'];
            $subentidad=$_SESSION['s_sube'];
        }elseif ((array_key_exists('s_entidade', $_SESSION)) and (array_key_exists('s_sube', $_SESSION)) and (array_key_exists('s_cliente', $_SESSION))){    
            $entidad=$_SESSION['s_entidade'];
            $subentidad=$_SESSION['s_sube'];
            $cliente=$_SESSION['s_cliente'];
        }else{
            $entidad=0;
            $subentidad=0;
            $cliente=0;
        }
    }else{
        $entidad=$_SESSION['s_entidad'];
        if ((array_key_exists('s_sube', $_SESSION)) and !(array_key_exists('s_cliente', $_SESSION))){
            $subentidad=$_SESSION['s_sube'];
        }elseif ((array_key_exists('s_sube', $_SESSION)) and (array_key_exists('s_cliente', $_SESSION))){
            $subentidad=$_SESSION['s_sube'];
            $cliente=$_SESSION['s_cliente'];
        }else{
            $subentidad=0;
            $cliente=0;
        }
    }
}

if ($_POST!=array() and (!isset($_POST['pag']))){
    if(($_POST['tien']>0) and ($_POST['besu']==0) and ($_POST['encli']==0)){
        $entidad=$_POST['tien'];
        $_SESSION['s_entidade']=$entidad;
        $subentidad=0;
        $_SESSION['s_sube']=$subentidad;
        $cliente=0;
        $_SESSION['s_cliente']=$cliente;
    }elseif(($_POST['tien']>0) and ($_POST['besu']>0) and ($_POST['encli']==0)){
        $entidad=$_POST['tien'];
        $_SESSION['s_entidade']=$entidad;
        $subentidad=$_POST['besu'];
        $_SESSION['s_sube']=$subentidad;
        $cliente=0;
        $_SESSION['s_cliente']=$cliente;
    }elseif(($_POST['tien']>0) and ($_POST['besu']>0) and ($_POST['encli']>0)){
        $entidad=$_POST['tien'];
        $_SESSION['s_entidade']=$entidad;
        $subentidad=$_POST['besu'];
        $_SESSION['s_sube']=$subentidad;
        $cliente=$_POST['encli'];
        $_SESSION['s_cliente']=$cliente;
    }else{
        $entidad=0;
        $_SESSION['s_entidade']=$entidad;
        $subentidad=0;
        $_SESSION['s_sube']=$subentidad;
        $cliente=0;
        $_SESSION['s_cliente']=$cliente;
    }
}

if ($paginar==1){
    $page = 1;
    if(array_key_exists('pg', $_GET)){
    	$page = $_GET['pg'];
    }
    if (($entidad>0) and ($subentidad==0) and ($cliente==0)){
        $query_cli="SELECT COUNT(*) as conteo "
                . "FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                . "WHERE a.id_entidad=".$entidad;
    }elseif (($entidad>0) and ($subentidad>0) and ($cliente==0)){
        $query_cli="SELECT COUNT(*) as conteo "
                . "FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                . "WHERE a.id_entidad=".$entidad." and a.id_sube=".$subentidad;
    }elseif (($entidad>0) and ($subentidad>0) and ($cliente>0)){
        $query_cli="SELECT COUNT(*) as conteo "
                . "FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                . "WHERE a.id_entidad=".$entidad." and a.id_sube=".$subentidad." and a.id_cliente=".$cliente;
    }else{
        $query_cli="SELECT COUNT(*) as conteo "
                 . "FROM clientes a inner join puntos b on a.id_cliente=b.id_client";
    }
    $conteo_cli = @mysql_query($query_cli) or die (mysql_error());
    $conteo = "";
    $obj = mysql_fetch_assoc($conteo_cli);
    if($conteo_cli){
    	$conteo =$obj['conteo'];
    }
    $max_num_paginas = ceil($conteo/8);
    if (($entidad>0) and ($subentidad==0) and ($cliente==0)){
        $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.points, b.fec_ven_puntos "
                    ."FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                    ."WHERE a.id_entidad=".$entidad." and b.activo='1' "
                    ."LIMIT ".(($page-1)*8).", 8";
    }elseif (($entidad>0) and ($subentidad>0) and ($cliente==0)){
        $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.points, b.fec_ven_puntos "
                    ."FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                    ."WHERE a.id_entidad=".$entidad." and a.id_sube=".$subentidad." and b.activo='1' "
                    ."LIMIT ".(($page-1)*8).", 8";
    }elseif (($entidad>0) and ($subentidad>0) and ($cliente>0)){
        $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.points, b.fec_ven_puntos "
                    ."FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                    ."WHERE a.id_entidad=".$entidad." and a.id_sube=".$subentidad." and a.id_cliente=".$cliente." and b.activo='1' "
                    ."LIMIT ".(($page-1)*8).", 8";
    }else{
         $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.points, b.fec_ven_puntos "
                    ."FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                    ."WHERE b.activo='1'"
                    ."LIMIT ".(($page-1)*8).", 8";
    }
}else{
    if (($entidad>0) and ($subentidad==0) and ($cliente==0)){
        $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.points, b.fec_ven_puntos "
                    ."FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                    ."WHERE a.id_entidad=".$entidad;
    }elseif (($entidad>0) and ($subentidad>0) and ($cliente==0)){
        $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.points, b.fec_ven_puntos "
                    ."FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                    ."WHERE a.id_entidad=".$entidad." and a.id_sube=".$subentidad;
    }elseif (($entidad>0) and ($subentidad>0) and ($cliente==0)){
        $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.points, b.fec_ven_puntos "
                    ."FROM clientes a inner join puntos b on a.id_cliente=b.id_client "
                    ."WHERE a.id_entidad=".$entidad." and a.id_sube=".$subentidad." and a.id_cliente=".$cliente;
    }else{
        $query_limit="SELECT b.id, a.cedula, CONCAT (a.nombre,' ',a.apellidos) as nombre, b.puntos, b.fec_ven_puntos 
                  FROM clientes a inner join puntos b on a.id_cliente=b.id_client";
    }
}
$segmento = @mysql_query($query_limit) or die (mysql_error());

$urladdpuntos="/ap/";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> : : Puntos : : </title>
    <script type="text/javascript" src="../Classes/jquery-latest.js"></script>
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
        function onEnviar(){
            var entidad = $("#cbotien").val();
            document.getElementById("tien").value=entidad;
            var subentidad = $("#cbobesu").val();
            document.getElementById("besu").value=subentidad;
            var cliente = $("#cboencli").val();
            document.getElementById("encli").value=cliente;
        }
    </script>
    <script> 
    function doSearch() { 
	var tableReg = document.getElementById('myTable'); 
	var searchText = document.getElementById('searchTerm').value.toLowerCase(); 
	var cellsOfRow=""; var found=false; var compareWith=""; 
		
	// Recorrer las filas con contenido de la tabla, excluyendo la cabecera
	for (var i = 1; i < tableReg.rows.length; i++) { 
            cellsOfRow = tableReg.rows[i].getElementsByTagName('td'); 
            found = false; 
		
	// Recorremos todas las celdas 
            for (var j = 0; j < cellsOfRow.length && !found; j++) { 
                compareWith = cellsOfRow[j].innerHTML.toLowerCase(); 
		
                // Buscamos el texto en el contenido de la celda 
		if (searchText.length == 1 || (compareWith.indexOf(searchText) > -1)) { 
                    found = true; 
                    } 
            } 
            if(found){ 
                tableReg.rows[i].style.display = ''; 
            }else{ 
                // si no ha encontrado ninguna coincidencia, esconde la 
		// fila de la tabla 
                tableReg.rows[i].style.display = 'none'; 
		} 
	} 
    } 
    </script>
    <script src="../js/modernizr.js"></script>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <dav>
        <ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Listado de Puntos por Usuario"; ?></a></li>
        </ul>
    </dav>
    <?php if ($_SESSION['s_rol']==1){?>
<form method="post" id="frmpuntosadmin" action="/lp/">
    <table>
        <tr>
            <td><label for="cbotien">Entidad : </label></td>
            <td><select id="cbotien" name="tien" title="Despliegue para seleccionar una entidad">
            <option value="0">Seleccione una entidad</option>
            <?php
                $entidades = obtenerTodasLasEntidades();
                foreach ($entidades as $enti) {
                    echo '<option value="'.$enti->id.'">'.utf8_encode($enti->entidad).'</option>';
                }
            ?>
            </select></td>
        </tr>
        <tr>
            <td><label for="cbobesu">Subentidad : </label></td>
            <td><select id="cbobesu" name="besu" title="Despliegue para seleccionar una subentidad">
            <option value="0">Seleccione una entidad</option>
            </select></td>
        </tr>
        <tr>
            <td><label for="cboencli">Usuario : </label></td>
            <td><select id="cboencli" name="encli" title="Despliegue para seleccionar un cliente">
            <option value="0">Seleccione una subentidad</option>
            </select></td>
            <td><button type="submit">Aceptar</button></td>
        </tr>
    </table>
</form>
    <?php }else{
        $enti_admin="select entidad from entidad where id_entidad=".$_SESSION['s_entidad'];
        $queentiadmin=@mysql_query($enti_admin) or die (mysql_error());
        $rsentiadmin=  mysql_fetch_assoc($queentiadmin);
        
        $sube_admin="select id_sube, sube from subentidad where id_entidad=".$_SESSION['s_entidad'];
        $quesubeadmin=@mysql_query($sube_admin) or die (mysql_error());
    ?>
<form method="post" id="frmpuntosadmin" action="/lp/">
    <table>
        <tr>
            <td><label for="cbotien">Entidad : </label></td>
            <td><input type="text" value="<?php echo $rsentiadmin['entidad']?>" readonly="readonly"/></td>
            <input type="hidden" name=tien  value="<?php echo $_SESSION['s_entidad']; ?>"/>
        </tr>
        <tr>
            <td><label for="cbosube">Subentidades : </label></td>
            <td><select id="cbobesu" name="besu" title="Despliegue para seleccionar una subentidad">
            <option value="0">Seleccione una subentidad</option>
        <?php
            while ($rssubeadmin=  mysql_fetch_assoc($quesubeadmin)){
                echo "<option value='".$rssubeadmin['id_sube']."'>".$rssubeadmin['sube']."</option>";
            }
        ?>
            </select></td>
        </tr>
        <tr>
            <td><label for="cboencli">Usuario : </label></td>
            <td><select id="cboencli" name="encli" title="Despliegue para seleccionar un cliente">
            <option value="0">Seleccione una subentidad</option>
            </select></td>
            <td><button type="submit">Aceptar</button></td>
        </tr>
    </table>
</form>
    <?php }
if (($entidad>0) and ($subentidad==0) and ($cliente==0)){
    $enti_sele="select entidad from entidad where id_entidad=".$entidad;
    $queenti=@mysql_query($enti_sele) or die (mysql_error());
    $rsenti=  mysql_fetch_assoc($queenti);
    if ($_SESSION['s_rol']==1) ?>
        <h2>Entidad : <?php echo $rsenti['entidad'];?></h2>
    <?php
}elseif (($entidad>0) and ($subentidad>0) and ($cliente==0)){
    $enti_sele="select entidad from entidad where id_entidad=".$entidad;
    $queenti=@mysql_query($enti_sele) or die (mysql_error());
    $rsenti=  mysql_fetch_assoc($queenti);
    $sube_sele="select sube from subentidad where id_sube=".$subentidad;
    $quesube=@mysql_query($sube_sele) or die (mysql_error());
    $rssube=  mysql_fetch_assoc($quesube);
    if ($_SESSION['s_rol']==1){ ?>
        <h2>Entidad : <?php echo $rsenti['entidad'];?>, Subentidad : <?php echo $rssube['sube'];?></h2>
<?php }else{ ?>
        <h2>Subentidad : <?php echo $rssube['sube'];?></h2>
<?php } 
}elseif (($entidad>0) and ($subentidad>0) and ($cliente>0)){
    $enti_sele="select entidad from entidad where id_entidad=".$entidad;
    $queenti=@mysql_query($enti_sele) or die (mysql_error());
    $rsenti=  mysql_fetch_assoc($queenti);
    $sube_sele="select sube from subentidad where id_sube=".$subentidad;
    $quesube=@mysql_query($sube_sele) or die (mysql_error());
    $rssube=  mysql_fetch_assoc($quesube);
    $cliente_sele="select CONCAT(nombre,' ',apellidos) as nombre from clientes where id_cliente=".$cliente;
    $quecliente=@mysql_query($cliente_sele) or die (mysql_error());
    $rscliente=  mysql_fetch_assoc($quecliente);
    if ($_SESSION['s_rol']==1){ ?>
    <h2>Entidad : <?php echo $rsenti['entidad'];?>, Subentidad : <?php echo $rssube['sube'];?>, Usuario : <?php echo $rscliente['nombre'];?></h2>
    <?php }else{ ?>
    <h2>Subentidad : <?php echo $rssube['sube'];?>, Usuario : <?php echo $rscliente['nombre'];?></h2>
<?php }
}else{?>
    <h2></h2>
<?php
}
if($segmento){ 
?>
    <div id="contenedor">
	<table id="myTable" width="600" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Cedula</th>
                    <th>Nombre</th>
                    <th>Puntos</th>
                    <th>Vencimiento</th>
                    <!--<th></th>
                    <th></th>-->
                </tr>
            </thead>
	<?php while ($rsCli = mysql_fetch_assoc($segmento)) {
            $urleditpuntos="/ep/".$rsCli['id'];
            $urldeletepuntos="/dp/".$rsCli['id'];
        ?>
            <tbody
                <tr>
                    <td><?php echo $rsCli['cedula'];?></td>
                    <td><?php echo $rsCli['nombre'];?></td>
                    <td><?php echo $rsCli['points'];?></td>
                    <td><?php echo $rsCli['fec_ven_puntos'];?></td>
                    <!--<td><a title="Editar" href=<?php echo $urleditpuntos; ?>><img src="../images/edit.jpg" width="25" height="30"/></a></td>
                    <td><a title="Eliminar" href=<?php echo $urldeletepuntos; ?>><img src="../images/delete.jpg" width="25" height="30"/></a></td>-->
                </tr>
            </tbody>
	<?php } ?>
	</table>
    </div>
<?php } ?>
<table>
    <tr>
	<td>
<form method="post" id="frmPuntos" action="/lp/"/>
<?php
if ($paginar==0){
    $paginar=1;
    echo '<table>
            <tr>
                <td><form><input id="searchTerm" placeholder="Buscar" type="text" onkeyup="doSearch()"/></form></td>
		<td><input type="hidden" id="pag" name="pag" value="'.$paginar.'"><button type="submit">Paginar</button></td>
            </tr>
	</table>';
}else{
    $paginar=0;
    if ($page>1){
        echo '<a title="Pagina Inicial" href="/lp/1"><img src="../images/primera.jpg" width="15" height="20" border="0"></a>  |  ';
        echo '<a title="Pagina Anterior" href="/lp/'.($page-1).'"><img src="../images/izq.gif" border="0"></a>  |  ';
    }
    for($i=0; $i<$max_num_paginas;$i++){
        echo '<a href="/lp/'.($i+1).'">'.($i+1).'</a> | ';
    }
    if ($page<$max_num_paginas){
        echo '<a title="Pagina Siguiente" href="/lp/'.($page+1).'"><img src="../images/der.gif" border="0"></a>  |  ';
        echo '<a title="Pagina Final" href="/lp/'.($max_num_paginas).'"><img src="../images/ultima.jpg" width="15" height="20" border="0"></a>  |  ';
    }
    if ($conteo>10){
        echo '<input type="hidden" id="pag" name="pag" value="'.$paginar.'"><button type="submit">Mostrar Todo</button>  Pagina '.$page.' de '.$max_num_paginas;
    }
}
echo '</form>';
?>
	</td>
    </tr>
</table>
<table>
    <tr>
	<!--<td><a title="Agregar" href=<?php echo $urladdpuntos; ?>><img src="../images/add.jpg" width="25" height="30"/></a></td>
        <td></td>-->
        <?php if ($_SESSION['s_rol']==1){?>
	<td><a title="Regresar" href="/psa/"><img src="../images/regresar.jpg" width="25" height="30"/></a></td>
        <?php }else{?>
        <td><a title="Regresar" href="/pa/"><img src="../images/regresar.jpg" width="25" height="30"/></a></td>
        <?php } ?>
        <td></td>
        <td><a title="Exportar a Excel" href="/expu/"><img src="../images/exportar.gif" width="25" height="20"/></a></td>
    </tr>
</table>
</body>
</html>