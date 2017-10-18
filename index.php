<?php 
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/slider.php';
	include 'includes/deals.php';

	$sql ="SELECT * FROm products WHERE featured = 1";
	$featured = $db->query($sql);
 ?>

		<!--main Content -->
    		<div class="col-md-9 col-sm-9 col-xs-12 pull-right">
    			<div class="row">
    				<h3 class="text-center">Featured Products</h3>
    				<?php while ($product = mysqli_fetch_assoc($featured)): ?>
    					<div class="col-md-3 col-sm-4 text-center img-parent">
	    					<h4><?=$product['title']?></h4>
	    					<img src="<?=$product['image'] ?>" class="img-fluid" alt="<?=$product['title']?>">
	    					<p>Price: $<?=$product['price']?></p>
	    						<button class="btn btn-sm btn-success" onclick="detailsModal(<?=$product['id']?>)">Details</button>
	    			</div>
	    			<?php endwhile; ?>
    			</div>
    		</div>

    	</div>
   	</div>

<?php 

include 'includes/footer.php'; ?>


