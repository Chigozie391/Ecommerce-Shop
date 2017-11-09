<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';
include 'includes/head2.php';
include 'includes/navigation.php';
include 'includes/headercat.php';

if(isset($_SESSION['myparser'])){

	$json = json_decode($_SESSION['myparser'],true);
	$cart_id = $json['cart_id'];
	$dQuery = $db->query("SELECT * FROM transaction WHERE cart_id = '$cart_id' LIMIT 1");
	$details = mysqli_fetch_assoc($dQuery);

	$itemQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id' LIMIT 1");
	$iresult = mysqli_fetch_assoc($itemQ);
	$cart = json_decode($iresult['items'],true);

	?>



	<h1 class="text-center text-success">Thank You </h1>
	<p class="text-center">You will be Contacted Shortly</p>
	<p class = "text-center" >You may Screenshot or Print this Page as Receipt</p>
	<div class="col-md-6">
		<h2 class="text-success">Order Information</h2>
		<address>
			<b>Name: </b><?=$details['full_name']; ?><br>
			<b>Email: </b><?=$details['email']; ?><br>
			<b>State: </b><?=$details['state'] ?><br>
			<b>Phone Number:</b>
			<?=$details['phone1']; ?><br>
			<?=($details['phone2'] != '')? $details['phone2'] : '' ;?>
			<p>Your Receipt Number is <b class="text-danger"><?=$cart_id ?></b></p>
			<p>Your Reference Number is <b class="text-danger"><?=$details['reference'] ?></b></p>
			<h4><b>Grand Total: <span class="text-success"><?=money($details['grand_total']) ?></span></b></h4>
		</address>
	</div>


	<?php
	foreach($cart as $c){
		$productQ = get_details($c['id']);
		$p = mysqli_fetch_assoc($productQ);
		$products[] = array_merge($p,$c);
	}

	?>
	<div class="col-md-6">
		<h3 class="text-center text-success" >Ordered Details</h3>
		<table class="table-condensed table table-striped table-bordered">
			<thead>
				<th>Title</th>
				<th>Category</th>
				<th>Size</th>
				<th>Quantity</th>
				<th>Price</th>
				<th>Sub-Total</th>
			</thead>
			<tbody>
				<?php foreach($products as $prod) :?>
					<tr>
						<td><?=$prod['title']?></td>
						<td><?=$prod['parent'] .' - '. $prod['child']; ?></td>
						<td id="size"><?=$prod['size']?></td>
						<td><?=$prod['quantity']?></td>
						<td><?=money($prod['price'])?></td>
						<td><?=money($prod['quantity'] * $prod['price']) ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>


	<?php 
	include 'includes/footer.php';

}else{

	header('Location:index.php');
}

?>