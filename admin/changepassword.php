<?php 
	require_once '../core/init.php';
	include 'includes/head.php';
	if(!is_logged_in()){
		login_error_redirect();
	}

	//userData is set from init.php
	$old_hashedPass = $userData['password'];
	//collect user inputs
	$old_password= ((isset($_POST['old_password']))?sanitize($_POST['old_password']): '');
	$old_password = trim($old_password);
	$password= ((isset($_POST['password']))?sanitize($_POST['password']): '');
	$password = trim($password);
	$confirm= ((isset($_POST['confirm']))?sanitize($_POST['confirm']): '');
	$confirm = trim($confirm);
	$new_hashed = password_hash($password, PASSWORD_DEFAULT);
	$userID = $userData['id'];
	$errors = array();

?>
<div class="container-fluid">

	<div id="login-form" class="col-md-6 col-sm-12 col-xs-12">
		<?php 
			if($_POST){
				//form validation
				//password is less than 6 character
				if(strlen($password)<6){
					$errors[] = 'Password must be atleast 6 characters.';
				}

				if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
					$errors[] = 'Fill out all the Fields.';
				}
				//f new pasword matches confirm
				if($password != $confirm){
					$errors[] = "The new password and the confirm password does not macth.";
				}
				if(!password_verify($old_password,$old_hashedPass)){
					$errors[] = "The old password does not match our records.";
				}
				//check for errors
				if(!empty($errors)){
				echo display_errors($errors);
			}else{
				//login user in
				$db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$userID'");
				$_SESSION['success_flash'] = "Your Password has been Updated";
				header('Location: index.php');
			}
		}

		 ?>
		<h2 class="text-center">Change Password</h2>
		<form action="changepassword.php" method="post">
			<div class="form-group">
				<label for="old_password">Old Password:</label>
				<input type="password" name="old_password" class="form-control" value="<?=$old_password ?>">
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" name="password" class="form-control" value="<?=$password ?>">
			</div>
			<div class="form-group">
				<label for="confirm">Confirm New Password:</label>
				<input type="password" name="confirm" class="form-control" value="<?=$confirm ?>">
			</div>
			<div class="form-group">
				<a href="index.php" class="btn btn-default">Cancel</a>
				<input type="submit" name="submit" value="Login" class="btn btn-primary">
			</div>
		</form>
		<p class="text-right"><a href="/shop/index.php" alt ="home">Visit SIte</a></p>
	</div>
	 
 <?php include 'includes/footer.php'; ?>
 <script>
	setTimeout(function(){
		$('.flash').fadeOut('slow');
		$('.flash').remove();
	},5000);
</script>