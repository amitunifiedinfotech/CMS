<script src="catalog/view/theme/default/assets/js/grid.js"></script>
<script>
$(document).ready(function(){
    //searchTitle();
});
</script>
<div class="main" style="position:relative;">
<div class="loading-div loading-divNew"><img src="image/ajax-loader.gif"></div>
    <ul id="og-grid" class="og-grid">
	
    <?php
    if(!empty($all_records)){
	
	
      foreach($all_records as $each_product){
      $title  =  ucwords($each_product["name"]).'-'.$each_product["issue_number"].'<br/><br/>(Variant :'.$each_product["variant"].')';
	  
	 
     // $title = '<div class="title_div_area" onclick="redirect_me()">'.ucwords($each_product["name"]).'-'.$each_product["issue_number"].'</div>';
      
	
	$description = $each_product['description'];
	
	if(($each_product["grading_price"]!="") && ($each_product["grading_price"]!="0.00"))
	{
	  $extra_info = '<br><span class="size">'.$each_product["certification_number"].'</span><br><span class="size">'.$each_product["publisher"].'</span><br><br><span class="size sizeAmount">'.'$'.$each_product["grading_price"].'</span>';
	}
	else
	{
	  $extra_info = '<br><span class="size">'.$each_product["certification_number"].'</span><br><span class="size">'.$each_product["publisher"].'</span><br><br><span class="size sizeAmount">'.'$'.$each_product["price"].'</span>';
	}
	
	
	if($each_product['now_allow']==0){
	  
	  
	$add_to_cart = '<div class="action-control thumbnalAnchorArea"><a class="btn btn-primary" onclick="cart.add_grade('.$each_product['product_id'].');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>';
	
	if($each_product["pull_avalable"] > 0)
	{
	  
	  if(empty($each_product['variants_arr']))
      {
		  $add_to_cart.= '<a class="btn btn-primary" onclick="add_to_pull_list('.$each_product['product_id'].',1);"> <span class="add2cart"><i class="fa fa-list-ul"> </i> Pull list </span> </a>';
	  }
	  else
	  {
		$add_to_cart.= '<a class="btn btn-primary" onclick="add_to_variant();"> <span class="add2cart"><i class="fa fa-list-ul"> </i> Pull list </span> </a>';
	  }
	}
		############ VARIANT LIST(start) ##################
		$add_to_cart.= '<div id="variant"  style="display: none;">';
		$add_to_cart.= '<h6 class="lastTitle" style="color:#ffffff">Other Variants</h6>';
		$add_to_cart.= '<form action="'.HTTP_CATALOG.'index.php?route=pull_list/list/add" id="pulllist" method="post">';
		$add_to_cart.= '<input type="hidden" name="main_product_id" value="'.$each_product["product_id"].'">';
		$add_to_cart.= '<input type="hidden" name="list_type" value="1">';
		if(!empty($each_product['variants_arr']))
        {
		  $add_to_cart.= '<ul>';
		  $i=rand(1,100);
		  foreach($each_product['variants_arr'] as $variants_list)
          {
			$variant_name = ucfirst($variants_list['name']);
			$issue_number = $variants_list['issue_number'];
			$url = SITE_URL.'grading-service&name='.base64_encode($each_product["product_id"]);
			
			$add_to_cart.= '<li><input type="checkbox" name="variant_list[]" id="'.$i.'" value="'.$variants_list['product_id'].'"><span style="cursor:pointer" onclick=product_details("'.$url.'")>'.$variant_name.'-'.$issue_number.'</span>';
																																				if($variants_list['variant']!="")
			{
			  $add_to_cart.= '(Variant:'.$variants_list['variant'].')';
			}
			$add_to_cart.= '</li>';
			
		  }
		  $add_to_cart.= '<button onclick="submit_pull_list();" class="addNew4Button" title="" type="button">Add</button>';
		  $add_to_cart.= '</ul>';
		}
		
		$add_to_cart.= '</form>';
		$add_to_cart.= '</div>';
		############ VARIANT LIST(end) ##################
		$add_to_cart.= '</div>';
	
	
	}
	else{
	    $add_to_cart = '';
	   
	}
	?>

		
     <li>
	    <a href="#" data-largesrc="<?php echo $each_product['image_big'];?>" data-title="<?php echo $title;?> " data-description='<?php echo $description.$extra_info.$add_to_cart;?>'>
		<img src="<?php echo $each_product['thumb'];?>" alt="img01"/>
	    </a>
      </li>
     <?php
      }
    }
    else{
    ?>
    <li><h3 class="text-center text-danger"><i class="fa fa-exclamation-triangle animated infinite tada"></i><?php echo 'No records found.'; ?></h3></li>
    <?php
    }
    ?>  
      
      
      
    </ul>
  </div>
    <div class="w100 categoryFooter col-lg-12" style="text-align:center;">
    <?php echo paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);?>
      <div>
	  <p>
	    <?php
		$text = 'Showing %d to %d of %d (%d Pages)';
		echo sprintf($text, ($get_total_rows) ? (($page_number - 1) * $item_per_page) + 1 : 0, ((($page_number - 1) * $item_per_page) > ($get_total_rows - $item_per_page)) ? $get_total_rows : ((($page_number - 1) * $item_per_page) + $item_per_page), $get_total_rows, ceil($get_total_rows / $item_per_page));
	    ?>
	  </p>
	  
      </div>
    </div>
    <div class="gap"></div>
<?php
function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
  //echo $total_pages.'current_page='.$current_page;
  $pagination = '';
  if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
      $pagination .= '<ul class="pagination">';
      
      $right_links    = $current_page + 3; 
      $previous       = $current_page - 3; //previous link 
      $next           = $current_page + 1; //next link
      $first_link     = true; //boolean var to decide our first link
      
      if($current_page > 1){
	$previous_link = ($previous==0)?1:$previous;
	$pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
	$pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
	for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
	    if($i > 0){
		$pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
	    }
	}   
	$first_link = false; //set first link to false
      }
      
      if($first_link){ //if current active page is first link
	  $pagination .= '<li class="first active">'.$current_page.'</li>';
      }elseif($current_page == $total_pages){ //if it's the last active link
	  $pagination .= '<li class="last active">'.$current_page.'</li>';
      }else{ //regular current link
	  $pagination .= '<li class="active">'.$current_page.'</li>';
      }
	      
      for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
	  if($i<=$total_pages){
	    $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
	  }
      }
      if($current_page < $total_pages){ 
	$next_link = ($i > $total_pages)? $total_pages : $i;
	$pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
	$pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
      }
      
      $pagination .= '</ul>'; 
  }
  return $pagination; //return pagination links
}

?>

<script>
function add_to_variant()
  {
    $('#variant').slideToggle();
  }
  function submit_pull_list()
  {
    $('#pulllist').submit();
  }
  function add_to_pull_list(product_id,list_type)
  {
    window.location = '<?php echo SITE_URL?>index.php?route=pull_list/list/add_to_pull_list&product_id='+product_id+'&list_type='+list_type;
  }  
  
  function product_details(page_url) {
	window.location = page_url;
  }
  
$(document).ready(function(e) {
    Grid.init();
});
function redirect_me(){
	alert('test');
}
</script>