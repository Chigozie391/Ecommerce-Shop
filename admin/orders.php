<?php 	

	require_once '../core/init.php';
	if(!is_logged_in()){
		//returns false runs this function
			login_error_redirect();
	} 	
	include 'includes/head.php';
	include 'includes/navigation.php';
	//complte order
	if(isset($_GET['complete']) && $_GET['complete'] == 1){
		$cart_id = sanitize((int)$_GET['cart_id']);
		$db->query("UPDATE carts SET shipped = 1 WHERE id = '$cart_id'");
		$_SESSION['success_flash'] = "The Order has been successfully shipped";
		header('Location: index.php');
	}

	$txn_id = sanitize((int)$_GET['txn_id']);
	$txnQuery = $db->query("SELECT * FROM transaction WHERE id = '$txn_id'");
	$txn = mysqli_fetch_assoc($txnQuery);
	$cart_id = $txn['cart_id'];
	$cartQuery = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$cart = mysqli_fetch_assoc($cartQuery);
	$items = json_decode($cart['items'],true);
	$idArray = array();
	$products = array();
	//gets aall the id
	foreach($items as $item){
 		$idArray[] = $item['id'];
	}
	$ids = implode(',',$idArray);
	

	//
	$productQR = get_details($ids);


	while($p = mysqli_fetch_assoc($productQR)){
		foreach($items as $item){
			//gets the item details(quabtity ans size)
			if($item['id'] == $p['id']){
				$x = $item;
				//move to the next
				continue;
			}
		}
		//complete product details as array
		$products[] = array_merge($x,$p); ;
	}

 ?>
	<h3 class="text-center">Ordered Products</h3>
	<table class="table-condensed table table-striped table-bordered">
		<thead>
			<th>Title</th>
			<th>Category</th>
			<th>Quantity</th>
			<th>Size</th>
		</thead>
		<tbody>
			<?php foreach($products as $product) :?>
				<tr>
					<td><?=$product['title']?></td>
					<td><?=$product['parent'] .' - '. $product['child']; ?> </td>
					<td><?=$product['quantity']?></td>
					<td><?=$product['size']?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<div class="row">
		<div class="col-md-6">
			<h4 class="text-center">Order Details</h4>
			<table class="table table-condensed table-striped table-bordered">
				<tbody>
					<tr>
						<td>Grand Totol</td>
						<td><?=money($txn['grand_total']);?></td>
					</tr>
					<tr>
						<td>Order Date</td>
						<td><?=pretty_date($txn['txn_date']);?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<h4 class="text-center">Shipping Address</h4>
		<div class="col-md-6">
			<address>
				<b>Name: </b><?=$txn['full_name'];?><br>
				<b>Email: </b><?=$txn['email'];?><br>
				<b>Address: </b><?=$txn['address'];?><br>
				<b>State: </b><?=$txn['state'];?><br>
				<b>Phone Number: </b>
				<?=$txn['phone1'];?><br>
				<?=($txn['phone2'] != '')?$txn['phone2']: '';?>
			</address>
		</div>
	</div>

 <div class="pull-right">
 	<a href="index.php" class="btn  btn-default">Cancel</a>
 	<a href="orders.php?complete=1&cart_id=<?=$cart_id?>" class="btn btn-primary ">Complete Order</a>
 </div>








 <?php include 'includes/footer.php'; ?>