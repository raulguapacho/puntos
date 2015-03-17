<?php
session_start();
include ('conexion.php');
if (isset($_SESSION['s_cedulae'])){
    $cedulae=$_SESSION['s_cedulae'];
    $sqlcent="select a.id_entidad, b.clave from clientes a inner join usuarios b on a.id_usuario=b.id_usuario 
              where a.cedula=".$cedulae." and b.activo='1' group by id_entidad";
    $quecent=@mysql_query($sqlcent) or die (mysql_error());
    $entidades=mysql_num_rows($quecent);
    if ($entidades>1){
    	$i=1;
    	while ($rscent=mysql_fetch_assoc($quecent)){
            $sqlent="select id_entidad, entidad from entidad where id_entidad=".$rscent['id_entidad'];
            $queent[$i]=@mysql_query($sqlent) or die (mysql_error());
            $i+=1;
	}
    }else{
	$rscent=mysql_fetch_assoc($quecent);
	$sqlent="select id_entidad, entidad from entidad where id_entidad=".$rscent['id_entidad'];
	$queent=@mysql_query($sqlent) or die (mysql_error());
	$rsent=mysql_fetch_assoc($queent);
	$sqlcsub="select id_sube from clientes a inner join usuarios b on a.id_usuario=b.id_usuario where a.cedula=".$cedulae." and b.activo='1'";
	$quecsub=@mysql_query($sqlcsub) or die (mysql_error());
	$subes=mysql_num_rows($quecsub);
	if ($subes>1){
            $j=1;
            while ($rscsub=mysql_fetch_assoc($quecsub)){
		$sqlsub="select id_sube, sube from subentidad where id_sube=".$rscsub['id_sube']." and id_entidad=".$rsent['id_entidad'];
		$quesub[$j]=@mysql_query($sqlsub) or die (mysql_error());
		$j+=1;
            }
	}
    }
}
echo "Bienvenid@, ".$_SESSION['s_usuario']."<br/>";
$i=1;
$j=1;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title> : : Seleccionar : : </title>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript" src="../Classes/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="../Classes/jquery-ui-1.8.23.custom.min.js"></script>
    <script>
    $(document).ready(function(){
    	$("#cboEntidades").change(function() {
            var entidad = $(this).val();
            var cedula = <?php echo $cedulae ?>;
            if(entidad > 0){
                $.post("../sub_filtra.php",{entidad, cedula}, function(subentidades) {     	
                    var $comboSubentidades = $("#cboSubentidades");
                    $comboSubentidades.empty();
                    $.each(subentidades, function(index, subentidad) {
                        $comboSubentidades.append("<option value=" + subentidad.id + ">" + subentidad.sube + "</option>");
                    });
                }, 'json');
            }else{
		var $comboSubentidades = $("#cboSubentidades");
	        $comboSubentidades.empty();
                $comboSubentidades.append("<option>Seleccione una entidad</option>");
            }
	});
    }); 
    </script>
    <link href="../estilo.css" rel="stylesheet" type="text/css" />
</head>
<body>
<h3>Seleccione Entidad y/o Subentidad : </h3>
    <form action="../puntos/usuario.php" method="post">
        <div id="contenedor">
	<?php if ($entidades==1){ ?>
	<label for="Entidad">Entidad : </label>
	<input disabled type="text" value="<?php echo $rsent['entidad']?>"/>
	<br />
	<input type="hidden" name="tien" value="<?php echo $rsent['id_entidad']?>"/>
        <br />
	<label for="Subentidad">Subentidad : </label>
	<select name="besu" title="Despliegue para seleccionar una subentidad">
	<option value="">Seleccionar</option>
	<?php while ($rssub[$j]=mysql_fetch_assoc($quesub[$j]))
            {echo "<option value='".$rssub[$j]['id_sube']."'>".$rssub[$j]['sube']."</option>";
            if ($j<$subes){$j+=1;}}?>
        </select>
	<br />
	<?php }else{ ?>
	<label for="Entidad">Entidad : </label>
	<select id="cboEntidades" name="entidad" title="Despliegue para seleccionar una entidad">
	<option value="">Seleccionar</option>
	<?php while ($rsent[$i]=mysql_fetch_assoc($queent[$i]))
            {echo "<option value='".$rsent[$i]['id_entidad']."'>".$rsent[$i]['entidad']."</option>";
            if ($i<$entidades){$i+=1;}}?>
	</select>
	<br />
	<br />
	<label for="cboSubentidades">Subentidad : </label>
	<select id="cboSubentidades" name="sube" title="Despliegue para seleccionar una subentidad">
	<option value="0">Seleccione una entidad</option>
	</select>
	<br />
	<br />
	<?php } ?>
	<h2 align="center"><input type="submit" name="btn_enviar" value="Aceptar" />
	<input type="button" value="Cancelar" onClick="location.href='/ex/'"/></h2>
	</div>
    </form>
</body>