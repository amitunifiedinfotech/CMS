<?php echo $header; ?>
<div class="container main-container headerOffset">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row">

    <div id="content" class="col-sm-12">
	<h1 class="section-title-inner"><span><i class="fa fa-list-alt"></i> <?php echo $heading_title; ?> </span></h1>
	
    
    <div class="row userInfo">
    	<div class="statusContent">
    
    	<div class="col-sm-12">
                <div class="order-box">
                    <div class="order-box-header"> <?php echo $text_order_detail; ?></div>
                    <div class="order-box-content">
                        <div class="address">
                            <?php if ($invoice_no) { ?>
                            <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
                            <?php } ?>
                            <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
                            <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?>
                            <br />
                            <?php if ($payment_method) { ?>
                            <b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
                            <?php } ?>
                            <?php if ($shipping_method) { ?>
                            <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
        </div>
    
    
    
    
    
    
    
    <div class="col-sm-6">
        <div class="order-box">
        	<div class="order-box-header"><?php echo $text_payment_address; ?></div>
            <div class="order-box-content">
                <div class="address">
                    <?php echo $payment_address; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6">
        <div class="order-box">
        	<div class="order-box-header">
            <?php if ($shipping_address) { ?>
            <?php echo $text_shipping_address; ?>
            <?php } ?></div>
            <div class="order-box-content">
                <div class="address">
                    <?php if ($shipping_address) { ?>
                    <?php echo $shipping_address; ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    
    
    
    
    
    <!--<table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" style="width: 50%;"><?php echo $text_payment_address; ?></td>
            <?php if ($shipping_address) { ?>
            <td class="text-left"><?php echo $text_shipping_address; ?></td>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $payment_address; ?></td>
            <?php if ($shipping_address) { ?>
            <td class="text-left"><?php echo $shipping_address; ?></td>
            <?php } ?>
          </tr>
        </tbody>
      </table>-->
    
    
    
    
    
    
      
      
      
      
      <div class="col-sm-12 clearfix">
          <div class="order-box">
          	<div class="order-box-header">Order Items</div>
              <div class="order-box-content">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <td class="text-left"><strong><?php echo 'Image'; ?></strong></td>
                          <td class="text-left"><strong><?php echo $column_name; ?></strong></td>
                          <td class="text-right"><strong><?php echo $column_quantity; ?></strong></td>
                          <td class="text-right"><strong><?php echo $column_price; ?></strong></td>
                          <td class="text-right"><strong><?php echo $column_total; ?></strong></td>
                          <?php if ($products) { ?>
                          <td style="width: 20px;"></td>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php foreach ($products as $product) { ?>
                        <tr>
                          <td class="text-left"><img src="<?php echo $product['image']; ?>" /></td>
                         <td class="text-left"><?php echo $product['name']; ?>
                            <?php foreach ($product['option'] as $option) { ?>
                            <br />
                            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                            <?php } ?></td>
                          <td class="text-right"><?php echo $product['quantity']; ?></td>
                          <td class="text-right"><?php echo $product['price']; ?></td>
                          <td class="text-right"><?php echo $product['total']; ?></td>
                          <td class="text-right" style="white-space: nowrap;"><?php if ($product['reorder']) { ?>
                            <a href="<?php echo $product['reorder']; ?>" data-toggle="tooltip" title="<?php echo $button_reorder; ?>" class="btn btn-primary"><i class="fa fa-shopping-cart"></i></a>
                            <?php } ?>
                            <!--<a href="<?php echo $product['return']; ?>" data-toggle="tooltip" title="<?php echo $button_return; ?>" class="btn btn-danger"><i class="fa fa-reply"></i></a>--></td>
                        </tr>
                        <?php } ?>
                        <?php foreach ($vouchers as $voucher) { ?>
                        <tr>
                          <td class="text-left"><?php echo $voucher['description']; ?></td>
                          <td class="text-left"></td>
                          <td class="text-right">1</td>
                          <td class="text-right"><?php echo $voucher['amount']; ?></td>
                          <td class="text-right"><?php echo $voucher['amount']; ?></td>
                          <?php if ($products) { ?>
                          <td></td>
                          <?php } ?>
                        </tr>
                        <?php } ?>
                      </tbody>
                      <tfoot>
                        <?php foreach ($totals as $total) { ?>
                        <tr>
                          <td colspan="3"></td>
                          <td class="text-right"><b><?php echo $total['title']; ?></b></td>
                          <td class="text-right"><?php echo $total['text']; ?></td>
                          <?php if ($products) { ?>
                          <td></td>
                          <?php } ?>
                        </tr>
                        <?php } ?>
                      </tfoot>
                    </table>
                  </div>
              </div>
          </div>
      </div>
      
      <?php if ($comment) { ?>
      
      <div class="col-sm-12 clearfix">
          <div class="order-box">
      		<div class="order-box-header">Comment</div>
            <div class="order-box-content">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $text_comment; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-left"><?php echo $comment; ?></td>
                  </tr>
                </tbody>
              </table>
              </div>
              </div>
              </div>
      
      <?php } ?>
      <?php if ($histories) { ?>

      <div class="col-sm-12 clearfix">
          <div class="order-box">
          	<div class="order-box-header"><?php echo $text_history; ?></div>
              <div class="order-box-content">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <td class="text-left"><strong><?php echo $column_date_added; ?></strong></td>
                        <td class="text-left"><strong><?php echo $column_status; ?></strong></td>
                        <td class="text-left"><strong><?php echo $column_comment; ?></strong></td>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($histories as $history) { ?>
                      <tr>
                        <td class="text-left"><?php echo $history['date_added']; ?></td>
                        <td class="text-left"><?php echo $history['status']; ?></td>
                        <td class="text-left"><?php echo $history['comment']; ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
              </div>
          </div>
      </div>
      
      
      <?php } ?>
      
      <div class="col-lg-12 clearfix">
            <ul class="pager clearfix">
                <li class="previous pull-right"><a href="<?php echo $continue; ?>"> <i class="fa fa-home"></i> Go to Shop </a>
                </li>
                <li class="next pull-left"><a href="<?php echo $account; ?>"> ← Back to My Account</a></li>
            </ul>
        </div>
      
      
      	</div>
      </div>
      
      
      </div>
    </div>
</div>
<div class="gap"></div>
<?php echo $footer; ?>