<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>PISCINA</title>
</head>
<body>
	<?php
		require 'comunes/conectar.php';
		function login(){
			if (isset($_SESSION['id_usuario'])){
	     	 	$id_usuario = (int) trim($_SESSION['id_usuario']);
	     	 	//$_SESSION['nick']
	  		}else{
	     		 header("Location: usuarios/login.php");
	    	}
	    	//aprovecho cojo el nick del usuario
	   		if (isset($con) && $con!= FALSE) {
	    		pg_close($con);
	    	}
			$con=conectar();
			$id_usuario=(int)$_SESSION['id_usuario'];
			$res=pg_query($con,"select nombre from usuarios where id=$id_usuario");
			$aux=pg_fetch_assoc($res, 0);
			//$nick=$aux['nombre'];
			$_SESSION['nick']=$aux['nombre'];
			pg_close($con);
			    	
		}
		function logout(){
			 session_destroy();
		      //setcookie(session_name(), '', 1, '/');
		      header("Location: usuarios/login.php");
		}
		login();
		if (isset($_POST['logout'])) {
			logout();
		}
		//declaro los arrays $dias y $horas
		$dias = array('l' => 'Lunes',
                      'm' => 'Martes',
                      'x' => 'Miércoles',
                      'j' => 'Jueves',
                      'v' => 'Viernes');
                          
        $horas = array(10,11,12,13,14,15,16,17,18,19,20);

        //hacer reserva
        if (isset($_POST['reservar'],$_POST['dia'],$_POST['hora'])) {
        	//recojo las variables que necesito 
        	$id_usuario=$_SESSION['id_usuario'];
        	$dia=$_POST['dia'];
        	$hora=$_POST['hora'];
        	echo $_POST['hora'];
        	//compruebo que no está reservado
        	$con=conectar();
        	$res = pg_query($con,"select * from reservas
        							where dia::text = '$dia' and hora = $hora and id_usuario = $id_usuario");
        	if (pg_num_rows($res) == 0) {
        		$res = pg_query($con, "insert into reservas(dia,hora,id_usuario)
                                       values('$dia', $hora, $id_usuario)");
        		if(pg_affected_rows($res) == 0){
            		echo "<script>alert(\"No se ha podido realizar la reserva.\")</script>";
            	}
        	}
        }
        
		
		


	?>
	<table border="1">
		<thead>
			<tr><td>HORAS\DIAS</td>
				<?php
				foreach ($dias as $key => $value) {
					?><th><?= $value ?></th><?php
				}
				?>
			</tr>
		</thead>
		<tbody>
			
				<?php
				$con=conectar();
				$id_usuario=$_SESSION['id_usuario'];
				foreach ($horas as $k => $value) {
					?>
					<tr>
						<td><?= $value ?>:00</td>
						<?php 
						foreach ($dias as $key => $value) {
							$res = pg_query($con, "select * from reservas where dia = '$key' and hora = $horas[$k]");
							if(pg_num_rows($res) == 0){
				                ?>
				                <td>
				                  <form action="index.php" method="post">
				                    <input type="hidden" name="dia" value="<?= $key ?>" />
				                    <input type="hidden" name="hora" value="<?= $horas[$k] ?>" />
				                    <input type="submit" name="reservar" value="Reservar" />
				                  </form>
				                </td>
				                <?php
				            }else{
				            	$res = pg_query($con, "select * from reservas where dia = '$key' AND hora = $horas[$k] 
                                      and id_usuario = $id_usuario");
				            	if (pg_num_rows($res) > 0) {
				            		?>
					                  <td>
					                    <form action="index.php" method="post">
					                      <input type="hidden" name="dia" value="<?= $key ?>" />
					                      <input type="hidden" name="hora" value="<?= $horas[$k] ?>" />
					                      <input type="submit" name="anular" value="Anular" />
					                    </form>
					                  </td>
					                 <?php
				            	}else{
				            		?><td>No disponible</td><?php
				            	}
				            }
						}
						?>
					</tr><?php
				}pg_close($con);
				?>
			
		</tbody>
	</table>
	<form action="index.php" method="post">
      <p align="right">Usuario: <strong><?= $_SESSION['nick'] ?></strong>
        <input type="submit" name="logout" value="Salir" />
      </p>
    </form>
</body>
</html>