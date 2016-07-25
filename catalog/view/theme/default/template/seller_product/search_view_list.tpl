  <!--/.productFilter-->
<div class="row  categoryProduct xsResponse clearfix">
	 
	 <?php
	 if($record_message!="")
	 {
	 ?>
	 <h3 class="text-center text-danger"><i class="fa fa-exclamation-triangle animated infinite tada"></i><?php echo $record_message; ?></h3>
	 <?php
	 }
	 ?>
	 
     <?php
	
  if(!empty($all_pre_order_records)){
	
	
	
   echo '<div class="categorySecPro"><div class="loading-div-preorder section-loader" style="display:none;"><img src="image/ajax-loader.gif" ></div>'; 
    echo '<h1 class="section-titleTxt"><span>Pre Order </span></h1>';
    foreach($all_pre_order_records as $each_product){
 ?>
	 
    <div class="item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value;?>">
      <div class="product">
      	<?php  if($each_product['now_allow']==0) {?>
      	    <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
      	<?php } ?> 

      	    <div class="image"><!--imageHover-->
          		<div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#nonfeature_comic<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#nonfeature_comic<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
          		<a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
      	    </div>

      	    <div class="description_180">
      		    <h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']);?> </a></h4>
          		<div class="grid-description">
          		    <p><?php echo $each_product['short_description'];?> </p>
          		</div>
          		<span class="size"><?php echo $each_product['certification_number'];?></span><br>
          		<span class="size"><?php echo $each_product['publisher'];?></span>
				<?php if($each_product['variant']!="") { ?><br/><span class="size">Variant : <?php echo $each_product['variant'];?></span><?php } ?>
      	    </div>
      	    <div class="price">
          		<?php if (!$each_product['special']) { ?>
          		    <span>$<?php echo number_format($each_product['price'],2);?></span>
          		<?php } else { ?>
          		    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
          		    <span>$<?php echo number_format($each_product['special'],2);?></span>
          		<?php } ?>
      	    </div>
      	    <?php  if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?>
      	    <div class="action-control">
			   <?php
			   if($each_product['quantity'] > 0)
			   {
			   ?>
			   <a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
			   <?php
			   }
			   else
			   {
					?>
					<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
					<?php
			   }
			   ?>
			</div>
			
      	    <?php } else if($each_product['coming_value']==1){?>
      		  <div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon...</span> </a></div>
      	    <?php } ?> 
      	</div>
    </div>
      
  <?php
      }
  ?>
  
    <div style="clear: both;"></div>
    <div class="w100 categoryFooter">
      <?php echo paginate_function($item_per_page, $page_number_pre, $get_total_rows_pre, $total_pages_pre,0);?>
        
        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        <p>
            <!--Showing 1–<?php echo $item_per_page;?> of <?php echo $get_total_rows;//$item_per_page * $total_pages;?> results-->
            <?php
            $text = 'Showing %d to %d of %d (%d Pages)';
            echo sprintf($text, ($get_total_rows_pre) ? (($page_number_pre - 1) * $item_per_page) + 1 : 0, ((($page_number_pre - 1) * $item_per_page) > ($get_total_rows_pre - $item_per_page)) ? $get_total_rows_pre : ((($page_number_pre - 1) * $item_per_page) + $item_per_page), $get_total_rows_pre, ceil($get_total_rows_pre / $item_per_page));
            ?>
        </p>
      
        </div>
    </div>
  <?php
  echo '</div>';
    }
   
  ?>  
   
   <!-- ===================================== For New release ===================================== -->
    

  <?php 
  if(!empty($all_new_release_records)){
  echo '<div class="categorySecPro"><div class="loading-div-newlease section-loader" style="display:none;"><img src="image/ajax-loader.gif" ></div>';
    echo '<h1 class="section-titleTxt"><span>New release </span></h1>';
    foreach($all_new_release_records as $each_product){
 ?>
		 
    <div class="item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value;?>">
      <div class="product">
        <?php  if($each_product['now_allow']==0) {?>
            <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
        <?php } ?> 

            <div class="image"><!--imageHover-->
              <div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#nonfeature_comic<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#nonfeature_comic<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
              <a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
            </div>

            <div class="description_180">
              <h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']);?> </a></h4>
              <div class="grid-description">
                  <p><?php echo $each_product['short_description'];?> </p>
              </div>
              <span class="size"><?php echo $each_product['certification_number'];?></span><br>
              <span class="size"><?php echo $each_product['publisher'];?></span>
			  <?php if($each_product['variant']!="") { ?><br/><span class="size">Variant : <?php echo $each_product['variant'];?></span><?php } ?>
            </div>
            <div class="price">
              <?php if (!$each_product['special']) { ?>
                  <span>$<?php echo number_format($each_product['price'],2);?></span>
              <?php } else { ?>
                  <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                  <span>$<?php echo number_format($each_product['special'],2);?></span>
              <?php } ?>
            </div>
            <?php  if($each_product['now_allow']==0 && $each_product['coming_value']==0) {
			   ?>
            <div class="action-control">
			  
			   <?php
			   if($each_product['quantity'] > 0)
			   {
			   ?>
			   <a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
			   <?php
			   }
			   else
			   {
					?>
					<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
					<?php
			   }
			   ?>
			   
			   
			</div>
            <?php } else if($each_product['coming_value']==1){?>
            <div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon...</span> </a></div>
            <?php } ?> 
        </div>
    </div>
      
  <?php
      }
  ?>
    <div style="clear: both;"></div>
    <div class="w100 categoryFooter">
      <?php echo paginate_function($item_per_page, $page_number_new, $get_total_rows_new, $total_pages_new,1);?>
        
        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        <p>
            <?php
            $text = 'Showing %d to %d of %d (%d Pages)';
            echo sprintf($text, ($get_total_rows_new) ? (($page_number_new - 1) * $item_per_page) + 1 : 0, ((($page_number_new - 1) * $item_per_page) > ($get_total_rows_new - $item_per_page)) ? $get_total_rows_new : ((($page_number_new - 1) * $item_per_page) + $item_per_page), $get_total_rows_new, ceil($get_total_rows_new / $item_per_page));
            ?>
        </p>
      
        </div>
    </div>
  <?php
  echo '</div>';
    }
  ?>

