<?php 
  session_start();
  if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
  }
    
  include "../conexion.php";

  if(!empty($_POST))
  {    
    $alert='';
    if(empty($_POST['proveedor']) || empty($_POST['producto']) || empty($_POST['precio']) || $_POST['precio'] <= 0 || empty($_POST['cantidad']) || $_POST['cantidad'] <= 0)
    {
      $alert='<div class="alert_error"><p>Todos los campos son obligatorios.</p></div>';
    }else{

      $proveedor = $_POST['proveedor'];
      $producto = $_POST['producto'];
      $precio  = $_POST['precio'];
      $cantidad   = $_POST['cantidad'];
      $usuario_id  = $_SESSION['idUser'];

      $foto = $_FILES['foto'];      
      $nombre_foto = $foto['name'];
      $tipo = $foto['type'];
      $url_temp = $foto['tmp_name'];

      $imgProducto = 'img_producto.png';

      if ($nombre_foto != '') {
        $destino = 'img/uploads/';
        $img_nombre = 'img_'.md5(date('d-m-Y H:m:s'));
        $imgProducto = $img_nombre.'.jpg';
        $src = $destino.$imgProducto;
      }
      
      $query_insert = mysqli_query($conection,"INSERT INTO producto(proveedor,descripcion,precio,existencia,usuario_id,foto)
                                  VALUES('$proveedor','$producto','$precio','$cantidad','$usuario_id','$imgProducto')");

      if($query_insert){
        if ($nombre_foto != '')
        {
          move_uploaded_file($url_temp, $src);
        }
        $alert='<div class="alert"><p>Producto guardado correctamente.</p></div>';
      }else{
        $alert='<div class="alert_error"><p>Error al guardar el producto.</p></div>';
      }      
    }
  }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include "includes/scripts.php"; ?>
  <title>Registro Producto</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    
    <div class="form_register">
      <h1><i class="fas fa-cart-plus"></i> Registro Producto</h1>
      <hr>
      <?php echo isset($alert) ? $alert : ''; ?>

      <form action="" method="post" enctype="multipart/form-data">
        <label for="proveedor">Proveedor</label>
        <?php 
          $query_proveedor = mysqli_query($conection, "SELECT codproveedor, proveedor FROM proveedor WHERE estatus = 1 ORDER BY proveedor ASC");

          $result_proveedor = mysqli_num_rows($query_proveedor);
          mysqli_close($conection);
        ?>
        <select name="proveedor" id="proveedor">
          <?php 
          if ($result_proveedor > 0)
          {
            while ($proveedor = mysqli_fetch_array($query_proveedor))
            {              
           ?>
          <option value="<?php echo $proveedor['codproveedor']; ?>"><?php echo $proveedor['proveedor']; ?></option>
          <?php
           }
          }
          ?>
        </select>
        <label for="producto">Producto</label>
        <input type="text" name="producto" id="producto" placeholder="Nombre completo del producto">
        <label for="precio">Precio</label>
        <input type="number" name="precio" id="precio" placeholder="Precio">
        <label for="cantidad">Cantidad</label>
        <input type="number" name="cantidad" id="cantidad" placeholder="Cantidad del producto">

        <div class="photo">
          <label for="foto">Foto</label>
                <div class="prevPhoto">
                <span class="delPhoto notBlock">X</span>
                <label for="foto"></label>
                </div>
                <div class="upimg">
                <input type="file" name="foto" id="foto" accept="image/png, .jpeg, .jpg, image/gif">
                </div>
                <div id="form_alert"></div>
        </div>
        
        <button type="submit" class="btn_save"><i class="fas fa-save"></i> Guardar producto</button>

      </form>


    </div>


  </section>
  <?php include "includes/footer.php"; ?>
</body>
</html>