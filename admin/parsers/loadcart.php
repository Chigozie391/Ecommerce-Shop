<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';
if($cart_id != ''){
	$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'],true);
}
$i = 1;
$grand_total = 0;
$item_count = 0;
ob_start();

?>
<div id="cart-reload">
	<div class="row">
		<div class="col-md-12">
			<h2 class="text-center">My Shopping Cart</h2>
			<?php if($cart_id == ''): ?>
				<div class="bg-danger">
					<p class="text-center text-danger">
						Your Shopping Cart is empty
					</p>
				</div>
			<?php else: ?>
				<table class="table table-bordered table-condensed table-striped">
					<thead>
						<th>#</th>
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
								<td><?=$i ?></td>
								<td><?=$product['title']?></td>
								<td><?=money($product['price']);?></td>
								<td>
									<button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?=$product['id']?>','<?=$item['size']?>')">-</button>
									<?=$item['quantity']?>
									<?php if($item['quantity'] < $available) :?>
										<button class="btn btn-xs btn-default" onclick="update_cart('addone','<?=$product['id']?>','<?=$item['size']?>')">+</button>
									<?php else: ?>
										<span class="text-danger"> Max</span>
									<?php endif; ?>
								</td>
								<td><?=$item['size']?></td>
								<td><?=money($item['quantity'] * $product['price']) ?></td>
							</tr>

							<?php 
							$i++;
							$item_count +=$item['quantity'];
							$grand_total += ($item['quantity'] * $product['price'] );
						}
						?>

					</tbody>
				</table>
			</div>
		</div>
		<div class="row">
			<legend>Totals</legend>
			<div class="col-md-8">
				<table class="table table-condensed table-bordered text-right">
					<thead class ="total-table-head">
						<th>Total Items</th>
						<th>Grand Total</th>
					</thead>
					<tbody>
						<tr>
							<td><?=$item_count?></td>
							<td class = "bg-success"><?=money($grand_total);?></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="col-md-4">
				<!-- Button trigger modal -->
				<button type="button" class="btn btn-primary checkout " data-toggle="modal" data-target="#check-out-modal"><span class="glyphicon glyphicon-shopping-cart"></span> Check Out</button>
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
							<input type="hidden" name = "grand_total" value="<?=$grand_total?>">
							<input type="hidden" name = "description" value="<?=$item_count.' item'.(($item_count>1)?'s':'').' from Dominique Store'?>">
							<input type="hidden" name = "cart_id" value="<?=$cart_id ?>">
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

	<script>
		function check_address(){
			var data = {
				'full_name':$('#full_name').val(),
				'email':$('#email').val(),
				'address':$('#address').val(),
				'state':$('#state').val(),
				'phone1':$('#phone1').val(),
				'phone2':$('#phone2').val(),
			};
			$.ajax({  
				url:'/shop/admin/parsers/check_address.php',
				method:'post',
				data:data,
				success:function(data){
					if(data != 'passed'){
						$('#modal_errors').html(data);
					}
					if(data == 'passed'){
						$('#modal_errors').fadeOut();
						payWithPaystack();
					}
				},
				error:function(){
					alert('Something Went Wrong');
				}
			});
		}

		function payWithPaystack(){
			var handler = PaystackPop.setup({
				key: 'pk_test_86d8e282e01b04726f1bb1a766016c1c789e0eb5',
				email: $('#email').val(),
				amount: '<?=(int)$grand_total; ?>' * 100,
				metadata: {
					custom_fields: [
					{
						display_name:"Full Name",
						variable_name:"full_name",
						value:$('#full_name').val(),

					},
					{
						display_name: "Mobile Number",
						variable_name: "mobile_number",
						value: $('#phone1').val() +' ' + $('#phone2').val()
					},
					{
						display_name:"Receipt",
						variable_name:"cart_id",
						value:'<?=$cart_id?>',

					},
					{
						display_name:"Address",
						variable_name:"address",
						value:$('#address').val(),

					},
					{
						display_name:"State",
						variable_name:"state",
						value:$('#state').val(),

					}
					]
				},
				callback: function(response){
					$('#response').val(response.reference);
					$('#payment-form').submit();
				},
				onClose: function(){
					alert('window closed');
				}
			});
			handler.openIframe();
		}
	</script>

<?php endif;
echo ob_get_clean();
?>
