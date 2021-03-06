<?php 
	require_once '../core/init.php';
		if(!is_logged_in()){
		//returns false runs this function
			login_error_redirect();
	}
	include 'includes/head.php';
	include 'includes/navigation.php';


	//if delete is clicked
	if(isset($_GET['delete'])){
		$delete_id = (int)$_GET['delete'];
		$dQuery = $db->query("UPDATE products SET deleted = 1 WHERE id = '$delete_id'");
		$_SESSION['success_flash'] = 'The Item has been Archived';
		header('Location: products.php');
	}



	//if we clicked the edit or add product button it shows a form
	$dbPath = '';
	if(isset($_GET['add']) || isset($_GET['edit'])){
	//stting up our parent select
	$brandQuery= $db->query("SELECT * FROM brands");
	$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");

	//gretting our post initail details
	$title = ((isset($_POST['title']) && $_POST['title'] != '')? sanitize($_POST['title']): '');
	$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))? sanitize($_POST['brand']) : '');
	$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):'');
	$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))? sanitize($_POST['parent']) : '');
	$price = ((isset($_POST['price']) && $_POST['price'] != '')? sanitize($_POST['price']): '');
	$description = ((isset($_POST['description']) && $_POST['description'] != '')? sanitize($_POST['description']): '');
	$size = ((isset($_POST['size']) && $_POST['size'] != '')? sanitize($_POST['size']): '');
	$saved_photo = '';

	//for editing products
	 if(isset($_GET['edit'])){
	 	$edit_id = (int)$_GET['edit'];
	 	$edit_productresults = $db->query("SELECT * FROM products WHERE id = '$edit_id'");
	 	$edit_product = mysqli_fetch_assoc($edit_productresults);

	 	//prepaeing our image for delete
	 	if(isset($_GET['delete_image'])){
	 		$imgi = (int)$_GET['imgi']-1;
	 		$images = explode(',', $edit_product['image']);
	 		$image_url = $_SERVER['DOCUMENT_ROOT'].$images[$imgi];
	 		//deletes the image from the folder
	 		unlink($image_url);
	 		//remove that from the image array
	 		unset($images[$imgi]);
	 		//glue tthe remaning with a comma
	 		$imageString = implode(',', $images);
	 		$db->query("UPDATE products SET image = '{$imageString}' WHERE id = $edit_id");
	 		header('Location:products.php?edit='.$edit_id);
	 	}

	 	//overwritting our details for edit
	 	$title = ((isset($_POST['title']) && $_POST['title'] != '')?sanitize($_POST['title']):$edit_product['title']);
	 	$brand = ((isset($_POST['brand']) && !empty($_POST['brand']))?sanitize($_POST['brand']):$edit_product['brand']);
	 	//for the child options
	 	$category = ((isset($_POST['child']) && !empty($_POST['child']))?sanitize($_POST['child']):$edit_product['categories']);
	 	//query to get parent item using the id of category which is the value of post or frome edit products
	 	$pQuery = $db->query("SELECT * FROM categories WHERE id = '$category'");
	 	$parentResult = mysqli_fetch_assoc($pQuery);
	 	$parent = ((isset($_POST['parent']) && !empty($_POST['parent']))?sanitize($_POST['parent']):$parentResult['parent']);
	 	$price = ((isset($_POST['price']) && $_POST['price'] != '')?sanitize($_POST['price']):$edit_product['price']);
	 	$description = ((isset($_POST['description']) && $_POST['description'] != '')?sanitize($_POST['description']):$edit_product['description']);
	 	$size = ((isset($_POST['size']) && $_POST['size'] != '')?sanitize($_POST['size']):$edit_product['sizes']);
	 	$saved_photo = (($edit_product['image'] != '')?$edit_product['image']: '');

	}


		//preparing our size and quantity to be remembered which is gotten from edit
		if(!empty($size)){
		$sizeString = $size;
		$sizeString = rtrim($sizeString,',');
		$sizeArray = explode(',', $sizeString);
		$sArray = array();
		$qArray = array();
		$tArray = array();
		foreach ($sizeArray as $ss) {
			$s = explode(':', $ss);
			$sArray[] = $s[0];
			$qArray[] = $s[1];
			$tArray[] = $s[2];
		}
		}else{
			//empty the array
			$sizeArray = array();
		}



	//if submitted
	if($_POST){
		$errors = array();
		//form validation
		$required = array('title', 'brand', 'price','parent','child','size','description');
		$tmpLoc  =array();
		$allowed = array('png','jpg','jpeg','gif');
		$uploadPath = array();
		forEach($required as $field){
		if($_POST[$field] == ''){
			$errors[] = 'All fields with asterisk are required';
			break;
		}
	}

	if (!empty($_FILES)){
		$photoCount = count($_FILES['photo']['name']);
		//multiple image
	 	if($photoCount > 0){
	 		for($i = 0; $i<$photoCount;$i++){
		 		$name = $_FILES['photo']['name'][$i];
				$nameArray = explode('.',$name);
				$fileName = $nameArray[0];
				$fileExt = $nameArray[1];
		 		$mime = explode('/', $_FILES['photo']['type'][$i]);
		 		$mimeType = $mime[0];
		 		$mimeExt = $mime[1];
				$tmpLoc[] = $_FILES['photo']['tmp_name'][$i];
		 		$filesize = $_FILES['photo']['size'][$i];
		 		$uploadName = md5(microtime().$i).'.'.$fileExt;
		 		$uploadPath[] = BASEURL.'images/products/'.$uploadName;
		 		if($i != 0){
		 			$dbPath .= ',';
		 		}
		 		$dbPath .= '/shop/images/products/'.$uploadName;
				if($mimeType != 'image'){
					$errors[] = 'The file must be an image';

				}
				if(!in_array($mimeExt, $allowed)){
					$errors[] = 'The file extension must be an png, jpg, jpeg, or gif.';
				}
				if($filesize > 15000000){
					$errors[] = 'The file must be under 15MB';
				}
			}
		}
	}

	if(!empty($errors)){
		echo display_errors($errors);
	}else{
		//upload files and update the database
		$size = rtrim($size,',');

		if($photoCount > 0){
			for($i = 0; $i < $photoCount; $i++){
				move_uploaded_file($tmpLoc[$i], $uploadPath[$i]);
			}
		}

		$insertSql = "INSERT INTO products (`title`,`price`,`brand`,`categories`,`image`,`description`,`sizes`) 
		VALUES ('$title','$price','$brand','$category','$dbPath','$description','$size')";

		//for edit,we redefine our sql
		if(isset($_GET['edit'])){
			if ($dbPath == ''){
				$dbPath = $saved_photo;
			}
			$insertSql = "UPDATE products SET title = '$title',price = '$price',brand = '$brand',categories = '$category',
			image = '$dbPath',description = '$description',sizes = '$size' WHERE id = '$edit_id'";
		}
		$db->query($insertSql);
		header('Location:products.php');
   }

}


