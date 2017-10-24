<?php 
	require_once '../core/init.php';
		if(!is_logged_in()){
		//returns false runs this function
			login_error_redirect();
	}
	include 'includes/head.php';
	include 'includes/navigation.php';

	$aQuery = $db->query("SELECT * FROM products WHERE deleted = 1");

		//delete
	if(isset($_GET['delete'])){
		$deleteID = (int)$_GET['delete'];
		$IQuery = $db->query("SELECT * FROM products WHERE id = '$deleteID'");
		$result = mysqli_fetch_assoc($IQuery);
		$image_url = $_SERVER['DOCUMENT_ROOT'].$result['image'];
		unlink($image_url);
		$dQuery = $db->query("DELETE FROM products WHERE id = '$deleteID'");
		$_SESSION['success_flash'] = 'Item has been permanently deleted.';
		header('Location:archive.php');

	
	}

	//restore
	if(isset($_GET['restore'])){
		$restoreID = (int)$_GET['restore'];
		$rQuery = $db->query("UPDATE products SET deleted = 0, featured = 0 WHERE id = '$restoreID'");
		$_SESSION['success_flash'] = 'Item has been restored to Products.';
		header('Location: archive.php');
	}
 ?>
 <div class="msg"></div>
<h4 class="text-center">Archived Products</h4><br>
<table class="table table-bordered table-condensed table-striped">
	<thead>
		<th></th>
		<th>Product</th>
		<th>Price</th>
		<th>Category</th>
		<th>Sold</th>
	</thead>
	<tbody>
		<?php while($archive = mysqli_fetch_assoc($aQuery)):
			$childID = $archive['categories'];
			$cQuery = $db->query("SELECT * FROM categories WHERE id = '$childID'");
			$child = mysqli_fetch_assoc($cQuery);
			$parentID = $child['parent'];
			$pQuery = $db->query("SELECT * FROM categories WHERE id = '$parentID'");
			$parent = mysqli_fetch_assoc($pQuery);
			$category = $parent['category'].' - '.$child['category'];

		 ?>
		<tr>
			<td>
				<a href="archive.php?restore=<?=$archive['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-refresh"></span></a>
				<a href="archive.php?delete=<?=$archive['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a>
			</td>
			<td><?=$archive['title']?></td>
			<td><?=money($archive['price']);?></td>
			<td><?=$category;?></td>
			<td>0</td>
		</tr>
	<?php 	endwhile; ?>
	</tbody>
</table>







 <?php include 'includes/footer.php' ;?>