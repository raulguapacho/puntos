<?php
include('conexion.php');
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> : : Activar Usuario : : </title>
<meta charset="utf-8">
<link href="estilo.css" rel="stylesheet">
</head>
<body>
    <div id="contenedor">
        <h3>Agregar, Editar y/o Eliminar entidades</h3>
	<form action="modifica_entidades.php" method="post">
	<div id="email" class="caja">
            <table class="editinplace">
            <tr>
                <th>Entidad</th>
            </tr>
            </table>
	</div>	
	</form>
	<p><a href="login.php">Ingreso de usuarios registrados</a></p>
    </div>
</body>