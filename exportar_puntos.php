<?php
session_start();
//include('conexion.php');
require_once ('Classes/PHPExcel.php');

$db_host="localhost";
$db_usuario="raul_guapacho";
$db_password="Zaq1xsw2-.";
$db_nombre="prueba";
$conexion = @mysql_connect($db_host, $db_usuario, $db_password) or die(mysql_error());
$db = @mysql_select_db($db_nombre, $conexion) or die(mysql_error());

$objPHPExcel = new PHPExcel();

$objPHPExcel->getDefaultStyle()->getFont()->setName('Arial');
$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);

$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nombre')
                    ->setCellValue('B1', 'Entidad')
                    ->setCellValue('C1', 'Subentidad')
                    ->setCellValue('D1', 'Puntos')
                    ->setCellValue('E1', 'Vencimiento')
                    ->setCellValue('F1', 'Fee')
                    ->setCellValue('G1', 'Creado')
                    ->setCellValue('H1', 'Actualizado');

$rol=$_SESSION['s_rol'];

if ($rol==1){
    if (($_SESSION['s_entidade']>0) and ($_SESSION['s_sube']==0) and ($_SESSION['s_cliente']==0)){
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube
                WHERE a.id_entidad=".$_SESSION['s_entidade'];
    }elseif(($_SESSION['s_entidade']>0) and ($_SESSION['s_sube']>0) and ($_SESSION['s_cliente']==0)){
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube
                WHERE a.id_entidad=".$_SESSION['s_entidade']." and a.id_sube=".$_SESSION['s_sube'];
    }elseif(($_SESSION['s_entidade']>0) and ($_SESSION['s_sube']>0) and ($_SESSION['s_cliente']>0)){
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube
                WHERE a.id_entidad=".$_SESSION['s_entidade']." and a.id_sube=".$_SESSION['s_sube']." and id_cliente=".$_SESSION['s_cliente'];
    }else{
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube";
    }
}else{
    $entidad=$_SESSION['s_entidad'];
    if (($_SESSION['s_sube']==0) and ($_SESSION['s_cliente']==0)){
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube
                WHERE a.id_entidad=".$entidad;
    }elseif(($_SESSION['s_sube']>0) and ($_SESSION['s_cliente']==0)){
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube
                WHERE a.id_entidad=".$entidad." and a.id_sube=".$_SESSION['s_sube'];
    }elseif(($_SESSION['s_sube']>0) and ($_SESSION['s_cliente']>0)){
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube
                WHERE a.id_entidad=".$entidad." and a.id_sube=".$_SESSION['s_sube']." and a.id_cliente=".$_SESSION['s_cliente'];
    }else{
        $query="SELECT CONCAT(a.nombre,' ',a.apellidos) as nombre, c.entidad, d.sube, b.points, b.fec_ven_puntos, b.fee, b.creado, b.actualizado 
                FROM clientes a inner join puntos b on a.id_cliente=b.id_client
                INNER JOIN entidad c on a.id_entidad=c.id_entidad
                INNER JOIN subentidad d on a.id_sube=d.id_sube
                WHERE a.id_entidad=".$entidad;
    }
}
$puntos=  mysql_query($query) or die (mysql_error());

$i=2;
while ($fila = mysql_fetch_assoc($puntos)) {
	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A'.$i, $fila['nombre'])
	->setCellValue('B'.$i, $fila['entidad'])
	->setCellValue('C'.$i, $fila['sube'])
        ->setCellValue('D'.$i, $fila['points'])
	->setCellValue('E'.$i, $fila['fec_ven_puntos'])
        ->setCellValue('F'.$i, $fila['fee'])
	->setCellValue('G'.$i, $fila['creado'])
	->setCellValue('H'.$i, $fila['actualizado']);
     $i++;
}	

$objPHPExcel->getActiveSheet()->setTitle('puntos');

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=puntos.xlsx");
header("Pragma: no-cache");
header("Expires: 0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');	
?>