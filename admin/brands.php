<?php 
	require_once '../core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	$sql = "SELECT *  FROM brands ORDER BY brand";
	$result = $db->query($sql);
	$errors = array();

//edit item
	if(isset($_GET['edit']) && !empty($_GET['edit'])){
		$edit_id = (int)$_GET['edit'];
		$edit_id = sanitize($edit_id);
		$sql2 ="SELECT * FROM brands WHERE id = $edit_id";
		$edit_result = $db->query($sql2);
		$eBrand = mysqli_fetch_assoc($edit_result);
	}


//delete item
	if(isset($_GET['delete']) && !empty($_GET['delete'])){
		$delete_id = (int)$_GET['delete'];
		$delete_id = sanitize($delete_id);
		$sql = "DELETE FROM brands WHERE id = '$delete_id'";
		$db->query($sql);
		header('Location: brands.php');
	}
	//form submitted
	if(isset($_POST['add_submit'])){
		$pbrand = sanitize($_POST['brand']);
	 //check if brand is empty
		if($pbrand == ''){
			$errors[] .='You must enter a brand';
		}
		//check if brands already exist in database
		$sql = "SELECT * FROM brands WHERE brand = '$pbrand'";
		$presult  = $db->query($sql);
		$count = mysqli_num_rows($presult);
		if($count > 0){
			$errors[] .= $pbrand.' Already Exits, Choose another Brand name';
		}
		//display errors
		if(!empty($errors)){
			echo display_errors($errors);
		}else{
			//add item to database
			$sql = "INSERT INTO brands (brand) VALUES ('$pbrand')";
			if(isset($_GET['edit'])){
				$sql = "UPDATE brands SET brand = '$pbrand' WHERE id = '$edit_id'";
			}
			$db->query($sql);
			header('Location: brands.php');

		}
	}


 ?>
<h4 class="text-center">Brands</h4><hr>


<!--brand form -->
<div class="text-center">
	<form action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id: '')  ?>" class="form-inline" method="post">
		<div class="form-group">
			<?php if (isset($_GET['edit'])) {
				$brand_value = $eBrand['brand'];
			}elseif (isset($_POST['brand'])) {
				$brand_value = $_POST['brand'];
			}else{
				$brand_value = '';
			}

			?>
			<label for="brand"><?=((isset($_GET['edit']))? 'Edit ': 'Add a ') ?>Brand</label>
			<input type="text" class="form-control" id="brand" name="brand" value="<?=$brand_value;?>">
			<input type="submit" name="add_submit" class="btn btn-success" value="<?=((isset($_GET['edit']))? 'Edit': 'Add a') ?> Brand">
			<?php if(isset($_GET['edit'])) :?>
				<a href="brands.php " class="btn btn-default">Cancel</a>
			<?php endif ?>
		</div>	
	</form>
</div>
<br>

<table class="table table-bordered table-striped table-auto">
	<thead>
		<th></th>
		<th>Brand</th>
		<th></th>
	</thead>
	<tbody>
			<?php while($brand = mysqli_fetch_assoc($result)) :?>
		<tr>

			<td><a href="brands.php?edit=<?=$brand['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
			<td><a href=""></a><?=$brand['brand'];?></td>
			<td><a href="brands.php?delete=<?=$brand['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a></td>
		</tr>
	<?php endwhile; ?>
	</tbody>
</table>



 <?php include 'includes/footer.php'; ?>