<?php
//session_start();
include('inactividad.php');
include ('conexion.php');
include('Classes/dm.php');
$rol=$_SESSION['s_rol'];

if ($rol==2){
    $entidad=$_SESSION['s_entidad'];
    $query="select id_entidad, entidad from entidad where id_entidad=".$entidad;
    $queEnt=@mysql_query($query) or die (mysql_error());
    $rsEnt=mysql_fetch_array($queEnt);
    $entidad_sel=$rsEnt['entidad'];
    $titulo="Entidad ".$entidad_sel;
}
?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title> : : Importar Archivo de usuarios : : </title>
<script src="../js/modernizr.js"></script>
<link rel="stylesheet" href="../css/nav.css"/>
<link rel="stylesheet" href="../css/estilo.css">

</head>
<body>
<!-- FORMULARIO PARA SOLICITAR LA CARGA DEL EXCEL -->
    <?php if ($rol==1){?>
        <dav>
            <ul>
		<li><a><?php echo "Administración de Tienda"; ?></a></li>
		<li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
		<li><a>Importar Usuarios</a></li>
            </ul>
	</dav>
    <?php }else{ ?>
	<dav>
            <ul>
                <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
		<li><a>Importat Usuario para <?php echo $titulo; ?></a></li>
            </ul>
	</dav>
    <?php } ?>
    <div id="contenedor" class="content">
	<h3>Selecciona el archivo a importar:</h3>
	<a href="../images/clientes.xlsx" download="clientes.xlsx">Descargar Plantilla</a>
	<form name="importa" method="post" action="/re/" enctype="multipart/form-data" >
            <h2>________________________________</h2>
            <input type="file" name="excel" />
            <input type="hidden" value="upload" name="action" />
            <input type='submit' name='enviar'  value="Importar"  />
            <h2>________________________________</h2>
	</form>
	<?php if ($rol==1){ ?>
            <p><a href="/psa/">Inicio</a></p>
	<?php }else{ ?>
            <p><a href="/pa/">Inicio</a></p>
	<?php } ?>
    </div>
</body>