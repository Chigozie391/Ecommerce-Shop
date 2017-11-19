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
		//if the item match
		if($item['id'] == $edit_id && $item['size'] == $edit_size ){
			if($item['quantity'] > 1){
				$item['quantity'] = $item['quantity'] - 1;
				$update_items[] = $item;
				$_SESSION['success'] = 'The Item has been Updated';
			}else{
				//sets it to 1
				$items['quantity'] = 1;
				$update_items[] = $item;
			}
		}else{
			$update_items[] = $item;
		}

	}

}


if($mode == 'addone'){
	foreach ($items as $item) {
		if($item['id'] == $edit_id && $item['size'] == $edit_size){
			$item['quantity'] = $item['quantity'] + 1;
			$_SESSION['success'] = 'The Item has been Updated';
		}
		$update_items[] = $item;
	}
}
if($mode == 'delete'){
	foreach ($items as $item ) {	
		if($item['id'] == $edit_id && $item['size'] == $edit_size){
			unset($item);
			$_SESSION['info_flash'] = 'The item has been deleted';
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
