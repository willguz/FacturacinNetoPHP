<?php 
  session_start();
  if($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2)
  {
    header("location: ./");
  }
  include "../conexion.php";

  if(!empty($_POST))
  {
    if (empty($_POST['idproveedor']))
    {
      header("location: lista_proveedores.php");
      mysqli_close($conection);
    }
    $idproveedor = $_POST['idproveedor'];

    //$query_delete = mysqli_query($conection,"DELETE FROM usuario WHERE idusuario =$idusuario ");
    $query_delete = mysqli_query($conection,"UPDATE proveedor SET estatus = 0 WHERE codproveedor = $idproveedor ");
    mysqli_close($conection);
    if($query_delete){
      header("location: lista_proveedores.php");
    }else{
      echo "Error al eliminar";
    }

  }
  
  // Mostrar Datos
  if(empty($_REQUEST['id']))
  {
    header("location: lista_proveedores.php");
    mysqli_close($conection);
  }else{

    $idproveedor = $_REQUEST['id'];

    $query = mysqli_query($conection,"SELECT * FROM proveedor WHERE codproveedor = $idproveedor ");
    
    mysqli_close($conection);
    $result = mysqli_num_rows($query);

    if($result > 0){
      while ($data = mysqli_fetch_array($query)) {
        
        $proveedor = $data['proveedor'];
        $contacto = $data['contacto'];
        
      }
    }else{
      header("location: lista_proveedores.php");
    }
  }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include "includes/scripts.php"; ?>
  <title>Eliminar Proveedor</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    <div class="data_delete">
      <i class="fas fa-user-times fa-5x" style="color: #e66262;"></i>
      <hr>
      <h2>¿Está seguro de eliminar el siguiente proveedor?</h2>
      <p>Nombre del proveedor: <span><?php echo $proveedor; ?></span></p>
      <p>Contacto: <span><?= $contacto ?></span></p>      

      <form method="post" action="">
        <input type="hidden" name="idproveedor" value="<?= $idproveedor; ?>">
        <a href="lista_proveedores.php" class="btn_cancel"><i class="fas fa-ban"></i> Cancelar</a>
        <button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>
      </form>
    </div>
  </section>
  <?php include "includes/footer.php"; ?>
</body>
</html>