
<?php 
$sql = "SELECT * from slides WHERE slide = 1";
$squery = $db->query($sql);
$active = true;
?>

<div id="my-carousel" class="carousel slide carousel-fade " data-ride="carousel">

<!-- Wrapper for slides -->
<div class="carousel-inner" role="listbox">
    <?php while($slide = mysqli_fetch_assoc($squery)) :?>
        <div class="item <?=($active)? 'active' : ''  ?>">
          <img src="<?= $slide['image'];?>" class = "img-index" alt = "<?=$slide['title'];?>"/>
      </div>
      <?php
      $active = false;
      endwhile; ?>
  </div>

  <a class="left carousel-control" href="#my-carousel" role="button" data-slide="prev">
    <i class="fa fa-angle-left arrow glyphicon-chevron-left" aria-hidden="true"></i>
    <span class="sr-only">Previous</span>
</a>
<a class="right carousel-control" href="#my-carousel" role="button" data-slide="next">
    <i class="fa fa-angle-right arrow glyphicon-chevron-left" aria-hidden="true"></i>
    <span class="sr-only">Next</span>
</a>
</div>

<div class="container wrapper">

