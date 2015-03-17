<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <script src="../js/sweet-alert.js"></script>
    <link rel="stylesheet" href="../css/sweet-alert.css"/>
</head>
<body>
<?php 
session_start();

    // establecemos el tiempo de espera en segundos
    $inactivo = 500;

    // verificamos que ya exista un valor para timeout
    if (isset($_SESSION["timeout"])) {

        // calculamos el tiempo que lleva la sesión
        $tiempoSession = time() - $_SESSION["timeout"];

        // si se pasó el umbral de inactividad
        if ($tiempoSession > $inactivo) {

            // destruimos la sesión y desconectamos al usuario
            session_destroy();
            echo "<script> swal({title: 'Información', text: 'La sesión ha finalizado', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
            //header("Location: /admin_puntos/lg/");
        }
    }
// el usuario interactúa por primera vez
    $_SESSION["timeout"] = time();
?>
</body>
</html>
    
<script>
function redireccionar(){
  window.location.href="/ex/";
}
</script>