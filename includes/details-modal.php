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
				<h4 class="modal-title text-center"><p><?=$modal['title'];?></p></h4>
			</div>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-sm-6">
						<div class="center-block">
							<img src="<?=$modal['image'];?>" class="details img-responsive">
						</div>
					</div>
					<div class="col-sm-6">
						<h4>Details</h4>
						<p><?=$modal['description'];?></p>
						<hr>
						<p>Price:$<?=$modal['price'];?></p>
						<p>Brand: <?=$brand['brand'];?></p>
						<form action="add_cart.php" method="post">
							<div class="form-group">
								<div class="col-xs-3 col-md-3">
									<label for="quantity">Quantity</label>
									<input type="text" class="form-control" id="quantity" name="quantity">
								</div>
								<div class="col-xs-6 col-md-6">
									<label for="size">Size:</label>
									<select name="size" id = "size" class="form-control">
										<option value=""></option>
										<?php foreach ($sizeArray as $string) {
											$string_array = explode(':', $string);
											$size = $string_array[0];
											$quantity = $string_array[1];
											echo '<option value="'.$size.'">'.$size.' ('.$quantity.' Available)'.'</option>';
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
		<div class="modal-footer">
			<button class="btn btn-default" id="closebtn" data-dismiss="modal">Close</button>
			<button class="btn btn-warning" type="submit"><span class="glyphicon glyphicon-shopping-cart"> Add to Cart</span></button>
		</div>
	</div>
	</div>
</div>
<?php echo ob_get_clean(); ?>