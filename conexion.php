<?php
    $db_host="localhost";
    $db_usuario="raul_guapacho";
    $db_password="Zaq1xsw2-.";
    $db_nombre="prueba";
    $conexion = @mysql_connect($db_host, $db_usuario, $db_password) or die(mysql_error());
    $db = @mysql_select_db($db_nombre, $conexion) or die(mysql_error());
?> 