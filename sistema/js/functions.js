$(document).ready(function(){

  //------------- Menu ---------------
  $('.btnMenu').click(function(e) {
    e.preventDefault();
    if ($('nav').hasClass('viewMenu'))
    {
      $('nav').removeClass('viewMenu');      
    }else{
      $('nav').addClass('viewMenu');
    }
  });

  $('nav ul li').click(function() {
    $('nav ul li ul').slideUp();
    $(this).children('ul').slideToggle();
  });
    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change",function(){
      var uploadFoto = document.getElementById("foto").value;
        var foto       = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
            if(uploadFoto !='')
            {
                var type = foto[0].type;
                var name = foto[0].name;
                if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png')
                {
                    contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                    $("#img").remove();
                    $(".delPhoto").addClass('notBlock');
                    $('#foto').val('');
                    return false;
                }else{  
                        contactAlert.innerHTML='';
                        $("#img").remove();
                        $(".delPhoto").removeClass('notBlock');
                        var objeto_url = nav.createObjectURL(this.files[0]);
                        $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
                        $(".upimg label").remove();
                        
                    }
              }else{
                alert("No selecciono foto");
                $("#img").remove();
              }              
    });

    $('.delPhoto').click(function(){
      $('#foto').val('');
      $(".delPhoto").addClass('notBlock');
      $("#img").remove();

      if ($("#foto_actual") && $("#foto_remove")){
        $("#foto_remove").val('img_producto.png');
      }

    });

    // Modal para agregar producto
    $('.add_product').click(function(e) {
      e.preventDefault();
      var producto = $(this).attr('product');
      var action = 'infoProducto';

      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,producto:producto},
        success: function (response) {
          if (response != 'error') {
            var info = JSON.parse(response);
            // $('#producto_id').val(info.codproducto);
            // $('.nameProducto').html(info.descripcion);

            $('.bodyModal').html('<form action="" method="POST" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                                    '<h1><i class="fas fa-cart-plus" style="font-size: 45pt;"></i> <br> Agregar Producto</h1>'+
                                    '<h2 class="nameProducto">'+info.descripcion+'</h2> <br>'+
                                    '<input id="texCantidad" type="number" name="cantidad" placeholder="Cantidad del producto" required="required"><br>'+
                                    '<input id="textPrecio" type="text" name="precio" placeholder="Precio del producto" required="required"><br>'+
                                    '<input id="producto_id" type="hidden" name="producto_id" value="'+info.codproducto+'" required="required">'+
                                    '<input type="hidden" name="action" value="addProduct" required="required">'+
                                    '<div class="alertAddProduct"></div>'+
                                    '<button type="submit" class="btn_add"><i class="fas fa-plus"></i> Agregar</button>'+
                                    '<a href="#" class="btn_ok closeModal" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                  '</form>');

          }
        },
        error: function (error) {
          console.log(error);
        }
      });
      
      $('.modal').fadeIn();
    });

    // Modal para eliminar  producto
    $('.del_product').click(function(e) {
      e.preventDefault();
      var producto = $(this).attr('product');
      var action = 'infoProducto';
      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,producto:producto},
        success: function (response) {
          if (response != 'error') {
            var info = JSON.parse(response);
            // $('#producto_id').val(info.codproducto);
            // $('.nameProducto').html(info.descripcion);

            $('.bodyModal').html('<form action="" method="POST" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
                                    '<h1><i class="fas fa-cart-plus" style="font-size: 45pt;"></i> <br> Eliminar Producto</h1>'+
                                    '<h2 class="nameProducto">'+info.descripcion+'</h2> <br>'+                                    
                                    '<p>¿Está seguro de eliminar el siguiente registro?</p>'+
                                    '<input id="producto_id" type="hidden" name="producto_id" value="'+info.codproducto+'" required="required">'+
                                    '<input type="hidden" name="action" value="delProduct" required="required">'+
                                    '<div class="alertDelProduct"></div>'+
                                    '<a href="lista_producto.php" class="btn_cancel" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+
                                    '<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Eliminar</button>'+
                                    
                                  '</form>');

          }
        },
        error: function (error) {
          console.log(error);
        }
      });

      $('.modal').fadeIn();
    });

    // Buscar producto con el select

    $('#search_proveedor').change(function(e){
      e.preventDefault();
      var sistema = getUrl();      
      location.href = sistema+'buscar_productos.php?proveedor='+$(this).val();
    });

    // Activa campos para regiostrar cliente

    $('.btn_new_cliente').click(function(e) {
      e.preventDefault();
      $('#nom_cliente').removeAttr('disabled');
      $('#tel_cliente').removeAttr('disabled');
      $('#dir_cliente').removeAttr('disabled');

      $('#div_registro_cliente').slideDown();
    });

    // Buscar Cliente
    $('#nit_cliente').keyup(function(e) {
      e.preventDefault();

      var cl = $(this).val();
      var action = 'searchCliente';

      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,cliente:cl},

        success: function(response)
        {
          if (response == 0){
            $('#idcliente').val('');
            $('#nom_cliente').val('');
            $('#tel_cliente').val('');
            $('#dir_cliente').val('');
            // Mostrar boton agregar
            $('.btn_new_cliente').slideDown();
          }else{
            var data = $.parseJSON(response);
            $('#idcliente').val(data.idcliente);
            $('#nom_cliente').val(data.nombre);
            $('#tel_cliente').val(data.telefono);
            $('#dir_cliente').val(data.direccion);
            // Ocultar boton agregar
            $('.btn_new_cliente').slideUp();

            // Bloque campos
            $('#nom_cliente').attr('disabled','disabled');
            $('#tel_cliente').attr('disabled','disabled');
            $('#dir_cliente').attr('disabled','disabled');

            //Oculta boton guardar
            $('#div_registro_cliente').slideUp();

          }
        },
        error: function(error) {
          // body...
        }
      });
    });

    // Crear cliente en ventas
    $('#form_new_cliente_venta').submit(function(e){
      e.preventDefault();
      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_new_cliente_venta').serialize(),

        success: function(response)
        {
          // Agregar id a input hidden
          $('#idcliente').val(response);
          // Bloque campos
          $('#nom_cliente').attr('disabled','disabled');
          $('#tel_cliente').attr('disabled','disabled');
          $('#dir_cliente').attr('disabled','disabled');

          // Ocultar boton agregar
          $('.btn_new_cliente').slideUp();
          //Oculta boton guardar
          $('#div_registro_cliente').slideUp();
        },
        error: function(error) {
          // body...
        }
      });
    });

    // Buscar producto en ventas
    $('#txt_cod_producto').keyup(function(e){
      e.preventDefault();

      var producto = $(this).val();
      var action = 'infoProducto';

      if (producto != ''){
        $.ajax({
          url: 'ajax.php',
          type: 'POST',
          async: true,
          data: {action:action,producto:producto},

          success: function(response)
          {
            if (response != 'error') {
              var info = JSON.parse(response);
              $('#txt_descripcion').html(info.descripcion);
              $('#txt_existencia').html(info.existencia);
              $('#txt_cant_producto').val('1');
              $('#txt_precio').html(info.precio);
              $('#txt_precio_total').html(info.precio);

              //Activar cantidad
              $('#txt_cant_producto').removeAttr('disabled');

              //Mostrar boton agregar
              $('#add_product_venta').slideDown();
            }else{
              $('#txt_descripcion').html('-');
              $('#txt_existencia').html('-');
              $('#txt_cant_producto').val('0');
              $('#txt_precio').html('0.00');
              $('#txt_precio_total').html('0.00');

              //Activar cantidad
              $('#txt_cant_producto').attr('disabled','disabled');

              //Mostrar boton agregar
              $('#btn_product_venta').slideUp();
            }
          },
          error: function(error) {
            // body...
          }
        });
      }
    });

    // Validar cantidad del producto antes de agregar
    $('#txt_cant_producto').keyup(function(e){
      e.preventDefault();

      var precio_total = $(this).val() * $('#txt_precio').html();
      var existencia = parseInt($('#txt_existencia').html());
      $('#txt_precio_total').html(precio_total);

      //Ocultar el boton agregar si la cantidad es menor que 1
      if (($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia)){
        $('#add_product_venta').slideUp();        
      }else{
        $('#add_product_venta').slideDown();
      }      
    });

    // Agregar producto al detalle
    $('#add_product_venta').click(function(e){
      e.preventDefault();
      if ($('#txt_cant_producto').val() > 0)
      {
        var codproducto = $('#txt_cod_producto').val();
        var cantidad = $('#txt_cant_producto').val();
        var action = 'addProductoDetalle';

        $.ajax({
          url: 'ajax.php',
          type: 'POST',
          async: true,
          data: {action:action,producto:codproducto,cantidad:cantidad},
          success: function(response)
          {
            if (response != 'error')
            {
              var info = JSON.parse(response);

              $('#detalle_venta').html(info.detalle);
              $('#detalle_totales').html(info.totales);

              $('#txt_cod_producto').html('-');
              $('#txt_descripcion').html('-');
              $('#txt_existencia').html('-');
              $('#txt_cant_producto').val('0');
              $('#txt_precio').html('0.00');
              $('#txt_precio_total').html('0.00');

              //Activar cantidad
              $('#txt_cant_producto').attr('disabled','disabled');

              //Mostrar boton agregar
              $('#btn_product_venta').slideUp();
            }else{
              console.log('No hay datos');
            }
            viewProcesar();
          },
          error: function (error) {
            console.log(error);
          }
        });
      }
    });

    //Anular venta
    $('#btn_anular_venta').click(function(e){
      e.preventDefault();

      var rows = $('#detalle_venta tr').length;

      if (rows > 0)
      {
        var action = 'anularVenta';

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action},
            success: function(response)
            {
              if (response != 'error')
              {
                location.reload();

              }
            },
            error: function (error) {
              console.log(error);
            }
          });

      }

    });

    //Facturar venta
    $('#btn_facturar_venta').click(function(e){
      e.preventDefault();

      var rows = $('#detalle_venta tr').length;

      if (rows > 0)
      {
        var action = 'procesarVenta';
        var codcliente = $('#idcliente').val();

        $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,codcliente:codcliente},
            success: function(response)
            {
              if (response != 'error')
              {
                var info = JSON.parse(response);
                // console.log(info);

                generarPDF(info.codcliente,info.nofactura);
                location.reload();
              }else{
                console.log('No hay datos');
              }
            },
            error: function (error) {
              console.log(error);
            }
          });

      }

    });

    // Modal para anular factura
    $('.anular_factura').click(function(e) {
      e.preventDefault();
      
      var nofactura = $(this).attr('fac');
      var action = 'infoFactura';

      $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: {action:action,nofactura:nofactura},
        success: function (response) {
          if (response != 'error') {
            var info = JSON.parse(response);

            $('.bodyModal').html('<form action="" method="POST" name="form_anular_factura" id="form_anular_factura" onsubmit="event.preventDefault(); anularFactura();">'+
                                    '<h1><i class="fas fa-cart-plus" style="font-size: 45pt;"></i> <br> Anular Factura</h1>'+                                    
                                    '<p>¿Realmente desea anular la factura?</p>'+
                                    '<p><strong>No. '+info.nofactura+'</strong></p>'+
                                    '<p><strong>Monto. '+info.totalfactura+'</strong></p>'+
                                    '<p><strong>Fecha. '+info.fecha+'</strong></p>'+
                                    '<input type="hidden" name="action" value="anularFactura">'+
                                    '<input type="hidden" name="no_factura" id="no_factura" value="'+info.nofactura+'" required>'+
                                    '<div class="alert alertDelProduct"></div>'+
                                    '<button type="submit" class="btn_ok"><i class="fas fa-trash-alt"></i> Anular</button>'+
                                    '<a href="#" class="btn_cancel" onclick="closeModal();"><i class="fas fa-ban"></i> Cerrar</a>'+                                    
                                  '</form>');

          }
        },
        error: function (error) {
          console.log(error);
        }
      });

      $('.modal').fadeIn();
    });

    $('.view_factura').click(function(e) {
      e.preventDefault();
      var codcliente = $(this).attr('cl');
      var noFactura = $(this).attr('f');
      generarPDF(codcliente,noFactura);
    });

    // Cambiar password
    $('.newPass').keyup(function(e) {
      validPass();
    });

    // Form cambiar contraseña
    $('#frmChangePass').submit(function(e) {
      e.preventDefault();

      var passActual = $('#txtPassUser').val();
      var passNuevo = $('#txtNewPassUser').val();
      var confirmPassNuevo = $('#txtPassConfirm').val();
      var action = "changePassword";

      if (passNuevo != confirmPassNuevo){
        $('.alertChangePass').html('<p style="color:red;">Las contraseñas no son iguales.</p>');
        $('.alertChangePass').slideDown();
        return false;
      }
      if(passNuevo.length < 5){
        $('.alertChangePass').html('<p style="color:red;">La nueva contraseña debe ser de 5 caracteres como mínimo.</p>');
        $('.alertChangePass').slideDown();
        return false;
      }

      $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: {action:action,passActual:passActual,passNuevo:passNuevo},
            success: function(response)
            {
              if (response != 'error')
              {                
                var info = JSON.parse(response);
                if (info.cod == '00'){
                  $('.alertChangePass').html('<p style="color:green">'+info.msg+'</p>');
                  $('#frmChangePass')[0].reset();
                }else{
                  $('.alertChangePass').html('<p style="color:red">'+info.msg+'</p>');
                }
                $('.alertChangePass').slideDown();
              }else{
                console.log('No hay datos');
              }
            },
            error: function (error) {
              console.log(error);
            }
          });
    });

    // Actualizar datos de empresa
    $('#frmEmpresa').submit(function(e) {
      e.preventDefault();

      var intNit = $('#txtNit').val();
      var strNombreEmp = $('#txtNombre').val();
      var strRSocial = $('#txtRSocial').val();
      var intTelEmp = $('#txtTelEmpresa').val();
      var strEmailEmp = $('#txtEmailEmpresa').val();
      var strDirEmp = $('#txtDirEmpresa').val();
      var intIva = $('#txtIva').val();

      if (intNit == '' || strNombreEmp == '' || intTelEmp == '' || strEmailEmp == '' || strDirEmp == '' || intIva == ''){
        $('.alertFormEmpresa').html('<p style="color: red;">Todos los campos son obligatorios.</p>');
        $('.alertFormEmpresa').slideDown();
        return false;
      }

      $.ajax({
            url: 'ajax.php',
            type: 'POST',
            async: true,
            data: $('#frmEmpresa').serialize(),
            beforeSend: function() {
              $('.alertFormEmpresa').slideUp();
              $('.alertFormEmpresa').html('');
              $('.frmEmpresa input').attr('disabled', 'disabled');
              
            },
            success: function(response)
            {
              if (response != 'error')
              {                
                var info = JSON.parse(response);
                if (info.cod == '00'){
                  $('.alertFromEmpresa').html('<p style="color: #23922d">'+info.msg+'</p>');                  
                  $('.alertFromEmpresa').slideDown();
                }else{
                  $('.alertFromEmpresa').html('<p style="color:red">'+info.msg+'</p>');
                }
                $('.alertFromEmpresa').slideDown();
                $('#frmEmpresa input').removeAttr('disabled');
              }else{
                console.log('No hay datos');
              }
            },
            error: function (error) {
              console.log(error);
            }
          });
    });

}); // End Ready

