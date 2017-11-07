<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';

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
$reference = $_POST['reference'];



$idArray = array();
$productsArray = array();

	//adjsut inventory
	//update products after each purchase
$itemQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id' LIMIT 1");
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
//}

$detailsArray = array(
	'id' => $idArray,
	'cart_id' =>$cart_id
);

$_SESSION['myparser'] = json_encode($detailsArray);

?>