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

		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
		
		$(".loading-div").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
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
			
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
		
		$(".loading-div").show();
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
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
		
	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),'per_page_limit':$('#per_page_show').val()};
	$("#results" ).load( "<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData,function(){
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
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};

		//show loading element
		$(".loading-div-preorder").show(); 		
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-preorder").hide(); 		
		});
		
	});
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-1 a", function (e){
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
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page1":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};

		//show loading element
		$(".loading-div-newlease").show(); 	
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-newlease").hide(); 	
		});
		
	});

	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-2 a", function (e){
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
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page2":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};

		//show loading element
		$(".loading-div-backissue").show();
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-backissue").hide();
		});
		
	});

	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-3 a", function (e){
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
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page3":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};

		//show loading element
		$(".loading-div-marketstreet").show();
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-marketstreet").hide();
		});
		
	});

	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-4 a", function (e){
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
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page4":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};

		//show loading element
		$(".loading-div-page4").show(); 		
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-page4").hide(); 		
		});
		
	});

	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-5 a", function (e){
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
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page5":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};

		//show loading element
		$(".loading-div-page5").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-page5").hide(); 
		});
		
	});
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination-6 a", function (e){
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
		
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page6":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};

		//show loading element
		$(".loading-div-page6").show();
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(){ 
			$(".loading-div-page6").hide();
		});
		
	});
	


	


});

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
	
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
		
	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
		//alert(html);
		$(".loading-div").hide(); //once done, hide loading element
	});	
}

// ......................... For pagination  ............................
function change_per_change() {
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
	
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
		
	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
		//alert(html);
		$(".loading-div").hide(); //once done, hide loading element
	});	
}

function searchTitle() {

	$('#error_title').html('');
	if ($('#tilte_book').val()=="") {
		$('#error_title').html('Please enter title of the book');
		return false;
	}
	
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

	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}

function searchPublisher() {

	$('#error_title').html('');
	if ($('#publisher').val()=="") {
		$('#error_publisher').html('Please enter title of the book');
		return false;
	}
	
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
		
	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}


function searchComingSoon() {

	$('#error_coming').html('');
	if ($('#is_coming_soon').val()=="") {
		$('#error_coming').html('Please choose new release filteration');
		return false;
	}
	
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
		
	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
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
	else if(param==4){
		$('#is_coming_soon').val('');
	}
	else{
		$('#tilte_book').val('');
	}

	var page = 1;
	
	str = getRadioSelected();
	
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
		
	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); //once done, hide loading element
	});
}

$(window).load(function() {
	$('.banner #pager a:nth-child(1)').attr('id', 'new_release');
	$('.banner #pager a:nth-child(2)').attr('id', 'backrelease');
	$('.banner #pager a:nth-child(3)').attr('id', 'pre_order');
	
	$('.banner #pager a').on('click', function(e) {
		e.preventDefault();
		var TabId = $(this).attr('id');
		$('#tabstore').val(TabId);
		//alert(TabId);
		
		$('.nav-tabs > li').removeClass('active');
		
		$(this).parent('li').addClass('active');
		
		$('#tilte_book').val('');
		
		// Fire ajax to get Data
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
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
			
		var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
		
		$(".loading-div").show(); 
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
			//alert(html);
			$(".loading-div").hide(); //once done, hide loading element
		});
		
		
	});
});



