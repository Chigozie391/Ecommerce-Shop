<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="/shop/admin/index.php" class="navbar-brand">DashBoard</a>
		</div>
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav navbar-right">
				<li><a href="brands.php">Brands</a></li>
				<li><a href="categories.php">Categories</a></li>
				<li><a href="products.php">Products</a></li>
				<li><a href="archive.php">Archived</a></li>
				<li><a href="sliders.php">Sliders</a></li>
				<?php if(has_permission('admin')): ?>
					<li><a href="users.php">Users</a></li>
				 <?php endif?>
				 <li class="dropdown">
				 	<a href="#" class="dropdown-toggle" data-toggle = "dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hello <?=$userData['first']?><span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="changepassword.php">Change Password</a></li>
						<li><a href="logout.php">Logout</a></li>
					</ul>
				 </li>
				<li><a href="/shop/index.php">Visit SIte</a></li>
			</ul>
		</div>
	</div>
	</nav>
  <div class="container-fluid">
 