<!-- ===================================== For New release ===================================== -->

<!-- ===================================== For Back Issue ===================================== -->
 
  <?php 
  if(!empty($back_issue_release_records)){
  echo '<div class="categorySecPro"><div class="loading-div-backissue section-loader" style="display:none;"><img src="image/ajax-loader.gif" ></div>';
    echo '<h1 class="section-titleTxt"><span>Back Issue </span></h1>';
    foreach($back_issue_release_records as $each_product){
 ?>
 
    <div class="item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value;?>">
      <div class="product">
        <?php  if($each_product['now_allow']==0) {?>
            <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
        <?php } ?> 

            <div class="image"><!--imageHover-->
              <div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#nonfeature_comic<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#nonfeature_comic<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
              <a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
            </div>

            <div class="description_180">
              <h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']);?> </a></h4>
              <div class="grid-description">
                  <p><?php echo $each_product['short_description'];?> </p>
              </div>
              <span class="size"><?php echo $each_product['certification_number'];?></span><br>
              <span class="size"><?php echo $each_product['publisher'];?></span>
		      <?php if($each_product['variant']!="") { ?><br/><span class="size">Variant : <?php echo $each_product['variant'];?></span><?php } ?>
            </div>
            <div class="price">
              <?php if (!$each_product['special']) { ?>
                  <span>$<?php echo number_format($each_product['price'],2);?></span>
              <?php } else { ?>
                  <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                  <span>$<?php echo number_format($each_product['special'],2);?></span>
              <?php } ?>
            </div>
            <?php  if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?>
            <div class="action-control">
			  <?php
			   if($each_product['quantity'] > 0)
			   {
			   ?>
			   <a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
			   <?php
			   }
			   else
			   {
					?>
					<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
					<?php
			   }
			   ?>
			</div>
            <?php } else if($each_product['coming_value']==1){?>
            <div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon...</span> </a></div>
            <?php } ?> 
        </div>
    </div>
      
  <?php
      }
  ?>
    <div style="clear: both;"></div>
    <div class="w100 categoryFooter">
      <?php echo paginate_function($item_per_page, $page_number_back, $get_total_rows_back, $total_pages_back,2);?>
        
        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        <p>
            <?php
            $text = 'Showing %d to %d of %d (%d Pages)';
            echo sprintf($text, ($get_total_rows_back) ? (($page_number_back - 1) * $item_per_page) + 1 : 0, ((($page_number_back - 1) * $item_per_page) > ($get_total_rows_back - $item_per_page)) ? $get_total_rows_back : ((($page_number_back - 1) * $item_per_page) + $item_per_page), $get_total_rows_back, ceil($get_total_rows_back / $item_per_page));
            ?>
        </p>
      
        </div>
    </div>
  <?php
  echo '</div>';
    }
  ?>
	
	
