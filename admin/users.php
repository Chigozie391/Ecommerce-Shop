<?php 
	require_once '../core/init.php';
	//if user tries to access ths page
	if(!is_logged_in()){
		//returns false runs this function
		login_error_redirect();
		return;
	}
	//checks if he is has admin rigths
	if(!has_permission('admin')){
		//if not admin, takes him back to index.php
		permission_error_redirect('index.php');
	}
	include 'includes/head.php';
	include 'includes/navigation.php'; 

	//delete operation
	if(isset($_GET['delete'])){
		$delete_id = sanitize($_GET['delete']);
		$db->query("DELETE FROM users WHERE id = '$delete_id'");
		$_SESSION['success_flash'] = "The user has been deleted";
		header('Location:users.php');
	}


	if(isset($_GET['add'])){ 
		$name = (isset($_POST['name'])? sanitize($_POST['name']) : '');
		$email = (isset($_POST['email'])?sanitize($_POST['email']): '');
		$password = (isset($_POST['password'])?sanitize($_POST['password']): '');
		$confirm = (isset($_POST['confirm'])?sanitize($_POST['confirm']): '');
		$permission = (isset($_POST['permission'])?sanitize($_POST['permission']): '');
		$error = array();

		if($_POST){
			//if email is already in databse
			$emailQuery = $db->query("SELECT * FROM users WHERE email = '$email'");
			$emailCount = mysqli_num_rows($emailQuery);
			$required = array('name', 'email', 'password', 'confirm', 'permission');
			
			if($emailCount != 0){
				$errors[] = 'That email already exist in the database.';
			}

			foreach($required as $field){
				if(empty($_POST[$field])){
					$errors[] = "You must fill out all fields.";
					break;
				}

			}

			if(strlen($password) < 6){
				$errors[] = 'Password must be atleast Six Character.';
			}
			if($password != $confirm){
				$errors[] = 'Your password do not match';
			}
			//validate email
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$errors[] = 'You must enter a valid email';
			}


			if(!empty($errors)){
				echo display_errors($errors);
			}else{
				//add user to database
				$hashed = password_hash($password,PASSWORD_DEFAULT);
				$db->query("INSERT INTO users (full_name,email,password,permission) VALUES ('$name','$email','$hashed','$permission') ");
				$_SESSION['success_flash'] = 'User has been Added';
				header('Location: users.php');
			}

		}



		?>

<h4 class="text-center">Add A New User</h4>
<form action="users.php?add=1" method= "post">
	<div class="form-group col-md-6">
		<label for="name">Name:</label>
		<input type="text" name = "name" class="form-control" value ="<?=$name; ?>">
	</div>
	<div class="form-group col-md-6">
		<label for="email">Email:</label>
		<input type="text" id = "email" name = "email" class="form-control" value ="<?=$email; ?>">
	</div>
	<div class="form-group col-md-6">
		<label for="password">Password:</label>
		<input type="password" id ="password" name = "password" class="form-control" value ="<?=$password; ?>">
	</div>
	<div class="form-group col-md-6">
		<label for="confirm">Confirm:</label>
		<input type="password" id = "confirm" name = "confirm" class="form-control" value ="<?=$confirm; ?>">
	</div>
	<div class="form-group col-md-6">
		<label for="permission">Permission:</label>
			<select name="permission" id="permission" class="form-control">
				<option value="" <?=(($permission == '')? ' selected': ''); ?> ></option>
				<option value="editor" <?=(($permission == 'editor') ? ' selected': ''); ?> >Editor</option>
				<option value="admin,editor" <?=(($permission == 'admin,editor')? ' selected': ''); ?> >Admin</option>
			</select>
	</div>
	<div class="form-group col-md-6 text-right add-user">
		<a href="users.php" class="btn btn-default">Cancel</a>
		<input type="submit" class="btn btn-primary" value="Submit">
	</div>

</form>





<?php 
	}else{

	$userQuery = $db->query("SELECT * FROM users ORDER BY full_name");
 ?>



<h4 class="text-center">Users</h4><hr>
<a href="users.php?add=1" class="btn btn-success pull-right">Add User</a>
<table class="table table-bordered table-striped table-condensed">
	<thead>
		<th></th>
		<th>Name</th>
		<th>Email</th>
		<th>Join Date</th>
		<th>Last Login</th>
		<th>Permission</th>
	</thead>
	<tbody>
		<?php while($user = mysqli_fetch_assoc($userQuery)) :?>
		<tr>
			<td>
				<?php 
				//don not show delete on the user that is logged in
				if($user['id'] != $userData['id']): ?>
				<a href="users.php?delete=<?=$user['id']?>" class="btn btn-xs btn-default"><i class="fa fa-trash-o"></i></a>
				<?php endif; ?>
			</td>
			<td><?=$user['full_name'] ?></td>
			<td><?=$user['email'] ?></td>
			<td><?=pretty_date($user['join_date']) ?></td>
			<td><?=(($user['last_login'] == '' || $user['last_login'] == '0000-00-00 00:00:00')?'Never': pretty_date($user['last_login']))  ?></td>
			<td><?=$user['permission'];?></td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>











 <?php }include 'includes/footer.php'; ?>