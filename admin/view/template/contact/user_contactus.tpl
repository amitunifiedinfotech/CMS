<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
     
      <h1><?php echo 'Contact Us'; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo 'Customer Queries'; ?></h3>
      </div>
      <div class="panel-body">
        
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                 <td class="text-left" width="10%">Name</td>
                  <td class="text-left" width="10%">Email</td>
                  <td class="text-left" width="70%">Message</td>
                  <td class="text-right" width="10%"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($results_set) { ?>
                <?php foreach ($results_set as $contact) { ?>
                <tr>
                  <td class="text-left"><?php echo $contact['name']; ?></td>
                  <td class="text-left"><?php echo $contact['email']; ?></td>
                  <td class="text-left"><?php echo $contact['message']; ?></td>
                  
                  <td class="text-right">
                    <a href="<?php HTTPS_SERVER;?>index.php?route=contact/contactus/reply&id=<?php echo $contact['contact_id'];?>&token=<?php echo $token;?>" data-toggle="tooltip" title="Reply" class="btn btn-warning"><i class="fa fa-reply"></i></a>
                    <a onclick="confirm('Are you sure?') ? location.href='<?php HTTPS_SERVER;?>index.php?route=contact/contactus/delete_contact&id=<?php echo $contact['contact_id'];?>&token=<?php echo $token;?>' : false;" data-toggle="tooltip" title="Delete Contact Query" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                    </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo 'no result found'; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?> 
