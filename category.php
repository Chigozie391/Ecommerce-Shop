<?php 
	require_once 'core/init.php';
	include 'includes/head2.php';
	include 'includes/navigation.php';
	include 'includes/headercat.php';
	include 'includes/sidebar.php';


	if(isset(($_GET['cat']))){
		//child id
		$cat_id = sanitize($_GET['cat']);
	}else{
		$cat_id = '';
	}

	$sql ="SELECT * FROM products WHERE categories = '$cat_id' AND deleted = 0";
	$productQ = $db->query($sql);
	//function to get boh child and parent 
	$category = get_category($cat_id);
 ?>
		<!--main Content -->
    		<div class="col-md-9 col-sm-9 col-xs-12 pull-right">
    			<div class="row">
    				<h3 class="text-center"><?=$category['parent'].' - '.$category['child'];?></h3>
    				<?php while ($product = mysqli_fetch_assoc($productQ)): ?>
    					<div class="col-md-3 col-sm-4 text-center img-parent">
	    					<h4><?=$product['title']?></h4>
	    					<?php $photos = explode(',', $product['image']); ?>
	    					<img src="<?=$photos[0]?>" class="img-fluid" alt="<?=$product['title']?>">
	    					<p>Price: $<?=$product['price']?></p>
	    						<button class="btn btn-sm btn-success" onclick="detailsModal(<?=$product['id']?>)">Details</button>
	    			</div>
	    			<?php endwhile; ?>
    			</div>
    		</div>


<?php 

include 'includes/footer.php'; ?>


