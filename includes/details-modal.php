<?php 
require_once '../core/init.php';
$id = $_POST['id'];
$id = (int)$id;
$sql = "SELECT * FROM products WHERE id = '$id'";
$dquery = $db->query($sql);
$modal = mysqli_fetch_assoc($dquery);
$brand_id = $modal['brand'];
$sql = "SELECT brand FROM brands WHERE id = '$brand_id'";
$bquery = $db->query($sql);
$brand = mysqli_fetch_assoc($bquery);
$sizeString = $modal['sizes'];
$sizeArray = explode(',', $sizeString);

?>


<?php ob_start(); ?>
<div class="modal fade details-1" id ="details-modal" tabindex="-1" role ="dialog" aria-labelledby ="details-1" aria-hidden	="true">
	<div class="modal-dialog modal-lg">
		<div class ="modal-content">
			<div class="modal-header">
				<button class="close" tyoe= "button " data-dismiss ="modal" aria-label="close">
					<span aria-hidden = "true">&times;</span>
				</button>
				<h3 class="modal-title card-title text-center h3-responsive"><?=$modal['title'];?></h3>
			</div>
			<div class="modal-body p-0">
				<div class="container-fluid">
					<div class="row">
						<div class=" col-md-6  col-sm-6 col-xs-12 callbacks_container">
							<ul class="rslides " id="slider1">
								<?php $photos = explode(',', $modal['image']);
								foreach($photos as $photo) :?>
								<li><img src="<?=$photo;?>" class=" img-responsive center-block"></li>
							<?php endforeach ?>
						</ul>
					</div>
					<div class="col-md-6 col-sm-6 col-xs-12 ">
						<div class="px-4">
							<h4 class="card-title h4-responsive">Description</h4>
							<p class="description"><?=nl2br($modal['description']);?></p>
							<hr class="my-2">
							<div class="my-3">
								<span class="h4-responsive ">Price: </span><span class="green-text h5-responsive price"><?=money($modal['price']);?></span>
							</div>
							<div class="my-3">
								<span class="h4-responsive ">Brand: </span><span class="card-title red-text h5-responsive price"><?=$brand['brand'];?></span>
							</div>
							<form action="add_cart.php" method="post" id = "add_to_cart_form">
								<input type="hidden" id="available" name="available" value="">
								<input type="hidden" id="price" name="price" value="<?=$modal['price'];?>">
								<input type="hidden" id="product_id" name = "product_id" value = "<?=$id?>">

								<div class="form-group my-3">
									<div class="row">
										<div class="col-md-6 col-sm-6 col-xs-6">
											<label for="quantity">Quantity</label>
											<input type="number" min = "0" class="form-control" id="quantity" name="quantity" placeholder="Select Quantity">
										</div>
										<div class="col-md-6 col-md-6 col-xs-6">
											<label for="size">Size:</label>
											<select name="size" id = "size" class="form-control">
												<option value="" disabled selected>Choose Size</option>
												<?php foreach ($sizeArray as $string) {
													$string_array = explode(':', $string);
													$size = $string_array[0];
													$available = $string_array[1];
													if($available > 0){
														echo '<option value="'.$size.'" data-available = "'.$available.'">'.$size.' ('.$available.' Available)'.'</option>';
													}
												} 
												?>
											</select>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-warning" id="closebtn" data-dismiss="modal">Close</button>
			<button class="btn btn-success" onclick="add_to_cart();"><i class="fa fa-cart-plus"></i> Add to Cart</button>
		</div>
	</div>
</div>
</div>
<script>
	$(function() {
    //size is change
    $('#size').change(function() {
        //gets the no of available using html data attr
        var available = $('#size option:selected').data('available');
        //will be used in the footer
        //sets the hidden input box eith the value
        $('#available').val(available);

    });

    $("#slider1").responsiveSlides({
    	auto: true,
    	nav: true,
    	speed: 500,
    	pause:true,
    	namespace: "callbacks",
    });
});
</script>
<?php echo ob_get_clean(); ?>