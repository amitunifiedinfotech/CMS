 <!--/.productFilter-->
<div class="row  categoryProduct xsResponse clearfix market">
    <?php
    if(!empty($all_records)){
      foreach($all_records as $each_product){
    ?>
    <div class="item new_item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value; ?>">
	<div class="product">
	  <?php if($each_product['now_allow']==0) {?>
	    <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
	  <?php } ?>
	    <div class="image"><!--imageHover-->
		<div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview prod_quick" data-target="#marketlist<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#marketlist<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
		<a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
	    </div>
	    <div class="description">
		<h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'; echo ($each_product['issue_number']==-1)?'NA':$each_product['issue_number'];?> </a></h4>
		<span class="size bold_me"><?php echo $each_product['grade_value'];?></span>
		<span><?php echo $each_product['page_quality'];?></span>
		<span><?php echo $each_product['author_name'];?></span>
		<div class="grid-description">
		    <p><?php echo $each_product['short_description'];?> </p>
		</div>
	    </div>
	    <div class="price">
		<?php if (!$each_product['special']) { ?>
		    <span>$<?php echo number_format($each_product['price'],2);?></span>
		<?php } else { ?>
		    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
		    <span>$<?php echo number_format($each_product['special'],2);?></span>
		<?php } ?>
	    </div>
	    <?php if($each_product['now_allow']==0) {
			
			if($each_product['quantity']<=0)
			{
				?>
				<div class="action-control"><a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a></div>
				<?php
			}
			else
			{
			?>
	    <div class="action-control"><a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
	    <?php
			}
		} ?> 
	</div>
    </div>
      
    <?php
      }
    }
    else{
    ?>
    <h3 class="text-center text-danger"><i class="fa fa-exclamation-triangle animated infinite tada"></i>No records found.</h3>
    <?php } ?>
    <div style="clear: both;"></div>
  <div class="w100 categoryFooter">
    <?php echo paginate_function($item_per_page, $page_number, $get_total_rows, $total_pages);?>
      <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
	  <p>
	      <?php
	      $text = 'Showing %d to %d of %d (%d Pages)';
		echo sprintf($text, ($get_total_rows) ? (($page_number - 1) * $item_per_page) + 1 : 0, ((($page_number - 1) * $item_per_page) > ($get_total_rows - $item_per_page)) ? $get_total_rows : ((($page_number - 1) * $item_per_page) + $item_per_page), $get_total_rows, ceil($get_total_rows / $item_per_page));
	      ?>
	  </p>
	  
      </div>
    </div>
  <!--/.categoryFooter-->
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
 // echo 'aa'.$pagination; exit;
  return $pagination; //return pagination links
}

?>



    <!-- ****************************************** For marketlist ****************************************** --> 
      <?php
    if(!empty($all_records)){
      foreach($all_records as $each_product){
            
      ?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="marketlist<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
                <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
              
                  <!-- product Image -->
                  <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
                  <!--/.main-image-->
                  
                  <div class="modal-product-thumb">
                      <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
                      <a class="thumbLink"><img data-large="<?php echo $each_product['model_main_img1'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img1'];?>"> </a>
                  </div>
                </div>
                <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'; echo ($each_product['issue_number']==-1)?'NA':$each_product['issue_number'];?></h1>
                  <h3 class="product-code"><strong>Grade: <?php echo $each_product['grade_value'];?></strong></h3>
                  <h3 class="product-code"><strong>Page Quality: </strong><?php echo $each_product['page_quality'];?></h3>
                  <h3 class="product-code"><strong>Serial Number: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <h3 class="product-code"><strong>Seller: </strong><?php echo $each_product['author_name'];?></h3>
                  <div class="product-price">
		    <?php if (!$each_product['special']) { ?>
		    <span class="price-sales">$<?php echo number_format($each_product['price'],2);?></span>
		<?php } else { ?>
		    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
		    <span class="price-sales">$<?php echo number_format($each_product['special'],2);?></span>
			    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
		<?php } ?>
		  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0) {?>
	       <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
	       
			<?php
			if($each_product['posted_by'] >0)
			{
			?>
				<input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />
            <?php
			}
			?>
				
				<div class="cart-actions">
                  <div class="addto">
					
			<?php
			if($each_product['quantity']>0)
			{
				if($each_product['posted_by'] >0)
				{
				?>		
				<button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>
				<?php
				}
				else
				{
					?>
					<button class="button btn-cart cart first" onclick="cart.add('<?php echo $each_product['product_id'];?>');" title="Add to Cart" type="button">Add to Cart </button>
					<?php
				}
			}
			else
			{
				?>
				<button class="button btn-cart cart sold" title="SOLD" type="button">SOLD</button>
				<?php
			}
			?>
                        
						<a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                  </div>
                </div>
                    <!--/.cart-actions-->
               <?php }?>  
               </div>
              <!--/.modal-details-inner--> 
            </div>
                <!--/.modal-details-->
                <div class="clear"></div>
            </div>
          <!--/.modal-content--> 
        </div>
        <!--/.modal-content--> 
      </div>
      <!-- End Modal -->
            
       <?php
            }
      }
      ?>    
    <!-- ****************************************** For marketlist ****************************************** -->


<style>
#orderby{
  display: block !important;
}
.description {
  margin: 0px auto;
  height: auto !important;
  max-width: 235px;
    overflow: hidden;
}
</style>
<script>

function clicked_elm(a){
    $('.show_quan_err').html("");
    $('.quantity_pro').val("");
    //alert();
    var x=a;
    console.log(a);
    //$(a).addClass('asdas');
    
    var this_src=$(a).find('.modal-product-thumb a:first-child img').attr('data-large');
    $(a).find('.product-largeimg-link img').addClass('asdas').attr('src',this_src);
}
$(document).ready(function(){
    $(".quantity_pro").keyup(function() {      
	this.value = this.value.match(/[0-9]*\.?[0-9]*/);
    });
})

 
</script>