?>
<h4 class="text-center"><?=((isset($_GET['edit']))?'Edit ':'Add A ') ?>Product</h4><hr>

	<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id: 'add=1') ?>" method="POST" enctype="multipart/form-data">	
	<div class="col-md-3 col-sm-3 form-group">
		<label for="title">Title*:</label>
		<input id="title" name = "title" type="text" class="form-control" value ="<?=$title; ?>" >
	</div>
	<div class="col-md-3 col-sm-3 form-group">
		<label for="brand">Brand*:</label>
		<select name="brand" id="brand" class="form-control">
			<option value=""<?=(($brand == '')?'selected': ''); ?>></option>
			<?php while($b = mysqli_fetch_assoc($brandQuery)) :?>
				<option value="<?=$b['id']?>"<?=(($brand == $b['id'])? 'selected':'');?>>
					<?=$b['brand'];?>
				</option>
			<?php endwhile; ?>
		</select>
	</div>
	<div class="col-md-3 col-sm-3 form-gropu">
		<label for="parent">Parent*:</label>
		<select name="parent" id="parent" class="form-control">
			<option value="" <?=(($parent == '')?'selected': '');?>></option>
				<?php while($p = mysqli_fetch_assoc($parentQuery)) :?>
					<option value="<?=$p['id']?>" <?=(($parent == $p['id'])? 'selected':'');?>><?=$p['category'];?></option>
			<?php endwhile; ?>
		</select>
	</div>
	<div class="form-group col-md-3 col-sm-3">
		<label for="child">Child*:</label>
		<select  name="child" id="child" class="form-control">
			<option value="" ></option>
			<!--populated from parsers-->
		</select>
	</div>
	<div class="form-group col-md-3 col-sm-3">
		<label for="price">Price*:</label>
		<input type="text" class="form-control" id="price" name="price" value="<?=$price?>">
	</div>
	<div class="form-group col-md-3 col-sm-3">
		<label for="qsize">Qauntity &amp; Size*:</label>
		<button id="qsize" class="btn btn-default form-control" onclick=" $('#sizesModal').modal('toggle'); return false;">Quantity and Sizes</button>
	</div>
	<div class="form-group col-md-6 col-sm-6">
		<label for="sizes">Sizes &amp; Quantity Preview:</label>
		<input  readonly class="form-control" type="text" name= "size" id="sizes" value="<?=$size?>">
	</div>
	<div class="form-group col-md-6 col-sm-6">
		<?php if($saved_photo != ''): ?>
		<?php 
			$imgi = 1;
			$images = explode(',', $saved_photo);
		?>
		<?php foreach($images as $image):?>
			<div class="col-md-4 saved_photo">
				<img src="<?=$image?>" alt="saved_photo" class="img-fluid-admin"><br>
				<a href="products.php?delete_image=1&edit=<?=$edit_id;?>&imgi=<?=$imgi;?>" class="text-danger">Delete Image</a>
			</div>
			<?php $imgi++ ?>
		<?php endforeach; ?>
			<?php else: ?>
			<label for="photo">Photo*:</label>
		<input type="file" class="form-control" id="photo" name="photo[]" multiple>
		<?php endif ?>
	</div>
	<div class="form-group col-md-6 col-sm-6">
		<label for="description">Description*:</label>
		<textarea name="description" id="description" rows="6" class="form-control"><?=$description; ?></textarea>
	</div>
	<div class="col-md-2 col-sm-2  pull-right">
		<input type="submit" class=" btn btn-success" value ="<?=((isset($_GET['edit']))?'Edit ': 'Add ') ?>Product">
	</div>

