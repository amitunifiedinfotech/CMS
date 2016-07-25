<?php echo $header; ?>

<script type="text/javascript">



// ......................... price Range Filteration ............................

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
		
		str = $('#customer_id').val();
	
		// get price
		min_price = ui.values[0];
		max_price = ui.values[1];
		
		// Set in hidden file
		$('#min_value_hidden').val(ui.values[0]);
		$('#max_value_hidden').val(ui.values[1]);
		
	
		var page = 1;
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
			
		
		var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};
		
		$(".loading-div").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(html){
			$(".loading-div").hide(); 		//once done, hide loading element
		});

	}
    });

    $('#Priceslider .ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#Priceslider').slider('values', 0) + ' - </i>' + $('#Priceslider').slider('values', 1) + '</span>');

    $('#Priceslider .ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#Priceslider').slider('values', 0) + '</span>');

    $('#Priceslider .ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#Priceslider').slider('values', 1) + '</span>');

});

// ......................... End of price Range Filteration ............................	
	
	
function getCheckboxSelected(){
	var str = '';
	$('.checkbox1').each(function() {	
		if ($(this).is(":checked")) {
			str += $(this).val()+',';
		}
	});
	
	str = str.slice(0, -1);
	return str;
}

function getRadioSelected(button){
	var radio_price = '';
	
	if (button!=undefined) {

		$('#error_price_range').html('');
		if ($('#min_value').val()=="" || $('#max_value').val()=="") {
			$('#error_price_range').html('These fields are required.');
			return false;
		}
		
		if (parseFloat($('#min_value').val()) >= parseFloat($('#max_value').val())) {
			$('#error_price_range').html('Min price cannot greater than max price.');			
			return false;
		}
		
		var page = 1;
		
		// get price 
		min_price = $('#min_value').val();
		max_price = $('#max_value').val();
		
		str = $('#customer_id').val();
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
		class_value = '';
		
		var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};
		
		$(".loading-div").show();
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(html){
		    $(".loading-div").hide();
		});
		
		$('#Priceslider').slider( "values", [ '<?php echo $min_value;?>', '<?php echo $max_value;?>' ]);
		$('.price-range-min').html('$<?php echo $min_value;?>');
		$('.price-range-max').html('$<?php echo $max_value;?>');

		$('#min_value_hidden').val('');
		$('#max_value_hidden').val('');
	}
	else{
		radio_price = $('#min_value_hidden').val()+'-'+$('#max_value_hidden').val();
		return radio_price;
	}
	
	
}

	
$(document).ready(function() {
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';

	var sendData = {'title_book':addslashes('<?php echo $title_book1;?>'),'class_value':class_value};
	
	$("#results" ).load( "<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData,function(){
	    
	}); 
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination- a", function (e){
		e.preventDefault();
		
		// get customer id
		str = $('#customer_id').val();

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
		
		$(".loading-div-preorder").show(); 			//show loading element
		
		var page = $(this).attr("data-page"); 		//get page number from link
		var class_value;
		
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
			
			
		
		var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};

		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(){ 
			$(".loading-div-preorder").hide(); 
		});
		
	});
	
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-1 a", function (e){
		
		e.preventDefault();
		
		// get customer id
		str = $('#customer_id').val();

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
		
		$(".loading-div-newlease").show(); 			//show loading element
		
		var page = $(this).attr("data-page"); 		//get page number from link
		var class_value;
		
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
		
		var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page1":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};

		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(){ 
			$(".loading-div-newlease").hide(); 
		});
		
	});
	
	
	
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-2 a", function (e){
		
		str = $('#customer_id').val();
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
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page2":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':addslashes('<?php echo $title_book1;?>'),"customer_filter":str,'per_page_limit':$('#per_page_show').val()};

		//show loading element
		$(".loading-div-backissue").show();
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(){ 
			$(".loading-div-backissue").hide(); 
		});
		
	});

	//executes code below when user click on pagination links
	
	$("#results").on( "click", ".pagination-31 a", function (e){
		
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
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page3":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':addslashes('<?php echo $title_book1;?>'),"customer_filter":str,'per_page_limit':$('#per_page_show').val()};

		//show loading element
		$(".loading-div-marketstreet").show();
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(){ 
			$(".loading-div-marketstreet").hide(); 
		});
		
	});

	//executes code below when user click on pagination links
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination4 a", function (e){
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
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page4":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val()};

		//show loading element
		$(".loading-div-page4").show(); 			//show loading element
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-page4").hide(); 
		});
		
	});

	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination5 a", function (e){
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
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page5":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val()};

		//show loading element
		$(".loading-div-page5").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-page5").hide(); 
		});
		
	});
	

	
	$('input:radio[name="price_filter"]').click(function(){
  
	    var page = 1;
	    
	    // get price 
	    min_max_price = $(this).val().split("-");
	    min_price = min_max_price[0];
	    max_price = min_max_price[1];
	    
	    // Turn off radio selection
	    $('#min_value').val('');
	    $('#max_value').val('');

	    
	    str = $('#customer_id').val();
	    
	    var class_value;
	    if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	    }
	    else
		class_value = '';
		
	    var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};
	    
	    $(".loading-div-page6").show(); 
	    $("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(html){
		    $(".loading-div-page6").hide(); 
	    });
		
		
		
	});
});

