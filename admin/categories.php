<?php 
	require_once '../core/init.php';
		if(!is_logged_in()){
		//returns false runs this function
			login_error_redirect();
	}
	include 'includes/head.php';
	include 'includes/navigation.php';
	$psql = "SELECT * FROM categories WHERE parent = 0";
	$presult = $db->query($psql);
	$errors = array();


	//edit
	
	if(isset($_GET['edit']) && !empty($_GET['edit'])){
		$edit_id = (int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$esql = "SELECT * FROM categories WHERE id = '$edit_id'";
		$eresult = $db->query($esql);
		$editCate = mysqli_fetch_assoc($eresult);
		
	}
	//set empty string for edit value
	$edit_value = '';
	$editParent_value = 0;
	if(isset($_GET['edit'])){
		//set edit value from the get value
		$edit_value = $editCate['category'];
		//for selecting the parent of the clicked element;
		$editParent_value = $editCate['parent'];
	}else{
		//if error occured keep the value in the input
		if(isset($_POST['category'])){
			$edit_value = $_POST['category']; 
			$editParent_value = $_POST['parent'];
		}
	}

	//delete category
	if(isset($_GET['delete']) && !empty($_GET['delete'])){
		$delete_id  = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		$dsql = "DELETE  FROM categories WHERE id = '$delete_id' OR parent = '$delete_id'";
		$db->query($dsql);
		header('Location: categories.php');
	}
	//process form
	if(isset($_POST) && !empty($_POST)){
		$parent = sanitize($_POST['parent']);
		$category = sanitize($_POST['category']);

		//if category is empty
		if($category == ''){
			$errors[] .= 'The category cannot be left balnk';
		}
		//if it already exist in database
		$query = "SELECT * FROM categories WHERE category = '$category' AND parent = '$parent'";
		$qresult = $db->query($query);
		$count = mysqli_num_rows($qresult);
		if($count>0){
			$errors[] .= $category.' Already exist in Database';
		}
		//display errors or update database
	if(!empty($errors)){
		//display errors
		$display = display_errors($errors);?>
		<script>
			$(function() {
				$('#errors').html('<?=$display ?>');
			});
		</script>

	<?php }else{
		//update database
		$updatesql = "INSERT INTO categories (category,parent) VALUES ('$category','$parent')";
		if(isset($_GET['edit'])){
			$updatesql = "UPDATE categories SET category = '$category' , parent = '$editParent_value' WHERE id = $edit_id";
		}
		$db->query($updatesql);
		header('Location: categories.php');		
	}

}


 ?>


 <h4 class="text-center">Categories</h4><br>
<div class="row">
	<div class="col-md-6">
		<!--form -->	
		<legend><?=((isset($_GET['edit']))?'Edit':'Add') ?> A Category</legend>
		<form action="categories.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:'') ?>" class="form" method="post">
			<div id="errors"></div>
			<div class="form-group">
				<label for="parent">Parent</label>
				<select class="form-control" name = "parent" id= "parent">
					<option value="0" <?=(($editParent_value == 0)? 'selected="selected"': '') ?>>Parent</option>
					<?php while($parent = mysqli_fetch_assoc($presult)) :?>
						<option value="<?=$parent['id'];?>" <?=(($editParent_value == $parent['id'])? ' selected = "selected" ': ''); ?>><?=$parent['category'];?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="form-group">
				<label for="category">Category</label>
				<input type="text" name = "category" class="form-control" id="category" value="<?=$edit_value?>">
			</div>
			<div class="form-group">
				<input type="submit" name ="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add') ?> Category" class="btn btn-success">
				<?php if(isset($_GET['edit'])) :?>
					<a href="categories.php" class="btn btn-default">Cancel</a>
				<?php endif; ?>
			</div>
		</form>
	</div>
	<!--table-->
	<div class="col-md-6">
		<table class="table-bordered table">
			<thead>
				<th>Categories</th>
				<th>Parent</th>
				<th></th>
			</thead>
			<tbody>
				<?php 
					$psql = "SELECT * FROM categories WHERE parent = 0";
					$presult = $db->query($psql);
					while($parent = mysqli_fetch_assoc($presult)):
					$parent_id = (int)$parent['id'];
					$csql = "SELECT * FROM categories WHERE parent = '$parent_id'";
					$cresult = $db->query($csql);
				?>

				<tr class="bg-primary">
					<td><?=$parent['category'];?></td>
					<td>Parent</td>
					<td>
						<a href="categories.php?edit=<?=$parent['id'];?>" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
						<a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><i class="fa fa-times"></i></a>

					</td>
				</tr> 
					<?php while($child = mysqli_fetch_assoc($cresult)) :?>
						<tr class="bg-info">
					<td><?=$child['category'];?></td>
					<td><?=$parent['category'];?></td>
					<td>
						<a href="categories.php?edit=<?=$child['id'];?>" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
						<a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><i class="fa fa-times"></i></a>

					</td>
				</tr> 

					<?php endwhile; ?>
				<?php endwhile; ?>
			</tbody>
		</table>
	</div>
</div>


 <?php include 'includes/footer.php'; ?>