<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';
	$product_id = sanitize($_POST['product_id']);
	$product_id = (int)$product_id;
	$size = sanitize($_POST['size']);
	$available = sanitize($_POST['available']);
	$quantity = sanitize($_POST['quantity']);
	$item = array();
	//multi-demensional array
	$item[] = array(
		'id'=>$product_id,
		'size' => $size,
		'quantity' => $quantity);

	//for the cookie
	$query = $db->query("SELECT * FROM products WHERE id = '$product_id'");
	$product = mysqli_fetch_assoc($query);
	$_SESSION['success_flash'] = $product['title'].' has been added to your cart.';


//check if the cart cookie exist (items is already in database) or we have some item in the cart
if($cart_id != ''){
	$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$cart = mysqli_fetch_assoc($cartQ);
	//set to true to return an assoc array not obj
	$previous_item = json_decode($cart['items'],true);
	$item_match = 0;
	//for adding new items
	$new_items = array();
	foreach($previous_item as $pitem){
		//if the id on the present items is equal to id of the previous one and size also match
		if($item[0]['id'] == $pitem['id'] && $item[0]['size'] == $pitem['size']){
			//add the quantity of the new one to present one(update the quantity)
			$pitem['quantity'] =  $pitem['quantity'] + $item[0]['quantity'];
			//check to see if the new quantity is mre than whats available
			if($pitem['quantity'] > $available){
				//sets it to maximium we have
				$pitem['quantity'] = $available;

			}
			//if they all checks out,then our itemm match
			$item_match = 1;
		}
		//store the old and new quantity for update
		$new_items[] = $pitem;
	}

	if($item_match != 1){
		//merge the new and the old so they can have the same id
		$new_items = array_merge($item,$previous_item);
	}
	
	$items_json = json_encode($new_items);
	$cart_expire = date('Y-m-d H:i:s',strtotime('+30 days'));
	$db->query("UPDATE carts SET items = '$items_json', expire_date = '$cart_expire' WHERE id = '$cart_id'");
	//we reset the cookie
	setcookie(CART_COOKIE,'',1,'/',$domain,false);
	setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);

 }else{
 	//add cart to databse and set cookie
 	//convert array to json
 	$items_json = json_encode($item);
 	$cart_expire = date("Y-m-d H:i:s",strtotime("+30 days"));
 	$db->query("INSERT INTO carts (items,expire_date) VALUES ('$items_json','$cart_expire')");
 	//for the client
 	//gets the id of the last insert item
 	$cart_id = $db->insert_id;
 	//seta the cokkie for the client
 	//NAME,VALUE,EXPIRE TIME,PATH,DOMAIN,SECURITY
 	setcookie(CART_COOKIE,$cart_id,CART_COOKIE_EXPIRE,'/',$domain,false);
 }
?>
