<?php echo $header; ?>

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
      <h2 onclick="showreplyOption()"><?php echo 'Reply'; ?></h2>
      <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" name='reply_frm' id='reply_frm'>
      <fieldset>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-firstname">Subject</label>
        <div class="col-sm-10">
          <?php echo $all_msg['subject'];?>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-issue_number">Message</label>
        <div class="col-sm-10">
          <?php echo $all_msg['content'];?>
        </div>
      </div>
      
      <div class="form-group" id="reply_section" style="display: none;">
      
        <label class="col-sm-2 control-label" for="input-address-2">Reply</label>
        <div class="col-sm-10">
          <textarea name="content" id="content"></textarea>
          <input type="submit" value="<?php echo 'Save' ?>" class="btn btn-primary" />
            <input type="button" onclick="window.location = '<?php echo $cancel ?>'" value="<?php echo 'Cancel' ?>" class="btn btn-default" />
        </div>
      </fieldset>
      
      </div>

    </form>
        
      
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
  
  
  $("#reply_frm").validate({
      rules: {
	content: "required"
	},
     messages: {
	
	content: "Please enter content"
     }
	
   });
});
</script>
<script>
  function showreplyOption(){
    //code
    $('#reply_section').toggle();
  }
  
</script>

<?php echo $footer; ?>