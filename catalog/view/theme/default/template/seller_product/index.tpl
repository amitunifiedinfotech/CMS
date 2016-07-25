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
		
		var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'per_page_limit':$('#per_page_show').val()};
		
		$(".loading-div").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
			$(".loading-div").hide(); 		//once done, hide loading element
		});



	}
    });

    $('#Priceslider .ui-slider-range').append('<span class="price-range-both value"><i>$' + $('#Priceslider').slider('values', 0) + ' - </i>' + $('#Priceslider').slider('values', 1) + '</span>');

    $('#Priceslider .ui-slider-handle:eq(0)').append('<span class="price-range-min value">$' + $('#Priceslider').slider('values', 0) + '</span>');

    $('#Priceslider .ui-slider-handle:eq(1)').append('<span class="price-range-max value">$' + $('#Priceslider').slider('values', 1) + '</span>');

});

// ......................... End of price Range Filteration ............................
	
	

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
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
			
		//str = getCheckboxSelected();
		str = $('#customer_id').val();
		
		var book_quantity_status = $('#book_quantity_status').val();
		
		
		var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()};
		
		$(".loading-div").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
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
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
	
	var sendData = {"class_value":class_value,'per_page_limit':$('#per_page_show').val()}
	
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(){ 
			$(".loading-div").hide(); 
		});
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination a", function (e){
		e.preventDefault();
		
		// get customer id
		//str = getCheckboxSelected();
		str = $('#customer_id').val();
		
		
		//alert($('#min_value').val());
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
		$(".loading-div").show(); 			//show loading element
		var page = $(this).attr("data-page"); 		//get page number from link
		
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
			
			var book_quantity_status = $('#book_quantity_status').val();
		
		var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()}

		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(){ 
			$(".loading-div").hide(); 
		});
		
	});
});

// ......................... For ordering  ............................
function filterProducts() {
	
	var page = 1;
	
	// get customer id
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
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
		
		var book_quantity_status = $('#book_quantity_status').val();	
	
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()}
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		//alert(html);
		$(".loading-div").hide(); //once done, hide loading element
	});
	
}

// ......................... For pagination  ............................
function change_per_change() {
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
		
		var book_quantity_status = $('#book_quantity_status').val();	
		
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()}
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); //once done, hide loading element
	});	
}


// ......................... For Filteration of customer  ............................
function filter_by_customer(customer_id,role) {

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
		
	var book_quantity_status = $('#book_quantity_status').val();	
	
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()}
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); //once done, hide loading element
	});
	
}

// ......................... For Filteration of Title  ............................
function searchTitle() {

	$('#error_title').html('');
	if ($('#tilte_book').val()=="") {
		$('#error_title').html('Please enter title of the book');
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
	var book_quantity_status = $('#book_quantity_status').val();
	
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()}
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}
// ......................... End of Filteration of Title  ............................



// ......................... For Filteration of Status  ............................
function searchStatus() {

var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
		
	var page = 1;
	
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

	var book_quantity_status = $('#book_quantity_status').val();
	
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()}
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}
// ......................... End of Filteration of Status  ............................




// ......................... For Filteration of Customer  ............................
function searchCustomer() {

	$('#error_customer_title').html('');
	if ($('#customer_id').val()=="") {
		$('#error_customer_title').html('Please enter name of the customer');
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
		
		var book_quantity_status = $('#book_quantity_status').val();
	
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}
// ......................... End of Filteration of Title  ............................

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
		$('.price-range-min').html('<?php echo $min_value;?>');
		$('.price-range-max').html('<?php echo $max_value;?>');

	
	}
	else if(param==3){
		$('#customer_name').val('')
		$('#customer_id').val('')
	}
	else{
		$('#tilte_book').val('');
	}

	var page = 1;
	
	var str = $('#customer_id').val();
	
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
		
		var book_quantity_status = $('#book_quantity_status').val();
	
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':addslashes($('#tilte_book').val()),'book_quantity_status':book_quantity_status,'per_page_limit':$('#per_page_show').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
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

