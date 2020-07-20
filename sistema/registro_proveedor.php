<?php 
  session_start();
  if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
  }
    
  include "../conexion.php";

  if(!empty($_POST))
  {
    $alert='';
    if(empty($_POST['proveedor']) || empty($_POST['contacto']) || empty($_POST['telefono']) || empty($_POST['direccion']))
    {
      $alert='<div class="alert_error"><p>Todos los campos son obligatorios.</p></div>';
    }else{

      $proveedor = $_POST['proveedor'];
      $contacto = $_POST['contacto'];
      $telefono  = $_POST['telefono'];
      $direccion   = $_POST['direccion'];
      $usuario_id  = $_SESSION['idUser'];
      
      $query_insert = mysqli_query($conection,"INSERT INTO proveedor(proveedor,contacto,telefono,direccion, usuario_id)
                                  VALUES('$proveedor','$contacto','$telefono','$direccion','$usuario_id')");

      if($query_insert){
        $alert='<div class="alert"><p>Proveedor guardado correctamente.</p></div>';
      }else{
        $alert='<div class="alert_error"><p>Error al guardar el proveedor.</p></div>';
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
  <title>Registro Proveedor</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    
    <div class="form_register">
      <h1><i class="fas fa-cart-plus"></i> Registro Proveedor</h1>
      <hr>
      <?php echo isset($alert) ? $alert : ''; ?>

      <form action="" method="post">
        <label for="proveedor">Proveedor</label>
        <input type="text" name="proveedor" id="proveedor" placeholder="Nombre de proveedor">
        <label for="contacto">Contacto</label>
        <input type="text" name="contacto" id="contacto" placeholder="Nombre completo del contacto">
        <label for="telefono">Teléfono</label>
        <input type="number" name="telefono" id="telefono" placeholder="Teléfono">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion" placeholder="Dirección completa">
        
        <button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar proveedor</button>

      </form>


    </div>


  </section>
  <?php include "includes/footer.php"; ?>
</body>
</html>