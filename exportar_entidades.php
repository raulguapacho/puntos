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
                    ->setCellValue('B1', 'Creada')
                    ->setCellValue('C1', 'Actualizada');

$query="SELECT entidad, creada, actualizada FROM entidad";
$entidades=  mysql_query($query) or die (mysql_error());

$i=2;
while ($fila = mysql_fetch_assoc($entidades)) {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$i, $fila['entidad'])
        ->setCellValue('B'.$i, $fila['creada'])
        ->setCellValue('C'.$i, $fila['actualizada']);
    $i++;
}	

$objPHPExcel->getActiveSheet()->setTitle('entidades');

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=entidades.xlsx");
header("Pragma: no-cache");
header("Expires: 0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');	
?>