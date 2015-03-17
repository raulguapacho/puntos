<?php
include('db.php');

$db = obtenerConexion();

if(isset($_POST['cedula'])) {
	$subentidades = array();	
	$sql = "select b.id_sube, b.sube 
			from clientes a inner join subentidad b on a.id_sube=b.id_sube
			inner join usuarios c on a.id_usuario=c.id_usuario 
			where a.cedula=".$_POST['cedula']." and a.id_entidad=".$_POST['entidad']." and c.activo='0'";
		$result = ejecutarQuery($db, $sql);
		while($row = $result->fetch_assoc()){
			$subentidad = new subentidad($row['id_sube'], $row['sube']);
			array_push($subentidades, $subentidad);
		}
	cerrarConexion($db, $result);
	echo json_encode($subentidades);
}
	
class subentidad {
	public $id;
	public $sube;
	function __construct($id, $sube){
		$this->id = $id;
		$this->sube = $sube;
	}
}
?>