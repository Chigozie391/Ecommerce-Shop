<?php 
require_once 'core/init.php';
if($_POST['response'] == ''){
	header('Location:index.php');

}else{
	setcookie(CART_COOKIE,'',1,'/',$domain,false);
		//get post data
	$full_name = sanitize($_POST['full_name']);
	$email = sanitize($_POST['email']);
	$address = sanitize($_POST['address']);
	$state = sanitize($_POST['state']);
	$phone1 = sanitize($_POST['phone1']);
	$phone2 = sanitize($_POST['phone2']);
	$grand_total = sanitize($_POST['grand_total']);
	$description = sanitize($_POST['description']); 
	$cart_id = sanitize($_POST['cart_id']);
	$reference = $_POST['response'];
 




	$idArray = array();
	$productsArray = array();

	//adjsut inventory
	//update products after each purchase
	$itemQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$iresult = mysqli_fetch_assoc($itemQ);
	$cart = json_decode($iresult['items'],true);
	//update the size in the databse
	foreach($cart as $c){
		$newSizeArray = array();
		//for updating our products table
		$item_id = $c['id'];
		//to store our ids
		$idArray[] = $c['id'];
		$productQ = $db->query("SELECT sizes FROM products WHERE id = '$item_id'");
		$product = mysqli_fetch_assoc($productQ);
		//function to get the sizes and quantity in array format
		$sizes = sizesToArray($product['sizes']);
		foreach($sizes as $size){
			if($size['size'] == $c['size']){
				//subtract the quantity in cart from the one in database
				$newQuantity = $size['quantity'] - $c['quantity'];
				$newSizeArray[] = array('size' => $size['size'], 'quantity' => $newQuantity, 'threshold' => $size['threshold']);
			}else{
				$newSizeArray[] = array('size' => $size['size'], 'quantity' => $size['quantity'], 'threshold' => $size['threshold']);
			}
		}
		$sizeString = sizesToString($newSizeArray);
		$db->query("UPDATE products SET sizes ='$sizeString' WHERE id = '$item_id'");
	}


	//update transaction
	$db->query("INSERT INTO transaction
		(cart_id,full_name,email,address,state,phone1,phone2,grand_total,description,reference) VALUES
		('$cart_id','$full_name','$email','$address','$state','$phone1','$phone2','$grand_total','$description','$reference') ");

	//update carts
	$db->query("UPDATE carts SET ordered  = 1 WHERE id = '$cart_id'");

	include 'includes/head2.php';
	include 'includes/navigation.php';
	include 'includes/headercat.php';
	?>

	<h1 class="text-center text-success">Thank You </h1>
	<p class="text-center">You will be Contacted Shortly</p>
	<p class = "text-center" >You may Screenshot or Print this Page as Receipt</p>
	<div class="col-md-6">
		<h2 class="text-success">Order Information</h2>
		<address>
			<b>Name: </b><?=$full_name; ?><br>
			<b>Email: </b><?=$email; ?><br>
			<b>State: </b><?=$state ?><br>
			<b>Phone Number:</b>
			<?=$phone1; ?><br>
			<?=($phone2 != '')? $phone2 : '' ;?>
			<p>Your Receipt Number is <b class="text-danger"><?=$cart_id ?></b></p>
			<p>Your Reference Number is <b class="text-danger"><?=$reference ?></b></p>
			<h4><b>Grand Total: </b><span class="bg-success green lighten-4"><?=money($grand_total) ?></span></h4>
		</address>
	</div>


	<?php 
	$ids = implode(',', $idArray);
	$productQR = get_details($ids);
	while($p = mysqli_fetch_assoc($productQR)){
		foreach($cart as $c){
			//for getting the quantity and size
			if($c['id'] == $p['id']){
				$x = $c;
				//move to the next
				continue;
			}
		}
		$productsArray[] = array_merge($x,$p);
	}


	?>
	<div class="col-md-6">
		<h3 class="text-center text-success" >Ordered Details</h3>
		<table class="table-condensed table table-striped table-bordered">
			<thead>
				<th>Title</th>
				<th>Category</th>
				<th>Quantity</th>
				<th>Size</th>
			</thead>
			<tbody>
				<?php foreach($productsArray as $prod) :?>
					<tr>
						<td><?=$prod['title']?></td>
						<td><?=$prod['parent'] .' - '. $prod['child']; ?></td>
						<td><?=$prod['quantity']?></td>
						<td id="size"><?=$prod['size']?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>


	<?php 
	include 'includes/footer.php';

}
?>