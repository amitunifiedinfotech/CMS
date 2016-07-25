<?php echo $header; ?>

<link href="<?php echo SITE_URL;?>/admin/view/javascript/bootstrap/opencart/opencart.css" type="text/css" rel="stylesheet" />

<script type="text/javascript">
  
$(document).ready(function(){
  
$("#edit_add_frm").validate({
    //ignore: "not:hidden",
      //ignore: "input[type='text']:hidden",
    
     rules: {
	    name: "required",
	    short_description:"required",
	    issue_number: {
		      number: true
		  },
	    grade: "required",
	    page_quality: "required",
	    publisher: "required",

	    certification_number: "required",
	    price: {
		    number: true,
		    required: true
		  },
	    special: {
		      number: true
		  },
	    quantity: {
		       digits: true,
		       required: true
		      }
	    },
    messages: {
	    name: "Please enter title of book",
	    short_description: "Please enter short description",
	    issue_number: "Please enter only numbers for issue number",
	    grade: "Please enter grade",
	    page_quality: "Please enter page quality",

	    publisher: "Please enter publisher",                
	    certification_number: "Please enter certification number",
	    quantity: {
		digits:"Please enter only digits",
		required:"Please enter quantity"
	    },
	    price: {
		number:"Please enter only numbers",
		required:"Please enter price"
	    },
	    special: {
		number:"Please enter only numbers",
	    }
  }
    
});
<?php if(!isset($_GET['product_id'])){?>
    
    $("#input-image").rules( "add", {
      required: true,
      messages: {
	required: "Please choose image",
      }
    });

  $("#input-image1").rules( "add", {
       required: true,
      messages: {
	required: "Please choose image",
      }
    });

<?php } ?> 
});

 

