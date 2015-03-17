<?php
    function obtenerConexion() {
        $db = new mysqli('localhost', 'raul_guapacho', 'Zaq1xsw2-.', 'prueba');
        if($db->connect_errno > 0){
            die('No es posible conectarse a la bd [' . $db->connect_error . ']');
        }
        return $db; 
    }
    function cerrarConexion($db, $query) {
        $query->free();
        $db->close();
    }
    function ejecutarQuery($db, $sql) {
	if(!$resultado = $db->query($sql)){
	    die('Error al ejecutar la consulta [' . $db->error . ']');
	}

    return $resultado;
    }
?>