<!-- ===================================== For Back Issue ===================================== -->

<!-- ===================================== For Market Place ===================================== -->
 
  <?php
 if(!empty($market_release_records)){
  echo '<div class="categorySecPro"><div class="loading-div-marketstreet section-loader" style="display:none;"><img src="image/ajax-loader.gif" ></div>';
    echo '<h1 class="section-titleTxt"><span>Market Street </span></h1>';
    foreach($market_release_records as $each_product){
 ?>

    <div class="item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value;?>">
      <div class="product">
        <?php  if($each_product['now_allow']==0) {?>
            <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
        <?php } ?> 

            <div class="image"><!--imageHover-->
              <div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#nonfeature_comic<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#nonfeature_comic<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
              <a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
            </div>

            <div class="description_180">
              <h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']);?> </a></h4>
              <div class="grid-description">
                  <p><?php echo $each_product['short_description'];?> </p>
              </div>
              <span class="size"><?php echo $each_product['certification_number'];?></span><br>
              <span class="size"><?php echo $each_product['publisher'];?></span>
			  
			  <?php if($each_product['variant']!="") { ?><br/><span class="size">Variant : <?php echo $each_product['variant'];?></span><?php } ?>
            </div>
            <div class="price">
              <?php if (!$each_product['special']) { ?>
                  <span>$<?php echo number_format($each_product['price'],2);?></span>
              <?php } else { ?>
                  <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                  <span>$<?php echo number_format($each_product['special'],2);?></span>
              <?php } ?>
            </div>
            <?php  if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?>
            <div class="action-control">
			   <?php
			   if($each_product['quantity'] > 0)
			   {
			   ?>
			   <a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
			   <?php
			   }
			   else
			   {
					?>
					<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
					<?php
			   }
			   ?>
			</div>
            <?php } else if($each_product['coming_value']==1){?>
            <div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon...</span> </a></div>
            <?php } ?> 
        </div>
    </div>
      
  <?php
      }
  ?>
    <div style="clear: both;"></div>
    <div class="w100 categoryFooter">
      <?php echo paginate_function($item_per_page, $page_number_market, $get_total_rows_market, $total_pages_market,31);?>
        
        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        <p>
            <?php
            $text = 'Showing %d to %d of %d (%d Pages)';
            echo sprintf($text, ($get_total_rows_market) ? (($page_number_market - 1) * $item_per_page) + 1 : 0, ((($page_number_market - 1) * $item_per_page) > ($get_total_rows_market - $item_per_page)) ? $get_total_rows_market : ((($page_number_market - 1) * $item_per_page) + $item_per_page), $get_total_rows_market, ceil($get_total_rows_market / $item_per_page));
            ?>
        </p>
      
        </div>
    </div>
  <?php
    echo '</div>';
    }
    
  ?>
 
