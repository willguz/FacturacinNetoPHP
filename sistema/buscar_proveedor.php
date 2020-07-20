<?php 
  session_start();
  if ($_SESSION['rol'] != 1 and $_SESSION['rol'] != 2) {
    header("location: ./");
  }
  include "../conexion.php";  

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include "includes/scripts.php"; ?>
  <title>Lista de proveedores</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    <?php 

      $busqueda = strtolower($_REQUEST['busqueda']);
      if(empty($busqueda))
      {
        header("location: lista_proveedores.php");
        mysqli_close($conection);
      }


     ?>
    
    <h1><i class="fas fa-luggage-cart"></i> Lista de proveedores</h1>
    <a href="registro_proveedor.php" class="btnNew"><i class="fas fa-cart-plus"></i>  Crear proveedor</a>
    
    <form action="buscar_proveedor.php" method="get" class="form_search">
      <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?= $busqueda; ?>">
      <input type="submit" value="Buscar" class="btn_search">
    </form>
    <div class="containerTable">
      <table>
        <tr>
          <th>ID</th>
          <th>Proveedor</th>
          <th>Contacto</th>
          <th>Teléfono</th>
          <th>Dirección</th>
          <th>Fecha</th>
          <th>Acciones</th>
        </tr>
        <?php 
          //Paginador     
          $sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM proveedor 
                                    WHERE ( codproveedor LIKE '%$busqueda%' OR 
                                        proveedor LIKE '%$busqueda%' OR 
                                        contacto LIKE '%$busqueda%' OR 
                                        telefono LIKE '%$busqueda%' ) 
                                    AND estatus = 1  ");

          $result_register = mysqli_fetch_array($sql_registe);
          $total_registro = $result_register['total_registro'];

          $por_pagina = 5;

          if(empty($_GET['pagina']))
          {
            $pagina = 1;
          }else{
            $pagina = $_GET['pagina'];
          }

          $desde = ($pagina-1) * $por_pagina;
          $total_paginas = ceil($total_registro / $por_pagina);

          $query = mysqli_query($conection,"SELECT * FROM proveedor 
                        WHERE 
                        ( codproveedor LIKE '%$busqueda%' OR 
                          proveedor LIKE '%$busqueda%' OR 
                          contacto LIKE '%$busqueda%' OR 
                          telefono LIKE '%$busqueda%' OR 
                          direccion LIKE '%$busqueda%' OR
                          date_add LIKE '%$busqueda%' ) 
                        AND
                        estatus = 1 ORDER BY codproveedor ASC LIMIT $desde,$por_pagina 
            ");
          mysqli_close($conection);
          $result = mysqli_num_rows($query);
          if($result > 0){

            while ($data = mysqli_fetch_array($query)) {
              
          ?>
            <tr>
              <td><?php echo $data["codproveedor"]; ?></td>          
              <td><?php echo $data["proveedor"]; ?></td>
              <td><?php echo $data["contacto"]; ?></td>
              <td><?php echo $data["telefono"]; ?></td>
              <td><?php echo $data["direccion"]; ?></td>
              <td><?php echo date('d-m-Y', strtotime($data["date_add"])); ?></td>
              <td>
                <a class="link_edit" href="editar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>"><i class="fas fa-user-edit"></i> Editar</a>         
                |
                <a class="link_delete" href="eliminar_confirmar_proveedor.php?id=<?php echo $data["codproveedor"]; ?>"><i class="fas fa-user-times"></i> Eliminar</a>            
              </td>
            </tr>
          
        <?php 
            }

          }
        ?>
      </table>
    </div>
<?php 
  
  if($total_registro != 0)
  {
 ?>
    <div class="paginador">
      <ul>
      <?php 
        if($pagina != 1)
        {
       ?>
        <li><a href="?pagina=<?php echo 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-step-backward"></i></a></li>
        <li><a href="?pagina=<?php echo $pagina-1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-backward"></i></a></li>
      <?php 
        }
        for ($i=1; $i <= $total_paginas; $i++) { 
          # code...
          if($i == $pagina)
          {
            echo '<li class="pageSelected">'.$i.'</li>';
          }else{
            echo '<li><a href="?pagina='.$i.'&busqueda='.$busqueda.'">'.$i.'</a></li>';
          }
        }

        if($pagina != $total_paginas)
        {
       ?>
        <li><a href="?pagina=<?php echo $pagina + 1; ?>&busqueda=<?php echo $busqueda; ?>"><i class="fas fa-forward"></i></a></li>
        <li><a href="?pagina=<?php echo $total_paginas; ?>&busqueda=<?php echo $busqueda; ?> "><i class="fas fa-step-forward"></i></a></li>
      <?php } ?>
      </ul>
    </div>
<?php } ?>


  </section>
  <?php include "includes/footer.php"; ?>
</body>
</html>