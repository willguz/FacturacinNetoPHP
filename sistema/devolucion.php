<?php 
session_start();
include "../conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <?php include "includes/scripts.php"; ?>
  <title>Nueva venta</title>
</head>
<body>
  <?php include "includes/header.php"; ?>
  <section id="container">
    <div class="title_page">
      <h1><i class="fas fa-hand-holding-usd"></i> Devolución</h1>
    </div>
    <div class="containerTable">
      <table class="tbl_venta">
        <thead>
          <tr>
            <th>Código</th>
            <th colspan="2">Descripción</th>
            <th>Cantidad</th>
            <th class="textright">Precio</th>
            <th class="textright">Precio total</th>
            <th>Acción</th>
          </tr>
        </thead>
        <tbody id="detalle_venta">
          <?php
            if(empty($_REQUEST['fac']))
            {
              header("location: ventas.php");
              mysqli_close($conection);
            }else{

              $query_iva = mysqli_query($conection,"SELECT iva FROM configuracion");
              $result_iva = mysqli_num_rows($query_iva);

              $detalleTabla = '';
              $sub_total = 0;
              $iva = 0;
              $total = 0;

              $nofactura = $_REQUEST['fac'];

              $query = mysqli_query($conection,"SELECT df.correlativo,df.nofactura,p.descripcion,df.cantidad,df.precio_venta
                FROM detallefactura df
                INNER JOIN producto p
                ON df.codproducto = p.codproducto
                WHERE nofactura = $nofactura ");
              
              mysqli_close($conection);
              $result = mysqli_num_rows($query);

              if($result > 0){
                if ($result_iva > 0) {
                  $info_va = mysqli_fetch_assoc($query_iva);
                  $iva = $info_va['iva'];
                }

                while ($data = mysqli_fetch_array($query)) {
                  $precioTotal = round($data['cantidad'] * $data['precio_venta'], 2);
                  $sub_total = round($sub_total + $precioTotal, 2);
                  $total = round($total + $precioTotal, 2);

                  ?>
                  <tr>
                    <td><?= $data['correlativo']?></td>
                    <td colspan="2"><?= $data['descripcion']; ?></td>
                    <td class="textcenter"><?= $data['cantidad']; ?></td>
                    <td class="textright"><?= $data['precio_venta']; ?></td>
                    <td class="textright"><?= $precioTotal; ?></td>
                    <td class="">
                      <a href="#" class="link_delete" onclick="event.preventDefault(); dev_product_detalle(<?= $data['correlativo'].','.$nofactura; ?>)"><i class="far fa-trash-alt"></i></a>
                    </td>
                  </tr>
                  <?php
                }
                $impuesto = round($sub_total * ($iva / 100), 2);
                $tl_sniva = round($sub_total - $impuesto, 2);
                $total = round($tl_sniva + $impuesto, 2);
              }else{
                header("location: ventas.php");
              }
            }
          ?>
        </tbody>
        <tfoot id="detalle_totales">
          <tr>
            <td colspan="5" class="textright">SUBTOTAL $.</td>
            <td class="textright"><?= $tl_sniva ?></td>
          </tr>
          <tr>
            <td colspan="5" class="textright">IVA (<?= $iva ?>%)</td>
            <td class="textright"><?= $impuesto ?></td>
          </tr>
          <tr>
            <td colspan="5" class="textright">TOTAL $.</td>
            <td class="textright"><?= $total ?></td>
          </tr>       
        </tfoot>
      </table>
    </div>
  </section>
  <?php include "includes/footer.php"; ?>
</body>
</html>