<!-- ===================================== For Market Place ===================================== -->

<!-- ===================================== For product_label1 (TPB/GN) ===================================== -->

  <?php 
  if(!empty($product_label1_release_records)){
   echo '<div class="categorySecPro"><div class="loading-div-page4 section-loader" style="display:none;"><img src="image/ajax-loader.gif" ></div>';
    echo '<h1 class="section-titleTxt"><span>'.$comic_label1.'</span></h1>';
    foreach($product_label1_release_records as $each_product){
 ?>

    <div class="item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value;?>">
      <div class="product">
        <?php  if($each_product['now_allow']==0) {?>
            <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
        <?php } ?> 

            <div class="image"><!--imageHover-->
              <div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#nonfeature_comic<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#nonfeature_comic<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
              <a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
            </div>

            <div class="description_180">
              <h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']);?> </a></h4>
              <div class="grid-description">
                  <p><?php echo $each_product['short_description'];?> </p>
              </div>
              <span class="size"><?php echo $each_product['certification_number'];?></span><br>
			  
              <span class="size"><?php echo $each_product['publisher'];?></span>
			  
			  <?php if($each_product['variant']!="") { ?><br/><span class="size">Variant : <?php echo $each_product['variant'];?></span><?php } ?>
			  
            </div>
            <div class="price">
              <?php if (!$each_product['special']) { ?>
                  <span>$<?php echo number_format($each_product['price'],2);?></span>
              <?php } else { ?>
                  <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                  <span>$<?php echo number_format($each_product['special'],2);?></span>
              <?php } ?>
            </div>
            <?php  if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?>
            <div class="action-control">
			   
			  <?php
			   if($each_product['quantity'] > 0)
			   {
			   ?>
			   <a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
			   <?php
			   }
			   else
			   {
					?>
					<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
					<?php
			   }
			   ?>
			
			</div>
            <?php } else if($each_product['coming_value']==1){?>
            <div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon...</span> </a></div>
            <?php } ?> 
        </div>
    </div>
      
  <?php
      }
  ?>
    <div style="clear: both;"></div>
    <div class="w100 categoryFooter">
      <?php echo paginate_function($item_per_page, $page_number_label1, $get_total_rows_label1, $total_pages_label1,4);?>
        
        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        <p>
            <?php
            $text = 'Showing %d to %d of %d (%d Pages)';
            echo sprintf($text, ($get_total_rows_label1) ? (($page_number_label1 - 1) * $item_per_page) + 1 : 0, ((($page_number_label1 - 1) * $item_per_page) > ($get_total_rows_label1 - $item_per_page)) ? $get_total_rows_label1 : ((($page_number_label1 - 1) * $item_per_page) + $item_per_page), $get_total_rows_label1, ceil($get_total_rows_label1 / $item_per_page));
            ?>
        </p>
      </div>
    </div>
  <?php
  echo '</div>';
    }
  ?>

<!-- ===================================== For product_label1 (TPB/GN) ===================================== -->

