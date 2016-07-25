<?php echo $header; ?>
<div class="container main-container headerOffset">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  
   
  
   
   <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <h1 class="section-title-inner"><span><i class="fa fa-list-alt"></i> <?php echo $heading_title; ?> </span></h1>

            <div class="row userInfo">
                <div class="col-lg-12">
                    <h2 class="block-title-2"> Your Order List </h2>
                </div>

                <div class="col-xs-12 col-sm-12">
                    <?php if ($orders) { ?>
                      <div class="table-responsive">
                        <!--<table class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <td class="text-right"><?php echo $column_order_id; ?></td>
                              <td class="text-left"><?php echo $column_status; ?></td>
                              <td class="text-left"><?php echo $column_date_added; ?></td>
                              <td class="text-right"><?php echo $column_product; ?></td>
                              <td class="text-left"><?php echo $column_customer; ?></td>
                              <td class="text-right"><?php echo $column_total; ?></td>
                              <td></td>
                            </tr>
                          </thead>
                          <tbody>
                            <?php foreach ($orders as $order) { ?>
                            <tr>
                              <td class="text-right">#<?php echo $order['order_id']; ?></td>
                              <td class="text-left"><?php echo $order['status']; ?></td>
                              <td class="text-left"><?php echo $order['date_added']; ?></td>
                              <td class="text-right"><?php echo $order['products']; ?></td>
                              <td class="text-left"><?php echo $order['name']; ?></td>
                              <td class="text-right"><?php echo $order['total']; ?></td>
                              <td class="text-right"><a href="<?php echo $order['href']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                            </tr>
                            <?php } ?>
                          </tbody>
                        </table>-->
                        <table class="footable">
                        <thead>
                        <tr>
                            <th width="12%" data-class="expand" data-sort-initial="true" data-type="numeric"><span title="table sorted by this column on load"><?php echo $column_order_id; ?></span></th>
                            <th width="18%" data-type="numeric"><?php echo $column_product; ?></th>
                            <th width="23%" data-type="alpha"><?php echo $column_customer; ?></th>
                            <th width="17%" data-type="numeric"> <?php echo $column_date_added; ?></th>
                            <th width="15%" data-type="numeric"> <?php echo $column_total; ?></th>
                            <th width="15%" data-type="numeric"> <?php echo $column_status; ?></th>
                            <th data-hide="default"><strong></strong></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $i=1; foreach ($orders as $order) {
			    
			    if($order['status']=='Pending')
			      $class = 'danger';
			    else if($order['status']=='Complete')
			      $class = 'success';
			    else if($order['status']=='Processing')
			      $class = 'default';
			     else
			      $class = 'primary';
                         ?>
                        <tr>
                            <td><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['products']; ?></td>
                            <td><?php echo $order['name']; ?></td>
                            <td><?php echo $order['date_added']; ?></td>
                            <td><?php echo $order['total']; ?></td>
                            <td data-value="3"><span class="label label-<?php echo $class;?>"><?php echo $order['status']; ?></span></td>
                            <td><a href="<?php echo $order['href']; ?>" class="btn btn-primary"><i class="fa fa-eye"></i> view status</a></td>
                        </tr>
                        <?php $i++;} ?>
                        </tbody>
                    </table>
                      </div>
                      <div class="text-right"><?php echo $pagination; ?></div>
                      <?php } else { ?>
                      <p><?php echo $text_empty; ?></p>
                      <?php } ?>
                </div>

                <div class="col-lg-12 clearfix">
                    <ul class="pager">
                        <li class="previous pull-right"><a href="<?php echo $continue; ?>"> <i class="fa fa-home"></i> Go to Shop </a></li>
                        <li class="next pull-left"><a href="<?php echo $account; ?>"> &larr; Back to My Account</a></li>
                    </ul>
                </div>
            </div>
            <!--/row end-->

        </div>
    </div>
</div>

<?php echo $footer; ?>

<script src="catalog/view/theme/default/assets/js/footable.js" type="text/javascript"></script>
<script src="catalog/view/theme/default/assets/js/footable.sortable.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.footable').footable();
    });
</script>

<!-- styles needed by footable  -->
<link href="catalog/view/theme/default/assets/css/footable-0.1.css" rel="stylesheet" type="text/css"/>
<link href="catalog/view/theme/default/assets/css/footable.sortable-0.1.css" rel="stylesheet" type="text/css"/>

