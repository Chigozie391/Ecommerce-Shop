<?php 
	require_once '../core/init.php';
	//if user tries to access ths page
	if(!is_logged_in()){
		//returns false runs this function
			//login_error_redirect();
		header('Location:login.php');
	}
	include 'includes/head.php';
	include 'includes/navigation.php'; 
 ?>


<h4 class="text-center">Administrator Home</h4>
 <script>
	setTimeout(function(){
		$('.flash').fadeOut('slow');
	},5000);
</script>
 <?php include 'includes/footer.php';
 ?>