function validPass() {
  var passNuevo = $('#txtNewPassUser').val();
  var confirmPassNuevo = $('#txtPassConfirm').val();
  if (passNuevo != confirmPassNuevo){
    $('.alertChangePass').html('<p style="color:red;">Las contraseñas no son iguales.</p>');
    $('.alertChangePass').slideDown();
    return false;
  }
  if(passNuevo.length < 5){
    $('.alertChangePass').html('<p style="color:red;">La nueva contraseña debe ser de 5 caracteres como mínimo.</p>');
    $('.alertChangePass').slideDown();
    return false;
  }
  $('.alertChangePass').html('');
  $('.alertChangePass').slideUp();
}

function anularFactura() {
  var noFactura = $('#no_factura').val();
  var action = 'anularFactura';

  $.ajax({
          url: 'ajax.php',
          type: 'POST',
          async: true,
          data: {action:action,noFactura:noFactura},
          success: function(response)
          {
            if (response == 'error')
            {
              $('.alertAddProduct').html('<p style="color:red;">Error al anular la factura.</p>');
            }else{
              $('#row_'+noFactura+' .estado').html('<span class="anulada">Anulada</span>');
              $('#form_anular_factura .btn_ok').remove();
              $('#row_'+noFactura+' .div_factura').html('<button type="button" class="btn_anular inactive"><i class="fas fa-ban"></i></button>');
              $('.alertDelProduct').html('<p>Factura anulada.</p>');
            }
            
          },
          error: function (error) {
            console.log(error);
          }
        });
}

