<?php
//session_start();
include('conexion.php');
class loginUsuario {
    private $loginId;
    private $loginCedula;
    private $loginEmail;
    private $loginPass;
	public function __construct($cliente, $cedula, $email, $clave){
            $this->loginId=$cliente;
            $this->loginCedula=$cedula;
            $this->loginEmail=$email;
            $this->loginPass=md5(md5($clave).sha1($clave));
	}
	public function usuario_cliente(){
            $consulta_id=("SELECT cliente, id_rol, activo, clave FROM usuarios WHERE cliente = '$this->loginId'") or die (mysql_error());
            $resultado_id=@mysql_query($consulta_id);
            $fila_id=@mysql_fetch_array($resultado_id);
            if ($fila_id ["cliente"] == ''){
		echo "<script type='text/javascript'>alert('Este usuario no existe');
			window.location.href='login.php'
			</script>";
            }elseif ($fila_id ["clave"] != $this->loginPass){
		echo "<script type='text/javascript'>alert('Contraseña Incorrecta');
			window.location.href='login.php'
			</script>";
            }elseif ($fila_id ["activo"] != 1){
		echo "<script type='text/javascript'>alert('Usuario Inactivo');
			window.location.href='index.php'
			</script>";
            }else{
		$consulta_cli=("SELECT cliente, nombre FROM clientes WHERE cliente = '$this->loginId' ") or die (mysql_error());
		$resultado_cli=@mysql_query($consulta_cli);
		$fila_cli=@mysql_fetch_array($resultado_cli);
		$_SESSION["s_usuario"] = $fila_cli["nombre"];
		if ($fila_id ["id_rol"] == 1){
                    header ("Location: super_admin.php");
		}elseif ($fila_id ["id_rol"] == 2){
                    header ("Location: principal_admin.php");
		}else{
                    header ("Location: principal_usu.php");
		}
            }
	}
	
	public function usuario_cedula(){
            $consulta_ced=("SELECT cedula, id_rol, activo, clave FROM usuarios WHERE cedula = '$this->loginCedula'") or die (mysql_error());
            $resultado_ced=@mysql_query($consulta_ced);
            $fila_ced=@mysql_fetch_array($resultado_ced);
            if ($fila_ced ["cedula"] == ''){
            	echo "<script type='text/javascript'>alert('Este usuario no existe');
            		window.location.href='login.php'
            		</script>";
            }elseif ($fila_ced ["clave"] != $this->loginPass){
            	echo "<script type='text/javascript'>alert('Contraseña Incorrecta');
            		window.location.href='login.php'
            		</script>";
            }elseif ($fila_ced ["activo"] != 1){
            	echo "<script type='text/javascript'>alert('Usuario Inactivo');
            		window.location.href='index.php'
            		</script>";
            }else{
            	$consulta_cli=("SELECT cedula, nombre FROM clientes WHERE cedula = '$this->loginCedula' ") or die (mysql_error());
            	$resultado_cli=@mysql_query($consulta_cli);
            	$fila_cli=@mysql_fetch_array($resultado_cli);
            	$_SESSION["s_usuario"] = $fila_cli["nombre"];
            	if ($fila_ced ["id_rol"] == 1){
                    header ("Location: super_admin.php");
		}elseif ($fila_ced ["id_rol"] == 2){
                    header ("Location: principal_admin.php");
		}else{
                    header ("Location: principal_usu.php");
		}
            }
	}
	
	public function usuario_email(){
            $consulta_ema="SELECT b.id_cliente, b.cedula, CONCAT(b.nombre,' ',b.apellidos) as nombre, b.id_entidad, b.id_sube, a.usuario, a.id_rol, a.activo, a.clave 
			   FROM usuarios a inner join clientes b on a.id_usuario=b.id_usuario 
			   WHERE a.usuario = '$this->loginEmail'";
            $resultado_ema=@mysql_query($consulta_ema) or die (mysql_error());
            $fila_ema=mysql_fetch_assoc($resultado_ema);
            $_SESSION["s_rol"] = $fila_ema["id_rol"];
            if (mysql_num_rows($resultado_ema)==0){
                echo "<script> swal({title: 'Error', text: 'Este usuario no existe', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
            }elseif (@mysql_num_rows($resultado_ema)==1){
		if ($fila_ema ["clave"] != $this->loginPass){
                        echo "<script> swal({title: 'Error', text: 'Contrase\u00f1a Incorrecta', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
		}elseif ($fila_ema ["activo"] != 1){
                    echo "<script> swal({title: 'Informaci\xf3n', text: 'Usuario Inactivo, es necesario realizar la activaci\xf3n', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
		}else{
                    $_SESSION["s_usuario"] = $fila_ema["nombre"];
                    $_SESSION["s_email"] = $fila_ema["usuario"];
                    $urlpsa="/psa/";
                    $urlpa="/pa/";	
                    if ($fila_ema ["id_rol"] == 1){
                        header ("Location:".$urlpsa);
                    }else{
                        header ("Location:".$urlpa);
                    }
		}
            }else{
		$_SESSION["s_usuario"] = $fila_ema["nombre"];
		$_SESSION["s_cedulae"] = $fila_ema["cedula"];
		$_SESSION["s_email"] = $fila_ema["usuario"];
		$_SESSION["s_vecla"] = $this->loginPass;
                echo "<script> swal({title: 'Error', text: 'Usuario Inactivo', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
            }
	}
    }
?>

<script>
function redireccionar(){
  window.location.href="/ex/";
}

function redireccionar1(){
  window.location.href="/in/";
}

function redireccionar2(){
  window.location.href="/sli/";
}
</script>