function checkSummernote(){
   $('#show_des_err').html('');
   cleanText = $("#input-description").code().replace(/<\/?[^>]+(>|$)/g, "");
   if (cleanText=="") {
      //alert('Please enter description');
      $('#show_des_err').html('Please enter description');
      return false;
   }
   return true;
}
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
      <h2><?php echo $add_edit_text; ?></h2>
      <?php if ($error_warning) { ?>
        <div class="text-danger"><?php echo $error_warning; ?></div>
      <?php } ?>
      <form action="" method="post" enctype="multipart/form-data" class="form-horizontal" name='edit_add_frm' id='edit_add_frm' onsubmit="checkSummernote()">
	<input type="hidden" value="<?php echo date("d-m-Y")?>" placeholder="DD-MM-YYYY"  id="input-date_available" class="form-control" name="date_available">
	<input type="hidden" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />        <fieldset>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-firstname">Title Of Book<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo 'Title Of Book' ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-issue_number">Issue Number</label>
            <div class="col-sm-10">
              <input type="text" name="issue_number" value="<?php echo $issue_number; ?>" placeholder="<?php echo 'Issue Number'; ?>" id="input-issue_number" class="form-control" />
              <?php if ($error_issue_number) { ?>
              <div class="text-danger"><?php echo $error_issue_number; ?></div>
              <?php } ?>
	      <?php if ($error_duplicate_name) { ?>
	       <div class="text-danger"><?php echo $error_duplicate_name; ?></div>
	       <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-company">Grade<sup>*</sup></label>
            <div class="col-sm-10">
               <select name="grade" id="grade" class="form-control">
                <option value="">Choose One</option>
                <?php
                  if(!empty($all_grade)){
                    foreach($all_grade as $each_grade){
                ?>
                <option value="<?php echo $each_grade['id'];?>" <?php if($grade==$each_grade['id']) {?> selected <?php } ?>><?php echo $each_grade['grade_meta'].' '.$each_grade['grade_value'];?></option>
                <?php
                    }
                  }
                
                ?>
                <?php ?>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address-1">Page Quality<sup>*</sup></label>
            <div class="col-sm-10">
              <select name="page_quality" id="page_quality" class="form-control">
		<option value="">Choose One</option>
		<option value="White" <?php if($page_quality=='White'){ ?> selected="selected" <?php }?>>White</option>
		<option value="Off-White/White" <?php if($page_quality=='Off-White/White'){ ?> selected="selected" <?php }?>>Off-White/White</option>
		<option value="Off-White" <?php if($page_quality=='Off-White'){ ?> selected="selected" <?php }?>>Off-White</option>
		<option value="Creme/Off-White" <?php if($page_quality=='Creme/Off-White'){ ?> selected="selected" <?php }?>>Creme/Off-White</option>
		<option value="Creme" <?php if($page_quality=='Creme'){ ?> selected="selected" <?php }?>>Creme</option>
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-address-2">Publisher<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="publisher" value="<?php echo $publisher; ?>" placeholder="<?php echo 'Publisher'; ?>" id="input-publisher" class="form-control" />
              <?php if ($error_publisher) { ?>
              <div class="text-danger"><?php echo $error_publisher; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-certification_number">Serial Number<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="certification_number" value="<?php echo $certification_number; ?>" placeholder="<?php echo 'Serial Number'; ?>" id="input-certification_number" class="form-control" />
              <?php if ($error_certification_number) { ?>
              <div class="text-danger"><?php echo $error_certification_number; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode">Image1<sup>*</sup></label>

	    <div class="col-sm-10">
	      <input type="file" name="image" id="input-image">
	      <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
	      <input type="text" name="old_image" value="<?php echo $image; ?>" id="input-image" style="opacity: 0; width:0;" />
	    </div>

            <!--<div class="col-sm-10">
              <a href="#" id="thumb-image" data-toggle="image" class="img-thumbnail">
                <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
              </a>
              <div><input type="text" name="image" value="<?php echo $image; ?>" id="input-image" style="opacity: 0; width:0;" /></div>
            </div>-->
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode">Image2<sup>*</sup></label>
            <div class="col-sm-10">
              <!--<a href="" id="thumb-image1" data-toggle="image" class="img-thumbnail">
                <img src="<?php echo $thumb1; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
              </a>
              <div><input type="text" name="image1" value="<?php echo $image1; ?>" id="input-image1" style="opacity: 0; width:0;" /></div>-->
	      <input type="file" name="image1" id="input-image1">
	      <img src="<?php echo $thumb1; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" />
	      <input type="text" name="old_image1" value="<?php echo $image1; ?>" id="input-image1" style="opacity: 0; width:0;" />

            </div>
          </div>
		  
	  <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode">Short Description<sup>*</sup></label>
             <div class="col-sm-10">
              <textarea name="short_description" placeholder="<?php echo $entry_short_description; ?>" id="word_count" style="width:100%;"><?php if(isset($short_description) && $short_description!="")  echo  $short_description; ?></textarea>
		<br>
		Total word count: <span id="display_count">0</span> words. Words left: <span id="word_left">15</span>
            </div>
          </div>
		  
		  
		  
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-postcode">Description<sup>*</sup></label>
             <div class="col-sm-10">
              <textarea name="product_description" placeholder="<?php echo $entry_description; ?>" id="input-description"><?php if(!empty($product_description))  echo  $product_description; else echo ''; ?></textarea>
	      <div id="show_des_err" style="color: red;"></div>
            </div>
          </div>
          
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone">Price<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
            </div>
          </div>
	  
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone">Special Price</label>
            <div class="col-sm-10">
              <input type="text" name="special" value="<?php echo $special; ?>" placeholder="Special Price" id="input-special" class="form-control" />
	      <?php if ($error_special) { ?>
              <div class="text-danger"><?php echo $error_special; ?></div>
              <?php } ?>
            </div>
          </div>
	  
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone">Quantity<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo 'Enter Quantity'; ?>" id="input-quantity" class="form-control" />
            </div>
          </div>
          <!--<div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone">Seo Keyword<sup>*</sup></label>
            <div class="col-sm-10">
              <input type="text" name="keyword" value="<?php echo $keyword; ?>" placeholder="<?php echo $entry_keyword; ?>" id="input-keyword" class="form-control" />
              <?php if ($error_keyword) { ?>
              <div class="text-danger"><?php echo $error_keyword; ?></div>
              <?php } ?>               
            </div>
          </div>-->
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-zone">Related Products</label>
            <div class="col-sm-10">
              <input type="text" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
              <div id="product-related" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($product_relateds as $product_related) { ?>
                <div id="product-related<?php echo $product_related['product_id']; ?>"><i class="fa fa-minus-circle"></i>
                    <?php echo $product_related['name'].'#'.$product_related['issue_number']; ?>
                  <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-adult"><?php echo "For Adult"; ?></label>
            <div class="col-sm-10">
              <select name="adult" id="input-adult" class="form-control">
                <option value="0" <?php if($adult==0) {?> selected="selected" <?php } ?>><?php echo 'No'; ?></option>
                <option value="1" <?php if($adult==1) {?> selected="selected" <?php } ?>><?php echo 'Yes'; ?></option>
              </select>
            </div>
          </div>
	  <!--<div class="form-group">
            <label class="col-sm-2 control-label" for="input-adult"><?php echo "Date Available"; ?></label>
            <div class="col-sm-10">
              <div id="OpeningDate" class="input-group date" data-date="<?php echo $date_available?>" data-date-format="DD-MM-YYYY">
		  <input type="text" value="<?php echo $date_available?>" placeholder="DD-MM-YYYY" id="input-dob" class="form-control" name="date_available"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
	       </div>
            </div>
          </div>-->
          <div class="form-group">
          	<div class="col-sm-10 col-sm-push-2 clearfix">
            	<input type="submit" value="<?php echo 'Save' ?>" class="btn btn-primary" />
            	<input type="button" onclick="window.location = '<?php echo $cancel ?>'" value="<?php echo 'Cancel' ?>" class="btn btn-default" />
            </div>
          </div>
        </fieldset>
        
      </form>
      <?php //echo $content_bottom; ?></div>
    <?php //echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
// Sort the custom fields
$('.form-group[data-sort]').detach().each(function() {
	if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('.form-group').length) {
		$('.form-group').eq($(this).attr('data-sort')).before(this);
	}

	if ($(this).attr('data-sort') > $('.form-group').length) {
		$('.form-group:last').after(this);
	}

	if ($(this).attr('data-sort') < -$('.form-group').length) {
		$('.form-group:first').before(this);
	}
});
//--></script>
<script type="text/javascript"><!--
$('button[id^=\'button-custom-field\']').on('click', function() {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	if (typeof timer != 'undefined') {
    	clearInterval(timer);
	}

	timer = setInterval(function() {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function() {
					$(node).button('loading');
				},
				complete: function() {
					$(node).button('reset');
				},
				success: function(json) {
					$(node).parent().find('.text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
//--></script>

<script type="text/javascript">
//$('#input-description').summernote({height: 300});
$('#input-description').summernote({
  height: 300,
  placeholder: 'Type your message here...',
  disableDragAndDrop: true,
  toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'superscript', 'subscript', 'strikethrough', 'clear']],
        ['fontname', ['fontname']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['height', ['height']],
        ['table', ['table']],
        ['insert', ['link',  'hr']],
        ['view', ['fullscreen', 'codeview']],
        ['help', ['help']]
      ]
  });

// Related
$('input[name=\'related\']').autocomplete({
	'source': function(request, response) {
	 $('input[name=\'related\']').addClass('input-loader');
		$.ajax({
			url: 'index.php?route=seller_product/product_list/autocomplete&filter_name=' +  encodeURIComponent(request)+'&product_id=<?php echo isset($product_id)? $product_id:"" ?>',
			dataType: 'json',			
			success: function(json) {
			   $('input[name=\'related\']').removeClass('input-loader');
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
		$('input[name=\'related\']').val('');
		
		$('#product-related' + item['value']).remove();
		
		$('#product-related').append('<div id="product-related' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '" /></div>');	
	}	
});

$('#product-related').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});


// Product Name
$('input[name=\'name\']').autocomplete({
  'source': function(request, response) {
   $('input[name=\'name\']').addClass('input-loader');
    $.ajax({
            url: 'index.php?route=seller_product/product_list/autocompleteTitle&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',			
            success: function(json) {
	       $('input[name=\'name\']').removeClass('input-loader');
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
         $('input[name=\'name\']').val(item['label']);
  }	
});


$('#OpeningDate').datetimepicker({
	format: 'DD-MM-YYYY'
});
</script>

<style>
#input-country{
	display:block !important;
}
#input-zone{
	display:block !important;
}
.minict_wrapper{
	display:none;
}
select {
	display:block !important;
}
</style>
<?php echo $footer; ?>

<script type="text/javascript">
  $(document).ready(function() {
	var value = $('#word_count').val();
	var regex = /\s+/gi;
	var words = value.trim().replace(regex, ' ').split(' ').length-1;
	
	word_left = 15-words;
	$('#word_left').html(word_left);
	$('#display_count').html(words);
	
    $("#word_count").on('keyup', function() {
	  var words=0;
	   if (this.value!='')
	   {
		  words = this.value.match(/\S+/g).length;
	   }
	   if (words > 15) {
			// Split the string on first 200 words and rejoin on spaces
            var trimmed = $(this).val().split(/\s+/,15).join(" ");
            // Add a space at the end to keep new typing making new words
            $(this).val(trimmed + " ");
        }
        else {
            $('#display_count').text(words);
            $('#word_left').text(15-words);
        }
    });
 });
  
  
 
</script>
