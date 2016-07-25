<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      
      <h1><?php echo $heading_title; ?></h1>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label class="control-label" for="input-name"><?php echo $entry_name; ?></label>
                <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              </div>
            </div>
            <div style="clear:both;"></div>
            <div class="col-sm-3">
              <button type="button" id="button-filter" class="btn btn-primary pull-left"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
              <button type="button" id="button-filter-clear" class="btn btn-danger pull-right"><i class="fa fa-times"></i> <?php echo 'Clear Filter'; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-customer">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'title') { ?>
                    <a href="<?php echo $sort_title; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_title; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_title; ?>"><?php echo $column_title; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'WL.issue_number') { ?>
                    <a href="<?php echo $sort_issue_number; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_issue_number; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_issue_number; ?>"><?php echo $column_issue_number; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_grade; ?></td>
                  <td class="text-left"><?php echo $column_price; ?></td>
                  <td class="text-left"><?php if ($sort == 'WL.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($wantlist)) { ?>
                <?php 
                	foreach ($wantlist as $each_wantlist) { 
                    	if($each_wantlist['grade_range1']=='' && $each_wantlist['grade_range2']=='')
                        	$grade = 'NA';
                        else
                        	$grade = $each_wantlist['grade_range1'].' to '.$each_wantlist['grade_range2'];
                    	if($each_wantlist['price_from']==0 && $each_wantlist['price_to']==0)
                        	$price = 'NA';
                        else
                        	$price = $each_wantlist['price_from'].' to '.$each_wantlist['price_to'];
                ?>
                <tr>
                  <td class="text-left"><?php echo $each_wantlist['name']; ?></td>
                  <td class="text-left"><?php echo $each_wantlist['title']; ?></td>
                  <td class="text-left"><?php echo $each_wantlist['issue_number']; ?></td>
                  <td class="text-left"><?php echo $grade; ?></td>
                  <td class="text-left"><?php echo $price; ?></td>
                  <td class="text-left"><?php echo $each_wantlist['date_added']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
  
<script type="text/javascript">
$('#button-filter').on('click', function() {
	url = 'index.php?route=wantlist/wantlist&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	location = url;
});
</script> 
<script type="text/javascript">
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=wantlist/wantlist/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['wanted_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}	
});
</script> 


<script type="text/javascript">
  $('#button-filter-clear').click(function(){
    //alert('<?php echo htmlspecialchars_decode($page_url)?>');return false;
    window.location = '<?php echo htmlspecialchars_decode($page_url);?>';
  })
  
</script>


</div>
<?php echo $footer; ?> 
