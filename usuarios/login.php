<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>LOGIN</title>
</head>
<body>
	<?php
		require '../comunes/conectar.php';
		if (isset($_POST['nick'],$_POST['password'])) {
			$nick=trim($_POST['nick']);
			$password=trim($_POST['password']);
			$con = conectar();
			$res=pg_query($con,"select * from usuarios where nombre='$nick' and contrasena=md5('$password')");
			if (pg_num_rows($res)>0) {
				//usuario está en bd
				$fila=pg_fetch_assoc($res, 0);
				$_SESSION['id_usuario']=$fila['id'];
				//ahora que ya tiene id_usuario en la sesion lo mandamos al index
				
				header("Location: ../index.php");
			}else{
				?><h3>Nombre y contraseña no valido</h3><?php
			}
		}
	?>
	<form action="login.php" method="post">
		<label>Nombre: </label>
		<input type="text" name="nick"><br>
		<label>Contraseña:</label>
		<input type="password" name="password"><br>
		<input type="submit" value ="Entrar"> 
	</form>
</body>
</html>