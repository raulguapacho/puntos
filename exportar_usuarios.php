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
                    ->setCellValue('B1', 'Cliente')
                    ->setCellValue('C1', 'Entidad')
                    ->setCellValue('D1', 'Subentidad')
					->setCellValue('E1', 'Nombre')
                    ->setCellValue('F1', 'Direccion')
                    ->setCellValue('G1', 'Telefono')
                    ->setCellValue('H1', 'Email')
					->setCellValue('I1', 'Estado');

$rol=$_SESSION['s_rol'];

if ($rol==1){
    $query="SELECT a.id_cliente, a.cedula, a.cliente, c.entidad, d.sube, CONCAT(a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, b.usuario, b.activo 
	FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
	inner join entidad c on a.id_entidad=c.id_entidad
	inner join subentidad d on a.id_sube=d.id_sube
	where b.id_rol=3";
}else{
    $entidad=$_SESSION['s_entidad'];
    $query="SELECT a.id_cliente, a.cedula, a.cliente, c.entidad, d.sube, CONCAT(a.nombre,' ',a.apellidos) as nombre, a.direccion, a.telefono, b.usuario, b.activo 
	FROM clientes a inner join usuarios b on a.id_usuario=b.id_usuario
	inner join entidad c on a.id_entidad=c.id_entidad
	inner join subentidad d on a.id_sube=d.id_sube
	where a.id_entidad=".$entidad." and b.id_rol=3";
}
$usuarios=  mysql_query($query) or die (mysql_error());

$i=2;
while ($fila = mysql_fetch_assoc($usuarios)) {
	if ($fila['activo']==1){
		$estado='Activo';
	}else{
		$estado='Inactivo';
	}
	$objPHPExcel->setActiveSheetIndex(0)
    	->setCellValue('A'.$i, $fila['cedula'])
	->setCellValue('B'.$i, $fila['cliente'])
	->setCellValue('C'.$i, $fila['entidad'])
        ->setCellValue('D'.$i, $fila['sube'])
	->setCellValue('E'.$i, $fila['nombre'])
	->setCellValue('F'.$i, $fila['direccion'])
	->setCellValue('G'.$i, $fila['telefono'])
	->setCellValue('H'.$i, $fila['usuario'])
	->setCellValue('I'.$i, $estado);
     $i++;
}	

$objPHPExcel->getActiveSheet()->setTitle('usuarios');

header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=usuarios.xlsx");
header("Pragma: no-cache");
header("Expires: 0");

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');	
?>