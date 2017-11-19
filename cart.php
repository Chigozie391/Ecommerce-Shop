<?php 
require_once 'core/init.php';
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headercat.php';
?>

<div>
	<h3 class=" h3-responsive text-center my-5">My Shopping Cart</h3>
	<div data-u="loading" class="jssorl-009-spin spin paystackspin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.6);">
		<img style="margin-top:-15px;position:relative;top:35%;width:38px;height:38px;" src="images/slider/spin.svg" />
	</div>
</div>
<div class="cart-wrapper">
	<div data-u="loading" class="jssorl-009-spin spin-cart" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;">
		<img style="margin-top:-15px;position:relative;top:35%;width:38px;height:38px;" src="images/slider/spin2.svg" />
	</div>
	<script>
		$(function(){
			load_cart();

		});
		
	</script>
</div>


<?php include 'includes/footer.php'; ?>
