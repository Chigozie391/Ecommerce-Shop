<?php 
	require_once '../core/init.php';
		if(!is_logged_in()){
		//returns false runs this function
			login_error_redirect();
	}
	include 'includes/head.php';
	include 'includes/navigation.php';
	$squery = $db->query("SELECT * FROM slides");
	//delete operation
	if(isset($_GET['delete'])){
		$delete_id = (int)($_GET['delete']);
		$Iquery = $db->query("SELECT * FROM slides WHERE id = '$delete_id'");
		$result = mysqli_fetch_assoc($Iquery);
		$image_url = $_SERVER['DOCUMENT_ROOT'].$result['image'];
		unlink($image_url);
		$dquery = $db->query("DELETE FROM slides WHERE id = '$delete_id'");
		$_SESSION['success_flash'] = 'Item has been deleted.';
		header('Location: sliders.php');
	}
	if(isset($_GET['slidetoggle'])){
		$id = (int)$_GET['id'];
		$slidetoggle = (int)$_GET['slidetoggle'];
		$tQuery = $db->query("UPDATE slides SET slide = '$slidetoggle' WHERE id = '$id'");
		$_SESSION['success_flash'] = 'Toggled Succesfully.';
		header('Location: sliders.php');

	}
	$title = ((isset($_POST['title']) && $_POST['title'] != '')? sanitize($_POST['title']) : '');
	if($_POST){
		
		if ($title == ''){
			$errors[] = 'The Title can not be blank';
		}

		if(!empty($_FILES['photo']['size'])){
			$photo = $_FILES['photo'];
			$name = $photo['name'];
			$nameArray = explode('.', $name);
			$fileName = $nameArray[0];
			$fileExt = $nameArray[1];
			$mime = explode('/',$photo['type']);
			$mimeType = $mime[0];
			$mimeExt = $mime[1];
			$tmpLoc = $photo['tmp_name'];
			$tmpsize = $photo['size'];
			$allowed = array('png','jpg','jpeg','gif');


			if ($mimeType != 'image') {
				$errors[] = 'The file must be an Image.';
			}
			if(!in_array($mimeExt, $allowed)){
				$errors[] = 'The file extension must be png, jpg, jpeg or gif';
			}
			if($tmpsize > 15000000){
				$errors[] = 'The file must be under 15MB.';
			}
		}else{
			$errors[] = 'Please choose an image.';
		}


		if(!empty($errors)){
			echo display_errors($errors);
		}else{
			$uploadName = md5(microtime()).'.'.$fileExt;
			$uploadPath = BASEURL.'images/slider/'.$uploadName;
			$dbPath = '/shop/images/slider/'.$uploadName;
			move_uploaded_file($tmpLoc, $uploadPath);

			$insertQ = "INSERT INTO slides (`title`,`image`) VALUES ('$title', '$dbPath')";
			$db->query($insertQ);
			$_SESSION['success_flash'] = 'Upload Succesful.';
			header('Location: sliders.php');
		}
	}


?>
<h4 class="text-center">Sliders</h4>

<div class="row">
	<div class="col-md-6 col-sm-6">
	<table class="table  table-striped table-bordered">

		<thead>
			<th>Title</th>
			<th>Operations</th>
		</thead>
		<tbody>
			<?php while($slide = mysqli_fetch_assoc($squery)): ?>
			<tr>
				<td><?=$slide['title']?></td>
				<td>
					<a href="sliders.php?slidetoggle=<?=(($slide['slide'] == 0)?'1':'0')?>&id=<?=$slide['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-<?=(($slide['slide'] == 1)?'minus': 'plus') ?>	"></span></a>
					<a href="sliders.php?delete=<?=$slide['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</td>
			</tr>
		<?php endwhile; ?>
		</tbody>
	</table>
	</div>


	<div class="col-md-6 col-sm-6">
		<form action="sliders.php" method="post" enctype="multipart/form-data">
			<div class="form-group">
				<label for="title">Title*:</label>
				<input type="text" class="form-control" id="title" name="title" value="<?=$title ?>">
			</div>
			<div class="form-group">
				<label for="photo">Photo*:</label>
				<input type="file" class="form-control" name = "photo" id ="photo">
			</div>
			<input type="submit" value="Upload" class="btn btn-primary">
		</form>
	</div>
</div>






<?php include 'includes/footer.php';?>