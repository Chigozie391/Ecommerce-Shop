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
					<div class="view overlay hm-white-slight" onclick="detailsModal('<?=$product['id']?>','index')">
						<div data-u="loading" class="jssorl-009-spin spin <?='spin'.$product['id']?>" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.5);">
							<img style="margin-top:-15px;position:relative;top:50%;width:38px;left:40%;height:38px;" src="images/slider/spin.svg" />
						</div>
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
						<h4 class="card-title h4-responsive"><strong><?=$product['title']?></strong></h4>

						<!--Description-->

						<!--Card footer-->
						<div class="card-footer">
							<div class="mt-4" ><p class="green-text price"><?=money($product['price'])?></p></div>
							<div class="pb-3">
								<button type="button" class="btn mb-2 btn-success" onclick="detailsModal('<?=$product['id']?>','index')">Details</button>
							</div>
						</div>

					</div>
					<!--Card content-->

				</div>
				<!--Card-->

			</div>
		<?php endwhile; ?>
	</div>

</section>

<!--Section: Products v.2-->

<?php 
if(isset($_SESSION['myparser'])){
	unset($_SESSION['myparser']);
}
include 'includes/footer.php'; ?>


<!--Section: Products v.2-->



