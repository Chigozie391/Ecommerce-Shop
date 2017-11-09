<?php 
	require_once $_SERVER['DOCUMENT_ROOT'].'/shop/core/init.php';
	$mode = sanitize($_POST['mode']);
	$edit_size = sanitize($_POST['edit_size']);
	$edit_id = sanitize($_POST['edit_id']);
	$cartQ = $db->query("SELECT * FROM carts WHERE id = '$cart_id'");
	$result = mysqli_fetch_assoc($cartQ);
	$items = json_decode($result['items'], true);
	$update_items = array();

	if($mode == 'removeone'){
		foreach ($items as $item) {
			if($item['id'] == $edit_id && $item['size'] == $edit_size){
				$item['quantity'] = $item['quantity'] - 1;	
			}
			//if the quantity is not greater than 0 uodate item will be empty
			if($item['quantity'] > 0){
				//store all and the new quantity for update
				$update_items[] = $item;
			}else{
				$item['quantity'] = 1;
				$update_items[] = $item;
			}
		}
	}


	if($mode == 'addone'){
		foreach ($items as $item) {
			if($item['id'] == $edit_id && $item['size'] == $edit_size){
				$item['quantity'] = $item['quantity'] + 1;
			}
			$update_items[] = $item;
		}
	}
	if($mode == 'delete'){
		foreach ($items as $item ) {	
			if($item['id'] == $edit_id && $item['size'] == $edit_size){
			 	unset($item);
			}else{
				$update_items[] = $item;
			}
		}
	}


	if (!empty($update_items)) {
		$json_updated = json_encode($update_items);
		$db->query("UPDATE carts SET items = '$json_updated' WHERE id = '$cart_id'");
		setcookie(CART_QUANTITY,$cqty,CART_QUANTITY_EXPIRE,'/',$domain,false);
	}
	//unset cart
	if(empty($update_items)){
		$db->query("DELETE FROM carts WHERE id = '$cart_id'");
		setcookie(CART_COOKIE,'',1,'/',$domain,false);
		setcookie(CART_QUANTITY,'',1,'/',$domain,false);
	}
	?>
<script>
	$('span.badge').html('<?=$cqty?>');
</script>
