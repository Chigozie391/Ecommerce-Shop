<h3 class="text-center h3-responsive">Shopping Cart</h3>
<div class="side-cart">
	<?php if(empty($cart_id)): ?>
		<p>Your Cart is empty.</p>
	<?php else: 
		$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
		$result = mysqli_fetch_assoc($cartQ);
		$items = json_decode($result['items'], true);
		$subtotal = 0;
	?> 
	<table class=" table table-condensed" id="widget">
		<tbody>
			<?php foreach ($items as $item):
				$productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}'");
				//to check if is available
				$count = mysqli_num_rows($productQ);
				if($count>0):
				$product = mysqli_fetch_assoc($productQ);
			?>
			<tr>
				<td><?= $item['quantity'];?></td>
				<td><?=substr($product['title'],0,10); ?></td>
				<td><?=money($item['quantity'] * $product['price']);?></td>	
			</tr>
			<?php
			 	$subtotal += ($item['quantity'] * $product['price']);
			 ?>
			<?php endif ;?>
		<?php endforeach; ?>
		<tr>
			<td colspan = "2" ><b class=" text-danger ">Sub Total</b></td>
			<td  class="bg-success green lighten-5"><b class="text-success"><?=money($subtotal) ;?></b></td>
		</tr>
		</tbody>
	</table>
	<a href="cart.php" class="btn btn-primary p-3">View Cart</a>
	<?php endif; ?>
</div>