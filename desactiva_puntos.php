<?php
include ('conexionpuntos.php');
$hoy=date("Y-m-d");
$sql="select id, fec_ven_puntos from ps_customer_points where fec_ven_puntos<CURDATE() and activo=1";
$qpuntos=mysql_query($sql);
if (mysql_num_rows($qpuntos)>1){
    while ($rspuntos=  mysql_fetch_assoc($qpuntos)){
        $id_puntos=$rspuntos['id'];
        $updatesql="update ps_customer_points set activo=0, actualizado='".$hoy."' where id=".$id_puntos;
        mysql_query($updatesql);
        echo "Se desactivaron los puntos del registro con id # ".$id_puntos."<br>";
    }
}else{
    $rspuntos=  mysql_fetch_assoc($qpuntos);
    $id_puntos=$rspuntos['id'];
    $updatesql="update ps_customer_points set activo=0, actualizado='".$hoy."' where id=".$id_puntos;
    mysql_query($updatesql);
    echo "Se desactivaron los puntos del registro con id # ".$id_puntos."<br>";
}