function searchLabel(param) {

	$('#tabstore').val(param);

	$('#tilte_book').val('');

	$('ul#pager a').removeClass('cycle-pager-active');

	// remove active class from all category tab
	$('.all_new_tab').removeClass('active');

	// Add to a particular tab
	$('.additional'+param).addClass('active');
	
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
		
	var sendData = {"class_value":class_value,"param":$('#tabstore').val(),"page":page,"orderby":$('#orderby').val(),"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val(),'publisher':$('#publisher').val(),'per_page_limit':$('#per_page_show').val(),'is_coming_soon':$('#is_coming_soon').val()};
	
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/comic_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}
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

<div class="banner">
    <div class="full-container">
        <div class="slider-content">
          <ul id="pager" class="container"></ul>
            <div class="slider slider-v2">
		<?php 
		if(!empty($comic_banners)){
			$i=1;
			foreach($comic_banners as $each_banner){
				if($i==3)
					$show_text = 'Pre Order';
				else if($i==2)
					$show_text = 'Back Issues';
				else
					$show_text = 'New Releases';
		?>
                <div class="slider-item slider-item-img1 " data-cycle-pager-template="<a href='#'><?php echo $show_text;?></a>">
                    <div class="sliderInfo">
                        <div class="container">
                            <div class="col-lg-5 col-md-5 col-sm-5 pull-right sliderText dark alpha80 hidden-xs">
                                <div class="inner">
                                    <h1><?php echo $each_banner['text_title'];?></h1>
                                    <p class="hidden-xs"> <?php echo $each_banner['text'];?></p>
                                    <a <?php if($each_banner['link']!=""){?> href="<?php echo $each_banner['link'];?>"<?php echo 'target="_blank"'; } ?>  class="slide-link">SHOP NOW  <span class="arrowUnicode">►</span></a>
								</div>
                            </div>
                        </div>
		   			</div>
                   <img class="img-responsive" src="<?php echo $each_banner['thumb_image'];?>" alt="img">
				</div>
        <?php
			$i++;
			}
		}
		?>
            </div>
            <!--/.slider-v2-->
        </div>
        <!--/.slider-content-->
    </div>
    <!--/.full-container-->
</div>
<!--/.banner style2-->

<div class="banner-bot-nav">
    <ul class="nav nav-tabs" role="tablist">
    <?php foreach ($label_settings_dtl as $key => $label_value) { ?>
        <li class="additional<?php echo $key; ?> all_new_tab" data-scroll="tabcat"><a id="<?php echo $key; ?>" href="javascript:void(0)" onclick="searchLabel('<?php echo $key; ?>')"><?php echo $label_value; ?></a></li>                
    <?php }?> 
     
      <input type="hidden" value="new_release" id="tabstore">
    </ul>
</div>

<div class="container main-container">

    <!-- Main component call to action --> 

    <!--<div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>-->
    <!-- /.row  -->
    
      
	



   <div class="globalPadding">
       
     <!-- Featured -->
     <div class="row featuredPostContainer style2">
	    <h3 class="section-title style2 text-center"><span>FEATURED PRODUCTS</span></h3>
		<div id="featuredslider" class="owl-carousel owl-theme">
		     
		     <?php
		     if(!empty($feature_products)){
			   foreach($feature_products as $each_feature_pro){
			   
		     ?>
		       <div class="items">
			   <div class="product">
			<?php  if($each_feature_pro['now_allow']==0) {?>
			   <a class="add-fav tooltipHere" data-toggle="tooltip" data-original-title="Add to Wishlist" data-placement="left" onClick="wishlist.add('<?php echo $each_feature_pro['product_id'];?>');"><i class="glyphicon glyphicon-heart"></i></a>
			<?php } ?>   
			   
			   <div class="image">
				<div class="quickview"> <a title="Quick View" class="btn btn-xs  btn-quickview" data-target="#feature_comic<?php echo $each_feature_pro['product_id'];?>" data-toggle="modal"> Quick View </a></div>
				<a href="<?php echo $each_feature_pro['href'];?>"><img src="<?php echo $each_feature_pro['thumb'];?>" alt="img" title="<?php echo ucwords($each_feature_pro['name']);?>" class="img-responsive"></a>
			   </div>
			   <div class="description">
				<h4><a href="<?php echo $each_feature_pro['href'];?>"><?php echo ucwords($each_feature_pro['name']);?></a></h4>
				
				<p><?php echo $each_feature_pro['short_description'];?></p>				
				 <!--<span class="size"><b>Certification Number : </b><?php echo $each_feature_pro['certification_number'];?></span>-->
			   </div>
				<p><?php echo $each_feature_pro['certification_number'];?></p>
				<p><?php echo $each_feature_pro['publisher'];?></p>
				<div class="price">
				    <?php if (!$each_feature_pro['special']) { ?>
					<span>$<?php echo number_format($each_feature_pro['price'],2);?></span>
				    <?php } else { ?>
					<span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_feature_pro['price'],2);?></span>
					<span>$<?php echo number_format($each_feature_pro['special'],2);?></span>
				    <?php } ?>
					
				</div>
				<?php  if($each_feature_pro['now_allow']==0 && $each_feature_pro['coming_value']==0) {?>
				<div class="action-control">
					<?php
					
					if($each_feature_pro['quantity']>0)
					{
					?>
					<a class="btn btn-primary" onClick="cart.add('<?php echo $each_feature_pro['product_id'];?>');" > <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a>
					<?php
					}
					else
					{
						?>
						<a class="btn btn-danger"> <span class="add2cart"> SOLD </span> </a>
						<?php
					}
					?>
				</div>
				<?php }else if($each_feature_pro['coming_value']==1){?>
				<div class="action-control"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon... </span> </a></div>
				<?php } ?> 
			   </div>
		       </div>     
		     
		     <?php
			   }
		     }
		     ?>
		</div>
	</div>
   
