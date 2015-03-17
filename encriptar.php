<?php
include('Classes/endesen.php');
$texto = "Son unos corruptos";
// Encriptamos el texto
$texto_encriptado = Encrypter::encrypt($texto);
// Desencriptamos el texto
$texto_original = Encrypter::decrypt($texto_encriptado);
if ($texto == $texto_original) echo '<br/>'.'Encriptación / Desencriptación realizada correctamente.';
?>