</form>
	<a href="products.php" class="btn btn-default pull-right">Cancel</a>

<!--Modal-->
<div class="modal fade" id="sizesModal" tabindex="-1" role="dialog" aria-labelledby="sizesModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="sizesModalLabel">Modal title</h4>
      </div>
      <div class="modal-body">
      	<div class="container-fluid">
        <?php for($i = 1;$i<=6; $i++) :?>
		<div class="form-group col-md-2 col-sm-2">
			<label for="size<?=$i?>">Size:</label>
			<input type="text" name="size<?=$i?>" id="size<?=$i?>" class = "form-control" value="<?=((!empty($sArray[$i-1]))?$sArray[$i-1]:'') ?>">
		</div>
		<div class="form-group col-md-2 col-sm-2">
			<label for="threshold<?=$i?>">Quantity:</label>
			<input type="number" name="qty<?=$i?>" id="qty<?=$i?>" min = "0" class="form-control" value ="<?=((!empty($qArray[$i-1]))?$qArray[$i-1]:'') ?>">
		</div>
		<div class="form-group col-md-2 col-sm-2">
			<label for="qty<?=$i?>">Threshold:</label>
			<input type="number" name="threshold<?=$i?>" id="threshold<?=$i?>" min = "0" class="form-control" value ="<?=((!empty($tArray[$i-1]))?$tArray[$i-1]:'') ?>">
		</div>
        <?php endfor; ?>
      </div>
	</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="updateSizes(); $('#sizesModal').modal('toggle'); return false;">Save changes</button>
      </div>
    </div>
  </div>
</div>

<?php
	}
	else
	{

	$sql = "SELECT * FROM products WHERE deleted = 0";
	$presult = $db->query($sql);

	if(isset($_GET['featured'])){
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$featuredsql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
		$db->query($featuredsql);
		header('Location: products.php');
	}

?>



<h4 class="text-center">Products</h4>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-button">Add Products</a>
<div class="table-responsive col-md-12 col-sm-12">
	<table class="table table-bordered table-condensed table-striped">
	<thead>
		<th></th>
		<th>Product</th>
		<th>Price</th>
		<th>Category</th>
		<th>Featured</th>
		<th>Sold</th>
	</thead>
	<tbody>
		<?php while($product = mysqli_fetch_assoc($presult)) :
			//getting the child and parent category
			$childID = $product['categories'];
			$cateSQL = "SELECT * from categories WHERE id = '$childID'";
			$cateResult = $db->query($cateSQL);
			$child = mysqli_fetch_assoc($cateResult);
			$parentID = $child['parent'];
			$pSQL = "SELECT * FROM categories WHERE id = '$parentID'";
			$parentresult = $db->query($pSQL);
			$parent = mysqli_fetch_assoc($parentresult);
			$cate = $parent['category'].' - '.$child['category'];

		?>
			<tr>
				<td>
					<a href="products.php?edit=<?=$product['id']?>" class="btn btn-xs btn-default"><i class="fa fa-pencil"></i></a>
					<a href="products.php?delete=<?=$product['id']?>" class="btn btn-xs btn-default"><i class="fa fa-times"></i></a>
				</td>
				<td><?=$product['title']?></td>
				<td><?=money($product['price'])?></td>
				<td><?=$cate?></td>
				<td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1': '0');?>&id=<?=$product['id'];?>" ><i class="btn btn-xs btn-default fa fa-<?=(($product['featured'] == 1)?'minus':'plus');?>"></i></a>
					<?=(($product['featured']==1)?'Featured':'') ?></td>
				<td>0</td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>
</div>


	
<?php 
	}
	include 'includes/footer.php';
?>
<script>
	$('document').ready(function(){
		get_child_options('<?=$category;?>');
	});

	setTimeout(function(){
		$('.flash').fadeOut('slow');
		$('.flash').remove();
	},5000);

</script>


