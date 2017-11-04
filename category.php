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
<section class="col-md-9 col-sm-9 col-xs-12 pull-right pb-3">

	<div class="row">
		<h3 class="text-center h3-responsive"><?=$category['parent'].' - '.$category['child'];?></h3>
		<!--Grid column-->
		<?php while ($product = mysqli_fetch_assoc($productQ)): ?>
			<div class="col-md-4 col-sm-4 col-xs-6 mb-r">

				<!--Card-->
				<div class="card card-cascade wider">

					<!--Card image-->
					<div class="view ovrlay hm-ewhite-slight" onclick="detailsModal(<?=$product['id']?>)">

						<?php $photos = explode(',', $product['image']); ?>
						<img src="<?=$photos[0]?>" class="img-fluid center-block" alt="<?=$product['title']?>">
						<a>
							<div class="mask"></div>
						</a>
					</div>
					<!--Card image-->

					<!--Card content-->
					<div class="card-body text-center mt-4">
						<!--Category & Title-->
						<h4 class="card-title h4-responsive"><strong><a href=""><?=$product['title']?></a></strong></h4>

						<!--Description-->

						<!--Card footer-->
						<div class="card-footer">
							<div class="mt-4"><p>Price: $<?=$product['price']?></p></div>
							<div class="pb-3">
								<button type="button" class="btn mb-2 btn-success" onclick="detailsModal(<?=$product['id']?>)">Details</button>
							</div>
						</div>

					</div>
					<!--Card content-->

				</div>
				<!--Card-->

			</div>
		<?php endwhile; ?>
	</div>
	<!--Grid column-->

</section>


<div class="col-md-12 col-sm-12 text-center">
<nav>
		<ul class="pagination pg-blue">
			<li class="page-item disabled">
				<a class="page-link" href="#" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
					<span class="sr-only">Previous</span>
				</a>
			</li>
			<li class="page-item active">
				<a class="page-link" href="#">1 <span class="sr-only">(current)</span></a>
			</li>
			<li class="page-item"><a class="page-link" href="#">2</a></li>
			<li class="page-item"><a class="page-link" href="#">3</a></li>
			<li class="page-item"><a class="page-link" href="#">4</a></li>
			<li class="page-item"><a class="page-link" href="#">5</a></li>
			<li class="page-item">
				<a class="page-link" href="#" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
					<span class="sr-only">Next</span>
				</a>
			</li>
		</ul>
	</nav>
</div>


<?php 
include 'includes/footer.php'; ?>


