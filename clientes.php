<?php

include('db.php');

if(isset($_POST['idSubentidad'])) {
	$clientes = array();
	$sql = "SELECT id_cliente, CONCAT(nombre, ' ', apellidos) as nombre 
                FROM clientes
		WHERE id_sube = ".$_POST['idSubentidad']; 
	$db = obtenerConexion();
	$result = ejecutarQuery($db, $sql);
	while($row = $result->fetch_assoc()){
            $cliente = new cliente($row['id_cliente'], $row['nombre']);
	    array_push($clientes, $cliente);
	}
	cerrarConexion($db, $result);
	echo json_encode($clientes);
}
	
class cliente {
	public $id;
	public $nombre;
	function __construct($id, $nombre){
		$this->id = $id;
		$this->nombre = $nombre;
	}
}
?>