<!-- ****************************************** For Comic List Model Box ****************************************** --> 
	<?php
	if(!empty($feature_products)){
	      foreach($feature_products as $each_feature_pro){
            
	?>
      
      <!-- Product Details Modal  -->
      <div class="modal fade product-details-modal" id="feature_comic<?php echo $each_feature_pro['product_id'];?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
                <button aria-hidden="true" data-dismiss="modal" class="close" type="button"> ×</button>
                <div class="col-lg-5 col-md-5 col-sm-5  col-xs-12"> 
              
                  <!-- product Image -->
                  <div class="main-image col-lg-12 no-padding style3"><a class="product-largeimg-link" href="<?php echo $each_feature_pro['href'];?>"><img src="<?php echo $each_feature_pro['model_main_img'];?>" class="img-responsive product-largeimg"  alt="img"></a></div>
                  <!--/.main-image-->
                  
                  <div class="modal-product-thumb">
                      <a class="thumbLink selected"><img data-large="<?php echo $each_feature_pro['model_main_img'];?>" alt="img" class="img-responsive" src="<?php echo $each_feature_pro['model_main_img'];?>"></a>
                  </div>
                </div>
                <!--/ product Image-->
                
            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12 modal-details no-padding">
              <div class="modal-details-inner">
                  <h1 class="product-title"> <?php echo ucwords($each_feature_pro['name']); if($each_feature_pro['variant']) echo ' ('.$each_feature_pro['variant'].')'?></h1>
                  <h3 class="product-code"><strong>product-code: </strong><?php echo $each_feature_pro['certification_number'];?></h3>
                  <h3 class="product-code"><strong>Publisher: </strong><?php echo $each_feature_pro['publisher'];?></h3>
                  <div class="product-price">
		     <?php if (!$each_feature_pro['special']) { ?>
			<span>$<?php echo number_format($each_feature_pro['price'],2);?></span>
		    <?php } else { ?>
			    <span class="price_normal" style="text-decoration: line-through;">$<?php echo number_format($each_feature_pro['price'],2);?></span>
			    <span>$<?php echo number_format($each_feature_pro['special'],2);?></span><br/>
			    <span class="price-sales red_color"> <?php $discount_amnt = ($each_feature_pro['price'] - $each_feature_pro['special']); echo '('.round(($discount_amnt/$each_feature_pro['price'] ) *100); ?>%) Savings</span>
		    <?php } ?>
		  </div>
                  <div class="details-description"><p><?php echo html_entity_decode($each_feature_pro['description'],ENT_QUOTES, 'UTF-8');//echo $each_feature_pro['short_description'];?></p></div>
               <?php if($each_feature_pro['now_allow']==0 && $each_feature_pro['coming_value']==0) {?> 
	       	<p id="show_err_quan<?php echo $each_feature_pro['product_id'];?>" class="show_quan_err"></p>
			<?php
			if($each_feature_pro['quantity']>0)
			{
			?>
				<input class="quantity_pro" type="text" name="quantity_choose<?php echo $each_feature_pro['product_id'];?>" id="quantity_choose<?php echo $each_feature_pro['product_id'];?>" placeholder="Quantity" />
			<?php
			}
			?>
                <div class="cart-actions">
                  <div class="addto">
					<?php
					if($each_feature_pro['quantity']>0)
					{
					?>
                    <button class="button btn-cart cart first" onclick="checkAvailibity('<?php echo $each_feature_pro['product_id'];?>',$('#quantity_choose<?php echo $each_feature_pro['product_id'];?>').val())" title="Add to Cart" type="button">Add to Cart </button>    				  <?php
					}
					else
					{
						?>
					<button type="button" title="SOLD" class="button btn-cart cart sold">SOLD</button>
						<?php
					}
					?>
			
                    <a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_feature_pro['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a>
                  </div>
                </div>
                    <!--/.cart-actions-->
               <?php } else if($each_feature_pro['coming_value']==1){?>
               <div class="btnboxbase">
		<div class="action-control left_across"><a class="btn btn-primary coming_soon"><span class="add2cart">Coming Soon... </span> </a></div>
		<div class="cart-actions left_across"><div class="addto"><a class="link-wishlist wishlist" onclick="wishlist.add('<?php echo $each_feature_pro['product_id'];?>')" data-dismiss="modal">Add to Wishlists</a></div></div>
        </div>
              <?php } ?>  
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
    <!-- ****************************************** For Comic List Model Box ****************************************** -->

    <div class="row">
        <!--left column-->
        <div class="col-lg-3 col-md-3 col-sm-12">
            <div class="panel panel-default" id="accordionNo">
                <div class="panel-heading">
                    <h4 class="panel-title">
					  <a class="collapseWill" data-toggle="collapse" href="#collapsePrice">Price <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
					  <span class="pull-right clearFilter  label-danger" onclick="clearFilteration(1)"> Clear </span>
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
                  </div>
                </div>
            </div>               
		    <div class="panel panel-default">
				<div class="panel-heading">
				    <h4 class="panel-title">
				      <a class="collapseWill" data-toggle="collapse" href="#collapseSearch">Search By Comic <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
					<span class="pull-right clearFilter label-danger" onClick="clearFilteration(2)"> Clear </span>              
				    </h4>
				</div>
				<div id="collapseSearch" class="panel-collapse collapse in">
		            <div class="panel-body">       
		              <form class="form-inline" role="form">
		                <div class="form-group title_input">
						    <input type="text" class="form-control" id="tilte_book" name="tilte_book" placeholder="Title of Book">
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
				      <a class="collapseWill" data-toggle="collapse" href="#collapseSearchPublish">Search By Publisher<span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
				      <span class="pull-right clearFilter label-danger" onClick="clearFilteration(3)"> Clear </span>              
				    </h4>
				</div>
				<div id="collapseSearchPublish" class="panel-collapse collapse in">
		            <div class="panel-body">       
		              <form class="form-inline" role="form">
		                <div class="form-group title_input">
						    <input type="text" class="form-control" id="publisher" name="publisher" placeholder="Publisher of Book">
						</div>
		                <div id="error_publisher" class="error"></div>
		                <button type="button" onClick="searchPublisher()" class="btn btn-default pull-right">Search</button>
		              </form>
		            </div>
				</div>                              	
	        </div>
	        <div class="panel panel-default">
				<div class="panel-heading">
				    <h4 class="panel-title">
				      <a class="collapseWill" data-toggle="collapse" href="#collapseSearchComing">Additional Filters<span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
				      <span class="pull-right clearFilter label-danger" onClick="clearFilteration(4)"> Clear </span>              
				    </h4>
				</div>
				<div id="collapseSearchComing" class="panel-collapse collapse in">
		            <div class="panel-body">       
		              <form class="form-inline" role="form">
		                <div class="form-group title_input">
						    <select class="form-control" name="is_coming_soon" id="is_coming_soon">
						      <option value="" selected="selected">Select Additional Filter</option>
						      <option value="1">Coming Soon</option>
						      <option value="0">Available to Purchase</option>						      
						    </select>
						</div>
		                <div id="error_coming" class="error"></div>
		                <button type="button" onClick="searchComingSoon()" class="btn btn-default pull-right">Search</button>
		              </form>
		            </div>
				</div>                              	
	        </div>
	    </div>
        

        <!--right column-->
        <div class="col-lg-9 col-md-9 col-sm-12" style="position:relative;">
        	<div style="position:absolute; left:0; top:-100px; width:1px; height:1px;" data-anchor="tabcat"></div>
		    <div class="w100 productFilter clearfix store-filter">
	            <div class="change-order selectStyle pull-left selectOrderBox">
				    <select class="form-control" name="per_page_show" id="per_page_show" onChange="change_per_change()">
				      <option value="6">6</option>
				      <option value="9">9</option>
				      <option value="18">18</option>
				      <option value="30">30</option>
				      <option value="50">50</option>
				    </select>
				</div>
				
	            <div class="pull-right right-alng">
				  <div class="change-order selectStyle pull-right">
				    <select class="form-control" name="orderby" id="orderby" onChange="filterProducts()">
				      <option value="sort_order-asc" selected="selected">Default sorting</option>
				      <option value="name-asc">Sort by Name(A-Z)</option>
				      <option value="name-desc">Sort by Name(Z-A)</option>
				      <option value="price-asc">Sort by Price(low-high)</option>
				      <option value="price-desc">Sort by Price(high-low)</option>
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
    
    </div><!-- /.row  -->
 
   
   </div>
</div>
<!-- /main container -->





<script type="text/javascript">
    
$('input[name=\'publisher\']').keypress(function(event){
    
    if (event.keyCode==13) {
	searchPublisher();
	return false;
    }  
});    
      
    
$('input[name=\'tilte_book\']').keypress(function(event){
    
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
	// Publisher Name
	$('input[name=\'publisher\']').autocomplete({
	  'source': function(request, response) {
	   $('input[name=\'publisher\']').addClass('input-loader');
	   
	// Check release
	if ($('#tabstore').val()=='backrelease') {
		param_value = 0;
	}
	else if ($('#tabstore').val()=='new_release') {
		param_value = 1;
	}
	if ($('#tabstore').val()=='pre_order') {
		param_value = 2;
	}
	   
	$.ajax({
		url: 'index.php?route=seller_product/comic_list/autocompletePublisher&filter_name=' +  encodeURIComponent(request)+'&param='+param_value,
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
	   
	// Check release
	if ($('#tabstore').val()=='backrelease') {
		param_value = 0;
	}
	else if ($('#tabstore').val()=='new_release') {
		param_value = 1;
	}
	if ($('#tabstore').val()=='pre_order') {
		param_value = 2;
	}
	   
	   
	    $.ajax({
		    url: 'index.php?route=seller_product/comic_list/autocompleteTitle&filter_name=' +  encodeURIComponent(request)+'&param='+param_value,
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

$('.grid-view, .list-view').on('click', function(e) {
	e.preventDefault();
	var ClassName = $(this).attr("class");
	//alert(ClassName);

	$('.comic_des_grid').css("display","none");
	$('.comic_des_list').css("display","none");
	if(ClassName=="grid-view")
		$('.comic_des_grid').css("display","block");	
	else
		$('.comic_des_list').css("display","block");

	
	$('#viewstore').val(ClassName);
});


</script>
<style>
  .quantity_choose{
    display: block !important;
  }
  #per_page_show{
    display: block !important;	
  }
  #is_coming_soon{
    display: block !important;
	width: 100%;
	margin-bottom: 10px;
  }
  .minict_wrapper{
	display:none !important;
  }
</style>


<?php echo $footer; ?>