function generarPDF(cliente,factura) {
  var ancho = 1000;
  var alto = 800;
  //calcular posicion x,y para centrar la venta
  var x = parseInt((window.screen.width/2) - (ancho / 2));
  var y = parseInt((window.screen.heigth/2) - (alto / 2));

  $url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
  window.open($url,"Factura","left="+x+",top="+y+",height="+alto+",width="+ancho+"scrollbar=si,location=no,resizable=si,menubar=no");
}

function del_product_detalle(correlativo){
  var action = 'delProductoDetalle';
  var id_detalle = correlativo;

  $.ajax({
          url: 'ajax.php',
          type: 'POST',
          async: true,
          data: {action:action,id_detalle:id_detalle},
          success: function(response)
          {
            if (response != 'error')
            {
              var info = JSON.parse(response);

              $('#detalle_venta').html(info.detalle);
              $('#detalle_totales').html(info.totales);

              $('#txt_cod_producto').html('-');
              $('#txt_descripcion').html('-');
              $('#txt_existencia').html('-');
              $('#txt_cant_producto').val('0');
              $('#txt_precio').html('0.00');
              $('#txt_precio_total').html('0.00');

              //Activar cantidad
              $('#txt_cant_producto').attr('disabled','disabled');

              //Mostrar boton agregar
              $('#btn_product_venta').slideUp();

            }else{
              $('#detalle_venta').html('');
              $('#detalle_totales').html('');
            }
            viewProcesar();
          },
          error: function (error) {
            console.log(error);
          }
        });
}

