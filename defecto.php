<?php
include('conexion.php');
$entidaddefecto=$_POST['defecto'];
$sql_entdefecto="update entidad set defecto=1 where id_entidad=".$entidaddefecto;
$sql_entNdefecto="update entidad set defecto=0 where id_entidad!=".$entidaddefecto;
mysql_query($sql_entdefecto) or die (mysql_error());
mysql_query($sql_entNdefecto) or die (mysql_error());
?>