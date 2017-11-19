<?php 
require_once '../core/init.php';
include 'includes/head.php';
$email= ((isset($_POST['email']))?sanitize($_POST['email']): '');
$password= ((isset($_POST['password']))?sanitize($_POST['password']): '');
$email = trim($email);
$password = trim($password);
$errors = array();

?>

	<style>	
	body{
		background-image:url("/shop/images/headerlogo/pattern-bg.png");
		background-attachment: fixed;
		background-repeat: repeat;
	}
</style>
<div id="headerWrapper" class="z-depth-3"></div>
<div class="container-fluid">
<div id="login-form" class="col-md-6 col-sm-12 col-xs-12">
	<?php 
	if($_POST){
		if($password == '' || $email == ''){
			$errors[] ='Please put your Email and Password';
		}else{
			$query = $db->query("SELECT * FROM users WHERE email = '$email' LIMIT 1");
			$user = mysqli_fetch_assoc($query);
			$userCount = mysqli_num_rows($query);

				//if it doesnt find a matching email
			if($userCount < 1){
				$errors[] = "Email or Password is not correct";
			}else{
				if(!password_verify($password,$user['password'])){
					$errors[] = "Email or Password is not corrects";
				}
			}
		}

				//check for errors
		if(!empty($errors)){
			echo display_errors($errors);
		}else{
					//login user in
			$userID = $user['id'];
			login($userID);
		}
	}

	?>
	<h2 class="text-center">Login</h2>
	<form action="login.php" method="post">
		<div class="form-group">
			<label for="email">Email:</label>
			<input type="text" name="email" class="form-control" value="<?=$email ?>">
		</div>
		<div class="form-group">
			<label for="password">Password:</label>
			<input type="password" name="password" class="form-control" value="<?=$password ?>">
		</div>
		<div class="form-group">
			<input type="submit" name="submit" value="Login" class="btn btn-primary">
		</div>
	</form>
	<p class="text-right"><a href="/shop/index.php" alt ="home">Visit SIte</a></p>
</div>



<?php include 'includes/footer.php'; ?>
