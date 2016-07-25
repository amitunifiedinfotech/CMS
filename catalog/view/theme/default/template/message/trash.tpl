<?php echo $header; ?>
<div class="container main-container headerOffset">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <div class="buttons clearfix">
        <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
        <div class="pull-right"><a href="<?php echo $inbox; ?>" class="btn btn-primary"><?php echo 'Inbox'; ?></a></div>
        <div class="pull-right"><a href="<?php echo $outbox; ?>" class="btn btn-primary"><?php echo 'Outbox'; ?></a></div>
        <div class="pull-right"><a href="<?php echo $trash; ?>" class="btn btn-primary"><?php echo 'Trash'; ?></a></div>
      </div>
      <?php if (!empty($all_msg)) { ?>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left">Name</td>
            <td class="text-left">Subject</td>
            <td class="text-left">Date</td>
            <td class="text-right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
        <?php
          foreach ($all_msg as $each_msg) {
            $class = '';
            if($each_msg['already_seen_inbox']==1){
              $class = 'unread';
            }
           
        ?>
          <tr class="<?php echo $class?>">
            <td class="text-left"><a href="<?php echo $each_msg['view_msg']; ?>"><?php echo $each_msg['name']; ?></a></td>
            <td class="text-left"><a href="<?php echo $each_msg['view_msg']; ?>"><?php echo $each_msg['subject']; ?></a></td>
            <td class="text-left"><?php echo date("d/m/Y",strtotime($each_msg['post_date'])); ?></td>
            <td class="text-right">
              <a href="javascript:checkConfirm('<?php echo $each_msg['message_id'];?>')" data-toggle="tooltip" title="<?php echo 'Remove Message'; ?>" class="btn btn-danger"><i class="fa fa-times"></i></a></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php } else { ?>
      <p><?php echo $text_empty; ?></p>
      <?php } ?>
      
       <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><div class="pagination"><?php echo $results; ?></div></div>
      </div>
      
     </div>
    </div>
</div>
<script>
  function checkConfirm(param) {
    
   if (confirm('Are you sure?')) {
      window.location  = '<?php echo SITE_URL?>index.php?route=message/message/removemsg&message_id='+param;
    }
  }
  
</script>
<?php echo $footer; ?>