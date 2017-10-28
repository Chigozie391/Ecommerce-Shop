<h3 class="text-center">Shopping Cart</h3>
<div>
	<?php if(empty($cart_id)): ?>
		<p>Your Cart is empty.</p>
	<?php else: 
		$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
		$result = mysqli_fetch_assoc($cartQ);
		$items = json_decode($result['items'], true);
		$subtotal = 0
	?> 
	<table class=" table table-condensed" id="widget">
		<tbody>
			<?php foreach ($items as $item):
				$productQ = $db->query("SELECT * FROM products WHERE id = '{$item['id']}'");
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
		<?php endforeach; ?>
		<tr>
			<td></td>
			<td>Sub Total</td>
			<td> <?=money($subtotal) ;?></td>
		</tr>
		</tbody>
	</table>
	<a href="cart.php" class="btn btn-xs btn-primary">View Cart</a>
	<?php endif; ?>
</div>