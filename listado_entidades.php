<?php
//session_start();
include ('inactividad.php');
include('conexion.php');
include('Classes/dm.php');

if (isset($_POST['pag'])){
    $paginar=$_POST['pag'];
}else{
    $paginar=1;
}

if ($paginar==1){
    $page = 1;
    if(array_key_exists('pg', $_GET)){
	$page = $_GET['pg'];
    }
    $query_ent="SELECT COUNT(*) as conteo FROM entidad";
    $conteo_ent = @mysql_query($query_ent) or die (mysql_error());
    $conteo = "";
    $obj = mysql_fetch_assoc($conteo_ent);
    if($conteo_ent){
    	$conteo =$obj['conteo'];
    }
    $max_num_paginas = ceil($conteo/10);
    $query_limit="SELECT id_entidad, entidad, activa FROM entidad where id_entidad>0 LIMIT ".(($page-1)*10).", 10";
    $segmento = @mysql_query($query_limit) or die (mysql_error());
}else{
    $query_limit="SELECT id_entidad, entidad, activa FROM entidad where id_entidad>0";
    $segmento = @mysql_query($query_limit) or die (mysql_error());
}
$urladdent="/ae/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> : : Entidades : : </title>
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
    <script>
    function defecto(entidad){
        var defecto1=entidad;
        $.post("../defecto.php", {defecto:defecto1});
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
            <li><a><?php echo "Listado de Entidades"; ?></a></li>
	</ul>
    </dav>
<?php
if($segmento){ 
?>
    <div id="contenedor">
	<table id="myTable" class="tablesorter" width="600" border="0" cellspacing="0" cellpadding="0">
            <thead> 
                <tr>
                    <th title="click para ordenar" ><u>Entidad</u></th>
                    <th>Estado</th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
		</tr>
            </thead> 
            <tbody>
            <?php 
            while ($rsEnt = mysql_fetch_assoc($segmento)) {
            $urleditent="/ee/".$rsEnt['id_entidad'];
            $urldeleteent="/de/".$rsEnt['id_entidad'];
            $urldesent="/ie/".$rsEnt['id_entidad'];
            $defecto="javascript:defecto(".$rsEnt['id_entidad'].")";
            ?>
                <tr>
                    <td><?php echo $rsEnt['entidad'];?></td>
                    <?php if ($rsEnt['activa']==1){ ?>
                        <td><input type="checkbox" checked="checked" disabled/></td>
                        <td></td>
                    <?php }else{ ?>
                        <td><input type="checkbox" disabled/></td>
                        <td><a title="Editar" href=<?php echo $urleditent; ?>><img src="../images/edit.jpg" width="25" height="30"/></a></td>
                    <?php } ?>
                    <!--<td><a title="Editar" href=<?php echo $urleditent; ?>><img src="../images/edit.jpg" width="25" height="30"/></a></td>-->
                    <td><a title="Eliminar" href=<?php echo $urldeleteent; ?>><img src="../images/delete.jpg" width="25" height="30"/></a></td>
                    <td><a title="Desactivar" href=<?php echo $urldesent; ?>><img src="../images/desactivar.jpg" width="25" height="30"/></a></td>
                    <td><a href=<?php echo $defecto ?>>></a></td>
		</tr>
            <?php } ?>
            </tbody>
	</table>
    </div>
<table>
<?php } ?>
    <tr>
	<td>
<form method="post" id="frmUsuario" action="/le/"/>
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
	echo '<a title="Pagina Inicial" href="/le/1"><img src="../images/primera.jpg" width="15" height="20" border="0"></a>  |  ';
	echo '<a title="Pagina Anterior" href="/le/'.($page-1).'"><img src="../images/izq.gif" border="0"></a>  |  ';
    }
    for($i=0; $i<$max_num_paginas;$i++){
	echo '<a href="/le/'.($i+1).'">'.($i+1).'</a> | ';
    }
    if ($page<$max_num_paginas){
    	echo '<a title="Pagina Siguiente" href="/le/'.($page+1).'"><img src="../images/der.gif" border="0"></a>  |  ';
	echo '<a title="Pagina Final" href="/le/'.($max_num_paginas).'"><img src="../images/ultima.jpg" width="15" height="20" border="0"></a>  |  ';
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
        <td><a title="Agregar" href=<?php echo $urladdent; ?>><img src="../images/add.jpg" width="25" height="30"/></a></td>
        <td></td>
	<td><a title="Regresar" href="/psa/"><img src="../images/regresar.jpg" width="25" height="30"/></a></td>
        <td></td>
        <td><a title="Exportar a Excel" href="/exen/"><img src="../images/exportar.gif" width="25" height="20"/></a></td>
    </tr>
</table>
</body>
</html>