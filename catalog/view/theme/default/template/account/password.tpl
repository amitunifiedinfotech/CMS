<?php echo $header; ?>


<script type="text/javascript">
$(document).ready(function(){
	
   $("#change_pass").validate({
			rules: {
				password: {
								required: true,
								minlength: 4,
								maxlength: 20
            				},
				confirm_pass: {
								equalTo: "#input-password",
								 minlength: 4,
								 maxlength: 20
							}
				},
			messages: {
				password: {
								
								required: "Please enter your password"
            				}
			}
	
   });
});
   
</script>

<div class="container main-container headerOffset">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="change_pass" autocomplete="Off">
        <fieldset>
          <legend><?php echo $text_password; ?></legend>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm_pass" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" class="form-control" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-10 col-sm-push-2 clearfix">
              <div class="pull-left"><input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" /></div>
              <div class="pull-right">
                <a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a>
              </div>
            </div>
          </div>
        </fieldset>
        
      </form>
      <?php echo $content_bottom; ?></div>
    <?php //echo $column_right; ?></div>
</div>
<?php echo $footer; ?>