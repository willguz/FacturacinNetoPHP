<?php 
	session_start();
		
	include "../conexion.php";

	if(!empty($_POST))
	{
		$alert='';
		if(empty($_POST['nit']) || empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']))
		{
			$alert='<div class="alert_error"><p>Todos los campos son obligatorios.</p></div>';
		}else{

			$nit = $_POST['nit'];
			$nombre = $_POST['nombre'];
			$telefono  = $_POST['telefono'];
			$direccion   = $_POST['direccion'];
			$usuario_id  = $_SESSION['idUser'];

			$result = 0;

			if (is_numeric($nit) and $nit != 0)
			{
				$query = mysqli_query($conection,"SELECT * FROM cliente WHERE nit = '$nit' ");
				$result = mysqli_fetch_array($query);
			}
			if ($result > 0)
			{
				$alert='<div class="alert_error"><p>El número de NIT ya existe.</p></div>';
			}else{
				$query_insert = mysqli_query($conection,"INSERT INTO cliente(nit,nombre,telefono,direccion, usuario_id)
																	VALUES('$nit','$nombre','$telefono','$direccion','$usuario_id')");

				if($query_insert){
					$alert='<div class="alert"><p>Cliente guardado correctamente.</p></div>';
				}else{
					$alert='<div class="alert_error"><p>Error al guardar el cliente.</p></div>';
				}
			}
		}
		mysqli_close($conection);
	}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<?php include "includes/scripts.php"; ?>
	<title>Registro Cliente</title>
</head>
<body>
	<?php include "includes/header.php"; ?>
	<section id="container">
		
		<div class="form_register">
			<h1><i class="fas fa-user-plus"></i> Registro cliente</h1>
			<hr>
			<?php echo isset($alert) ? $alert : ''; ?>

			<form action="" method="post">
				<label for="nit">NIT o identificación</label>
				<input type="number" name="nit" id="nit" placeholder="Número de identificación">
				<label for="nombre">Nombre</label>
				<input type="text" name="nombre" id="nombre" placeholder="Nombre completo">
				<label for="telefono">Teléfono</label>
				<input type="number" name="telefono" id="telefono" placeholder="Teléfono">
				<label for="direccion">Dirección</label>
				<input type="text" name="direccion" id="direccion" placeholder="Dirección completa">
				
				<button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar cliente</button>

			</form>


		</div>


	</section>
	<?php include "includes/footer.php"; ?>
</body>
</html>