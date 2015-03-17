<?php
//session_start();
include('inactividad.php');
include('conexion.php');
include('Classes/dm.php');

if (isset($_POST['pag'])){
    $paginar=$_POST['pag'];
}else{
    $paginar=1;
}

if (isset($_SESSION['s_entidad']))
    $entidad=$_SESSION['s_entidad'];
else
    $entidad=0;

$query_sin="SELECT id_entidad, entidad FROM entidad where id_entidad>0";
$queEnt_sin=@mysql_query($query_sin) or die (mysql_error());
$rol=1;
$i=0;
$_SESSION["s_rol"]=$rol;

if (isset($_POST['entidad'])){
    $entidad=$_POST['entidad'];
    $_SESSION['s_entidad']=$entidad;
}

if ($paginar==1){
    $page = 1;
    if(array_key_exists('pg', $_GET)){$page = $_GET['pg'];}
        if ($entidad==0){
            $query_ent="SELECT COUNT(*) as conteo FROM clientes";
            $query_con="SELECT id_entidad, entidad FROM entidad";
        }else{
            $query_ent="SELECT COUNT(*) as conteo FROM clientes where id_entidad=".$entidad;
            $query_con="SELECT id_entidad, entidad FROM entidad where id_entidad=".$entidad;
        }
        $conteo_ent = @mysql_query($query_ent) or die (mysql_error());
        $conteo = 1;
        $obj = mysql_fetch_assoc($conteo_ent);
        if($conteo_ent){
            $conteo =$obj['conteo'];
        }
        $max_num_paginas = ceil($conteo/8);
        if ($entidad==0){
            $query_limit="SELECT a.id_cliente, a.cedula, a.cliente, c.entidad, d.sube, CONCAT(a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, a.id_usuario, b.usuario, b.activo 
                          FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
                          inner join entidad c on a.id_entidad=c.id_entidad
                          inner join subentidad d on a.id_sube=d.id_sube
                          where b.id_rol='3' order by c.entidad, d.sube
                          LIMIT ".(($page-1)*8).", 8";
        }else{
            $query_limit="SELECT a.id_cliente, a.cedula, a.cliente, c.entidad, d.sube, CONCAT(a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, b.usuario, b.activo 
                          FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
                          inner join entidad c on a.id_entidad=c.id_entidad
                          inner join subentidad d on a.id_sube=d.id_sube
                          where a.id_entidad=".$entidad." and b.id_rol='3'
                          order by a.cedula LIMIT ".(($page-1)*8).", 8";
            }
}else{
    if ($entidad==0){
        $query_limit="SELECT a.id_cliente, a.cedula, a.cliente, c.entidad, d.sube, CONCAT(a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, a.id_usuario, b.usuario, b.activo 
                    FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
                    inner join entidad c on a.id_entidad=c.id_entidad
                    inner join subentidad d on a.id_sube=d.id_sube
                    where b.id_rol='3' order by c.entidad, d.sube";
        $query_con="SELECT id_entidad, entidad FROM entidad";
    }else{
        $query_limit="SELECT a.id_cliente, a.cedula, a.cliente, c.entidad, d.sube, CONCAT(a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, b.usuario, b.activo 
                    FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
                    inner join entidad c on a.id_entidad=c.id_entidad
                    inner join subentidad d on a.id_sube=d.id_sube
                    where a.id_entidad=".$entidad." and b.id_rol='3' order by a.cedula";
        $query_con="SELECT id_entidad, entidad FROM entidad where id_entidad=".$entidad;
    }
}

$segmento = @mysql_query($query_limit) or die (mysql_error());
$queEnt_con=@mysql_query($query_con) or die (mysql_error());
$rsEnt_con = mysql_fetch_assoc($queEnt_con);
$i=1;

$urladdua="/au/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> : : Usuarios : : </title>
    <script type="text/javascript" src="../Classes/jquery-latest.js"></script> 
    <script type="text/javascript" src="../Classes/jquery.tablesorter.js"></script>
    <script>$(document).ready(function(){$("#myTable").tablesorter();});</script>
    <script> 
    function doSearch() { 
	var tableReg = document.getElementById('myTable'); 
	var searchText = document.getElementById('searchTerm').value.toLowerCase(); 
	var cellsOfRow=""; var found=false; var compareWith=""; 
		
	// Recorrer las filas con contenido de la tabla, excluyendo la cabecera
	for (var i = 1; i < tableReg.rows.length; i++) { 
            cellsOfRow = tableReg.rows[i].getElementsByTagName('td'); 
            found = false; 
		
	// Recorrer las celdas 
            for (var j = 0; j < cellsOfRow.length && !found; j++) { 
                compareWith = cellsOfRow[j].innerHTML.toLowerCase(); 
	
            // Buscar texto en el contenido de la celda
                if (searchText.length == 1 || (compareWith.indexOf(searchText) > -1)) { 
                    found = true; 
		} 
            } 
            if(found){ 
                tableReg.rows[i].style.display = ''; 
            }else{ 
		// si no hay coincidencias, esconde las fila de la tabla 
		tableReg.rows[i].style.display = 'none'; 
            } 
	} 
    } 
    </script>
    <script src="../js/modernizr.js"></script>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
    <dav>
        <ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Listado de Usuarios"; ?></a></li>
        </ul>
    </dav>
