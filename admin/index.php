<?php 
	require_once '../core/init.php';
	//if user tries to access ths page
	if(!is_logged_in()){
		//returns false runs this function
			//login_error_redirect();
		header('Location:login.php');
	}
	include 'includes/head.php';
	include 'includes/navigation.php'; 
 ?>
<?php 
	$txnQuery = "SELECT t.id,t.cart_id,t.full_name,t.description,t.txn_date,t.grand_total,c.items,c.ordered,c.shipped 
	FROM transaction t 
	LEFT JOIN carts c ON t.cart_id = c.id
	WHERE c.ordered = 1 AND c.shipped = 0 
	ORDER BY t.txn_date";

$txnResult = $db->query($txnQuery);
 ?>

	<h3 class="text-center">Orders To Ship</h3>
<div class="col-md-12 col-sm-12 col-xs-12">
	<table class="table table-condensed table-striped table-bordered">
		<thead>
			<th></th>
			<th>Name</th>
			<th>Description</th>
			<th>Receipt</th>
			<th>Total</th>
			<th>Date</th>
		</thead>
		<tbody>
			<?php while($order = mysqli_fetch_assoc($txnResult)) :?>
				<tr>
					<td><a href="orders.php?txn_id=<?=$order['id'];?>" class="btn btn-info btn-sm">Details</a></td>
					<td><?=$order['full_name'] ?></td>
					<td><?=$order['description']?></td>
					<td><?=$order['cart_id'];?></td>
					<td><?=money($order['grand_total']);?></td>
					<td><?=pretty_date($order['txn_date']) ?></td>
				</tr>
			<?php endwhile; ?>
		</tbody>
	</table>
</div>

<!--Inventory -->
<?php 
$iQuery = $db->query("SELECT * FROM products WHERE deleted = 0");
$lowItems = array();
while ($product = mysqli_fetch_assoc($iQuery)){
	$items = array();
	$sizes = sizesToArray($product['sizes']);
	foreach($sizes as $size){
		if($size['quantity'] <= $size['threshold']){
			$cat = get_category($product['categories']);
			$items = array(
				'title' => $product['title'],
				'size' => $size['size'],
				'quantity' => $size['quantity'],
				'threshold' => $size['threshold'],
				'category' => $cat['parent'].' - '.$cat['child'],
			);
			$lowItems[] = $items;
		}
	}
}

 ?>
<div class="col-md-12 col-sm-12 col-xs-12">
	<h3 class="text-center">Low Inventory</h3>
	<table class="table table-condensed table-bordered table-striped">
		<thead>
			<th>Product</th>
			<th>Category</th>
			<th>Size</th>
			<th>Quantity</th>
			<th>Threshold</th>
		</thead>
		<tbody>
			<?php foreach ($lowItems as $low): ?>
			<tr >
				<td <?=($low['quantity'] == 0)?'class = "bg-danger"' :''; ?> ><?=$low['title'];?></td>
				<td <?=($low['quantity'] == 0)?'class = "bg-danger"' :''; ?> ><?=$low['category'];?></td>
				<td <?=($low['quantity'] == 0)?'class = "bg-danger"' :''; ?> ><?=$low['size'];?></td>
				<td <?=($low['quantity'] == 0)?'class = "bg-danger"' :''; ?>><?=($low['quantity'] =='')? 0 : $low['quantity'];?></td>
				<td <?=($low['quantity'] == 0)?'class = "bg-danger"' :''; ?> ><?=$low['threshold'];?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
















 <script>
	setTimeout(function(){
		$('.flash').fadeOut('slow');
	},5000);
</script>
 <?php include 'includes/footer.php';
 ?>
