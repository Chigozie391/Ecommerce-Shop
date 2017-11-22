<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';
include 'includes/head.php';
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
	$full_name = $details['full_name'];
	$email = $details['email'];
	$address = $details['address'].' '. $details['state'];
	$phone = $details['phone1'] .'<br> '. $details['phone2'];
	$grand_total = $details['grand_total'];
	$reference = $details['reference'];
	
	?>
	<div class = "check text-center"><i class="fa fa-check-circle" aria-hidden="true"></i></div>

	<h1 class="text-center thankyou"><strong>Thank You!</strong></h1>
	<p class="text-center">An email has been sent to you with your Order Information <br>
	You may also Screenshot or Print this Page as Receipt</p>
	<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12 order-info pl-5">
		<h2 class="text-success">Order Information</h2>
			<p><span>Name: </span> <?=$full_name?></p>
			<p><span>Email: </span> <?=$email ?></p>
			<p><span>Shipping Address: </span> <?=$address?></p>
			<p><span>Phone Number: </span><?=$phone?></p>
			<p><span>Your Receipt Number is </span><b style="color: #CC0000;"><?=$cart_id ?></b></p>
			<p><span>Your Reference Number is </span><b style="color: #CC0000;"><?=$reference?></b></p>
			<h4 class="h4-responsive"><span>Grand Total: </span><span style="color:#007E33;"><?=money($grand_total);?></span></h4>
	</div>



	<?php
	foreach($cart as $c){
		$productQ = get_details($c['id']);
		$p = mysqli_fetch_assoc($productQ);
		$products[] = array_merge($p,$c);
	}

	?>
	<div class="col-md-6 col-sm-6 col-xs-12 scroll order-info">
		<h2 class="text-center text-success" >Ordered Details</h2>
		<table class="table-condensed table table-striped table-bordered table-responsive">
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

</div>
<div class ="continue-shop"><button class="btn btn-warning">Continue Shopping</button></div>
<?php
	include 'includes/footer.php';

}else{

	header('Location:index.php');
}

?>