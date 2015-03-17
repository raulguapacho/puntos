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
                    ->setCellValue('A1', 'Entidad')
                    ->setCellValue('B1', 'Subentidad')
                    ->setCellValue('C1', 'Valor Punto')
                    ->setCellValue('D1', 'Creada')
                    ->setCellValue('E1', 'Actualizada');

$rol=$_SESSION['s_rol'];

if ($rol==1){
    $query="SELECT a.entidad, b.sube, b.val_punto, b.creada, b.actualizada
            FROM entidad a inner join subentidad b on a.id_entidad=b.id_entidad";
}else{
    $entidad=$_SESSION['s_entidad'];
    $query="SELECT a.entidad, b.sube, b.val_punto, b.creada, b.actualizada
            FROM entidad a inner join subentidad b on a.id_entidad=b.id_entidad
            where a.id_entidad=".$entidad;
}
$subes=  mysql_query($query) or die (mysql_error());

$i=2;
while ($fila = mysql_fetch_assoc($subes)) {
	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A'.$i, $fila['entidad'])
	->setCellValue('B'.$i, $fila['sube'])
	->setCellValue('C'.$i, $fila['val_punto'])
        ->setCellValue('D'.$i, $fila['creada'])
	->setCellValue('E'.$i, $fila['actualizada']);
     $i++;
}	

$objPHPExcel->getActiveSheet()->setTitle('subentidades');

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=subentidades.xlsx");
header("Pragma: no-cache");
header("Expires: 0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');	
?>