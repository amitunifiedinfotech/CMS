<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
     <div class="pull-right"><button type="submit" form="add_edit_form" data-toggle="tooltip" title="<?php echo 'Submit'; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo 'Cancel'; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo 'Edit Email template'; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo 'Edit Email template'; ?></h3>
      </div>
      <div class="panel-body">
        <form action="" method="post" enctype="multipart/form-data" id="add_edit_form" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value">Template Name</label>
            <div class="col-sm-10">
              <input type="text" name="value" value="<?php echo $post_arr['template_name']; ?>" size="100" readonly="readonly" placeholder="Template Name" id="input-template_name" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value">Newsletter Subject</label>
            <div class="col-sm-10">
              <input type="text" name="post_title" value="<?php echo $post_arr['subject']; ?>" placeholder="Newsletter Subject" id="input-post_title" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value">Content</label>
            <div class="col-sm-10">
            	<textarea name="content" id="input-excerpt"><?php echo $post_arr['content']; ?></textarea>
            </div>
          </div>
          
      </form>
    </div>
    </div>
  </div>
 </div>


<script type="text/javascript">
$('#input-excerpt').summernote({
	height: 300
});
</script>
<script type="text/javascript">
$(document).ready(function(){
	
        $("#add_edit_form").validate({
			rules: {
				
				post_title: "required",
				content : "required"
                   },
			messages: {
				
				post_title: "Please enter title"
			},
        
	
   });
});
   
</script>
<?php echo $footer; ?> 
