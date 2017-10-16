<?php 
	require_once '../core/init.php';
	include 'includes/head.php';
	include 'includes/navigation.php';
	//if we clicked the product button
	if(isset($_GET['add'])){
	$brandQuery= $db->query("SELECT * FROM brands");
	$parentQuery = $db->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");


?>
<h4 class="text-center">Add a New Product</h4><hr>

<form action="products.php?add=1" method="post", enctype="multipart/form-data">
	
	<div class="col-md-3 form-group">
		<label for="title">Title*:</label>
		<input type="text" class="form-control" <?=((isset($_POST['title']))? $_POST['title']:''); ?> >
	</div>
	<div class="col-md-3 form-group">
		<label for="brand">Brand*:</label>
		<select name="brand" id="brand" class="form-control">
			<option value=""<?=((isset($_POST['brand']) && $_POST['brand'] == '')?'selected': ''); ?>></option>
			<?php while($brand = mysqli_fetch_assoc($brandQuery)) :?>
				<option value="<?$brand['id']?>"<?=((isset($_POST['brand']) && $_POST['brand'] == $brand['id'])? 'selected':'');?>>
					<?=$brand['brand'];?>
				</option>
			<?php endwhile; ?>
		</select>
	</div>
	<div class="col-md-3 form-gropu">
		<label for="parent">Parent*:</label>
		<select name="parent" id="parent" class="form-control">
			<option value="" <?=((isset($_POST['parent']) && $_POST['parent'] == '')?'selected': '');?>></option>
				<?php while($parent = mysqli_fetch_assoc($parentQuery)) :?>
					<option value="<?=$parent['id']?>" <?=((isset($_POST['parent']) && $_POST['parent'] == $parent['id'])? 'selected':'');?>><?=$parent['category'];?></option>
			<?php endwhile; ?>
		</select>
	</div>
	<div class="form-group col-md-3">
		<label for="child">Child*:</label>
		<select class="form-control" name="child" id="child">
			<!--populated from parsers-->
		</select>
	</div>
	<div class="form-group col-md-3">
		<label for="price">Price*:</label>
		<input type="text" class="form-control" id="price" name="price" value="<?=((isset($_POST['price']))?sanitize($_POST['price']): ''); ?>">
	</div>
	<div class="form-group col-md-3">
		<label for="qsize">Qauntity &amp; Size*:</label>
		<button id="qsize" class="btn btn-default form-control" onclick=" $('#sizeModal').modal('toggle'); return false;">Quantity and Sizes</button>
	</div>
	<div class="form-group col-md-3">
		<label for="size">Sizes &amp; Quantity Preview:</label>
		<input  readonly class="form-control" type="text" name= "size" id="size" value="<?=((isset($_POST['size']))?$_POST['size']: ''); ?>">
	</div>
	<div class="form-group col-md-6">
		<label for="photo">Photo</label>
		<input type="file" class="form-control" id="photo" name="photo">
	</div>
	<div class="form-group col-md-6">
		<label for="description">Description*:</label>
		<textarea name="description" id="description" rows="6" class="form-control"><?=((isset($_POST['description']))?sanitize($_POST['description']):''); ?></textarea>
	</div>
	<div class="col-md-2  pull-right">
		<input type="submit" class="form-control btn btn-success" value ="Add Product">
	</div>


</form>



<?php
	}else{
	$sql = "SELECT * FROM Products WHERE deleted = 0";
	$presult = $db->query($sql);

	if(isset($_GET['featured'])){
		$id = (int)$_GET['id'];
		$featured = (int)$_GET['featured'];
		$featuredsql = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
		$db->query($featuredsql);
		header('Location: products.php');
	}

?>



<h4 class="text-center">Products</h4><br>
<a href="products.php?add=1" class="btn btn-success pull-right" id="add-product-button">Add Products</a>
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
			$cateSQL = "SELECT * from categories WHERE id = $childID";
			$cateResult = $db->query($cateSQL);
			$child = mysqli_fetch_assoc($cateResult);
			$parentID = $child['parent'];
			$pSQL = "SELECT * FROM categories WHERE id = '$parentID'";
			$parentresult = $db->query($pSQL);
			$parent = mysqli_fetch_assoc($parentresult);
			$category = $parent['category'].' - '.$child['category'];

		?>
			<tr>
				<td>
					<a href="products.php?edit=<?=$product['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
					<a href="products.php?delete=<?=$product['id']?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</td>
				<td><?=$product['title']?></td>
				<td><?=money($product['price'])?></td>
				<td><?=$category?></td>
				<td><a href="products.php?featured=<?=(($product['featured'] == 0)?'1': '0');?>&id=<?=$product['id'];?>" ><span class="btn btn-xs btn-default glyphicon glyphicon-<?=(($product['featured'] == 1)?'minus':'plus');?>"></span></a>
					<?=(($product['featured']==1)?'Featured':'') ?></td>
				<td>0</td>
			</tr>
		<?php endwhile; ?>
	</tbody>
</table>

	
<?php 
	}
	include 'includes/footer.php';
?>