<form method="post" id="frmusuadmin" action="/lua/">
    <table>
	<tr>
	<td><h3 class="confirm">Seleccione una entidad:</h3></td>
       	<td><select name="entidad" class="confirm">
       	<?php
       	if (isset($_SESSION['s_entidad'])){?>
            <option value="0">Todos</option>
            <?php while ($rsEnt_sin = mysql_fetch_assoc($queEnt_sin)){;
            if ($rsEnt_sin['id_entidad']==$entidad){echo "<option selected value='".$rsEnt_sin['id_entidad']."'>".$rsEnt_sin['entidad']."</option>";}
            else{echo "<option value='".$rsEnt_sin['id_entidad']."'>".$rsEnt_sin['entidad']."</option>";}}
	}else{ ?>
            <option value="">Seleccionar</option>
            <option selected value="0">Todos</option>
            <?php
            while ($rsEnt_sin = mysql_fetch_assoc($queEnt_sin)){echo "<option value='".$rsEnt_sin['id_entidad']."'>".$rsEnt_sin['entidad']."</option>";}
            }?>     
       	</select></td>
    	<input type="hidden">
    	<td><button type="submit">Aceptar</button></td>
	</tr>
    </table>
</form>
<?php
if($segmento){ 
?>
<div id="contenedor">
    <table id="myTable" width="600" border="0" cellspacing="0" cellpadding="5">
        <thead> 
            <tr>
        <?php
        if ($i==1){?>
                <th>Cedula</th>
                <th>Cliente</th>
                <th>Entidad</th>
                <th>Subentidad</th>
                <th>Nombres y Apellidos</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Usuario</th>
                <th>Estado</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        <thead> 
        <?php
            while ($rsUsu = mysql_fetch_assoc($segmento)) {
                $urleditua="/eu/".$rsUsu['id_cliente'];
                $urldeleteua="/du/".$rsUsu['id_cliente'];
                $urldesua="/iu/".$rsUsu['id_cliente'];
	?>
        <tbody> 
            <tr>
                <td><?php echo $rsUsu['cedula'];?></td>
                <td><?php echo $rsUsu['cliente'];?></td>
                <td><?php echo $rsUsu['entidad'];?></td>
                <td><?php echo $rsUsu['sube'];?></td>
                <td><?php echo $rsUsu['nombre'];?></td>
                <td><?php echo $rsUsu['direccion'];?></td>
                <td><?php echo $rsUsu['telefono'];?></td>
                <td><?php echo $rsUsu['usuario'];?></td>
        <?php if ($rsUsu['activo']==1){?>
                <td width=6><input type="checkbox" name="estado" checked="checked" disabled/></td> 
	<?php }else{ ?>
                <td width=6><input type="checkbox" name="estado" disabled/></td>
	<?php }	?>
                <td><a title="Editar" href=<?php echo $urleditua; ?>><img src="../images/edit.jpg" width="25" height="30"/></a></td>
                <td><a title="Eliminar" href=<?php echo $urldeleteua; ?>><img src="../images/delete.jpg" width="25" height="30"/></a></td>
                <td><a title="Desactivar" href=<?php echo $urldesua; ?>><img src="../images/desactivar.jpg" width="25" height="30"/></a></td>
            </tr>
        <?php } ?>
        </tbody>
        <?php } ?>
    </table>
</div>
<?php } ?>
<table>
    <tr>
        <td>
<form method="post" id="frmusu" action="/lua/">
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
	echo '<a title="Pagina Inicial" href="/lua/1"><img src="../images/primera.jpg" width="15" height="20" border="0"></a>  |  ';
	echo '<a title="Pagina Anterior" href="/lua/'.($page-1).'"><img src="../images/izq.gif" border="0"></a>  |  ';
    }
    for($i=0; $i<$max_num_paginas;$i++){
	echo '<a href="/lua/'.($i+1).'">'.($i+1).'</a> | ';
	}
    if ($page<$max_num_paginas){
	echo '<a title="Pagina Siguiente" href="/lua/'.($page+1).'"><img src="../images/der.gif" border="0"></a>  |  ';
	echo '<a title="Pagina Final" href="/lua/'.($max_num_paginas).'"><img src="../images/ultima.jpg" width="15" height="20" border="0"></a>  |  ';
    }
    if ($conteo>8){
	echo '<input type="hidden" id="pag" name="pag" value="'.$paginar.'">'
            .'<button type="submit">Mostrar Todo</button>  Pagina '.$page.' de '.$max_num_paginas;
    }
}
echo '</form>';
?>
        </td>
    </tr>
</table>
<table>
    <tr>
        <td><a title="Agregar" href=<?php echo $urladdua; ?>><img src="../images/add.jpg" width="25" height="30"/></a></td>
        <td></td>
        <td><a title="Regresar" href="/psa/"><img src="../images/regresar.jpg" width="25" height="30"/></a></td>
        <td></td>
        <td><a title="Exportar a Excel" href="/exus/"><img src="../images/exportar.gif" width="25" height="20"/></a></td>
    </tr>
</table>
</body>
</html>