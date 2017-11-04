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
				<h3 class="modal-title text-center h3-responsive"><?=$modal['title'];?></h3>
			</div>
			<div class="modal-body p-0">
				<div class="container-fluid">
					<div class="row">
						<div id="modal_errors" class= "bg-danger"></div>
						<div class="col-sm-6 fotorama">
							<?php $photos = explode(',', $modal['image']);
							foreach($photos as $photo) :?>
							<img src="<?=$photo;?>" class=" img-responsive">
						<?php endforeach ?>
					</div>
					<div class="col-sm-6">
						<div class="px-4">
							<h4>Details</h4>
							<p><?=nl2br($modal['description']);?></p>
							<hr>
							<p>Price:$<?=$modal['price'];?></p>
							<p>Brand: <?=$brand['brand'];?></p>
							<form action="add_cart.php" method="post" id = "add_product_form">
								<input type="hidden" id="available" name="available" value="">
								<input type="hidden" id="product_id" name = "product_id" value = "<?=$id?>">
								<div class="form-group">
									<div class="col-xs-4 col-md-4">
										<label for="quantity">Quantity</label>
										<input type="number" min = "0" class="form-control" id="quantity" name="quantity">
									</div>
									<div class="col-xs-6 col-md-6">
										<label for="size">Size:</label>
										<select name="size" id = "size" class="form-control">
											<option value=""></option>
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
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-default" id="closebtn" data-dismiss="modal">Close</button>
			<button class="btn btn-warning" onclick="add_to_cart();"><span class="glyphicon glyphicon-shopping-cart"></span> Add to Cart</button>
		</div>
	</div>
</div>
</div>
<script>
	//size is change
	$('#size').change(function(){
		//gets the no of available using html data attr
		var available = $('#size option:selected').data('available');
		//will be used in the footer
		//sets the hidden input box eith the value
		$('#available').val(available);
		
	});
	
	$(function(){
		$('.fotorama').fotorama({'loop':true,'autoplay':true});
	});
</script>
<?php echo ob_get_clean(); ?>