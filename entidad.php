<?php
include('db.php');

function obtenerTodasLasEntidades() {
    $entidades = array();
    $sql = "SELECT id_entidad, entidad 
	    FROM entidad where id_entidad>0"; 
    $db = obtenerConexion();
    $result = ejecutarQuery($db, $sql);
    while($row = $result->fetch_assoc()){
	$entidad = new entidad($row['id_entidad'], $row['entidad']);
        array_push($entidades, $entidad);
    }
cerrarConexion($db, $result);
return $entidades;
}

class entidad {
    public $id;
    public $entidad;
    function __construct($id, $entidad) {
	$this->id = $id;
	$this->entidad = $entidad;
    }
}
?>