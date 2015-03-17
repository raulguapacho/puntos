<?php
//session_start();
include('conexion.php');
class activaUsuario {
    private $activaCliente;
    private $activaCedula;
    private $activaEmail;
    private $Pass;
	public function __construct($cliente, $cedula, $email, $clave){
            $this->activaCliente=$cliente;
            $this->activaCedula=$cedula;
            $this->activaEmail=$email;
            $this->Pass=md5(md5($clave).sha1($clave));
	}
	
	public function activar_cliente(){
            $sql_cliente=("select b.nombre, a.cliente, a.activo, a.clave from usuarios a inner join clientes b on a.cedula=b.cedula 
			  where cliente='$this->activaCliente'") or die (mysql_error());
            $activa_cliente=@mysql_query($sql_cliente);
            if (@mysql_num_rows($activa_cliente)>0){
            	$estado_cliente=@mysql_fetch_array($activa_cliente);
		if ($estado_cliente['activo']=='1'){
                    echo "<script type='text/javascript'>alert('Este Cliente ya esta activo como usuario');
			window.location.href='login.php'
			</script>";
		}elseif ($estado_cliente['clave']!=$this->Pass){
                    echo "<script type='text/javascript'>alert('Contraseña Incorrecta');
			window.location.href='index.php'
			</script>";
		}else{
                    $_SESSION["s_usuario"] = $estado_cliente["nombre"];
                    $_SESSION["s_cliente"] = $estado_cliente["cliente"];
                    echo "<script type='text/javascript'>alert('Es necesario cambiar la contraseña');
			  window.location.href='login.php';
			  </script>";
                }
            }
	}
		
	public function activar_cedula(){
            $sql_cedula=("select b.nombre, a.cedula, a.activo, a.clave from usuarios a inner join clientes b on a.cedula=b.cedula 
			 where cedula='$this->activaCedula'") or die (mysql_error());
            $activa_cedula=@mysql_query($sql_cedula);
            if (@mysql_num_rows($activa_cedula)>0){
		$estado_cedula=@mysql_fetch_array($activa_cedula);
		if ($estado_cedula['activo']=='1'){
                    echo "<script type='text/javascript'>alert('Este Cliente ya esta activo como usuario');
			window.location.href='login.php'
			</script>";
		}elseif ($estado_cedula['clave']!=$this->Pass){
                    echo "<script type='text/javascript'>alert('Contraseña Incorrecta');
			window.location.href='index.php'
			</script>";
		}else{
                    $_SESSION["s_usuario"] = $estado_cedula["nombre"];
                    $_SESSION["s_cedula"] = $estado_cedula["cedula"];
                    echo "<script type='text/javascript'>alert('Es necesario cambiar la contraseña');
			  window.location.href='login.php';
			  </script>";
		}
            }
	}
	
	public function activar_email(){
            $sql_email="select b.id_cliente, b.cedula, CONCAT(b.nombre,' ',b.apellidos) as nombre, b.id_entidad, b.id_sube, a.usuario, a.activo, a.clave, a.id_rol
			from usuarios a inner join clientes b on a.id_usuario=b.id_usuario 
			where a.usuario='$this->activaEmail'";
            $activa_email=@mysql_query($sql_email) or die (mysql_error());
            $estado_email=@mysql_fetch_array($activa_email);
            if (@mysql_num_rows($activa_email)==0){
                echo "<script> swal({title: 'Error', text: 'Este usuario no existe', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
            }elseif (@mysql_num_rows($activa_email)==1){
		if ($estado_email['activo']=='1'){
                    if ($estado_email['id_rol']=='3'){
                        echo "<script> swal({title: 'Información', text: 'Este Cliente ya esta activo como usuario', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar1()', 2000)</script>";
                    }else{
                        echo "<script> swal({title: 'Información', text: 'Este Cliente ya esta activo como usuario', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
                    }
		}elseif ($estado_email['clave']!=$this->Pass){
                    echo "<script> swal({title: 'Error', text: 'Contraseña Incorrecta', type: 'error', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar()', 2000)</script>";
		}else{
                    $_SESSION["s_usuario"] = $estado_email["nombre"];
                    $_SESSION["s_cedulae"] = $estado_email["cedula"];
                    $_SESSION["s_email"] = $estado_email["usuario"];
                    $_SESSION["s_entidad"] = $estado_email["id_entidad"];
                    $_SESSION["s_sube"] = $estado_email["id_sube"];
                    echo "<script> swal({title: 'Información', text: 'Es necesario cambiar la contraseña', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar3()', 2000)</script>";
		}
            }else{
		$_SESSION["s_usuario"] = $estado_email["nombre"];
		$_SESSION["s_cedulae"] = $estado_email["cedula"];
		$_SESSION["s_email"] = $estado_email["usuario"];
		$_SESSION["s_vecla"] = $this->Pass;
                echo "<script> swal({title: 'Información', text: 'Este Usuario pertenece a más de una entidad y/o subentidad', type: 'info', confirmButtonText: 'Aceptar'});setTimeout ('redireccionar2()', 2000)</script>";
            }
	}
    }
?>

<script>
function redireccionar(){
  window.location.href="/ex/";
}

function redireccionar1(){
  window.location.href="http://puntos.com";
}

function redireccionar2(){
  window.location.href="/sla/";
}

function redireccionar3(){
  window.location.href="/pcp/";
}
</script>