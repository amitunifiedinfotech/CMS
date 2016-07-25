<?php echo $header; ?>
<div class="container main-container headerOffset">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if (($success_add_edit)) { ?>
    <div class="col-lg-12">
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success_add_edit; ?> 
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
    </div>
    </div>
  <?php } ?>
  <div class="row innerPage">
    <div id="content">
      <h1 class="title-big text-center section-title-style2"><span><?php echo $heading_title; ?></span></h1>
      
      <?php if ($products) { ?>
        
        <div class="w100 productFilter clearfix">
        <p class="pull-left">
            <a class="btn btn-primary" href="<?php echo $add_product; ?>"><i class="fa fa-plus-square"></i> Add Product</a>  
            <a class="btn btn-primary" href="<?php echo $add_mass_upload_product; ?>"><i class="fa fa-cloud-upload"></i> Upload Mass File</a>
        </p>
        <div class="pull-right">
        <div class="change-order pull-right">        
        <div class="row">
          <label class="control-label col-sm-2" for="input-sort"><?php echo $text_sort; ?></label>
            <div class="col-sm-5">
              <select id="input-sort" class="form-control" onchange="location = this.value;">
                <?php foreach ($sorts as $sorts) { ?>
                <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
        
          <label class="control-label col-sm-2" for="input-limit"><?php echo $text_limit; ?></label>
          	<div class="col-sm-3">
              <select id="input-limit" class="form-control" onchange="location = this.value;">
                <?php foreach ($limits as $limits) { ?>
                <?php if ($limits['value'] == $limit) { ?>
                <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
        </div>
        </div>
        
        <div class="change-view pull-right">
          <a id="grid-view" data-toggle="tooltip" title="<?php echo $button_grid; ?>" class="grid-view"> <i class="fa fa-th-large"></i> </a> 
          <a id="list-view" data-toggle="tooltip" title="<?php echo $button_list; ?>" class="list-view "><i class="fa fa-th-list"></i></a>
      </div>
      </div>
      
      </div>
      
      
      <div class="row">
        <?php	  foreach ($products as $product) { ?>
        
        <div class="product-layout product-list col-xs-12">
        <div class="item">
          <div class="product">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div class="description">
              <div class="caption">
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name'].'# ';echo ($product['issue_number']==-1)?'NA':$product['issue_number']; ?></a></h4>
                <div class="grid-description"><p><?php echo $product['short_description']; ?></p></div>
               
                <?php if ($product['price']) { ?>
                <div>
                  <span class="size"><?php echo $product['grade_value']; ?></span>
                </div>
                
                <div>
                  <span class="size"><?php echo $product['posted_date'];?></span>
                </div>
                
                </div> 
               </div>
                <div class="price">
                  <p>
		     <?php if (!$product['special']) { ?>
			<span>$<?php echo number_format($product['price'],2);?></span>
		    <?php } else { ?>
			<span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($product['price'],2);?></span>
			<span>$<?php echo number_format($product['special'],2);?></span>
		    <?php } ?>
		    <span><?php //echo '$'.number_format($product['price'],2); ?></span>
		  </p>
                </div>
                <?php } ?>
                
              
              <div class="action-control">
                <button type="button" data-toggle="tooltip" title="Edit Product" onclick="edit('<?php echo base64_encode($product['product_id']); ?>');" class="btn btn-primary">
                  <i class="fa fa-pencil"></i>
                </button>
                <button type="button" data-toggle="tooltip" title="Delete Product" onclick="delete_product('<?php echo $product['product_id']; ?>');" class="btn btn-danger">
                  <i class="fa fa-trash-o"></i>
                </button>
              </div>
            
          </div>
          </div>
        </div>
        
        <?php } ?>
      </div>
      
      
      
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><div class="pagination"><?php echo $results; ?></div></div>
      </div>
      
      <?php } ?>
      <?php if (!$products) { ?>
      
      <div class="w100 productFilter clearfix">
        <p class="pull-left">
            <a class="btn btn-primary" href="<?php echo $add_product; ?>"><i class="fa fa-plus-square"></i> Add Product</a>  
            <a class="btn btn-primary" href="<?php echo $add_mass_upload_product; ?>"><i class="fa fa-cloud-upload"></i> Upload Mass File</a>
        </p>
        <p class="buttons pull-right">
            <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
          </p>
      </div>
      <h3 class="text-center text-danger"><i class="fa fa-exclamation-triangle animated infinite tada"></i><?php echo $text_empty; ?></h3>
      
      <?php } ?>
      </div>
  </div>
</div>
<style>
#input-sort{
	display:block !important;
}
#input-limit{
	display:block !important;
}
.minict_wrapper{
	display:none;
}
</style>
<script>
  function edit(param){
    window.location = '<?php echo SITE_URL;?>index.php?route=seller_product/product_list/addEdit&product_id='+param;
  }
  
  function delete_product(param){
    if (confirm("Are you sure??")) {
      window.location = '<?php echo SITE_URL;?>index.php?route=seller_product/product_list/delete_product&product_id='+param;
    }
    //return false;
    
  }
  
</script>

<?php echo $footer; ?>