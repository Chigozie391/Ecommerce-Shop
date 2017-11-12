<?php require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php'; ?>


<div class="side-cart ">
	<div class="reloadside"> 
		<h4 class="text-center h4-responsive text-primary">Shopping Cart</h4>
		<?php if(empty($cart_id)): ?>
			<p class="text-center">Your Cart is empty.</p>
		<?php else: 
		$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
		$result = mysqli_fetch_assoc($cartQ);
		$items = json_decode($result['items'], true);
		$subtotal = 0;
		$cqty = 0;
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
	<a href="cart.php" class="btn btn-primary p-3">View Cart</a>
<?php endif; ?>
</div>
<?php echo ob_get_clean() ?>
</div>
<script>
	$('span.badge').html('<?=$cqty?>');
</script>
