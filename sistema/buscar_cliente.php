<?php 
  session_start();
  
  include "../conexion.php";  

 ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include "includes/scripts.php"; ?>
  <title>Lista de cliente</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    <?php 

      $busqueda = strtolower($_REQUEST['busqueda']);
      if(empty($busqueda))
      {
        header("location: lista_clientes.php");
        mysqli_close($conection);
      }


     ?>
    
    <h1><i class="fas fa-user-friends"></i>Lista de clientes</h1>
    <a href="registro_cliente.php" class="btnNew"><i class="fas fa-user-plus"></i>Crear cliente</a>
    
    <form action="buscar_cliente.php" method="get" class="form_search">
      <input type="text" name="busqueda" id="busqueda" placeholder="Buscar" value="<?= $busqueda; ?>">
      <button type="submit" class="btn_search"><i class="fas fa-search"></i></button>
    </form>
    <div class="containerTable">
      <table>
        <tr>
          <th>ID</th>
          <th>Nit</th>
          <th>Nombre</th>
          <th>Teléfono</th>
          <th>dirección</th>
          <th>Acciones</th>
        </tr>
        <?php 
          //Paginador     
          $sql_registe = mysqli_query($conection,"SELECT COUNT(*) as total_registro FROM cliente 
                                    WHERE ( idcliente LIKE '%$busqueda%' OR 
                                        nit LIKE '%$busqueda%' OR 
                                        nombre LIKE '%$busqueda%' OR 
                                        telefono LIKE '%$busqueda%' OR 
                                        direccion LIKE '%$busqueda%'
                                        ) 
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

          $query = mysqli_query($conection,"SELECT * FROM cliente 
                        WHERE 
                        ( idcliente LIKE '%$busqueda%' OR 
                          nit LIKE '%$busqueda%' OR 
                          nombre LIKE '%$busqueda%' OR 
                          telefono LIKE '%$busqueda%' OR 
                          direccion    LIKE  '%$busqueda%' ) 
                        AND
                        estatus = 1 ORDER BY idcliente ASC LIMIT $desde,$por_pagina 
            ");
          mysqli_close($conection);
          $result = mysqli_num_rows($query);
          if($result > 0){

            while ($data = mysqli_fetch_array($query)) {
              
          ?>
            <tr>
              <td><?php echo $data["idcliente"]; ?></td>
              <td><?php echo $data["nit"]; ?></td>
              <td><?php echo $data["nombre"]; ?></td>
              <td><?php echo $data["telefono"]; ?></td>
              <td><?php echo $data["direccion"]; ?></td>
              <td>
                <a class="link_edit" href="editar_cliente.php?id=<?php echo $data["idcliente"]; ?>"><i class="fas fa-user-edit"></i> Editar</a>

              <?php if($_SESSION['rol'] == 1 || $_SESSION['rol'] == 2){ ?>
                |
                <a class="link_delete" href="eliminar_confirmar_cliente.php?id=<?php echo $data["idcliente"]; ?>"></i> Eliminar</a>
              <?php } ?>
                
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