<!-- ===================================== For product_label2 (Supplies) ===================================== -->
 
  <?php 
  if(!empty($product_label2_release_records)){
  echo '<div class="categorySecPro"><div class="loading-div-page5 section-loader" style="display:none;"><img src="image/ajax-loader.gif" ></div>';
    echo '<h1 class="section-titleTxt"><span>'.$comic_label2.'</span></h1>';
    foreach($product_label2_release_records as $each_product){
 ?>

    <div class="item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value;?>">
      <div class="product">
        <?php  if($each_product['now_allow']==0) {?>
            <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
        <?php } ?> 

            <div class="image"><!--imageHover-->
              <div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#nonfeature_comic<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#nonfeature_comic<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
              <a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
            </div>

            <div class="description_180">
              <h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']);?> </a></h4>
              <div class="grid-description">
                  <p><?php echo $each_product['short_description'];?> </p>
              </div>
              <span class="size"><?php echo $each_product['certification_number'];?></span><br>
              <span class="size"><?php echo $each_product['publisher'];?></span>
			  <?php if($each_product['variant']!="") { ?><br/><span class="size">Variant : <?php echo $each_product['variant'];?></span><?php } ?>
            </div>
            <div class="price">
              <?php if (!$each_product['special']) { ?>
                  <span>$<?php echo number_format($each_product['price'],2);?></span>
              <?php } else { ?>
                  <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                  <span>$<?php echo number_format($each_product['special'],2);?></span>
              <?php } ?>
            </div>
            <?php  if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?>
            <div class="action-control">
			   <?php
			   if($each_product['quantity'] > 0)
			   {
			   ?>
			   <a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
			   <?php
			   }
			   else
			   {
					?>
					<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
					<?php
			   }
			   ?>
			</div>
            <?php } else if($each_product['coming_value']==1){?>
            <div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon...</span> </a></div>
            <?php } ?> 
        </div>
    </div>
      
  <?php
      }
  ?>
    <div style="clear: both;"></div>
    <div class="w100 categoryFooter">
      <?php echo paginate_function($item_per_page, $page_number_label2, $get_total_rows_label2, $total_pages_label2,5);?>
        
        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        <p>
            <?php
            $text = 'Showing %d to %d of %d (%d Pages)';
            echo sprintf($text, ($get_total_rows_label2) ? (($page_number_label2 - 1) * $item_per_page) + 1 : 0, ((($page_number_label2 - 1) * $item_per_page) > ($get_total_rows_label2 - $item_per_page)) ? $get_total_rows_label2 : ((($page_number_label2 - 1) * $item_per_page) + $item_per_page), $get_total_rows_label2, ceil($get_total_rows_label2 / $item_per_page));
            ?>
        </p>
      
        </div>
    </div>
  <?php
  echo '</div>';
    }
  ?>
 
<!-- ===================================== For product_label2 (Supplies) ===================================== -->

<!-- ===================================== For product_label3 (Other) ===================================== -->
 
  <?php 
  if(!empty($product_label3_release_records)){
  echo '<div class="categorySecPro"><div class="loading-div-page6 section-loader" style="display:none;"><img src="image/ajax-loader.gif" ></div>';
    echo '<h1 class="section-titleTxt"><span>'.$comic_label3.'</span></h1>';
    foreach($product_label3_release_records as $each_product){
 ?>

    <div class="item itemauto col-sm-4 col-lg-4 col-md-4 col-xs-6 <?php echo $class_value;?>">
      <div class="product">
        <?php  if($each_product['now_allow']==0) {?>
            <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onclick="wishlist.add('<?php echo $each_product['product_id'];?>');"> <i class="glyphicon glyphicon-heart"></i></a>
        <?php } ?> 

            <div class="image"><!--imageHover-->
              <div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#nonfeature_comic<?php echo $each_product['product_id'];?>" data-toggle="modal" onclick='clicked_elm("#nonfeature_comic<?php echo $each_product['product_id'];?>");'> Quick View </a></div>
              <a href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['thumb'];?>" alt="img" class="img-responsive"></a>
            </div>

            <div class="description_180">
              <h4><a href="<?php echo $each_product['href'];?>" target="_blank"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']);?> </a></h4>
              <div class="grid-description">
                  <p><?php echo $each_product['short_description'];?> </p>
              </div>
              <span class="size"><?php echo $each_product['certification_number'];?></span><br>
              <span class="size"><?php echo $each_product['publisher'];?></span>
			  <?php if($each_product['variant']!="") { ?><br/><span class="size">Variant : <?php echo $each_product['variant'];?></span><?php } ?>
            </div>
            <div class="price">
              <?php if (!$each_product['special']) { ?>
                  <span>$<?php echo number_format($each_product['price'],2);?></span>
              <?php } else { ?>
                  <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                  <span>$<?php echo number_format($each_product['special'],2);?></span>
              <?php } ?>
            </div>
            <?php  if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?>
            <div class="action-control">
			   <?php
			   if($each_product['quantity'] > 0)
			   {
			   ?>
			   <a class="btn btn-primary" onclick="cart.add('<?php echo $each_product['product_id'];?>');"> <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
			   <?php
			   }
			   else
			   {
					?>
					<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
					<?php
			   }
			   ?>
			</div>
            <?php } else if($each_product['coming_value']==1){?>
            <div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon...</span> </a></div>
            <?php } ?> 
        </div>
    </div>
      
  <?php
      }
  ?>
    <div style="clear: both;"></div>
    <div class="w100 categoryFooter">
      <?php echo paginate_function($item_per_page, $page_number_label3, $get_total_rows_label3, $total_pages_label3,6);?>
        
        <div class="pull-right pull-right col-sm-4 col-xs-12 no-padding text-right text-left-xs">
        <p>
            <?php
            $text = 'Showing %d to %d of %d (%d Pages)';
            echo sprintf($text, ($get_total_rows_label3) ? (($page_number_label3- 1) * $item_per_page) + 1 : 0, ((($page_number_label3 - 1) * $item_per_page) > ($get_total_rows_label3 - $item_per_page)) ? $get_total_rows_label3 : ((($page_number_label3 - 1) * $item_per_page) + $item_per_page), $get_total_rows_label3, ceil($get_total_rows_label3 / $item_per_page));
            ?>
        </p>
      
        </div>
    </div>
  <?php
  echo '</div>';
    }
  ?>

