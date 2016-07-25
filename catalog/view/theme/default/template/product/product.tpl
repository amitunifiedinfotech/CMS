<?php echo $header; ?>
<div class="container">
  
  <div class="container main-container headerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
          <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
    </div>
    <div class="row transitionfx">

        <!-- left column -->

        <div class="col-lg-6 col-md-6 col-sm-6 productImageZoom">

            <div class='zoom' id='zoomContent'>
               <?php if ($thumb || $images) { ?>
                <?php if ($thumb) { ?>
                <a class="gall-item" title="product-title" href="<?php echo $thumb; ?>">
                <img class="zoomImage1 img-responsive" data-src="<?php echo $thumb; ?>" src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" />
                </a>
              <?php
                }
               }
              ?>
                <!--<a class="gall-item" title="product-title" href="<?php echo SITE_URL?>catalog/view/theme/default/images/zoom/zoom1hi.jpg">
                <img class="zoomImage1 img-responsive" data-src="<?php echo SITE_URL?>catalog/view/theme/default/images/zoom/zoom1hi.jpg" src='<?php echo SITE_URL?>catalog/view/theme/default/images/zoom/zoom1hi.jpg' alt='Daisy on the Ohoopee'/></a>-->
            </div>


            <div class="zoomThumb ">
                <a class="zoomThumbLink"><img data-large="<?php echo $thumb; ?>" src="<?php echo $thumb; ?>" alt="Saleen" title=""/></a>
                <a class="zoomThumbLink"><img data-large="<?php echo $thumb1; ?>" src="<?php echo $thumb1; ?>" alt="Saleen" title=""/></a>

            </div>

        </div>
        <!--/ left column end -->


        <!-- right column -->
        <div class="col-lg-6 col-md-6 col-sm-5">
           <?php if ($success) { ?>
            <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
            <?php } ?>

            <h1 class="product-title"> <?php echo $heading_title.'-'.$issue_number; ?></h1>

            <h3 class="product-code"><strong>Publisher :</strong> <?php echo $publisher; ?></h3>
            <h3 class="product-code"><strong>product-code</strong> <?php echo $certification_number; ?></h3>
            <h3 class="product-code"><strong>Author:</strong><?php echo $author_name; ?></h3>
            <?php if(isset($grade_value) && $grade_value!=""){?>
            <h3 class="product-code"><strong>Grade:</strong><?php echo $grade_value; ?></strong></h3>
            <?php } ?>

            <!--<div class="rating">
                <p><span><i class="fa fa-star"></i></span> <span><i class="fa fa-star"></i></span> <span><i
                        class="fa fa-star"></i></span> <span><i class="fa fa-star"></i></span> <span><i
                        class="fa fa-star-o "></i></span> <span class="ratingInfo"> <span> / </span> <a
                        data-toggle="modal" data-target="#modal-review"> Write a review</a> </span></p>
            </div>-->
            <div class="product-price">
                <span class="price-sales"> <?php echo $price; ?></span>
                <!--<span class="price-standard"><?php echo $price; ?></span>-->
            </div>

            <div class="details-description">
                <p><?php echo substr(strip_tags(html_entity_decode($description,ENT_QUOTES, 'UTF-8')),0,500); ?></p>
            </div>

            <?php if($now_allow==0){?>
            <div class="productFilter productFilterLook2">
              <div class="filterBox">
                <select id="quantity_choose" class="quantity_choose">
                 <?php
                    if($pro_quantity!=0){
                          for($i=1;$i<=$pro_quantity;$i++){
                 ?>   
                  <option value="<?php echo $i;?>"><?php echo $i;?></option>
                 <?php
                        }
                    } else {?> 
                  <option value="">No quanity available</option>
                 <?php } ?> 
                </select>
              </div>
            </div>
            <?php } ?>  

            <!-- productFilter -->

            <div class="cart-actions">
              <?php if($now_allow==0){?>
                <div class="addto">
                  <button onclick="cart.add('<?php echo $product_id;?>',$('#quantity_choose').val())" class="button btn-cart cart first" title="Add to Cart" type="button">Add to Cart</button>
                    <a class="link-wishlist wishlist"  onclick="wishlist.add('<?php echo $product_id; ?>');">Add to Wishlist</a>
                    <button onclick="" class="pullist" title="" type="button">pull List</button>
                </div>
              <?php } ?>  

                <div style="clear:both"></div>
                <?php
                if(!$out_stock){
                ?>
  
                <h3 class="incaps"><i class="fa fa fa-check-circle-o color-in"></i> In stock</h3>
                <?php
                }
                else{
                ?> 
              <h3 style="" class="incaps"><i class="fa fa-minus-circle color-out"></i> Out of stock</h3>
              <?php
                }
                ?>
              <h3 class="incaps"><i class="glyphicon glyphicon-lock"></i> Secure online ordering</h3>
            </div>
            <!--/.cart-actions-->

            <div class="clear"></div>
            <div class="report-row">
            <?php
            if($report_val==0){
            ?>
            
              <button onclick="report('<?php echo $product_id;?>')" class="btn btn-sm btn-danger" title="Report" type="button"><i class="fa fa-bug"></i> Report This Book</button>
              <a href="#" id="report_id" data-toggle="modal" data-target="#reportBox"> <span class="hidden-xs"></span></a>
            
           <?php
            }
            else{
          ?>
            <div class="text-danger"><i class="fa fa-exclamation-triangle"></i> You have already report this book.</div>
          <?php
            }
           ?>
            </div>
            <!-- Modal Login start -->
            <div class="modal fade" id="reportBox" tabindex="-1" role="dialog">
              <div class="modal-dialog ">
                <div class="modal-content">
                <form action="<?php echo SITE_URL; ?>?route=product/product/report" method="post" enctype="multipart/form-data" id="report_form" autocomplete="Off">
                <input type="hidden" name="pro_id" id="pro_id" value='<?php echo $product_id;?>' />
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
                    <h3 class="modal-title-site text-center"> Report This Book</h3>
                  </div>
                  <div class="modal-body">

                    <div class="form-group login-password">
                      <div><textarea name="report_msg" id="report_msg" class="form-control input" placeholder="Give your feedback to report this book"></textarea></div>
                    </div>
                    <div>
                      <div><input name="report_me" id="report_me" class="btn  btn-block btn-lg btn-primary" value="Report" type="submit"></div>
                    </div>
                  <!--userForm--> 
                </div>
                  <div class="modal-footer"><p class="text-center">&nbsp; </p></div>
              
                </form>
                </div>
                    <!-- /.modal-content --> 
              </div>
              <!-- /.modal-dialog --> 
            </div>
          <!-- /.Modal Login --> 

           

            <div class="product-tab w100 clearfix">

              <ul class="nav nav-tabs">
                  <li class="active"><a href="#details" data-toggle="tab">Details</a></li>
                  <li><a href="#shipping" data-toggle="tab">Shipping</a></li>
              </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                  <div class="tab-pane active" id="details"><?php echo strip_tags(html_entity_decode($description,ENT_QUOTES, 'UTF-8')); ?></div>
                  <div class="tab-pane" id="shipping">
                      <table>
                          <colgroup>
                              <col style="width:33%">
                              <col style="width:33%">
                              <col style="width:33%">
                          </colgroup>
                          <tbody>
                          <tr>
                              <td>Standard</td>
                              <td>1-5 business days</td>
                              <td>$7.95</td>
                          </tr>
                          <tr>
                              <td>Two Day</td>
                              <td>2 business days</td>
                              <td>$15</td>
                          </tr>
                          <tr>
                              <td>Next Day</td>
                              <td>1 business day</td>
                              <td>$30</td>
                          </tr>
                          </tbody>
                          <tfoot>
                          <tr>
                              <td colspan="3">* Free on orders of $50 or more</td>
                          </tr>
                          </tfoot>
                      </table>
                  </div>

                 <!-- -->

                </div>
            </div>
            <!--/.product-tab-->

            <div style="clear:both"></div>
  
            <?php if($now_allow==0){?>
            <div class="product-share clearfix">
                <p> SHARE </p>

                <div class="socialIcon">
                  
                   <!-- Facebook-->
                   <div id="fb-root"></div>
                    <script>(function(d, s, id) {
                      var js, fjs = d.getElementsByTagName(s)[0];
                      if (d.getElementById(id)) return;
                      js = d.createElement(s); js.id = id;
                      js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=1411675195718012";
                      fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                  
                  <div class="fb-share-button" data-href="<?php echo $href_product;?>" data-type="box_count"></div>
                  <!-- Facebook-->

                  <!-- Twitter-->
                  <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo $href_product;?>" data-via="<?php echo SITE_NAME;?>" data-lang="en_US" data-related="anywhereTheJavascriptAPI" data-text="<?php echo $heading_title.'-'.$issue_number; ?>" data-count="vertical">Tweet</a>

                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
                  <!-- Twitter-->
                  
                   <!-- Place this tag where you want the +1 button to render. -->
                  <div class="g-plusone" data-size="tall"  lang="en_US"></div>
                  
                  <!-- Place this tag after the last +1 button tag. -->
                  <script type="text/javascript">
                          (function() {
                          var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                          po.src = 'https://apis.google.com/js/plusone.js';
                          var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                          })();
                  </script>
                </div>
            </div>

        
            <?php } ?>    
    </div>
        <!--/ right column end -->

    </div>
    <!--/.row-->
    
    <?php
      if ($products) {
    ?>      

    <div class="row recommended">
      <h1> YOU MAY ALSO LIKE </h1>
      <div id="SimilarProductSlider">
        <?php    
            foreach ($products as $product) {
        ?>  
          <div class="item">
            <div class="product"><a class="product-image" href="<?php echo $product['href']; ?>"> <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" > </a>
              <div class="description">
                  <h4><a href="san-remo-spaghetti"><?php echo $product['name']; ?></a></h4>

                  <div class="price"><span><?php echo $product['price']; ?></span></div>
              </div>
            </div>
          </div>
    <?php
        }
    ?>
      </div>

    </div>
    
  <?php
    }
  ?>
</div>
</div>
<div style="clear:both"></div>

<script>
  $(document).ready(function(){
    $("#report_form").validate({
	 rules: {
          report_msg:{ required: true}
	 }
    });
  });
 
  
  
  function report(product_id) {
    
    $.ajax({
      url: 'index.php?route=product/product/check_login',
      type: 'post',
      data: {'product_id':product_id},
      success: function(msg) {
        if(msg > 0)
        {
          $('#report_id').trigger('click');
        }
        else
          window.location = '<?php echo SITE_URL?>index.php?route=account/login';
      }
   });
    
  }
  
</script>  
 

<style>
.quantity_choose{
  display: block !important;
}
</style> 
  


<?php echo $footer; ?>