<!--added on 17thjune-->
<div class="full-container top_gap">

        <div class=" image-show-case-wrapper center-block relative">
            <div id="imageShowCase" class="owl-carousel owl-theme">
		
		<?php
		if(!empty($comic_banners)){
			foreach($comic_banners as $each_banner){
		?>
		      <div class="product-slide">
			<div class="box-content-overly box-content-overly-white">
			    <div class="box-text-table">
				<div class="box-text-cell ">
				    <div class="box-text-cell-inner">
					<p><?php echo $each_banner['text'];?></p>
					<a <?php if($each_banner['link']!=""){?> href="<?php echo $each_banner['link'];?>"<?php echo 'target="_blank"'; } ?>  class="slide-link btn-stroke-light">SHOP NOW <span class="arrowUnicode">►</span></a>
				    </div>
				</div>
			    </div>
			</div>
			<a href="#" class="full_widthimg"><img class="img-responsive" src="<?php echo $each_banner['thumb_image'];?>" ></a>
		      </div>
		    <!-- /.product-slide  -->
		<?php
			}
		}
		?>
	    </div>
            <!--/#imageShowCase -->

            <div style="clear:both;"></div>
            <a id="ps-next" class="ps-nav"><img src="image/arrow-right.png" alt="N E X T"></a>
	    <a id="ps-prev" class="ps-nav"><img src="image/arrow-left.png" alt="P R E V"></a>
	</div>
        <!--/.image-show-case-wrapper -->
    
</div>
<!--added on 17th june-->	
    