<!-- ===================================== For product_label3 (Other) ===================================== -->
 
   
  <!--/.categoryFooter-->
</div>


<div class="gap"></div>


<?php
function paginate_function($item_per_page, $current_page, $total_records, $total_pages,$pagination_number)
{
  //echo $total_pages.'current_page='.$current_page;exit;
  if($pagination_number==0){
    $pagination_number = '';
  }

  $pagination = '';
  if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number
      $pagination .= '<ul class="pagination pagination-'.$pagination_number.'">';
      
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

<!-- ****************************************** For Pre Order Model Box ****************************************** --> 
	<?php
	 if(!empty($all_pre_order_records)){
	  foreach($all_pre_order_records as $each_product){            
	?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="nonfeature_comic<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
            <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
          
              <!-- product Image -->
              <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
              <!--/.main-image-->
              
              <div class="modal-product-thumb">
                  <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
              </div>
            </div>
            <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']); if($each_product['variant']) echo ' ('.$each_product['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <div class="product-price">
            		    <?php if (!$each_product['special']) { ?>
            		    <span>$<?php echo number_format($each_product['price'],2);?></span>
            		    <?php } else { ?>
            		    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
            		    <span>$<?php echo number_format($each_product['special'],2);?></span>
            		    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
            		<?php } ?>
            		  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?> 
                	   <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
                		<input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />

                  <div class="cart-actions">
                    <div class="addto">
                      <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    
                      <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                    </div>
                  </div>
                    <!--/.cart-actions-->
               <?php } else if($each_product['coming_value']==1){?>
               <div class="btnboxbase">
              		<div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon..</span> </a></div>
              		<div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
                </div>
              <?php } ?>    
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
<!-- ****************************************** For Pre Order Model Box ****************************************** -->

<!-- ****************************************** For New Release Model Box ****************************************** --> 
  <?php
   if(!empty($all_new_release_records)){
    foreach($all_new_release_records as $each_product){            
  ?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="nonfeature_comic<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
            <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
          
              <!-- product Image -->
              <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
              <!--/.main-image-->
              
              <div class="modal-product-thumb">
                  <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
              </div>
            </div>
            <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']); if($each_product['variant']) echo ' ('.$each_product['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <div class="product-price">
                    <?php if (!$each_product['special']) { ?>
                    <span>$<?php echo number_format($each_product['price'],2);?></span>
                    <?php } else { ?>
                    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                    <span>$<?php echo number_format($each_product['special'],2);?></span>
                    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
                <?php } ?>
                  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?> 
                     <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
                    <input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />

                  <div class="cart-actions">
                    <div class="addto">
                      <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    
                      <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                    </div>
                  </div>
                    <!--/.cart-actions-->
               <?php } else if($each_product['coming_value']==1){?>
               <div class="btnboxbase">
                  <div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon..</span> </a></div>
                  <div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
                </div>
              <?php } ?>    
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
<!-- ****************************************** For New Release Model Box ****************************************** -->


<!-- ****************************************** For Back Issue Model Box ****************************************** --> 
  <?php
   if(!empty($back_issue_release_records)){
    foreach($back_issue_release_records as $each_product){            
  ?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="nonfeature_comic<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
            <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
          
              <!-- product Image -->
              <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
              <!--/.main-image-->
              
              <div class="modal-product-thumb">
                  <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
              </div>
            </div>
            <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']); if($each_product['variant']) echo ' ('.$each_product['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <div class="product-price">
                    <?php if (!$each_product['special']) { ?>
                    <span>$<?php echo number_format($each_product['price'],2);?></span>
                    <?php } else { ?>
                    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                    <span>$<?php echo number_format($each_product['special'],2);?></span>
                    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
                <?php } ?>
                  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?> 
                     <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
                    <input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />

                  <div class="cart-actions">
                    <div class="addto">
                      <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    
                      <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                    </div>
                  </div>
                    <!--/.cart-actions-->
               <?php } else if($each_product['coming_value']==1){?>
               <div class="btnboxbase">
                  <div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon..</span> </a></div>
                  <div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
                </div>
              <?php } ?>    
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
<!-- ****************************************** For Back Issue Model Box ****************************************** -->

<!-- ****************************************** For Market Place Model Box ****************************************** --> 
  <?php
   if(!empty($market_release_records)){
    foreach($market_release_records as $each_product){            
  ?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="nonfeature_comic<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
            <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
          
              <!-- product Image -->
              <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
              <!--/.main-image-->
              
              <div class="modal-product-thumb">
                  <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
              </div>
            </div>
            <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']); if($each_product['variant']) echo ' ('.$each_product['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <div class="product-price">
                    <?php if (!$each_product['special']) { ?>
                    <span>$<?php echo number_format($each_product['price'],2);?></span>
                    <?php } else { ?>
                    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                    <span>$<?php echo number_format($each_product['special'],2);?></span>
                    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
                <?php } ?>
                  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?> 
                     <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
                    <input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />

                  <div class="cart-actions">
                    <div class="addto">
                      <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    
                      <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                    </div>
                  </div>
                    <!--/.cart-actions-->
               <?php } else if($each_product['coming_value']==1){?>
               <div class="btnboxbase">
                  <div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon..</span> </a></div>
                  <div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
                </div>
              <?php } ?>    
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
<!-- ****************************************** For Market Place Model Box ****************************************** -->



<!-- ****************************************** For product_label1 (TPB/GN) Model Box ****************************************** --> 
  <?php
   if(!empty($product_label1_release_records)){
    foreach($product_label1_release_records as $each_product){            
  ?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="nonfeature_comic<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
            <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
          
              <!-- product Image -->
              <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
              <!--/.main-image-->
              
              <div class="modal-product-thumb">
                  <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
              </div>
            </div>
            <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']); if($each_product['variant']) echo ' ('.$each_product['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <div class="product-price">
                    <?php if (!$each_product['special']) { ?>
                    <span>$<?php echo number_format($each_product['price'],2);?></span>
                    <?php } else { ?>
                    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                    <span>$<?php echo number_format($each_product['special'],2);?></span>
                    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
                <?php } ?>
                  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?> 
                     <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
                    <input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />

                  <div class="cart-actions">
                    <div class="addto">
                      <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    
                      <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                    </div>
                  </div>
                    <!--/.cart-actions-->
               <?php } else if($each_product['coming_value']==1){?>
               <div class="btnboxbase">
                  <div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon..</span> </a></div>
                  <div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
                </div>
              <?php } ?>    
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
<!-- ****************************************** For product_label1 (TPB/GN) Model Box ****************************************** -->


<!-- ****************************************** For product_label2 (Supplies) Model Box ****************************************** --> 
  <?php
   if(!empty($product_label2_release_records)){
    foreach($product_label2_release_records as $each_product){            
  ?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="nonfeature_comic<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
            <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
          
              <!-- product Image -->
              <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
              <!--/.main-image-->
              
              <div class="modal-product-thumb">
                  <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
              </div>
            </div>
            <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']); if($each_product['variant']) echo ' ('.$each_product['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <div class="product-price">
                    <?php if (!$each_product['special']) { ?>
                    <span>$<?php echo number_format($each_product['price'],2);?></span>
                    <?php } else { ?>
                    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                    <span>$<?php echo number_format($each_product['special'],2);?></span>
                    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
                <?php } ?>
                  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?> 
                     <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
                    <input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />

                  <div class="cart-actions">
                    <div class="addto">
                      <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    
                      <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                    </div>
                  </div>
                    <!--/.cart-actions-->
               <?php } else if($each_product['coming_value']==1){?>
               <div class="btnboxbase">
                  <div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon..</span> </a></div>
                  <div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
                </div>
              <?php } ?>    
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
<!-- ****************************************** For product_label2 (Supplies) Model Box ****************************************** -->


<!-- ****************************************** For product_label3 (Other) Model Box ****************************************** --> 
  <?php
   if(!empty($product_label3_release_records)){
    foreach($product_label3_release_records as $each_product){            
  ?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="nonfeature_comic<?php echo $each_product['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
            <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
          
              <!-- product Image -->
              <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_product['href'];?>" target="_blank"><img src="<?php echo $each_product['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
              <!--/.main-image-->
              
              <div class="modal-product-thumb">
                  <a class="thumbLink selected"><img data-large="<?php echo $each_product['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_product['model_main_img'];?>"></a>
              </div>
            </div>
            <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_product['name']).' #'.(($each_product['issue_number']==-1)?'N/A':$each_product['issue_number']); if($each_product['variant']) echo ' ('.$each_product['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_product['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_product['publisher'];?></h3>
                  <div class="product-price">
                    <?php if (!$each_product['special']) { ?>
                    <span>$<?php echo number_format($each_product['price'],2);?></span>
                    <?php } else { ?>
                    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_product['price'],2);?></span>
                    <span>$<?php echo number_format($each_product['special'],2);?></span>
                    <span class="price-sales red_color"> <?php $discount_amnt = ($each_product['price'] - $each_product['special']); echo '('.round(($discount_amnt/$each_product['price'] ) *100); ?>%) Savings</span>
                <?php } ?>
                  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_product['description'],ENT_QUOTES, 'UTF-8');//echo $each_product['short_description'];?></p></div>
               <?php if($each_product['now_allow']==0 && $each_product['coming_value']==0) {?> 
                     <p id="show_err_quan<?php echo $each_product['product_id'];?>" class="show_quan_err"></p>
                    <input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_product['product_id'];?>" id="quantity_choose<?php echo $each_product['product_id'];?>" placeholder="Quantity" />

                  <div class="cart-actions">
                    <div class="addto">
                      <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_product['product_id'];?>',$('#quantity_choose<?php echo $each_product['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    
                      <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                    </div>
                  </div>
                    <!--/.cart-actions-->
               <?php } else if($each_product['coming_value']==1){?>
               <div class="btnboxbase">
                  <div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon..</span> </a></div>
                  <div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_product['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
                </div>
              <?php } ?>    
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
<!-- ****************************************** For product_label3 (Other) Model Box ****************************************** -->






<script>
    $(".quantity_pro").keyup(function() {      
	   this.value = this.value.match(/[0-9]*\.?[0-9]*/);
    });
    
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
</script>

<style>
  #orderby{
    display: block !important;
  }
</style>
