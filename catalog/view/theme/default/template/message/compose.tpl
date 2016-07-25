<?php echo $header; ?>
<script type="text/javascript">
$(document).ready(function(){
  $.validator.addMethod("email", function(value, element) 
    { 
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
    }, "Please enter a valid email address.");
  $("#compose_msg").validate({
      rules: {
	email: {
	  'required': true,
	  'email': true
	    },
	subject: "required",
	content: "required"
	},
     messages: {
	email: "Please enter your valid email id",
	subject: "Please enter subject",
	content: "Please enter content"
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
    <div id="content" class="<?php echo $class; ?>"> <?php echo $content_top; ?>
      <h2><?php echo 'Compose Message'; ?></h2>
      <?php if ($error_warning) { ?>
        <div class="text-danger"><?php echo $error_warning; ?></div>
      <?php } ?>
      <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" name='compose_msg' id='compose_msg' onsubmit="checkSummernote()">
        <fieldset>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname">Email<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="" placeholder="<?php echo 'Email' ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-issue_number">Subject<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="subject" value="" placeholder="<?php echo 'Subject'; ?>" id="input-subject" class="form-control" />
              <?php if ($error_subject) { ?>
              <div class="text-danger"><?php echo $error_subject; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-address-2">Message<sup>*</sup></label>
            <div class="col-sm-10">
	      <textarea name="content" id="input-content" class="form-control" placeholder="<?php echo 'Insert your message'; ?>"></textarea>
              <?php if ($error_content) { ?>
              <div class="text-danger"><?php echo $error_content; ?></div>
              <?php } ?>
            </div>
          </div>
          
          <div class="form-group">
          	<div class="col-sm-10 col-sm-push-2 clearfix">
            	<input type="submit" value="<?php echo 'Save' ?>" class="btn btn-primary" />
            	<input type="button" onclick="window.location = '<?php echo $cancel ?>'" value="<?php echo 'Cancel' ?>" class="btn btn-default" />
            </div>
          </div>
        </fieldset>
        
      </form>
      </div>
    </div>
</div>


<script type="text/javascript">



// Product Name
$('input[name=\'email\']').autocomplete({
  'source': function(request, response) {
   $('input[name=\'email\']').addClass('input-loader');
    $.ajax({
            url: 'index.php?route=message/message/autocompleteTitle&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',			
            success: function(json) {
	       $('input[name=\'email\']').removeClass('input-loader');
                    response($.map(json, function(item) {
                     
                        return {
                                label: item['name'],
                                value: item['product_id']
                        }
                    }));
            }
    });
  },
  'select': function(item) {
         $('input[name=\'email\']').val(item['label']);
  }	
});

</script>


<?php echo $footer; ?>
