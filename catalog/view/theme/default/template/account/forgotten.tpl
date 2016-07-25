<?php echo $header; ?>

<script type="text/javascript">
$(document).ready(function(){
	$.validator.addMethod("email", function(value, element) 
	{ 
	return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
	}, "Please enter a valid email address.");
   $("#forgot_pass").validate({
			rules: {
				email: {
							required: true,
							email: true
						}
				},
			messages: {
				
				email: {
							required: "Please enter your email id"
						}
				
			}
	
   });
});
   
</script>
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
        <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
         <?php } ?>
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span> <i class="fa fa-unlock-alt"> </i> Forgot your password? </span></h1>

            <div class="row userInfo">
                <div class="col-xs-12 col-sm-12">
                    <p> To reset your password, enter the email address you use to sign in to site. We will then send you a new password. </p>

                    
                    <form role="form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="forgot_pass">
                        <div class="form-group">
                            <label for="exampleInputEmail1"> Email address </label>
                            <input type="text" name="email" class="form-control" id="input-email" placeholder="Enter email">
                        </div>
                        <button type="submit" class="btn   btn-primary"><i class="fa fa-unlock"> </i> Retrieve Password</button>
                        <!--<input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />-->
                    </form>
                    <div class="clear clearfix">
                        <ul class="pager">
                            <li class="previous pull-right"><a href="<?php echo $back;?>"> &larr; Back to Login </a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
</div>
<!-- /wrapper -->

<div class="gap"></div>

  <?php /*?><div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?> 
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <p><?php echo $text_email; ?></p>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
          <legend><?php echo $text_your_email; ?></legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
            </div>
          </div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div><?php */?>
    
</div>
<?php echo $footer; ?>