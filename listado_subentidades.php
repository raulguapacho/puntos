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
if (isset($_SESSION['s_entidad'])){
    $entidad=$_SESSION['s_entidad'];
    if ($paginar==1){
    	$page = 1;
    	if(array_key_exists('pg', $_GET)){$page = $_GET['pg'];}
	$query_ent="SELECT COUNT(*) as conteo FROM subentidad where id_entidad=".$entidad;
	$conteo_ent = @mysql_query($query_ent) or die (mysql_error());
	$conteo = "";
	$obj = mysql_fetch_assoc($conteo_ent);
	if($conteo_ent){$conteo =$obj['conteo'];}
            $max_num_paginas = ceil($conteo/10);
            $query_limit="SELECT id_entidad, id_sube, sube, val_punto FROM subentidad where id_entidad=".$entidad." LIMIT ".(($page-1)*10).", 10";
            $segmento = @mysql_query($query_limit) or die (mysql_error());
	}else{
            $query_limit="SELECT id_entidad, id_sube, sube, val_punto FROM subentidad where id_entidad=".$entidad;
            $segmento = @mysql_query($query_limit) or die (mysql_error());
	}
    $query="SELECT id_entidad, entidad FROM entidad where id_entidad=".$entidad;
    $queEnt=@mysql_query($query) or die (mysql_error());
    $rsEnt = mysql_fetch_assoc($queEnt);
}	
$urladdsa="/as/";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Subentidades : : </title>
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
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Listado de Subentidades"; ?></a></li>
	</ul>
    </dav>
<?php
if($segmento){ 
?>
<div id="contenedor">
    <table id="myTable" width="600" border="0" cellspacing="0" cellpadding="0">
	<thead> 
            <tr>
		<th>Subentidades</th>
		<th>Valor Punto</th>
		<th></th>
		<th></th>
            </tr>
	</thead> 
	<tbody> 
	<?php while ($rssEnt = mysql_fetch_assoc($segmento)) { 
            $urleditsa="/es/".$rssEnt['id_sube'];
            $urldeletesa="/ds/".$rssEnt['id_sube'];
	?>	
            <tr>
		<td><?php echo $rssEnt['sube']; ?></td>
		<td><?php echo $rssEnt['val_punto']; ?></td>
		<td><a title="editar" href=<?php echo $urleditsa ?>><img src="../images/edit.jpg" width="25" height="30"/></a></td>
		<td><a title="eliminar" href=<?php echo $urldeletesa ?>><img src="../images/delete.jpg" width="25" height="30"/></a></td>
            </tr> 
	<?php } ?>
        </tbody> 
    </table>
</div>
<table>
<?php } ?>
    <tr>
        <td>
<form method="post" id="frmUsuario" action="/ls/"/>
<?php
if ($paginar==0){
    $paginar=1;
    echo '<table>
            <tr>
                <form><input id="searchTerm" placeholder="Buscar" type="text" onkeyup="doSearch()"/></form>
		<input type="hidden" id="pag" name="pag" value="'.$paginar.'"><button type="submit">Paginar</button>
            </tr>
	</table>';
}else{
    $paginar=0;
    if ($page>1){
    	echo '<a title="Pagina Inicial" href="/ls/1"><img src="../images/primera.jpg" width="15" height="20" border="0"></a>  |  ';
    	echo '<a title="Pagina Anterior" href="/ls/'.($page-1).'"><img src="../images/izq.gif" border="0"></a>  |  ';
    }
    for($i=0; $i<$max_num_paginas;$i++){
    	echo '<a href="/ls/'.($i+1).'">'.($i+1).'</a> | ';
    }
    if ($page<$max_num_paginas){
    	echo '<a title="Pagina Siguiente" href="/ls/'.($page+1).'"><img src="../images/der.gif" border="0"></a>  |  ';
    	echo '<a title="Pagina Final" href="/ls/'.($max_num_paginas).'"><img src="../images/ultima.jpg" width="15" height="20" border="0"></a>  |  ';
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
	<td><a title="agregar" href=<?php echo $urladdsa ?>><img src="../images/add.jpg" width="25" height="30"/></a></td>
        <td></td>
	<td><a title="regresar" href="/pa/"><img src="../images/regresar.jpg" width="25" height="30"/></a></td>
        <td></td>
        <td><a title="Exportar a Excel" href="/exsu/"><img src="../images/exportar.gif" width="25" height="20"/></a></td>
    </tr>
</table>	
</body>
</html>