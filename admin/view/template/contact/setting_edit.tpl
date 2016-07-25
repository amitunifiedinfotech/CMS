<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
     <div class="pull-right">
      <?php
	      if($setting_detail['is_image'] == 0)
	      {
		?>
		<button type="submit" form="add_edit_form" data-toggle="tooltip" title="<?php echo 'Submit'; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
      <?php
	      }
	      else
	      {
		?>
		<button type="button" form="add_edit_form" data-toggle="tooltip" title="<?php echo 'Submit'; ?>" class="btn btn-primary" onclick="validation();"><i class="fa fa-save"></i></button>
		<?php
		
	      }
      ?>
      
      
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo 'Cancel'; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo 'Settings'; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo 'settings'; ?></h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="add_edit_form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value"><?php echo 'Code key'; ?></label>
            <div class="col-sm-10">
              <input type="text" name="key" value="<?php echo $setting_detail['key']; ?>" placeholder="Setting Key" readonly id="input-value" class="form-control" />
            </div>
          </div>
	  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value"><?php echo 'Code value'; ?></label>
            <div class="col-sm-10">
	      <?php
	      
	      if($setting_detail['is_image'] == 0)
	      {
		?>
		<input type="hidden" name="is_image" value="0" >
		<textarea name="value" id="value" class="form-control"><?php echo $setting_detail['value']; ?></textarea>
		<?php
	      }
	      else
	      {
		?>
		<input type="hidden" name="is_image" value="1" >
		<input type="file" name="uload" id="settings_img" class="" onchange="check_file_type(this.value)"><p id="img_error" style="color: red"></p>
		<input type="hidden" name="old_image" id="old_image" value="<?php echo $setting_detail['value']; ?>">
		<img src="<?php echo $thumb_image; ?>">  
		<?php
	      }
		?>
		
	      
              
            </div>
          </div>
        </form>
    </div>
    </div>
  </div>
 </div>



<script type="text/javascript">
$(document).ready(function(){
	
  $("#add_edit_form").validate({
    rules: {
	    value: "required"
	  }
   });
});
   function check_file_type(fileName)
          {
               if (!(/\.(gif|jpg|jpeg|tiff|png)$/i).test(fileName)) {              
                    //alert('You must select an image file only');
		    $('#img_error').html("You must select an image file only.");
                    $('#settings_img').val('');
               }
	       else
	       {
		  $('#img_error').html("");
	       }
          }
	  
	  
  $( document ).ready(function() {       
          
    var _URL = window.URL || window.webkitURL;
   $("#settings_img").change(function (e) {
       var file, img;
       if ((file = this.files[0])) {
	   img = new Image();
	   img.onload = function () {
	      if(this.width<1600 || this.height<1000)
	     {
		  $('#settings_img').val("");
		  $('#img_error').html("Image height should be minimum 1600X1000.");
		  //alert("Image height should be minimum 1600X1000");
	     }
	     else
	     {
	      $('#img_error').html("");
	     }
	     
	   };
	   img.src = _URL.createObjectURL(file);
       }
   });   
   })
  
  function validation()
  {
    var settings_img = $('#settings_img').val();
    var old_image = $('#old_image').val();
    
    if ((old_image == "") && (settings_img == "")) {
      $('#img_error').html("Required.");
    }
    else
    {
      $('#img_error').html("");
      $('#add_edit_form').submit();
    }
  }
</script>
<?php echo $footer; ?> 
