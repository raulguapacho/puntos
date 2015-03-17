<?php
//session_start();
include ('inactividad.php');
unset ($_SESSION['key'],$_SESSION['s_email']);
unset ($_SESSION['ex']);
$_SESSION['s_entidad']=0;
$_SESSION['s_entidade']=0;
$_SESSION['s_sube']=0;
$_SESSION['s_cliente']=0;
include('Classes/dm.php');
$urllistadoadm="/la/";
$urlimportar="/im/";
$urllistadousu="/lua/";
$urllistadopun="/lp/";
$urllistadored="/lr/";
$urllistadoent="/le/";
$urllistadosub="/lsa/";
$urlsalir="/lg/";
?>
	
<html lang="en">
    <!--// Sección principal o cabecera de la página -->
<head>
    <meta charset="utf-8">
    <title>Menu Principal</title>
    <script src="../js/jquery.js"></script>
    <script src="../js/modernizr.js"></script>
    <!--// Incluir el archivo CSS3 principal -->
    <link rel="stylesheet" href="../css/nav.css">
        <!--[if IE]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!--// Si se utiliza ie7, se cargan estos archivos css -->        
        <!--[if IE 7]>
    <link rel="stylesheet" href="css/ie.css">
    <link rel="stylesheet" href="css/ie7.css">
        <![endif]-->
</head>
<body class="no-js">
    <script>
	var el = document.getElementsByTagName("body")[0];
	el.className = "";
    </script>
    <noscript>
       	<!--[if IE]>
       	<link rel="stylesheet" href="css/ie.css">
        <![endif]-->
    </noscript>
    <!--// Comienzo de la estructura del menú -->
    <nav id="topNav">
	<dav>
            <ul>
		<li><a><?php echo "Administración de Tienda"; ?></a></li>
		<li><a><?php echo $_SESSION['s_usuario'] ?></a></li>
		<li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
            </ul>
	</dav>
            <ul>
		<li><a href="<?php echo $urllistadoent; ?>" title="Agregar Entidades">Entidades</a></li>
		<li><a href="<?php echo $urllistadosub; ?>" title="Agregar Subentidades">Subentidades</a></li>
		<li><a href="#" title="Agregar y/o Listar Usuarios">Usuarios</a>
                <ul>
                    <li><a href="<?php echo $urlimportar; ?>" title="Cargar Archivo">Importar Usuarios</a></li>
                    <li><a href="<?php echo $urllistadousu; ?>" title="Listar Usuarios">Usuarios</a></li>
                    <li><a href="<?php echo $urllistadopun; ?>" title="Listar Puntos de Usuarios">Puntos</a></li>
                    <li><a href="<?php echo $urllistadored; ?>" title="Listar Redenciones de Usuarios">Redenciones</a></li>
                </ul>
		</li>
		<li><a href="<?php echo $urllistadoadm;?>" title="Agregar Administradores de Entidades">Administradores</a></li>
		<li><a href="<?php echo $urlsalir; ?>" title="Salir del Aplicativo">Salir</a></li>
            </ul>
    </nav>
    <div class="content">
        <!--Aquí el resto del contenido de la página web.-->
    </div>
        <!--// Inclusión de los archivos Javascript -->
    <script>
	(function($){
            var nav = $("#topNav");
            nav.find("li").each(function() {
                if ($(this).find("ul").length > 0) {
		$("<span>").text("^").appendTo($(this).children(":first"));
                    $(this).mouseenter(function() {
			$(this).find("ul").stop(true, true).slideDown();
                    });
                    $(this).mouseleave(function() {
			$(this).find("ul").stop(true, true).slideUp();
                    });
		}
            });
	})(jQuery);
    </script>
</body>
</html>