function dev_product_detalle(correlativo,factura){
  var action = 'devProductoDetalle';
  var id_detalle = correlativo;
  var nofactura = factura;

  $.ajax({
          url: 'ajax.php',
          type: 'POST',
          async: true,
          data: {action:action,id_detalle:id_detalle,nofactura:nofactura},
          success: function(response)
          {
            if (response != 'error')
            {
              var info = JSON.parse(response);

              $('#detalle_venta').html(info.detalle);
              $('#detalle_totales').html(info.totales);

            }else{
              $('#detalle_venta').html('');
              $('#detalle_totales').html('');
            }
          },
          error: function (error) {
            console.log(error);
          }
        });
}
//Mostrar/Ocultar boton procesar
function viewProcesar(){
  if ($('#detalle_venta tr').length > 0)
  {
    $('#btn_facturar_venta').show();
  }else{
    $('#btn_facturar_venta').hide();
  }
}

function searchForDetalle(id){
  
  var action = 'searchForDetalle';
  var user = id;

  $.ajax({
          url: 'ajax.php',
          type: 'POST',
          async: true,
          data: {action:action,user:user},
          success: function(response)
          {
            if (response != 'error')
            {
              var info = JSON.parse(response);

              $('#detalle_venta').html(info.detalle);
              $('#detalle_totales').html(info.totales);

            }else{
              console.log('No hay datos');
            }
            viewProcesar();
          },
          error: function (error) {
            console.log(error);
          }
        });
}

