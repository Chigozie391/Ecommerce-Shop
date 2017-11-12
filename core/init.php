<?php 
$db = mysqli_connect('127.0.0.1', 'root', '', 'shop');

if(mysqli_connect_errno()){
	echo 'Database Connection Failed with the following error: '.mysqli_connect_error();
	die();
}
$domain =($_SERVER['HTTP_HOST'] != '127.0.0.1')?'.'.$_SERVER['HTTP_HOST'] : false;
session_start();
require_once $_SERVER['DOCUMENT_ROOT'].'/shop/config.php';
require_once BASEURL.'helpers/helpers.php';


//gets the id of items stored i the cart
$cart_id = '';
if(isset($_COOKIE[CART_COOKIE])){
	$cart_id = sanitize($_COOKIE[CART_COOKIE]);
}
$cqty = '';
if(isset($_COOKIE[CART_QUANTITY])){
	$cqty = sanitize($_COOKIE[CART_QUANTITY]);
}

//gets the user name from the session
if(isset($_SESSION['SBUser'])){
	$userID = $_SESSION['SBUser'];
	$query = $db->query("SELECT * FROM users WHERE id = '$userID'");
	$userData = mysqli_fetch_assoc($query);
	$fn = explode(' ',$userData['full_name']);
	$userData['first'] = $fn[0];
	//if last name exist
	if(count($fn) > 1){
		$userData['last'] = $fn[1];
	}

}

if(isset($_SESSION['success_flash'])):?>
	<script>
		toastr.success("<?=$_SESSION['success_flash']?>");
	</script>
<?php 
	unset($_SESSION['success_flash']);
 endif;

 if(isset($_SESSION['error_flash'])):?>
	<script>
		toastr.error("<?=$_SESSION['error_flash']?>");
	</script>
<?php 
	unset($_SESSION['error_flash']);
 endif;

 if(isset($_SESSION['info_flash'])):?>
	<script>
		toastr.info("<?=$_SESSION['info_flash']?>");
	</script>
<?php 
	unset($_SESSION['info_flash']);
 endif;

 if(isset($_SESSION['warning_flash'])):?>
	<script>
		toastr.warning("<?=$_SESSION['warning_flash']?>");
	</script>
<?php 
	unset($_SESSION['warning_flash']);
 endif;


ob_start();
?>
