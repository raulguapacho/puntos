<?php
include('db.php');

if(isset($_POST['idEntidad'])) {
	$subentidades = array();
	$sql = "SELECT id_sube, sube 
		FROM subentidad 
		WHERE id_entidad = ".$_POST['idEntidad']; 
	$db = obtenerConexion();
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