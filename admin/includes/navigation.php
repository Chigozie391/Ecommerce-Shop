<nav class="navbar navbar-default nav-fixed-top">
		<div class="container-fluid">
			<a href="/shop/admin/index.php" class="navbar-brand">DashBoard</a>

			<ul class="nav navbar-nav pull-right">
				<li><a href="brands.php">Brands</a></li>
				<li><a href="categories.php">Categories</a></li>
				<li><a href="products.php">Products</a></li>
				<li><a href="archive.php">Archived</a></li>
				<li><a href="sliders.php">Sliders</a></li>
				<?php if(has_permission('admin')): ?>
					<li><a href="users.php">Users</a></li>
				 <?php endif?>
				 <li class="dropdown">
				 	<a href="" class="dropdown-toggle" data-toggle = "dropdown">Hello <?=$userData['first']?><span class="caret"></span></a>
					<ul class ="dropdown-menu" role = "menu">
						<li><a href="changepassword.php">Change Password</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				 </li>
				<li><a href="/shop/index.php">Visit SIte</a></li>
			</ul>
		</div>
	</nav>
  <div class="container-fluid">
