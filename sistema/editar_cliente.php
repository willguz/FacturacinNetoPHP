<?php 
  
  session_start();

  include "../conexion.php";

  if(!empty($_POST))
  {
    $alert='';
    if(empty($_POST['nombre']) || empty($_POST['telefono']) || empty($_POST['direccion']))
    {
      $alert='<div class="alert_error"><p>Todos los campos son obligatorios.</p></div>';
    }else{

      $idCliente = $_POST['id'];
      $nit  = $_POST['nit'];
      $nombre = $_POST['nombre'];
      $telefono   = $_POST['telefono'];
      $direccion  = $_POST['direccion'];
      
      $result = 0;

      if (is_numeric($nit) and $nit != 0)
      {
        $query = mysqli_query($conection,"SELECT * FROM cliente 
                             WHERE (nit = '$nit' AND idcliente != $idCliente) ");
        
        $result = mysqli_fetch_array($query);
        $result = count((array) $result);
      }      


      if($result > 0){
        $alert='<div class="alert_error"><p>El número de NIT ya existe, registre otro.</p></div>';
      }else{
        if ($nit =='') 
        {
          $nit = 0;
        }

          $sql_update = mysqli_query($conection,"UPDATE cliente
                              SET nit = '$nit', nombre = '$nombre', telefono='$telefono',direccion='$direccion'
                              WHERE idcliente= $idCliente ");

        if($sql_update){
          $alert='<div class="alert"><p>Cliente actualizado correctamente.</p></div>';
        }else{
          $alert='<div class="alert_error"><p>Error al actualizar el Cliente.</p></div>';
        }

      }
    }
  }

  //Mostrar Datos
  if(empty($_REQUEST['id']))
  {
    header('Location: lista_clientes.php');
    mysqli_close($conection);
  }
  $idcliente = $_REQUEST['id'];

  $sql= mysqli_query($conection,"SELECT *
                  FROM cliente
                  WHERE idcliente= $idcliente and estatus = 1");
  mysqli_close($conection);
  $result_sql = mysqli_num_rows($sql);

  if($result_sql == 0){
    header('Location: lista_clientes.php');
  }else{
    while ($data = mysqli_fetch_array($sql)) {
      $idcliente  = $data['idcliente'];
      $nit  = $data['nit'];
      $nombre  = $data['nombre'];
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
  <title>Actualizar Cliente</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    
    <div class="form_register">
      <h1><i class="fas fa-user-edit"></i> Actualizar cliente</h1>
      <hr>
      <?php echo isset($alert) ? $alert : ''; ?>
      <form action="" method="post">
        <input type="hidden" name="id" value="<?= $idcliente ?>">
        <label for="nit">NIT</label>
        <input type="number" name="nit" id="nit" placeholder="Número de NIT" value="<?= $nit ?>">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" placeholder="Nombre completo" value="<?= $nombre ?>">
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