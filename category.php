<?php 
require_once 'core/init.php';
//incase the get variable is not un the database
if(isset(($_GET['cat']))){
		//child id
	$cat_id = sanitize($_GET['cat']);
}else{
	$cat_id = '';
}

$catID_arr = array();
$getQuery = $db->query("SELECT id FROM categories WHERE parent != 0");
while($get_check = mysqli_fetch_assoc($getQuery)){
	$catID_arr[] = $get_check['id'];
}
if(!in_array($cat_id, $catID_arr)){
	header('Location:index.php');
}

include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/headercat.php';
include 'includes/sidebar.php';

//counts the number id in prod table
$navQuery = $db->query("SELECT COUNT(id) FROM products WHERE categories = '$cat_id' AND deleted = 0 ");
$rowArr = mysqli_fetch_row($navQuery);
	//gets the number of rows
$rows= $rowArr[0];
	//how many to display per page
$page_rows = 2;
	//for getting the last page and rounding it up
$last_page = ceil($rows/$page_rows);
	//makes sure our last is > 0
if($last_page < 1){
	$last_page = 1;
}
	//initialize pagenum
$pagenum = 1;
	//makes sure if and int
if(isset($_GET['pn'])){
	$pagenum = preg_replace('#[^0-9]#', '', $_GET['pn']);
}
if($pagenum < 1){
	$pagenum = 1;
}elseif ($pagenum > $last_page) {
	$pagenum = $last_page;
}
	//set the range of rows to query for a given page(LIMIT 0,pagerows) for ist page
$limit = 'LIMIT ' .($pagenum - 1) * $page_rows . ',' .$page_rows;

$sql ="SELECT * FROM products WHERE categories = '$cat_id' AND deleted = 0 ORDER BY id DESC $limit";
$productQ = $db->query($sql);
	//function to get boh child and parent 
$category = get_category($cat_id);
?>

<h3 class="text-center h3-responsive mb-5"><?=$category['parent'].' - '.$category['child'];?></h3>
<!--main Content -->
<section class="col-md-9 col-sm-12 col-xs-12 pull-right pb-3">

	<div class="row">
		<!--Grid column-->
		<?php while ($product = mysqli_fetch_assoc($productQ)): ?>
			<div class="col-md-4 col-sm-4 col-xs-6 mb-r">

				<!--Card-->
				<div class="card card-cascade wider">

					<!--Card image-->
					<div class="view overlay hm-white-slight" onclick="detailsModal(<?=$product['id']?>)">

						<?php $photos = explode(',', $product['image']) ?>
						<img src="<?=$photos[0]?>" class="img-fluid center-block" alt="<?=$product['title']?>">
						<a>
							<div class="mask"></div>
						</a>
					</div>
					<!--Card image-->

					<!--Card content-->
					<div class="card-body text-center mt-4">
						<!--Category & Title-->
						<h4 class="card-title h4-responsive"><strong><a href=""><?=$product['title']?></a></strong></h4>

						<!--Description-->

						<!--Card footer-->
						<div class="card-footer">
							<div class="mt-4"><p>Price: &#8358; <?=$product['price']?></p></div>
							<div class="pb-3">
								<button type="button" class="btn mb-2 btn-success" onclick="detailsModal(<?=$product['id']?>)">Details</button>
							</div>
						</div>

					</div>
					<!--Card content-->

				</div>
				<!--Card-->

			</div>
		<?php endwhile; ?>
	</div>
	<!--Grid column-->

</section>

<div class="col-md-12 col-sm-12 text-center">



	<nav>
		<ul class="pagination pg-blue">
			<?php 

			$paginationCtrls = '';
	//if there are more than one page worth of results
			if($last_page != 1){
		//if pagenum is > 1,we need to show previous button
				if($pagenum > 1){
					$previous = $pagenum - 1;
					$paginationCtrls .= '<li class = "page-item"><a class = "page-link" href="'.$_SERVER['PHP_SELF'].'?cat='.$cat_id.'&pn='.$previous.'" aria-label = "Previous"><span aria-hidden = "true">&laquo;</span><span class ="sr-only">Previous</span></a></li>';
			//generate the < 4 links that will be o the left side
					for($i = $pagenum - 4;$i < $pagenum;$i++){
						if($i > 0){{

							$paginationCtrls .= '<li class = "page-item"><a class = "page-link" href="'.$_SERVER['PHP_SELF'].'?cat='.$cat_id.'&pn='.$i.'">'.$i.'</a></li>';
						}
					}
				}
			}
		//render the target page without it being a link
			$paginationCtrls .='<li class="page-item active"><a class="page-link" href="#">'.$pagenum.'<span class="sr-only">(current)</span></a></li>';
		//generate the linkd that will appear on the right
			for ($i = $pagenum + 1; $i <= $last_page; $i++){
				$paginationCtrls .= '<li class = "page-item"><a class = "page-link" href="'.$_SERVER['PHP_SELF'].'?cat='.$cat_id.'&pn='.$i.'">'.$i.'</a></li>';
				if($i >= $pagenum + 4){
					break;
				}
			}
			if($pagenum != $last_page){
				$next = $pagenum + 1;
				$paginationCtrls .= '<li class = "page-item"><a class = "page-link" href="'.$_SERVER['PHP_SELF'].'?cat='.$cat_id.'&pn='.$next.'" aria-label = "Next"><span aria-hidden = "true">&raquo;</span><span class ="sr-only">Next</span></a></li>';
			}
		}

		echo $paginationCtrls;
		?>
	</ul>
</nav>
</div>


<?php 
if(isset($_SESSION['myparser'])){
	unset($_SESSION['myparser']);
}
include 'includes/footer.php'; ?>


