		<nav>
			<ul>
				<li><a href="index.php"><i class="fas fa-home"></i> Inicio</a></li>
			<?php 
				if($_SESSION['rol'] == 1){
			 ?>
				<li class="principal">

					<a href="#"><i class="fas fa-users"></i> Usuarios<span class="arrow"> <i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_usuario.php"><i class="fas fa-user-plus"></i> Nuevo Usuario</a></li>
						<li><a href="lista_usuarios.php"><i class="fas fa-users"></i> Lista de Usuarios</a></li>
					</ul>
				</li>
			<?php } ?>
				<li class="principal">
					<a href="#"><i class="fas fa-user-friends"></i> Clientes<span class="arrow"> <i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_cliente.php"><i class="fas fa-user-plus"></i> Nuevo Cliente</a></li>
						<li><a href="lista_clientes.php"><i class="fas fa-user-friends"></i> Lista de Clientes</a></li>
					</ul>
				</li>
        <?php 
          if($_SESSION['rol'] == 1 OR $_SESSION['rol'] == 2){
        ?>
				<li class="principal">
					<a href="#"><i class="fas fa-truck"></i> Proveedores<span class="arrow"> <i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="registro_proveedor.php"><i class="fas fa-cart-plus"></i> Nuevo Proveedor</a></li>
						<li><a href="lista_proveedores.php"><i class="fas fa-luggage-cart"></i> Lista de Proveedores</a></li>
					</ul>
				</li>
        <?php } ?>
				<li class="principal">
					<a href="#"><i class="fas fa-shopping-basket"></i> Productos<span class="arrow"> <i class="fas fa-angle-down"></i></span></a>
					<ul>
            <?php 
              if($_SESSION['rol'] == 1 OR $_SESSION['rol'] == 2){
            ?>
						<li><a href="registro_producto.php"><i class="fas fa-cart-plus"></i> Nuevo Producto</a></li>
            <?php } ?>
						<li><a href="lista_producto.php"><i class="fas fa-shopping-basket"></i> Lista de Productos</a></li>
					</ul>
				</li>
				<li class="principal">
					<a href="#"><i class="fas fa-file-invoice"></i> Ventas<span class="arrow"> <i class="fas fa-angle-down"></i></span></a>
					<ul>
						<li><a href="nueva_venta.php"><i class="fas fa-folder-plus"></i> Nueva Venta</a></li>
						<li><a href="ventas.php"><i class="fas fa-folder"></i> Ventas</a></li>
					</ul>
				</li>
			</ul>
		</nav>