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

if ($paginar==1){
    $page = 1;
    if(array_key_exists('pg', $_GET)){
    	$page = $_GET['pg'];
    }
    $query_ent="SELECT COUNT(*) as conteo FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
    inner join entidad c on a.id_entidad=c.id_entidad
    where b.id_rol='2'";
    $conteo_ent = @mysql_query($query_ent) or die (mysql_error());
    $conteo = "";
    $obj = mysql_fetch_assoc($conteo_ent);
    if($conteo_ent){
    	$conteo =$obj['conteo'];
    }
    $max_num_paginas = ceil($conteo/10);
    $query_limit="select a.id_cliente, a.cedula, c.entidad, CONCAT (a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, b.usuario, b.activo 
                  FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
                  inner join entidad c on a.id_entidad=c.id_entidad
                  where b.id_rol='2' LIMIT ".(($page-1)*10).", 10";
}else{
    $query_limit="select a.id_cliente, a.cedula, c.entidad, CONCAT (a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, b.usuario, b.activo 
                  FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
                  inner join entidad c on a.id_entidad=c.id_entidad
                  where b.id_rol='2'";
}
$segmento = @mysql_query($query_limit) or die (mysql_error());
$urladdadmin="/aa/";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> : : Administradores : : </title>
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
    <script src="../js/modernizr.js"></script>
    <link rel="stylesheet" href="../css/nav.css"/>
    <link rel="stylesheet" href="../css/styles.css"/>
</head>
<body>
    <dav>
       	<ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            <li><a><?php echo "Listado de Administradores"; ?></a></li>
	</ul>
    </dav>
<?php
if($segmento){ 
?>
    <div id="contenedor">
        <table id="myTable" class="tablesorter" width="600" border="0" cellspacing="0" cellpadding="0">
            <thead>
		<tr>
                    <th>Cedula</th>
                    <th>Entidad</th>
                    <th>Nombre</th>
                    <th>Direccion</th>
                    <th>Telefono</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th></th>
                    <th></th>
		</tr>
            </thead>
    <?php while ($rsUsu = mysql_fetch_assoc($segmento)) {
	$urleditadmin="/ea/".$rsUsu['id_cliente'];
	$urldeleteadmin="/da/".$rsUsu['id_cliente'];
    ?>
            <tbody>
                <tr>
                    <td><?php echo $rsUsu['cedula'];?></td>
                    <td><?php echo $rsUsu['entidad'];?></td>
                    <td><?php echo $rsUsu['nombre'];?></td>
                    <td><?php echo $rsUsu['direccion'];?></td>
                    <td><?php echo $rsUsu['telefono'];?></td>
                    <td><?php echo $rsUsu['usuario'];?></td>
                    <td><?php if ($rsUsu['activo']==1){?>
                        <input disabled type="checkbox" name="estado" checked="checked"/>
                    <?php }else{ ?>
                        <input disabled type="checkbox" name="estado"/>                   
                    <?php } ?>
                    </td>
                    <td><a title="Editar" href=<?php echo $urleditadmin; ?>><img src="../images/edit.jpg" width="25" height="30"/></a></td>
                    <td><a title="Eliminar" href=<?php echo $urldeleteadmin; ?>><img src="../images/delete.jpg" width="25" height="30"/></a></td>
		</tr>
    <?php } ?>
            </tbody>
	</table>
    </div>
<table>
<?php } ?>
    <tr>
	<td>
<form method="post" id="frmUsuario" action="/la/">
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
        echo '<a title="Pagina Inicial" href="/la/1"><img src="../images/primera.jpg" width="15" height="20" border="0"></a>  |  ';
        echo '<a title="Pagina Anterior" href="/la/'.($page-1).'"><img src="../images/izq.gif" border="0"></a>  |  ';
    }
    for($i=0; $i<$max_num_paginas;$i++){
        echo '<a href="/la/'.($i+1).'">'.($i+1).'</a> | ';
    }
    if ($page<$max_num_paginas){
        echo '<a title="Pagina Siguiente" href="/la/'.($page+1).'"><img src="../images/der.gif" border="0"></a>  |  ';
        echo '<a title="Pagina Final" href="/la/'.($max_num_paginas).'"><img src="../images/ultima.jpg" width="15" height="20" border="0"></a>  |  ';
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
        <td><a title="Agregar" href=<?php echo $urladdadmin; ?>><img src="../images/add.jpg" width="25" height="30"/></a></td>
        <td></td>
	<td><a title="Regresar" href="/psa/"><img src="../images/regresar.jpg" width="25" height="30"/></a></td>
        <td></td>
        <td><a title="Exportar a Excel" href="/exad/"><img src="../images/exportar.gif" width="25" height="20"/></a></td>
    </tr>
</table>
</body>
</html>