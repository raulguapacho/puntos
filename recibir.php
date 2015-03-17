<?php 
include('inactividad.php');
include('Classes/dm.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8"/>
<script src="../js/modernizr.js"></script>
<script src="../js/sweet-alert.js"></script>
<link rel="stylesheet" href="../css/sweet-alert.css"/>
<link rel="stylesheet" href="../css/nav.css"/>
</head>
<body>
    <dav>
        <ul>
            <li><a><?php echo "Administración de Tienda"; ?></a></li>
            <li><a><?php echo $dia.date(", d ")."de ".$mes." de".date(" Y"); ?></a></li>
        </ul>
    </dav>
<?php
session_start();
error_reporting(0);
extract($_POST);
$hoy=date("Y-m-d");
$hora=date("_H_i_s_");

//entidad del usuario
$rol=$_SESSION['s_rol'];

if ($action == "upload"){
//cargar archivo al servidor con el mismo nombre y agregar sufijo bak_ 
	$archivo = $_FILES['excel']['name'];
	$tipo = $_FILES['excel']['type'];
	if($tipo){
            $destino = "uploads/bak_".$hoy.$hora.$archivo;
            copy($_FILES['excel']['tmp_name'],$destino); 
	}
	
////////////////////////////////////////////////////////
if (file_exists ("uploads/bak_".$hoy.$hora.$archivo)){ 
/* Clases necesarias */
require_once('Classes/PHPExcel.php');
require_once('Classes/PHPExcel/Reader/Excel2007.php');

// Cargar la hoja de cálculo
$objReader = new PHPExcel_Reader_Excel2007();
$objPHPExcel = $objReader->load("uploads/bak_".$hoy.$hora.$archivo);
$objFecha = new PHPExcel_Shared_Date();       

// Asignar hoja de excel activa
$objPHPExcel->setActiveSheetIndex(0);

//conectar la bd 
$cn = mysql_connect ("localhost","raul_guapacho","Zaq1xsw2-.") or die ("ERROR EN LA CONEXION");
$db = mysql_select_db ("prueba",$cn) or die ("ERROR AL CONECTAR A LA BD");

//entidad del usuario activo
if ($rol==2){
    $id_entidad=$_SESSION['s_entidad'];
    $reg_entidad="select entidad from entidad where id_entidad=".$id_entidad;
    $entidad_usu=@mysql_query($reg_entidad);
    $reg_entidadusu=@mysql_fetch_array($entidad_usu);
    $entidadusu=$reg_entidadusu['entidad'];
}

// contar celdas con datos
$l=2;
do{
    $celda=$objPHPExcel->getActiveSheet()->getCell('A'.$l)->getCalculatedValue();
    if ($celda!='')
	$l++;
    else
	break;
}while ($celda!='');
$filas_archivo=$l-2;

// Llenar arreglo con datos  del archivo xlsx
for ($i=2;$i<=$l-1;$i++){
	$_DATOS_EXCEL[$i]['cedula'] = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['entidad']= $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['subentidad']= $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['cliente'] = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['nombre'] = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['apellidos'] = $objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
        $_DATOS_EXCEL[$i]['departamento'] = $objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
        $_DATOS_EXCEL[$i]['ciudad'] = $objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['direccion'] = $objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['telefono'] = $objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['email'] = $objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();
	$fecha = PHPExcel_Shared_Date::ExcelToPHP($objPHPExcel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue());
	$fecha = strtotime("+1 day",$fecha);
	$_DATOS_EXCEL[$i]['fec_ven_puntos'] = date("Y-m-d",$fecha);
	$_DATOS_EXCEL[$i]['puntos'] = $objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['fee'] = $objPHPExcel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['extra1'] = $objPHPExcel->getActiveSheet()->getCell('O'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['extra2'] = $objPHPExcel->getActiveSheet()->getCell('P'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['extra3'] = $objPHPExcel->getActiveSheet()->getCell('Q'.$i)->getCalculatedValue();
	$_DATOS_EXCEL[$i]['extra4'] = $objPHPExcel->getActiveSheet()->getCell('R'.$i)->getCalculatedValue();
	}
}
//si no cargo el archivo bak_ 
else{
    echo "<script> swal({title: 'Error', text: 'SELECCIONE UN ARCHIVO', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
}
$errores=0;$registro=0;$cant_insert=0;$cant_insert_puntos=0;$cant_update_puntos=0;
//recorrer el arreglo multidimensional para leer datos del excel e ir insertandolos en la BD
foreach($_DATOS_EXCEL as $campo => $valor){
    $insert_clientes = "INSERT INTO clientes (cedula, id_entidad, id_sube, cliente, nombre, apellidos, id_depto, id_ciudad, direccion, telefono, email, extra1, extra2, extra3, extra4) VALUES (";
    $insert_puntos = "INSERT INTO ps_customer_points (fec_ven_puntos, points, fee) VALUES ('";
    $registro+=1;
    foreach($valor as $campo2 => $valor2){
    	if ($campo2=="cedula"){
            if (is_numeric($valor2)){
		$cedula=$valor2;
            }else{
		echo "la cedula del registro # ".$registro." no es correcta<br>";
            }	
	}elseif ($campo2=="entidad"){
            $entidad=trim($valor2);
        }elseif ($campo2=="subentidad"){
            if ($rol==2){
                if ($entidadusu==$entidad){
                    $sql_entidad="select id_entidad from entidad where entidad='$entidad'";
                    //"select id_entidad from entidad where entidad='".$entidad."'";
                    //"select id_entidad from entidad where entidad='$entidad'"
                    $ejecuta_entidad=@mysql_query($sql_entidad) or die (mysql_error());
                    $verifica_entidad=@mysql_num_rows($ejecuta_entidad);
                    if ($verifica_entidad==0){
			echo "la entidad del registro # ".$registro." no existe en la BD.<br>";
                    }else{
                        $id_entidad=@mysql_fetch_assoc($ejecuta_entidad);
			$id_ent=$id_entidad["id_entidad"];
			$sql_sube="select a.id_entidad, b.id_sube, b.sube from entidad a inner join subentidad b
                                   on a.id_entidad=b.id_entidad where a.id_entidad=".$id_ent." and b.sube='".$valor2."'";
			$ejecuta_sube=@mysql_query($sql_sube) or die (mysql_error());
			$verifica_sube=@mysql_num_rows($ejecuta_sube);
			if ($verifica_sube==0){
                            echo "La subentidad del registro # ".$registro." No esta relacionada con la entidad.<br>";
			}else{
                            $id_sube=@mysql_fetch_assoc($ejecuta_sube);
                            $id_sube=$id_sube["id_sube"];
                            $valores="select id_cliente, id_usuario from clientes where cedula=".$cedula." and id_entidad=".$id_ent." and id_sube=".$id_sube; 
                            $valores1=@mysql_query($valores) or die (mysql_error());
                            $valores2=mysql_fetch_assoc($valores1);
                            $id_cliente=$valores2['id_cliente'];
                            $id_usuario=$valores2['id_usuario'];
                            $valores3=@mysql_num_rows($valores1);
                            if ($valores3>0){
				$existe=1;
                            }else{
                            	$insert_clientes.= $cedula.",'";
				$insert_clientes.= $id_ent."',";
				$insert_clientes.= $id_sube.",'";
				$existe=0;
                            }
			}
                    }
		}else{
                    echo "la entidad del registro # ".$registro." no corresponde a este usuario.<br>";
		}
            }else{
                $sql_entidad="select id_entidad from entidad where entidad='$entidad'";
                $ejecuta_entidad=@mysql_query($sql_entidad) or die (mysql_error());
		$verifica_entidad=@mysql_num_rows($ejecuta_entidad);
		if ($verifica_entidad==0){
                    echo "la entidad del registro # ".$registro." no existe en la BD.<br>";
		}else{
                    $id_entidad=@mysql_fetch_assoc($ejecuta_entidad);
                    $id_ent=$id_entidad["id_entidad"];
                    $sql_sube="select a.id_entidad, b.id_sube, b.sube from entidad a inner join subentidad b
                               on a.id_entidad=b.id_entidad where a.id_entidad='$id_ent' and b.sube='$valor2'";
                    $ejecuta_sube=@mysql_query($sql_sube) or die (mysql_error());
                    $verifica_sube=@mysql_num_rows($ejecuta_sube);
                    if ($verifica_sube==0){
                        echo "La subentidad del registro # ".$registro." No esta relacionada con la entidad.<br>";
                    }else{
                        $id_sube=@mysql_fetch_assoc($ejecuta_sube);
                        $id_sube=$id_sube["id_sube"];
                        $valores="select id_cliente, id_usuario from clientes where cedula='$cedula' and id_entidad=".$id_ent." and id_sube='$id_sube'";
                        $valores1=@mysql_query($valores) or die (mysql_error());
                        $valores2=mysql_fetch_assoc($valores1);
                        $id_cliente=$valores2['id_cliente'];
                        $id_usuario=$valores2['id_usuario'];
                        $valores3=@mysql_num_rows($valores1);
                        if ($valores3>0){
                            $existe=1;
                        }else{
                            $insert_clientes.= $cedula.",'";
                            $insert_clientes.= $id_ent."',";
                            $insert_clientes.= $id_sube.",'";
                            $existe=0;
                        }
                    }
		}
            }
        }elseif ($campo2=="departamento"){
            if ($existe==0){
                $depto=trim($valor2);
                $sqldepto="SELECT id_depto from deptos where depto='$depto'";
                $qdepto=mysql_query($sqldepto) or die (mysql_error());
                if (mysql_num_rows($qdepto)==0){
                    echo "El depto del registro # ".$registro." no existe en la BD.<br>";
                }else{
                    $rsdepto=  mysql_fetch_assoc($qdepto);
                    $id_depto=$rsdepto['id_depto'];
                    $insert_clientes.=$id_depto."','";
                }
            }
        }elseif ($campo2=="ciudad"){
            if ($existe==0){
                $ciudad=trim($valor2);
                $sqlciudad="SELECT id_depto from ciudades where ciudad='$ciudad'";
                $qciudad=mysql_query($sqlciudad) or die (mysql_error());
                if (mysql_num_rows($qciudad)==0){
                    echo "La ciudad del registro # ".$registro." no existe en la BD.<br>";
                }else{
                    $rsciudad=mysql_fetch_assoc($qciudad);
                    $id_dep=$rsciudad['id_depto'];
                    $sqlciudep="select b.id_ciudad from deptos a inner join ciudades b on a.id_depto=b.id_depto "
                              ."where a.id_depto='$id_dep' and b.ciudad='$ciudad'";
                    $qsqlciudep=  mysql_query($sqlciudep) or die (mysql_error());
                    if (mysql_num_rows($sqlciudep)){
                        echo "La ciudad del registro # ".$registro." no esta relacionada con el depto.<br>";
                    }else{
                        $rssqlciudep=  mysql_fetch_assoc($qsqlciudep);
                        $id_ciudad=$rssqlciudep['id_ciudad'];
                        $insert_clientes.=$id_ciudad."','";
                    }
                }
            }
        }elseif ($campo2=="direccion"){
            if ($existe==0){
                $direccion=$valor2;
                $insert_clientes.=$direccion."','";
            }
        }elseif ($campo2=="telefono"){
            if ($existe==0){
                $telefono=$valor2;
                $insert_clientes.=$telefono."','";
            }
	}elseif ($campo2=="email"){
            if(filter_var($valor2, FILTER_VALIDATE_EMAIL)){
                if ($existe==0){
                    $insert_clientes.= $valor2."','";
                }
            }else{
		echo "El email del registro # ".$registro." no es correcto<br>";
            }
	}elseif ($campo2=="fec_ven_puntos"){
            if ($valor2>$hoy){
		$fecha_llega=$valor2;
		$insert_puntos.= $valor2."',";
		if ($existe==1){
                    $update_puntos="update ps_customer_points set points=";
		}
            }else{
		echo "La fecha de vencimiento del registro # ".$registro." es incorrecta<br>";
            }
	}elseif ($campo2=="puntos"){
            $insert_puntos.= $valor2.",";
            $puntos=$valor2;
            if ($existe==1){
		$puntos_vi=$valor2;
            }
	}elseif ($campo2=="fee"){
            $insert_puntos.= $valor2.");";
            $fee=$valor2;
        }elseif (($campo2=="extra4") && ($existe==0)){
            $insert_clientes.= $valor2."');";	
	}else{
            if($existe==0){
                $insert_clientes.= $valor2."','";
            }
	}
    }
	//inserta registro en la tabla clientes
    if ($existe==0){
        //Inserta registro tabla clientes
	$result_clientes =  mysql_query($insert_clientes) or die (mysql_error());
        //Inserta registro tabla ps_customer_points
        include ('conexionpuntos.php');
	$result_puntos = mysql_query($insert_puntos) or die (mysql_error());
	if (($result_clientes==false) || ($result_puntos==false)){
            $errores+=1;
	}else{
            $cant_insert+=1;
            $cant_insert_puntos+=1;
            echo "El cliente del registro # ".$registro." Fue creado en la BD.<br>";
	}
        include ('conexion.php');
	//actualiza o crea registro en la tabla puntos
    }else{
        include ('conexionpuntos.php');
	$sql_fec="select id, fec_ven_puntos, points from ps_customer_points where id_client='$id_cliente' order by id DESC LIMIT 1";
	$fec=@mysql_query($sql_fec) or die (mysql_error());
	$fec_puntos=mysql_fetch_assoc($fec);
	$fecha=$fec_puntos['fec_ven_puntos'];
	if ($fecha_llega>$fecha){
            $result_puntos =@mysql_query($insert_puntos) or die (mysql_error());
            //consulta el ultimo registro insertado tabla puntos
            $query_reg_pts="Select id from puntos order by id DESC LIMIT 1";
            $ult_reg_pts=@mysql_query($query_reg_pts) or die(mysql_error());
            $reg_pts=mysql_fetch_assoc($ult_reg_pts);
            $id_puntos=$reg_pts['id'];
            $update_reg_pts="update ps_customer_points set id_client='$id_cliente',creado='$hoy' where id='$id_puntos'";
            @mysql_query($update_reg_pts) or die (mysql_error());
            $inserta=1;
	}else{
            $id_puntos=$fec_puntos['id'];
            $puntos=$puntos_vi+$fec_puntos['puntos'];
            $update_puntos.= $puntos.", actualizado='".$hoy."' where id=".$id_puntos;
            $actual_puntos =@mysql_query($update_puntos) or die (mysql_error());
            $inserta=0;
            if ($actual_puntos==false){
		$errores+=1;
            }else{
		$cant_update_puntos+=1;
		echo "Puntos del cliente del registro # ".$registro." fueron actualizados.<br>";
            }
	}
        include ('conexion.php');
    }
    //verifica si hay errores or die (mysql_error())
    if ($errores==0){
	if ($existe==0){
            //inserta registro en la tabla usuarios
            $insert_usuario="INSERT INTO usuarios(cedula, cliente, usuario) SELECT cedula, cliente, email FROM clientes where cedula=".$cedula." and id_entidad=".$id_ent." and id_sube=".$id_sube;
            @mysql_query($insert_usuario) or die (mysql_error());
		
            //consulta el ultimo registro insertado tabla usuarios
            $query_reg_usu="Select id_usuario from usuarios order by id_usuario DESC LIMIT 1";
            $ultimo_reg_usu=@mysql_query($query_reg_usu) or die (mysql_error());
            $reg_usu=@mysql_fetch_array($ultimo_reg_usu);
            $id_usuario=$reg_usu['id_usuario'];
	
            //Actualiza registro tabla usuarios Clave la misma cedula, fecha de creación y rol
            $clave=md5(md5($cedula).sha1($cedula));
            $actualiza_usuario="update usuarios SET clave='".$clave."' ,creado='".$hoy."',id_rol='3', activo='0' WHERE id_usuario=".$id_usuario;
            @mysql_query($actualiza_usuario) or die (mysql_error());
	
            //consulta el ultimo registro insertado tabla clientes
            $query_reg_cli="Select id_cliente, id_entidad, id_sube, nombre, apellidos, email from clientes order by id_cliente DESC LIMIT 1";
            $ultimo_reg_cli=@mysql_query($query_reg_cli) or die (mysql_error());
            $reg_cli=mysql_fetch_assoc($ultimo_reg_cli);
            $id_cliente=$reg_cli['id_cliente'];
            $id_ent=$reg_cli['id_entidad'];
            $id_sube=$reg_cli['id_sube'];
            $nombre=$reg_cli['nombre'];
            $apellidos=$reg_cli['apellidos'];
            $email=$reg_cli['email'];
	
            //Actualiza registro tabla clientes con el id del usuario y la fecha de creación
            $actualiza_cliente="update clientes SET id_usuario=".$id_usuario.", creado='".$hoy."' where id_cliente=".$id_cliente;
            @mysql_query($actualiza_cliente) or die (mysql_error());
		
            //consulta el ultimo registro insertado tabla ps_customer_points
            include ('conexionpuntos.php');
            $query_reg_pun="Select id, points, fec_ven_puntos, fee from ps_customer_points order by id DESC LIMIT 1";
            $ultimo_reg_pun=@mysql_query($query_reg_pun) or die (mysql_error());
            $reg_pun=mysql_fetch_assoc($ultimo_reg_pun);
            $id_puntos=$reg_pun['id'];
            $puntos=$reg_pun['puntos'];
            $fec_ven_puntos=$reg_pun['fec_ven_puntos'];
            $fee=$reg_pun['fee'];
	
            //Actualiza registro tabla ps_customer_points con el id del cliente, id del usuario y fecha de creación
            $actualiza_cliente="update ps_customer_points SET id_client=".$id_cliente.", id_customer=".$id_usuario.", creado='".$hoy."', activo='1' where id=".$id_puntos;
            @mysql_query($actualiza_cliente) or die (mysql_error());
	
            //determinar valor del punto para entidad - subentidad
            include ('conexion.php');
            $sql_valpunto="select val_punto from subentidad where id_entidad=".$id_ent." and id_sube=".$id_sube;
            $query_valpunto=@mysql_query($sql_valpunto) or die (mysql_error());
            $rs_valpunto=mysql_fetch_assoc($query_valpunto);
            $val_punto=$rs_valpunto['val_punto'];
	
            //inserta registro tabla ps_customer de la BD puntos
            include ('conexionpuntos.php');
            $pass=md5('I3mLxuxZo1zSFjdCAcWhubRk9Gu3ZbTxtTdnrPSxX7zJYRu2x0IuZZDW'.$cedula);
            $secure = md5(uniqid(rand(), true));
            $last_passwd_gen = date('Y-m-d H:i:s');
            $newsletter_date_add = date('Y-m-d H:i:s');
            $date_add = date('Y-m-d H:i:s');
            $date_upd = date('Y-m-d H:i:s');
	
            $sql_customer="INSERT INTO ps_customer (id_customer, id_shop_group, id_shop, id_gender, id_default_group, id_lang, id_risk, company, siret, ape, firstname, lastname, email, passwd, last_passwd_gen, birthday, newsletter, ip_registration_newsletter, newsletter_date_add, optin, website, outstanding_allow_amount, show_public_prices, max_payment_days, secure_key, note, active, is_guest, deleted, date_add, date_upd, id_entidad, id_sube, val_punto) 
                           VALUES(".$id_usuario.", 1, 1, 1, 3, 1, 0, '', '', '','".$nombre."','".$apellidos."','".$email."','".$pass."', '".$last_passwd_gen."', NULL, 1, '', '".$newsletter_date_add."', 1, '', 0.000000, 0, 0, '".$secure."', '', 0, 0, 0, '".$date_add."', '".$date_upd."', '".$id_ent."','".$id_sube."','".$val_punto."')";
            $query_puntos=@mysql_query($sql_customer) or die (mysql_error());
		
            //inserta registro tabla ps_customer_group de la BD puntos
            $sql_group="INSERT INTO ps_customer_group (id_customer, id_group) VALUES (".$id_usuario.", '3')";
            $query_group=@mysql_query($sql_group) or die (mysql_error());
            
            //inserta registro tabla ps_address de la BD puntos
            $sql_address="INSERT INTO ps_address (id_country, id_state, id_customer, id_manufacturer, id_supplier, id_warehouse, alias, company, lastname, firstname, address1, address2, postcode, city, other, phone, phone_mobile, vat_number, dni, date_add, date_upd, active, deleted)
                          VALUES (69,".$id_depto.",".$id_usuario.",0,0,0,'Registrada','','".$apellidos."','".$nombre."','".$direccion."','','000000','".$ciudad."','','".$telefono."','','','','".$date_add."','".$date_upd."','1','0')";
            $query_address=@mysql_query($sql_address) or die (mysql_error());
            include ('conexion.php');
        }
    }else{
	echo '<a href="/im/">Regresar</a>';
    }
}
$exito="ARCHIVO IMPORTADO CON EXITO. \\n";
$cinsert="Se han agregado ".$cant_insert." registros de clientes. \\n";
$pinsert="Se han agregado ".$cant_insert_puntos." registros de puntos de clientes. \\n";   
$pupdatep="Se han actualizado ".$cant_update_puntos." registros de puntos de clientes.";
if (($cant_insert>0) or ($cant_insert_puntos>0) or ($cant_update_puntos>0)){
    if ($rol==1){
        echo "<script> swal({title: 'Exito', text: '".$exito.$cinsert.$pinsert.$pupdatep."', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 4000)</script>";
    }else{
        echo "<script> swal({title: 'Exito', text: '".$exito.$cinsert.$pinsert.$pupdatep."', type: 'success', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 4000)</script>";
    }
}
}
?>
</body>
</html>

<script>
function redireccionar(){
  window.location.href="/im/";
}

function redireccionar1(){
  window.location.href="/psa/";
}

function redireccionar2(){
  window.location.href="/pa/";
}
</script>