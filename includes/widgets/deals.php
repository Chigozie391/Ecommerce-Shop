	
<?php 
$transQ = $db->query("SELECT * FROM carts WHERE ordered = 1 ORDER BY id DESC LIMIT 10 ");
$results = array();
while($row = mysqli_fetch_assoc($transQ)){
	$results[] = $row;
}
		//gets the nimber of rows
$row_count = $transQ->num_rows;
$used_ids = array();

	//loop through all the ordered items
for($i = 0;$i < $row_count ;$i++){
			//gets the ith item
	$json_items = $results[$i]['items'];
			//store it as assoc array
	$items = json_decode($json_items,true);
	foreach($items as $item){
				//if that id is not already there
		if(!in_array($item['id'], $used_ids)){
			$used_ids[] = $item['id'];
		}
	}
}
?>
<div class="popular-item">
	<div data-u="loading" class="jssorl-009-spin spin popular-spin" style="position:absolute;top:0px;left:0px;width:100%;height:100%;text-align:center;">
		<img style="margin-top:-15px;position:relative;top:50%;width:38px;left:0%;height:38px;" src="images/slider/spin2.svg" />
	</div>
	<div class="side-header">
		<h5 class="text-center h5-responsive">Popular Items</h5>
	</div>

	<table class="table table-condensed table-hover deals" id='widget'>
		<tbody>
			<?php foreach($used_ids as $id): 
			$productQ = $db->query("SELECT id,title FROM products WHERE id = '$id'");
			$count = $productQ->num_rows;
			if($count < 1){
				continue;
			}
			$product = mysqli_fetch_assoc($productQ); ?>
			<tr onclick ="detailsModal('<?=$product['id']?>','deals')" >
				<td><?=$product['title']; ?></td>
				<td>View</td>
			</tr>
		<?php endforeach ?>
	</tbody>
</table>
</div>