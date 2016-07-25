<?php echo $header; ?>
<script>
// Price Range Slider
$(function() {
var minPrice = '<?php echo $min_value;?>';
var minRangePrice = '<?php echo $min_value;?>';
var maxRangePrice = '<?php echo $max_value;?>';
var maxPrice = '<?php echo $max_value;?>';

    function PriceCollision($div1, $div2) {
        var x1 = $div1.offset().left;
        var w1 = 70;
        var r1 = x1 + w1;
        var x2 = $div2.offset().left;
        var w2 = 70;
        var r2 = x2 + w2;

        if (r1 < x2 || x1 > r2) return false;
        return true;
    }

    $('#Priceslider').slider({
        range: true,
        min: parseFloat(minPrice),
        max: parseFloat(maxPrice),
        values: [parseFloat(minRangePrice), parseFloat(maxRangePrice)],
        slide: function(event, ui) {

            $('#Priceslider .ui-slider-handle:eq(0) .price-range-min').html('$' + ui.values[0]);
            $('#Priceslider .ui-slider-handle:eq(1) .price-range-max').html('$' + ui.values[1]);
            $('#Priceslider .price-range-both').html('<i>$' + ui.values[0] + ' - </i>$' + ui.values[1]);

            if (ui.values[0] == ui.values[1]) {
                $('#Priceslider .price-range-both i').css('display', 'none');
		
            } else {
                $('#Priceslider .price-range-both i').css('display', 'inline');
		
            }

            if (PriceCollision($('#Priceslider .price-range-min'), $('#Priceslider .price-range-max')) == true) {
                $('#Priceslider .price-range-min, #Priceslider .price-range-max').css('opacity', '0');
                $('#Priceslider .price-range-both').css('display', 'block');
            } else {
                $('#Priceslider .price-range-min, #Priceslider .price-range-max').css('opacity', '1');
                $('#Priceslider .price-range-both').css('display', 'none');
            }
        },
	stop: function( event, ui ) {
		$('#min_value').val('');
		$('#max_value').val('');
			
		// get price
		min_price = ui.values[0];
		max_price = ui.values[1];
		
		// Set in hidden file
		$('#min_value_hidden').val(ui.values[0]);
		$('#max_value_hidden').val(ui.values[1]);
		
	
		var page = 1;
		var sendData = {"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val()};
		
		$(".loading-div").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",sendData, function(html){
			$(".loading-div").hide(); 		//once done, hide loading element
		});

		$('html,body').animate({
			scrollTop: $("#og-grid").offset().top-60},
			'slow');

	}
    });

    $('#Priceslider .ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#Priceslider').slider('values', 0) + ' - </i>' + $('#Priceslider').slider('values', 1) + '</span>');

    $('#Priceslider .ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#Priceslider').slider('values', 0) + '</span>');

    $('#Priceslider .ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#Priceslider').slider('values', 1) + '</span>');

});

  
function getRadioSelected(button){
    var radio_price = '';
    
    if (button!=undefined) {

            $('#error_price_range').html('');
            if ($('#min_value').val()=="" || $('#max_value').val()=="") {
                    $('#error_price_range').html('These fields are required.');
                    return false;
            }
			$('html,body').animate({
			scrollTop: $("#og-grid").offset().top-60},
			'slow');
            
            if (parseFloat($('#min_value').val()) >= parseFloat($('#max_value').val())) {
                    $('#error_price_range').html('Min price cannot greater than max price.');			
                    return false;
            }
            
            var page = 1;
            
            // get price 
            min_price = $('#min_value').val();
            max_price = $('#max_value').val();

            var sendData = {"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val()};
            
            $(".loading-div").show();
            $("#results").load("<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",sendData, function(html){
                    $(".loading-div").hide();
                    
            });

            $('#Priceslider').slider( "values", [ '<?php echo $min_value;?>', '<?php echo $max_value;?>' ]);
            $('.price-range-min').html('<?php echo $min_value;?>');
            $('.price-range-max').html('<?php echo $max_value;?>');
            
            $('#min_value_hidden').val('');
            $('#max_value_hidden').val('');
            
    }
    else{
            radio_price = $('#min_value_hidden').val()+'-'+$('#max_value_hidden').val();
            return radio_price;
    }
}


$(document).ready(function() {


    $("#results" ).load( "<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",'',function(){ 
      $(".loading-div").hide();
    });	
    
    //executes code below when user click on pagination links
    $("#results").on( "click", ".pagination a", function (e){
            e.preventDefault();
            
            // get price
            if ($('#min_value').val()!='' && $('#max_value').val()!='') {
                    min_price = $('#min_value').val();
                    max_price = $('#max_value').val();
            }
            else{
                    radio_price = getRadioSelected();
                    
                    min_max_price = radio_price.split("-");
                    min_price = min_max_price[0];
                    max_price = min_max_price[1];
            }
            
            //alert(min_price+max_price);
            var page = $(this).attr("data-page"); 		//get page number from link

            var sendData = {"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val()};

            //show loading element
            $(".loading-div").show();
            $("#results").load("<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",sendData, function(){ 
                    $(".loading-div").hide(); 
            });
            
    });
	
});

 
function searchTitle() {

    $('#error_title').html('');
    if ($('#tilte_book').val()=="") {
            $('#error_title').html('Please enter title of the book');
			$('#tilte_book').focus()
            return false;
    }
$('html,body').animate({
        scrollTop: $("#og-grid").offset().top-60},
        'slow');    
    // get price
    if ($('#min_value').val()!='' && $('#max_value').val()!='') {
            min_price = $('#min_value').val();
            max_price = $('#max_value').val();
    }
    else{
            radio_price = getRadioSelected();
            min_max_price = radio_price.split("-");
            min_price = min_max_price[0];
            max_price = min_max_price[1];		
    }
  
    var page = 1;
  
 
      var sendData = {"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'book_variant':$('#book_variant').val(),'publisher':$('#publisher').val()};
      
      $(".loading-div").show(); 
      $("#results").load("<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",sendData, function(html){
              $(".loading-div").hide(); 		//once done, hide loading element
      });

}

function searchPublisher() {

    $('#error_title').html('');
    if ($('#publisher').val()=="") {
            $('#error_publisher').html('Please enter title of the book');
			$('#tilte_book').focus()
            return false;
    }
    $('html,body').animate({
        scrollTop: $("#og-grid").offset().top-60},
        'slow'); 
    // get price
    if ($('#min_value').val()!='' && $('#max_value').val()!='') {
            min_price = $('#min_value').val();
            max_price = $('#max_value').val();
    }
    else{
            radio_price = getRadioSelected();
            min_max_price = radio_price.split("-");
            min_price = min_max_price[0];
            max_price = min_max_price[1];		
    }

    var page = 1;

    var sendData = {"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val()};
    
    $(".loading-div").show(); 
    $("#results").load("<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",sendData, function(html){
            $(".loading-div").hide(); 		//once done, hide loading element
    });
	
}
// ......................... For ordering  ............................
function filterProducts() {
	
    var page = 1;
    
    // get price
    if ($('#min_value').val()!='' && $('#max_value').val()!='') {
        min_price = $('#min_value').val();
        max_price = $('#max_value').val();
    }
    else{
        radio_price = getRadioSelected();
        
        min_max_price = radio_price.split("-");
        min_price = min_max_price[0];
        max_price = min_max_price[1];
    }

      var sendData = {"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val()};
    
    $(".loading-div").show(); 
    $("#results").load("<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",sendData, function(html){
      $(".loading-div").hide(); 		//once done, hide loading element
    });
	$('html,body').animate({
			scrollTop: $("#og-grid").offset().top-60},
			'slow');
	
}

function clearFilteration(param){
	
	if(param==1){
		// set price to zero
		min_price = '';
		max_price = '';	
		
		// uncheck all price range and blank text box
		$('#min_value').val('');
		$('#max_value').val('');
	
		$('#Priceslider').slider( "values", [ '<?php echo $min_value;?>', '<?php echo $max_value;?>' ]);
		$('.price-range-min').html('<?php echo $min_value;?>');
		$('.price-range-max').html('<?php echo $max_value;?>');
	}
	else if(param==3){
		$('#publisher').val('');
	}
	else{
		$('#tilte_book').val('');
	}

	var page = 1;
	
	str = getRadioSelected();
	
	var sendData = {"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=grading_service/grading_service/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	$('html,body').animate({
			scrollTop: $("#og-grid").offset().top-60},
			'slow');
}
 
  
</script>

<div class="banner-boxes">
  <div style="background-image: url('image/<?php echo $grading_service_image;?>'); background-position: 50% -44px;" class="parallax-section parallax-image-1">
   <div class="container">
      <div class="row ">
          <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="parallax-content clearfix">
                <?php echo html_entity_decode($grading_service_banner_text);?>
              <div style="clear:both; height:40px;"></div>
              <a class="btn btn-discover" target="_blank" href="<?php echo html_entity_decode($grading_service_link);?>">View Details</a>
            </div>
          </div>
   </div>
 </div>
      <!--/.container--> 
    </div>
</div>
<div class="container"> 
   <!-- Main component call to action -->      
   <div class="row featuredPostContainer globalPadding style2">
      <h3 class="section-title style2 text-center"><span>GRADING SERVICE</span></h3>
      <p class="grad1ngServicePara"><?php echo $grading_service_text;?></p>	
   </div>
 </div>
 <div class="container main-container" style="min-height:100px;">

    <!-- Main component call to action --> 

    <div class="globalPadding totalGrandingContentArea" style="padding-bottom:25px;">
     <div class="row">

        <!--left column-->
        <div class="col-md-6 col-sm-12">
            <div id="accordionNo" class="panel panel-default">
              <div class="panel-heading">
                  <h4 class="panel-title">
                    <a href="#collapsePrice" data-toggle="collapse" class="collapseWill">Price <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
                    <span onclick="clearFilteration(1)" class="pull-right clearFilter  label-danger"> Clear </span>
                  </h4>
              </div>
              <div class="panel-collapse collapse in" id="collapsePrice">
                  <div class="panel-body priceFilterBody clearfix" style="padding-bottom:0; padding-top:0;">
                  <div class="width50Left">
                    <div class="filter-menu" style="margin-bottom:12px;">
                      <div class="price-range">
                          <div class="slider-outer" style="margin-top:55px;">
                               <div id="Priceslider"></div>
                          </div>
                          <div class="row range-txt">
                               <div class="col-xs-6">$<?php echo $min_value;?></div>
                               <div class="col-xs-6 text-right">$<?php echo $max_value;?></div>
                               <input type="hidden" class="form-control" id="min_value_hidden" name="min_value_hidden">
                               <input type="hidden" class="form-control" id="max_value_hidden" name="max_value_hidden">
                          </div>
                      </div>
                    </div>
                  </div>
                     <div class="width50Right">
                  <p>Enter a Price range </p>

                  <form name="price_frm" id="price_frm" role="form" class="form-inline">
                      <div class="form-group">
                          <input type="text" placeholder="100" name="min_value" id="min_value" class="form-control">
                      </div>
                      <div class="form-group sp"> -</div>
                      <div class="form-group">
                          <input type="text" placeholder="500" name="max_value" id="max_value" class="form-control">
                      </div>
                      <div class="error newValidate4ErrorArea" id="error_price_range"></div>
                      <button class="btn btn-default pull-right" onclick="getRadioSelected('button')" id="check_btn" type="button">check</button>
                  </form>
      </div>
                </div>
              </div>
          </div>
        </div>
        <div class="col-md-3 col-sm-12">
          <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                  <a href="#collapseSearch" data-toggle="collapse" class="collapseWill">Search By Comic <span class="pull-left">
                    <i class="fa fa-caret-right"></i></span>
                  </a>
                    <span onclick="clearFilteration(2)" class="pull-right clearFilter label-danger"> Clear </span>              
                </h4>
             </div>
            <div class="panel-collapse collapse in" id="collapseSearch">
                <div class="panel-body" style="padding-bottom:25px;">       
                  <form class="form-inline" role="form" id="srch_by_title">
                    <div class="form-group title_input"><input type="text" class="form-control" id="tilte_book" name="tilte_book" placeholder="Title of Book" value="<?php echo $pro_name; ?>">
					<input type="hidden" class="form-control" id="book_variant" name="book_variant"  value="<?php echo $variant_name; ?>">
					
					</div>
                    <div id="error_title" class="error"></div>
                    <button type="button" onClick="searchTitle()" class="btn btn-default pull-right">Search</button>
                  </form>
               </div>
            </div>
           </div>
        </div>
          <div class="col-md-3 col-sm-12">
          <div class="panel panel-default">
          <div class="panel-heading">
            <h4 class="panel-title">
              <a href="#collapseSearchPublish" data-toggle="collapse" class="collapseWill">Search By Publisher<span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
              <span onclick="clearFilteration(3)" class="pull-right clearFilter label-danger"> Clear </span>              
            </h4>
           </div>
          <div id="collapseSearchPublish" class="panel-collapse collapse in">
            <div class="panel-body" style="padding-bottom:25px;">       
              <form class="form-inline" role="form">
                <div class="form-group title_input"><input type="text" class="form-control" id="publisher" name="publisher" placeholder="Publisher of Book"></div>
                <div id="error_publisher" class="error"></div>
                <button type="button" onClick="searchPublisher()" class="btn btn-default pull-right">Search</button>
              </form>
            </div>
          </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <select class="form-control" name="orderby" id="orderby" onChange="filterProducts()">
          <option value="sort_order-desc" selected="selected">Default sorting</option>
          <option value="name-asc">Sort by Name(A-Z)</option>
          <option value="name-desc">Sort by Name(Z-A)</option>
          <option value="price-asc">Sort by price(low-high)</option>
          <option value="price-desc">Sort by price(high-low)</option>
           <option value="date_added-asc">Sort by Date(Oldest)</option>
          <option value="date_added-desc">Sort by Date(Latest)</option>
        </select>
     </div>
    </div>
  </div>

  
  <div id="results"><!-- content will be loaded here --></div>


<script>
$(document).ready(function(){
    // Publisher Name
    $('input[name=\'publisher\']').autocomplete({
      'source': function(request, response) {
    $('input[name=\'publisher\']').addClass('input-loader');
       
    $.ajax({
            url: 'index.php?route=grading_service/grading_service/autocompletePublisher&filter_name=' +  encodeURIComponent(request),
            dataType: 'json',			
            success: function(json) {
               $('input[name=\'publisher\']').removeClass('input-loader');
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
           $('input[name=\'publisher\']').val(item['label']);
    }	
   });
})


  
$(document).ready(function(){
  // Product Name
  $('input[name=\'tilte_book\']').autocomplete({
    'source': function(request, response) {
     $('input[name=\'tilte_book\']').addClass('input-loader');
     //alert(<?php echo $param_val;?>)
      $.ajax({
          url: 'index.php?route=grading_service/grading_service/autocompleteTitle&filter_name=' +  encodeURIComponent(request),
          dataType: 'json',			
          success: function(json) {
             $('input[name=\'tilte_book\']').removeClass('input-loader');
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
           $('input[name=\'tilte_book\']').val(item['label']);
    }	
  });
})

 $(function() {
    $("#min_value").keyup(function() {       
        this.value = this.value.match(/[-+]?[0-9]*\.?[0-9]*/);
	
    });
    $("#max_value").keyup(function() {       
        this.value = this.value.match(/[-+]?[0-9]*\.?[0-9]*/);
	
    });
});
</script>  

<?php echo $footer; ?>