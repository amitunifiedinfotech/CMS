<?php echo $header; ?>
<div class="container main-container headerOffset">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="row userInfo">
    <div class="col-lg-9 col-md-9 col-sm-7">
      <h1 class="section-title-inner"><span><i class="fa fa-map-marker"></i> <?php echo $text_address_book; ?></span></h1>
      <p>Please configure your default billing and delivery addresses when placing an order. You may also add additional addresses, which can be useful for sending gifts or receiving an order at your office.</p>
       <h2 class="block-title-2"> Your addresses are listed below. </h2>
        <p> Be sure to update your personal information if it has changed.</p>
        
      <?php if ($addresses) { ?>     
      
      
      <div class="row">
      <div class="w100 clearfix">
      		
            <?php foreach ($addresses as $result) { ?>
      
            <div class="col-xs-12 col-sm-6 col-md-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><strong>My Address</strong></h3>
                    </div>
                    <div class="panel-body">
                        <?php echo $result['address']; ?>
                    </div>
                    <div class="panel-footer panel-footer-address">
                    <a href="<?php echo $result['update']; ?>" class="btn btn-sm btn-success"><i class="fa fa-edit"> </i> <?php echo $button_edit; ?></a>
                    <a href="<?php echo $result['delete']; ?>" class="btn btn-sm btn-danger"><i class="fa fa-minus-circle"></i> <?php echo $button_delete; ?></a>
                    </div>
                </div>
            </div>
            
            <?php } ?>
        </div>
        </div>
                <!--/.w100-->
            
      
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <!--<div class="buttons clearfix">
        <div class="pull-left"><a href="<?php echo $add; ?>" class="btn btn-primary"><?php echo $button_new_address; ?></a></div>
        <div class="pull-right"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
      </div>-->
      <div class="clearfix">
            <ul class="pager">
                <li class="previous pull-left"><a href="<?php echo $add; ?>"><i class="fa fa-plus-circle"></i> <?php echo $button_new_address; ?> </a></li>
                <li class="next pull-right"><a href="<?php echo $back; ?>">&larr; Back to My Account</a></li>
            </ul>
        </div>
        
      </div>
    </div>
</div>
<div class="gap"></div>
<?php echo $footer; ?>