// ......................... For ordering  ............................
function filterProducts() {
	
	var page = 1;
	
	// get customer id
	str = $('#customer_id').val();
	
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
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
		
	var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(html){
		//alert(html);
		$(".loading-div").hide(); //once done, hide loading element
	});
	
}

// ......................... For Filteration of Customer  ............................
function searchCustomer() {

	$('#error_customer_title').html('');
	if ($('#customer_id').val()=="") {
		$('#error_customer_title').html('This Customer does not exists.');
		return false;
	}
	
	//str = getCheckboxSelected();
	str = $('#customer_id').val();
	
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
	
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
	
	var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}
// ......................... End of Filteration of Customer  ............................

// ......................... Clear Filteration for All  ............................

function clearFilteration(param){
	
	if(param==1){
		
		// set price to zero
		min_price = '';
		max_price = '';	
		
		// blank price text box
		$('#min_value').val('');
		$('#max_value').val('');
		$('#min_value_hidden').val('');
		$('#max_value_hidden').val('');
		
	
		$('#Priceslider').slider( "values", [ '<?php echo $min_value;?>', '<?php echo $max_value;?>' ]);
		$('.price-range-min').html('$<?php echo $min_value;?>');
		$('.price-range-max').html('$<?php echo $max_value;?>');

	
	}
	else if(param==3){
		$('#customer_name').val('')
		$('#customer_id').val('')
	}
	
	var page = 1;
	
	var str = $('#customer_id').val();
	
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
	
	var sendData = {"class_value":class_value,'title_book':addslashes('<?php echo $title_book1;?>'),"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/search_result/loadcontent",sendData, function(html){
		$(".loading-div").hide(); //once done, hide loading element
	});
}


// ......................... End Clear Filteration for All  ............................
</script>
<style>

.contents{
	margin: 20px;
	padding: 20px;
	list-style: none;
	background: #F9F9F9;
	border: 1px solid #ddd;
	border-radius: 5px;
}
.contents li{
    margin-bottom: 10px;
}
.loading-div{
	position: absolute;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.56);
	z-index: 999;
	display:none;
}
.loading-div img {
	margin-top: 20%;
	margin-left: 50%;
}

/* Pagination style */
.pagination{margin:0;padding:0;}
.pagination li{
	display: inline;
	padding: 6px 10px 6px 10px;
	/*border: 1px solid #ddd;
	margin-right: -1px;*/
	font: 15px/20px Arial, Helvetica, sans-serif;
	/*background: #FFFFFF;*/
	box-shadow: inset 1px 1px 5px #F4F4F4;
}
.pagination li a{
    text-decoration:none;
    color: rgb(89, 141, 235);
}
.pagination li.first {
    border-radius: 5px 0px 0px 5px;
}
.pagination li.last {
    border-radius: 0px 5px 5px 0px;
}
.pagination li:hover{
	background: #CFF;
}
.pagination li.active{
	background: #F0F0F0;
	color: #333;
}
</style>

<body>
	
