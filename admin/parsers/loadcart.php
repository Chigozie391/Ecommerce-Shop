<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';
if($cart_id != ''){
	$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'],true);
	$updatedItems = array();
	$error_flash = 0;

//checking for item changes since last visit and removing them if any
	foreach($items as  $item){
		$product_id = $item['id'];
		$productQ = $db->query("SELECT id,sizes,price FROM products WHERE id  = '$product_id'");
		$count = mysqli_num_rows($productQ);
		if($count > 0){
			$product = mysqli_fetch_assoc($productQ);
			$sArray = explode(',',$product['sizes']);
			foreach($sArray as $sizeString){
				$s = explode(':', $sizeString);
					//comparing the size in products and the ones in the cart
				if($item['size'] == $s[0] ){
					$available = $s[1];
					if($item['quantity'] > $available){
						$item['quantity'] = 1;
					}
					if($item['price'] != $product['price']){
						$item['price'] = $product['price'];
					}
					$updatedItems[] = $item;
				}
			}
		}else{
			$error_flash++;

		}
	}
	$item_json = json_encode($updatedItems);
	$db->query("UPDATE carts SET items ='$item_json' WHERE id = '$cart_id'");
	if($error_flash > 0 ){
		$_SESSION['error_flash'] = "We ran out of stock on some items in your Carts since your last visit";
	}
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
				</div>
			</div>
		<?php else: ?>
			<?php foreach($updatedItems as $uitem){
				$up_id = $uitem['id'];
						//from products table
				$uproductQ = $db->query("SELECT * FROM products WHERE id  = '$up_id'");
				$product =mysqli_fetch_assoc($uproductQ);

				?>
				<div class="row">

					<div class="col-md-5 col-sm-4 my-2 cartimg">
						<span class="delbtn">
							<a onclick="update_cart('delete','<?=$product['id']?>','<?=$uitem['size']?>')" class="btn red lighten-2 btn-xs p-2 mr-3"><span class="glyphicon glyphicon-trash"></span></a>
						</span>
						<?php $photos = explode(',', $product['image']) ?>
						<img src="<?=$photos[0]?>" class="img-fluid center-block" alt="<?=$product['title']?>">
					</div>

					
					<div class="col-md-4 col-sm-4 my-5">
						<table class="table table-condensed tcart">
							<tbody>
								<tr>
									<td><b>Name:</b></td>
									<td><?=$product['title'];?></td>
								</tr>
								<tr>
									<td><b>Size:</b></td>
									<td class="size"><?=$uitem['size'];?></td>
								</tr>
								<tr>
									<td><b>Price:</b></td>
									<td><?=money($product['price']);?></td>
								</tr>
								<tr>
									<td><b>Quantity:</b></td>
									<td>
										<a onclick="update_cart('removeone','<?=$product['id']?>','<?=$uitem['size']?>')" class="btn yellow darken-2 btn-xs p-2"><span class="glyphicon glyphicon-minus"></span></a>

										<span class ="mx-3"><b><?=$uitem['quantity']?></b></span>

										<?php if($uitem['quantity'] < $available) :?>
											<a onclick="update_cart('addone','<?=$product['id']?>','<?=$uitem['size']?>')" class="btn green lighten-2 btn-xs p-2"><span class="glyphicon glyphicon-plus"></span></a>
										<?php else: ?>
											<span class="text-danger"> Max</span>
										<?php endif; ?>
									</td>
								</tr>
								<tr>
									<td><b class="text-danger">Subtotal:</b></td>
									<td class=" green lighten-5"><b><?=money($uitem['quantity'] * $product['price']);?></b></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<?php
				$item_count +=$uitem['quantity'];
				$grand_total += ($uitem['quantity'] * $product['price']);
				
			} ?>

			<div class="row">

				<div class="col-md-12 col-sm-12">
					<h3 class="h3-responsive mt-0">Totals</h3>
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
								<input type="hidden" id="grand_total" name = "grand_total" value="<?=(int)$grand_total?>">
								<input type="hidden"  id ="description" name = "description" value="<?=$item_count.' item'.(($item_count>1)?'s':'').' from Dominique Store'?>">
								<input type="hidden" id="cart_id" name = "cart_id" value="<?=$cart_id ?>">
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
if(isset($_SESSION['myparser'])){
	unset($_SESSION['myparser']);
}
?>
<!--SPin-->