<div class="container main-container">
    <div class="row">

	<div class="row featuredPostContainer globalPadding style2">
		<h3 class="section-title style2 text-center"><span>FEATURED PRODUCTS</span></h3>
		<div id="featuredslider" class="owl-carousel owl-theme homefirst">
		     
		<?php
		     if(!empty($feature_products)){
			foreach($feature_products as $each_feature_pro){
			   
		?>
		       <div class="item">
			   <div class="product">
			   <?php if($each_feature_pro['now_allow']==0) {?>
			   <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onClick="wishlist.add('<?php echo $each_feature_pro['product_id'];?>');"><i class="glyphicon glyphicon-heart"></i></a>
			   <?php } ?> 
			   <div class="image">
				<div class="quickview"><a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#feature_market<?php echo $each_feature_pro['product_id'];?>" data-toggle="modal"> Quick View </a></div>
				<a href="<?php echo $each_feature_pro['href'];?>"><img src="<?php echo $each_feature_pro['thumb'];?>" alt="img" title="<?php echo ucwords($each_feature_pro['name']);?>" class="img-responsive"></a>
			   </div>
			   <div class="description">
				<h4><a href="<?php echo $each_feature_pro['href'];?>"><?php echo ucwords($each_feature_pro['name']);?></a></h4>
				<p class="bold_me"><?php echo $each_feature_pro['grade'];?></p>
				<p><?php echo $each_feature_pro['page_quality'];?></p>
				<p><?php echo $each_feature_pro['author_name'];?></p>
				<p><?php echo $each_feature_pro['short_description'];?></p>
				 <!--<span class="size"><b>Certification Number : </b><?php echo $each_feature_pro['certification_number'];?></span>-->
			   </div>
			   <div class="price">
			    <?php if (!$each_feature_pro['special']) { ?>
				<span>$<?php echo number_format($each_feature_pro['price'],2);?></span>
			    <?php } else { ?>
				<span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_feature_pro['price'],2);?></span>
				<span>$<?php echo number_format($each_feature_pro['special'],2);?></span>
			    <?php } ?>
			    
			   </div>
			   
			   
			   <?php if($each_feature_pro['now_allow']==0) {?>
			    <?php
				
					if($each_feature_pro['quantity']<=0)
					{
				?>
						 <div class="action-control"><a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a></div>
						 <?php	
					}
					else
					{
					?>
					<div class="action-control"><a class="btn btn-primary" onClick="cart.add('<?php echo $each_feature_pro['product_id'];?>');" > <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
					<?php
					}
			   }?>
			   </div>
		       </div>     
		     
		<?php
			}
		     }
		?>
		</div>
	
	     
	  </div>



        <!--left column-->

        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="panel-group" id="accordionNo">

                <div class="panel panel-default">
                    <div class="panel-heading">
                     <h4 class="panel-title">
                        <a class="collapseWill" data-toggle="collapse" href="#collapsePrice">Price <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
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
				<div id="error_price_range" class="error" style="clear:both;"></div>
                                <button type="button" id="check_btn" onClick="getRadioSelected('button')" class="btn btn-default pull-right">check</button>
                            </form>
			    <div style="clear: both;"></div>
			    
                        </div>
                    </div>
                </div>
	      <div class="panel panel-default">
		<div class="panel-heading">
                     <h4 class="panel-title">
                        <a class="collapseWill" data-toggle="collapse" href="#collapseSearch">Search By Seller <span class="pull-left"><i class="fa fa-caret-right"></i></span> </a>
                        <span class="pull-right clearFilter label-danger" onClick="clearFilteration(3)"> Clear </span>                    
                     </h4>
                </div>
                <div id="collapseSearch" class="panel-collapse collapse in">
                    <div class="panel-body">       
                      <form class="form-inline" role="form">
                        <div class="form-group title_input">
			    <input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Seller username">
			    <input type="hidden" class="form-control" id="customer_id" name="customer_id" placeholder="Customer Name">
			</div>
                        <div id="error_customer_title" class="error"></div>
                        <button type="button" onClick="searchCustomer()" class="btn btn-default pull-right">Search</button>
                      </form>
                      </div>
                </div>
	      </div>
               
               <div class="panel panel-default">
				<div class="panel-heading">
							 <h4 class="panel-title">
								<a class="collapseWill" data-toggle="collapse" href="#collapseBook">Search Book <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
								<span class="pull-right clearFilter label-danger" onClick="clearFilteration(2)"> Clear </span>                    
							 </h4>
						</div>
				<div id="collapseBook" class="panel-collapse collapse in">
							<div class="panel-body">       
							  <form class="form-inline" role="form">
								<div class="form-group title_input">
						<input type="text" class="form-control" id="tilte_book" name="tilte_book" placeholder="Title of Book">
						<input type="hidden" class="form-control" id="customer_id" name="customer_id" placeholder="Customer Name">
					</div>
								<div id="error_title" class="error"></div>
								<button type="button" onClick="searchTitle()" class="btn btn-default pull-right">Search</button>
							  </form>
							  </div>
					</div>              
                </div>
			   
			   <div class="panel panel-default">
				<div class="panel-heading">
							 <h4 class="panel-title">
								<a class="collapseWill" data-toggle="collapse" href="#collapseBook">Search by Status <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
							 </h4>
						</div>
						<div id="collapseBook" class="panel-collapse collapse in">
									<div class="panel-body">       
									  <form class="form-inline" role="form">
										<div class="form-group title_input">
									<select class="form-control" name="book_quantity_status" id="book_quantity_status"><option value="1" selected="selected">Available</option><option value="0">Sold</option></select>	
							</div>
										<div id="error_title" class="error"></div>
										<button type="button" onClick="searchStatus()" class="btn btn-default pull-right">Search</button>
									  </form>
									  </div>
							</div>              
                </div>
                
                
            </div>
        </div>

        <!--right column-->
        <div class="col-lg-9 col-md-9 col-sm-12">
	    <div class="w100 productFilter clearfix store-filter">
		<div class="change-order pull-left">
		    <select class="form-control" name="per_page_show" id="per_page_show" onChange="change_per_change()">
		      <option value="6">6</option>
		      <option value="9">9</option>
		      <option value="18">18</option>
		      <option value="30">30</option>
		      <option value="50">50</option>
		    </select>
		</div>
                
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


	<!-- ****************************************** For marketlist ****************************************** --> 
	<?php
	if(!empty($feature_products)){
	  foreach($feature_products as $each_feature_pro){
            
	?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="feature_market<?php echo $each_feature_pro['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
                <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
              
                  <!-- product Image -->
                  <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_feature_pro['href'];?>"><img src="<?php echo $each_feature_pro['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
                  <!--/.main-image-->
                  
                  <div class="modal-product-thumb">
                      <a class="thumbLink selected"><img data-large="<?php echo $each_feature_pro['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_feature_pro['model_main_img'];?>"></a>
                      <a class="thumbLink"><img data-large="<?php echo $each_feature_pro['model_main_img1'];?>" alt="img" class="img-responsive" src="<?php echo $each_feature_pro['model_main_img1'];?>"> </a>
                  </div>
                </div>
                <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_feature_pro['name']);?></h1>
                  <h3 class="product-code"><strong>Grade: <?php echo $each_feature_pro['grade'];?></strong></h3>
                  <h3 class="product-code"><strong>Page Quality: </strong><?php echo $each_feature_pro['page_quality'];?></h3>
                  <h3 class="product-code"><strong>Serial Number: </strong><?php echo $each_feature_pro['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_feature_pro['publisher'];?></h3>
                  <h3 class="product-code"><strong>Seller: </strong><?php echo $each_feature_pro['author_name'];?></h3>
                  <div class="product-price">
		    <?php if (!$each_feature_pro['special']) { ?>
			<span class="price-sales">$<?php echo number_format($each_feature_pro['price'],2);?></span>
		    <?php } else { ?>
			<span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_feature_pro['price'],2);?></span>
			<span class="price-sales">$<?php echo number_format($each_feature_pro['special'],2);?></span>
			    <span class="price-sales red_color"> <?php $discount_amnt = ($each_feature_pro['price'] - $each_feature_pro['special']); echo '('.round(($discount_amnt/$each_feature_pro['price'] ) *100); ?>%) Savings</span>
		    <?php } ?>
		  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_feature_pro['description'],ENT_QUOTES, 'UTF-8');//echo $each_feature_pro['short_description'];?></p></div>
               <?php if($each_feature_pro['now_allow']==0) {?>
	       	    <p id="show_err_quan<?php echo $each_feature_pro['product_id'];?>" class="show_quan_err"></p>
	       <!--<input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_feature_pro['product_id'];?>" id="quantity_choose<?php echo $each_feature_pro['product_id'];?>" placeholder="Quantity" />-->
                    <!-- productFilter -->         
                    
					
                  <div class="cart-actions">
                  <div class="addto">
				<?php
				if($each_feature_pro['quantity']>0)
				{
				?>
					<button class="button btn-cart cart first" onClick="cart.add('<?php echo $each_feature_pro['product_id'];?>')" title="Add to Cart" type="button" data-dismiss="modal">Add to Cart </button>
				<?php
				}
				else
				{
					?>
					<button class="button btn-cart cart sold" title="SOLD" type="button">SOLD</button>
					<?php
				}
				?>
			
		   <!-- <button class="button btn-cart cart first" onClick="checkAvailibity('<?php echo $each_feature_pro['product_id'];?>',$('#quantity_choose<?php echo $each_feature_pro['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>-->
			
			<!--<button class="button btn-cart cart first" onClick="checkAvailibity('<?php echo $each_feature_pro['product_id'];?>',$('#quantity_choose<?php echo $each_feature_pro['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>-->
		    
			<a class="link-wishlist wishlist" onClick="wishlist.add('<?php echo $each_feature_pro['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                  </div>
                  </div>
                    <!--/.cart-actions-->
               <?php }?>  
               </div>
              <!--/.modal-details-inner--> 
            </div>
                <!--/.modal-details-->
                <div class="clear"></div>
            </div>
          <!--/.modal-content--> 
        </div>
        <!--/.modal-content--> 
      </div>
      <!-- End Modal -->
            
	<?php
            }
	}
	?>    
    <!-- ****************************************** For marketlist ****************************************** -->


