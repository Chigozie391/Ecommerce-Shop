<?php require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php'; ?>


<div class="side-cart z-depth-1">
	<div class="side-header">
		<h5 class="text-center h5-responsive">Shopping Cart</h5>
	</div>

	<?php if(empty($cart_id)): ?>
		<p class="text-center my-3 text-danger">Your Cart is empty.</p>
	<?php else: 
	$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'], true);
	$subtotal = 0;
	$cqty = 0;
	?>
	<div class="sidecart-table">
		<div data-u="loading" class="jssorl-009-spin sidecart-spin spin" style="position: absolute; top:50%;left:40%">
			<img style="margin-top:-15px;position:relative;width:38px;height:38px;" src="images/slider/spin2.svg" />

		</div>
		<table class=" table table-condensed" id="widget">
			<tbody>
				<?php foreach ($items as $item):
				$productQ = $db->query("SELECT title,price FROM products WHERE id = '{$item['id']}'");
				//to check if is available
				$count = mysqli_num_rows($productQ);
				if($count>0):
					$product = mysqli_fetch_assoc($productQ);
					?>
					<tr>
						<td><?= $item['quantity'];?></td>
						<td><?=substr($product['title'],0,15); ?></td>
						<td><?=money($item['quantity'] * $product['price']);?></td>	
					</tr>
					<?php $subtotal += ($item['quantity'] * $product['price']);
					$cqty +=(int)$item['quantity'];
					?>
				<?php endif ;?>
			<?php endforeach;
			setcookie(CART_QUANTITY,$cqty,CART_QUANTITY_EXPIRE,'/',$domain,false);
			?>
			<tr>
				<td colspan = "2" ><b class=" text-danger ">Sub Total</b></td>
				<td  class="bg-success green lighten-5"><b class="text-success"><?=money($subtotal) ;?></b></td>
			</tr>
		</tbody>
	</table>
</div>

<a href="cart.php" class="btn p-3 unique-color">View Cart</a>
<?php endif; ?>
<?php echo ob_get_clean() ?>
</div>
<script>
	$(function(){
		$('span.badge').html('<?=$cqty?>');
	});

</script>
