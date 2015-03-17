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
                    ->setCellValue('A1', 'Cedula')
                    ->setCellValue('B1', 'Nombre')
                    ->setCellValue('C1', 'Entidad')
                    ->setCellValue('D1', 'Direccion')
                    ->setCellValue('E1', 'Telefono')
                    ->setCellValue('F1', 'Email')
                    ->setCellValue('G1', 'Estado');

$query="SELECT a.cedula, CONCAT(a.nombre,' ',a.apellidos) as nombre, b.entidad, a.direccion, a.telefono, a.email, c.activo
        FROM clientes a inner join entidad b on a.id_entidad=b.id_entidad
        inner join usuarios c on a.id_usuario=c.id_usuario
        where c.id_rol=2";
$admin=  mysql_query($query) or die (mysql_error());

$i=2;
while ($fila = mysql_fetch_assoc($admin)) {
	if ($fila['activo']==1){
		$estado='Activo';
	}else{
		$estado='Inactivo';
	}
	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A'.$i, $fila['cedula'])
	->setCellValue('B'.$i, $fila['nombre'])
	->setCellValue('C'.$i, $fila['entidad'])
        ->setCellValue('D'.$i, $fila['direccion'])
	->setCellValue('E'.$i, $fila['telefono'])
	->setCellValue('F'.$i, $fila['email'])
	->setCellValue('G'.$i, $estado);
     $i++;
}	

$objPHPExcel->getActiveSheet()->setTitle('administradores');

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=administradores.xlsx");
header("Pragma: no-cache");
header("Expires: 0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');	
?>