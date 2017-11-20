<?php 
$sql = "SELECT * FROM categories WHERE parent = 0";
$pquery = $db->query($sql);
?>

<nav class="navbar navbar-default navbar-fixed-top">
	<div class="container-fluid">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a href="index.php" class="navbar-brand">Dominique's Store</a>
		</div>

		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav navbar-right">
				<?php while($parent = mysqli_fetch_assoc($pquery)) :?>
					<?php $parent_id = $parent['id']; ?>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle = "dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$parent['category'];?><span class="caret"></span></a>
						<?php $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
						$cquery = $db->query($sql2);
						?>
						
						<ul class ="dropdown-menu py-0" role ="menu">
							<?php while($child = mysqli_fetch_assoc($cquery)) :
							?>
							<li><a href = "category.php?cat=<?=$child['id']?>"><?=$child['category'] ?></a></li>
							<?php endwhile; ?>
						</ul>
					</li>
				<?php endwhile; ?>
				<li><a href="cart.php" class="navcart-icon"><i class="fa fa-shopping-cart"></i><b> Cart </b><span id="cqty" class="badge z-depth-2 red"><?=$cqty; ?></span></a></li>
			</ul>
		</div>
	</div>
</nav>