<?php 
	require_once 'core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	include 'includes/headercat.php';
?>


<div data-u="loading" class="jssorl-009-spin paystackspin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;background-color:rgba(0,0,0,0.7);">
			<img style="margin-top:-15px;position:relative;top:25%;width:38px;height:38px;" src="images/slider/spin.svg" />
</div>


<!--loads the carts.php-->
<script>
	$(function(){
		load_cart();

	});
	
</script>

<?php include 'includes/footer.php'; ?>