function getUrl() {
  var loc = window.location;
  var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
  return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));  
}

function sendDataProduct() {
  $('.alertAddProduct').html('');

  $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_add_product').serialize(),
        success: function (response) {
          if (response == 'error') {
            $('.alertAddProduct').addClass('alert_error');
            $('.alertAddProduct').html('<p>Error al agregar el producto.</p>');
          }else{
            var info = JSON.parse(response);
            $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
            $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
            $('input').val('');
            $('.alertAddProduct').addClass('alert');            
            $('.alertAddProduct').html('<p>Producto guardado correctamente.</p>');

          }
        },
        error: function (error) {
          console.log(error);
        }
      });
}

function delProduct() {
  var pr = $('#producto_id').val();
  $('.alertDelProduct').html('');

  $.ajax({
        url: 'ajax.php',
        type: 'POST',
        async: true,
        data: $('#form_del_product').serialize(),
        success: function (response) {
          if (response == 'error') {
            $('.alertDelProduct').addClass('alert_error');
            $('.alertDelProduct').html('<p>Error al eliminar el producto.</p>');
          }else{
            $('.row'+pr).remove();
            $('#form_del_product .btn_ok').remove();
            $('.alertDelProduct').addClass('alert');                     
            $('.alertDelProduct').html('<p>Producto eliminado correctamente.</p>');

          }
        },
        error: function (error) {
          console.log(error);
        }
      });
}

function closeModal() {
  $('.alertAddProduct').html('');
  $('input').val('');
  $('.modal').fadeOut();
}