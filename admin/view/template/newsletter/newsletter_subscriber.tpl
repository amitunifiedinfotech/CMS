<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo $add_button; ?>" data-original-title="Add New"><i class="fa fa-plus"></i></a>
        <button onclick="confirm('Are you sure?') ? $('#form-customer').submit() : false;" class="btn btn-danger" title="" data-toggle="tooltip" type="button" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>
      </div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo 'Newsletter Template'; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                 <td class="text-left" widt h="10%">Template Subject</td>
                  <td class="text-left" wid th="10%">Template Content</td>
                  <td class="text-right" wid th="10%">Action</td>
                </tr>
              </thead>
              <tbody>
                <?php if ($newsletter_arr) { ?>
                <?php foreach ($newsletter_arr as $each_newsletter) { ?>
                <tr>
                  <td class="text-left"><?php echo $each_newsletter['newsletter_subject']; ?></td>
                  <td class="text-left"><?php echo substr(strip_tags(html_entity_decode($each_newsletter['newsletter_message'])),0,100)."....."; ?></td>
                  <td class="text-right">
                    <a href="<?php echo HTTP_SERVER; ?>?route=newsletter/news/add_newsletter_template&id=<?php echo $each_newsletter['id']; ?>&token=<?php echo $_GET['token']; ?>" data-toggle="tooltip" title="Edit Newsletter Template" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <a onclick="confirm('Are you sure?') ? location.href='index.php?route=newsletter/news/del_newsletter_template&id=<?php echo $each_newsletter['id']; ?>&token=<?php echo $_GET['token']; ?>' : false;" data-toggle="tooltip" title="Delete Newsletter Template" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
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
  </div>
<?php echo $footer; ?> 
