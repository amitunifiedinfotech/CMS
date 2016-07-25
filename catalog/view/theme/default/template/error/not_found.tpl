<?php echo $header; ?>
<!--<div class="container main-container headerOffset">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row userInfo">
    <div class="col-lg-12 col-md-12 col-sm-12">
      <h1 class="title-big text-center section-title-style2"><span><?php echo $heading_title; ?></span></h1>
      <p class="lead text-danger text-center"><?php echo $text_error; ?></p>
      <div class="buttons  text-center">
        <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
      </div>
      </div>
    </div>
</div>-->



<div class="container main-container headerOffset">

    <div class="row innerPage">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="row userInfo">
                <p class="lead text-center"> ... CONTENT NOT FOUND .... </p>
                <h1 class="h1error"><span class="err404"> 404</span> ERROR </h1>
                <h1 class="h1error"><span class="err404"> <i class="fa fa-frown-o"></i></span></h1>
                 <ul class="pager text-center">
                    <li class="previous"><a href="<?php echo $continue; ?>" style="float:none;"> <i class="fa fa-home"></i> Go to Shop </a></li>
                </ul>
            </div>
            <!--/row end-->
        </div>
    </div>
    <!--/.innerPage-->
    <div style="clear:both"></div>
</div>



<?php echo $footer; ?>