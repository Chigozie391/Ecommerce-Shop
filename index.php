<?php 
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/slider.php';
include 'includes/sidebar.php';

$sql ="SELECT * FROM products WHERE featured = 1 AND deleted = 0";
$featured = $db->query($sql);
?>



<!--main Content -->
<section class="col-md-9 col-sm-12 col-xs-12 ">
	<div class="header my-4">
		<h2 class="text-center h2-responsive">Featured Products</h2>
	</div>

	<div class="row">
		
		<!--Grid column-->
		<?php while ($product = mysqli_fetch_assoc($featured)): ?>
		<div class="index col-md-4 col-sm-4 col-xs-6 mb-r">

				<!--Card-->
				<div class="card card-cascade wider">

					<!--Card image-->
					<div class="view overlay hm-white-slight" onclick="detailsModal(<?=$product['id']?>)">

						<?php $photos = explode(',', $product['image']) ?>
						<img src="<?=$photos[0]?>" class="img-fluid imgthumb" alt="<?=$product['title']?>">
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
							<div class="mt-4"><p>Price: &#8358;<?=$product['price']?></p></div>
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

<!--Section: Products v.2-->

<?php 
if(isset($_SESSION['myparser'])){
	unset($_SESSION['myparser']);
}
include 'includes/footer.php'; ?>


<!--Section: Products v.2-->