</div>


<script type="text/javascript">
$('input[name=\'customer_name\']').keypress(function(event){
    
    if (event.keyCode==13) {
	//searchCustomer();
	return false;
    }  
});
$('input[name=\'tilte_book\']').keypress(function(event){
    
    if (event.keyCode==13) {
	searchTitle();
	return false;
    }  
});

$('input[name=\'book_quantity_status\']').keypress(function(event){
    
    if (event.keyCode==13) {
	searchTitle();
	return false;
    }  
});
    
$('input[name=\'max_value\']').keypress(function(event){
    if (event.keyCode==13) {
	getRadioSelected('button');
    }  
});    
      
$('input[name=\'min_value\']').keypress(function(event){
    if (event.keyCode==13) {
	getRadioSelected('button');
    }  
});     
    
    
$(document).ready(function(){
	// Product Name
	$('input[name=\'customer_name\']').autocomplete({
	  'source': function(request, response) {
	   $('input[name=\'customer_name\']').addClass('input-loader');
	    $.ajax({
		    url: 'index.php?route=seller_product/market_list/autocompleteCustomer&filter_name=' +  encodeURIComponent(request),
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
		    url: 'index.php?route=seller_product/market_list/autocompleteTitle&filter_name=' +  encodeURIComponent(request),
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

<style>
  #orderby{
    display: block !important;
  }
  .quantity_choose{
    display: block !important;
  }
  #per_page_show{
    display: block !important;	
  }
</style>


<?php echo $footer; ?>