<div class="container main-container headerOffset">

    <!-- Main component call to action --> 

    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
             <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- /.row  -->

    <div class="row">

        <!--left column-->

        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="panel-group" id="accordionNo">
                <div class="panel panel-default">
                  <div class="panel-heading">
                     <h4 class="panel-title">
                        <a class="collapseWill" data-toggle="collapse" href="#collapsePrice">Price <span class="pull-left"> <i class="fa fa-caret-right"></i></span></a>
                        <span class="pull-right clearFilter  label-danger" onClick="clearFilteration(1)"> Clear </span>                  
                    </h4>
                    </div>
                    <div id="collapsePrice" class="panel-collapse collapse in">
                       <div class="panel-body priceFilterBody">
                       <div class="filter-menu">
                           <div class="price-range">
                            <div class="slider-outer">
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
			   
                            <p>Enter a Price range </p>

                            <form class="form-inline" role="form" id="price_frm" name="price_frm">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="min_value" name="min_value"  placeholder="100">
                                </div>
                                <div class="form-group sp"> -</div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="max_value" name="max_value" placeholder="500">
                                </div>
				<div id="error_price_range" style="clear:both;"></div>
                                <button type="button" id="check_btn" onClick="getRadioSelected('button')" class="btn btn-default pull-right">check</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!--/price panel end-->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a data-toggle="collapse" href="#collapseBrand" class="collapseWill">Username <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
				<span class="pull-right clearFilter label-danger" onClick="clearFilteration(3)"> Clear </span> 
			</h4>
                    </div>
                    <div id="collapseBrand" class="panel-collapse collapse in">
                     <div class="panel-body">       
                      <form class="form-inline" role="form">
                        <div class="form-group title_input">
			    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Username">
			    <input type="hidden" class="form-control" id="customer_id" name="customer_id" placeholder="Username">
			</div>
                        <div id="error_customer_title" class="error"></div>
                        <button type="button" onClick="searchCustomer()" class="btn btn-default pull-right">Search</button>
                      </form>
                      </div>
		    </div>
                </div>
                <!--/brand panel end-->
            </div>
        </div>

        <!--right column-->
        <div class="col-lg-9 col-md-9 col-sm-12">
            
	    <div class="w100 productFilter clearfix">
	     <div class="pull-right ">
		<div class="change-order pull-right">
		  <select class="form-control" name="orderby" id="orderby" onChange="filterProducts()">
		    <option value="sort_order-asc" selected="selected">Default sorting</option>
		    <option value="name-asc">Sort by Name(A-Z)</option>
		    <option value="name-desc">Sort by Name(Z-A)</option>
		    <option value="price-asc">Sort by Price(low-high)</option>
		    <option value="price-desc">Sort by Price(high-low)</option>
		    <option value="grade-desc">Sort by Grade(low-high)</option>
		    <option value="grade-asc">Sort by Grade(high-low)</option>
		    <option value="date_added-asc">Sort by Date(Oldest)</option>
		    <option value="date_added-desc">Sort by Date(Latest)</option>
		  </select>
		</div>
		<div class="change-view pull-right">
		  <a href="#" title="Grid" class="grid-view"><i class="fa fa-th-large"></i></a>
		  <a href="#" title="List" class="list-view"><i class="fa fa-th-list"></i></a>
		  <input type="hidden" value="grid-view" id="viewstore">
		</div>
              </div>
	     </div>
            <!--/.productFilter-->
          <div class="loading-div"><img src="image/ajax-loader.gif" ></div>
		  <div id="results"><!-- content will be loaded here --></div>
        </div>
    <!-- /.row  -->
  
   </div>
<!-- /main container -->


</div>


<script type="text/javascript">
    
    
$('input[name=\'customer_name\']').keypress(function(event){
    
    if (event.keyCode==13) {
	searchCustomer();
	return false;
    }
    
    //return false;
   // return event.keyCode != 13;
});    
    
    
    
$(document).ready(function(){
	// Product Name
	$('input[name=\'customer_name\']').autocomplete({
	  'source': function(request, response) {
	   $('input[name=\'customer_name\']').addClass('input-loader');
	    $.ajax({
		    url: 'index.php?route=seller_product/search_result/autocompleteCustomer&filter_name=' +  encodeURIComponent(request),
		    dataType: 'json',			
		    success: function(json) {
		       $('input[name=\'customer_name\']').removeClass('input-loader');
			    response($.map(json, function(item) {
			     
				return {
					label: item['show_name'],
					value: item['value']
				}
			    }));
		    }
	    });
	  },
	  'select': function(item) {
		$('input[name=\'customer_name\']').val(item['label']);
		$('input[name=\'customer_id\']').val(item['value']);
	  }	
	});
})

$(document).ready(function(){
	// Product Name
	$('input[name=\'tilte_book\']').autocomplete({
	  'source': function(request, response) {
	   $('input[name=\'tilte_book\']').addClass('input-loader');
	    $.ajax({
		    url: 'index.php?route=seller_product/product_list/autocompleteTitle&filter_name=' +  encodeURIComponent(request),
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
        this.value = this.value.match(/[0-9]*\.?[0-9]*/);
	
    });
    $("#max_value").keyup(function() {       
        this.value = this.value.match(/[0-9]*\.?[0-9]*/);
	
    });
});
 
$('.grid-view, .list-view').on('click', function(e) {
	e.preventDefault();
	var ClassName = $(this).attr("class");
	$('#viewstore').val(ClassName);
});

</script>

<?php echo $footer; ?>