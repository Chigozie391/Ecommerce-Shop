<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';
if($cart_id != ''){
	$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'],true);
}

$grand_total = 0;
$item_count = 0;
ob_start();

?>
<div id="cart-reload">
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12 col-sm-12">
				<h3 class="text-center h3-responsive mt-0">My Shopping Cart</h3>
				<?php if($cart_id == ''): ?>
					<div class="bg-danger red lighten-4">
						<p class="text-center text-danger">
							Your Shopping Cart is empty
						</p>
					</div>
				<?php else: ?>
					<table class="table table-striped">
						<thead class="mdb-color lighten-2 cart-th">

							<th>Item</th>
							<th>Price</th>
							<th>Quantity</th>
							<th>Size</th>
							<th>Sub-Total</th>
						</thead>
						<tbody>
							<?php 
							foreach($items as $item){
								$product_id = $item['id'];
						//from products table
								$productQ = $db->query("SELECT * FROM products WHERE id  = '$product_id'");
								//checks if the item exist
								$count = mysqli_num_rows($productQ);
								if($count == 0){
									continue;
								}

								$product =mysqli_fetch_assoc($productQ);
								$sArray = explode(',',$product['sizes']);
								foreach($sArray as $sizeString){
									$s = explode(':', $sizeString);
							//comparing the size in products and the ones in the cart
									if($s[0] == $item['size']){
								//quantity
										$available = $s[1];
									}
								}
								?>
								<tr>

									<td>
										<a onclick="update_cart('delete','<?=$product['id']?>','<?=$item['size']?>')" class="btn red lighten-2 btn-xs p-2 mr-3"><span class="glyphicon glyphicon-trash"></span></a>
										<?=$product['title']?>
									</td>
									<td><?=money($product['price']);?></td>
									<td>
										<a onclick="update_cart('removeone','<?=$product['id']?>','<?=$item['size']?>')" class="btn yellow darken-2 btn-xs p-2"><span class="glyphicon glyphicon-minus"></span></a>

										<span class ="mx-3"><b><?=$item['quantity']?></b></span>

										<?php if($item['quantity'] < $available) :?>
											<a onclick="update_cart('addone','<?=$product['id']?>','<?=$item['size']?>')" class="btn green lighten-2 btn-xs p-2"><span class="glyphicon glyphicon-plus"></span></a>
										<?php else: ?>
											<span class="text-danger"> Max</span>
										<?php endif; ?>
									</td>
									<td id="size"><?=$item['size']?></td>
									<td><?=money($item['quantity'] * $product['price']) ?></td>
								</tr>

								<?php 

								$item_count +=$item['quantity'];
								$grand_total += ($item['quantity'] * $product['price'] );
							}
							?>

						</tbody>
					</table>
				</div>
			</div>
			<div class="row">

				<div class="col-md-12 col-sm-12">
					<h3 class="h3-responsive">Totals</h3>
					<div class="row">
						<div class="col-md-8">	
							<table class="table table-condensed table-bordered text-right">
								<thead class ="total-table-head">
									<th>Total Items</th>
									<th>Grand Total</th>
								</thead>
								<tbody>
									<tr>
										<td><?=$item_count?></td>
										<td class = "bg-success green lighten-4"><b><?=money($grand_total);?></b></td>
									</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-4 text-right mt-4">
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-primary " data-toggle="modal" data-target="#check-out-modal"><span class="glyphicon glyphicon-shopping-cart"></span> Check Out</button>
						</div>
					</div>
				</div>

			</div>
			<!-- Modal -->
			<div class="modal fade" id="check-out-modal" tabindex="-1" role="dialog" aria-labelledby="checkoutModal">
				<div class="modal-dialog modal-lg" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="checkout-title">Shipping Address</h4>
						</div>
						<div class="modal-body">

							<form action="thankyou.php" method="POST" id="payment-form" >

								<div id="modal_errors" class= "bg-danger"></div>
								<input type="hidden" id="grand_total" name = "grand_total" value="<?=$grand_total?>">
								<input type="hidden" name = "description" value="<?=$item_count.' item'.(($item_count>1)?'s':'').' from Dominique Store'?>">
								<input type="hidden" id="cart_id" name = "cart_id" value="<?=$cart_id ?>">
								<input type="hidden" name = "response" id="response">
								<div class="row">
									<div class="form-group col-md-6">
										<label for="full_name">Full Name*: </label>
										<input type="text" class="form-control" id="full_name" name="full_name">
									</div>
									<div class="form-group col-md-6">
										<label for="email">Email*: </label>
										<input type="email" class="form-control" id="email" name="email">
									</div>
									<div class="form-group col-md-6">
										<label for="address">Address*: </label>
										<input type="text" class="form-control" id="address" name="address">
									</div>
									<div class="form-group col-md-6">
										<label for="state">State*: </label>
										<input type="text" class="form-control" id="state" name="state">
									</div>
									<div class="form-group col-md-6">
										<label for="number">Phone No*: </label>
										<input type="text" class="form-control" id="phone1" name="phone1">
									</div>
									<div class="form-group col-md-6">
										<label for="number">Phone No 2: </label>
										<input type="text" class="form-control" id="phone2" name="phone2">
									</div>
								</div>
								<div class="modal-footer">
									<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
									<button type="button" id="next" class="btn btn-primary" onclick="check_address();">PAY</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


<?php 

endif;
echo ob_get_clean();

?>
<!--SPin-->
