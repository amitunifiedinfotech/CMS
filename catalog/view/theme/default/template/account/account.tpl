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
        <?php if ($success) { ?>
        <div class="col-lg-12">
  			<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
            </div>
  		<?php } ?>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <h1 class="section-title-inner"><span><i class="fa fa-unlock-alt"></i> My account </span></h1>

            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <p> Your account has been created. </p>

                    <h2 class="block-title-2"><span>Welcome to your account. Here you can manage all of your personal information and orders.</span></h2>
                    <ul class="myAccountList row">
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="Orders" href="<?php echo $order; ?>"><i class="fa fa-calendar"></i> Order history </a></div>
                        </li>
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="My addresses" href="<?php echo $password; ?>"><i class="fa fa-key"></i> <?php echo $text_password; ?></a></div>
                        </li>
                        
                        <!--<li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="My addresses" href="<?php echo $newsletter; ?>"><i class="fa fa-envelope"></i> <?php echo $text_newsletter; ?></a></div>
                        </li>-->
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="My addresses" href="<?php echo $address; ?>"><i class="fa fa-map-marker"></i> My addresses</a></div>
                        </li>
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="Add  address" href="<?php echo $add_address; ?>"> <i class="fa fa-edit"> </i> Add address</a></div>
                        </li>
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="Personal information" href="<?php echo $edit; ?>"><i class="fa fa-user"></i> Personal information</a></div>
                        </li>
                        <li class="col-lg-2 col-md-2 col-sm-3 col-xs-4  text-center ">
                            <div class="thumbnail equalheight"><a title="My wishlists" href="<?php echo $wishlist; ?>"><i class="fa fa-heart"></i> My wishlists </a></div>
                        </li>
                    </ul>
                    <div class="clear clearfix"></div>
                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
 
    <?php //echo $column_right; ?>
</div>
<?php echo $footer; ?>