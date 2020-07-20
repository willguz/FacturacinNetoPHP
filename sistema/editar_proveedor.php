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

      $idproveedor = $_POST['id'];
      $proveedor  = $_POST['proveedor'];
      $contacto = $_POST['contacto'];
      $telefono   = $_POST['telefono'];
      $direccion  = $_POST['direccion'];
      
      $sql_update = mysqli_query($conection,"UPDATE proveedor
                              SET proveedor = '$proveedor', contacto = '$contacto', telefono='$telefono',direccion='$direccion'
                              WHERE codproveedor= $idproveedor ");

      if($sql_update){
        $alert='<div class="alert"><p>Proveedor actualizado correctamente.</p></div>';
      }else{
        $alert='<div class="alert_error"><p>Error al actualizar el Proveedor.</p></div>';
      }      
    }
  }

  //Mostrar Datos
  if(empty($_REQUEST['id']))
  {
    header('Location: lista_proveedores.php');
    mysqli_close($conection);
  }
  $idproveedor = $_REQUEST['id'];

  $sql= mysqli_query($conection,"SELECT *
                  FROM proveedor
                  WHERE codproveedor= $idproveedor and estatus = 1");
  mysqli_close($conection);
  $result_sql = mysqli_num_rows($sql);

  if($result_sql == 0){
    header('Location: lista_proveedores.php');
  }else{
    while ($data = mysqli_fetch_array($sql)) {
      $idproveedor  = $data['codproveedor'];
      $proveedor  = $data['proveedor'];
      $contacto  = $data['contacto'];
      $telefono = $data['telefono'];
      $direccion   = $data['direccion'];
      
    }
  }

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include "includes/scripts.php"; ?>
  <title>Actualizar Proveedor</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    
    <div class="form_register">
      <h1><i class="fas fa-user-edit"></i> Actualizar proveedor</h1>
      <hr>
      <?php echo isset($alert) ? $alert : ''; ?>
      <form action="" method="post">
        <input type="hidden" name="id" value="<?= $idproveedor ?>">
        <label for="proveedor">Proveedor</label>
        <input type="text" name="proveedor" id="proveedor" placeholder="Nombre del proveedor" value="<?= $proveedor ?>">
        <label for="contacto">Contacto</label>
        <input type="text" name="contacto" id="contacto" placeholder="Nombre completo del contacto" value="<?= $contacto ?>">
        <label for="telefono">teléfono</label>
        <input type="number" name="telefono" id="telefono" placeholder="Teléfono" value="<?= $telefono ?>">
        <label for="direccion">Dirección</label>
        <input type="text" name="direccion" id="direccion" placeholder="Dirección completa" value="<?= $direccion ?>">
                
        <button  type="submit" class="btn_save"><i class="fas fa-user-edit"></i> Actualizar</button>
      </form>
    </div>
  </section>
  <?php include "includes/footer.php"; ?>
</body>
</html>