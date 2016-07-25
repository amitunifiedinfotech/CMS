<?php echo $header; ?>
 <script type="text/javascript">
 $(document).ready(function(){

	$.validator.addMethod("email", function(value, element) 
		{ 
		return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
		}, "Please enter a valid email address.");
	
	$("#login_form").validate({
	 rules: {
			 
			 email: {
							 required: true,
							 email: true
						 },
			 password:{ required: true}
			 
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
		  <?php if ($success) { ?>
          <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
          <?php } ?>
          <?php if ($error_warning) { ?>
          <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
          <?php } ?>
        </div>
    </div>

    <div class="row">

        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i class="fa fa-lock"></i> Authentication</span></h1>

            <div class="row userInfo">

                <div class="col-xs-12 col-sm-6">
                    <h2 class="block-title-2"> Create an account </h2>
					<p><strong><?php echo $text_register; ?></strong></p>
                    <p><?php echo $text_register_account; ?></p>
                    <a href="<?php echo $register; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
                    
                </div>
                <div class="col-xs-12 col-sm-6">
                    <h2 class="block-title-2"><span>Already registered?</span></h2>
					<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="logForm" autocomplete="Off" id="login_form">
                        <div class="form-group">
                            <label>Email address</label>
                            <input type="text" name="email" id="input-email" value="<?php echo $remember_cookie_email;?>" class="form-control" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" id="input-password" class="form-control" placeholder="Password">
                        </div>
                        <div class="checkbox">
                            <label><input name="rememberme" value="forever" checked="checked" type="checkbox">Remember Me </label>
                        </div>
                        <div class="form-group">
                            <p><a title="Recover your forgotten password" href="<?php echo $forgotten; ?>">Forgot your password? </a></p>
                        </div>
                        <input type="submit" value="Sign In" class="btn btn-primary" />
                          <?php if ($redirect) { ?>
                          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
                          <?php } ?>
                    </form>
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
      <div class="row">
        <div class="col-sm-6">
          <div class="well">
            <h2><?php echo $text_new_customer; ?></h2>
            <p><strong><?php echo $text_register; ?></strong></p>
            <p><?php echo $text_register_account; ?></p>
            <a href="<?php echo $register; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        </div>
        <div class="col-sm-6">
          <div class="well">
            <h2><?php echo $text_returning_customer; ?></h2>
            <p><strong><?php echo $text_i_am_returning_customer; ?></strong></p>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
              <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary" />
              <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
              <?php } ?>
            </form>
          </div>
        </div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div><?php */?>
    
</div>
<?php echo $footer; ?>