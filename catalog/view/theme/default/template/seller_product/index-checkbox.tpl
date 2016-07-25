<?php echo $header; ?>

<script type="text/javascript">
	
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
		
		// Turn off range selection
		$('.radiobutton').each(function() {
			$(this).prop('checked',false);
			
		});
		
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
			
		str = getCheckboxSelected();
		$(".loading-div").show(); 
		var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val()}
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
			$(".loading-div").hide();
			
		});
		
	}
	else{
		$('.radiobutton').each(function() {	
			if ($(this).is(":checked")) {
				radio_price = $(this).val();
			}
		});
		
		
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
	
	var sendData = {"class_value":class_value}
	
	//$("#results" ).load( "<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData,success); //load initial records
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(){ 
			$(".loading-div").hide(); 
		});
	
	//executes code below when user click on pagination links
	$("#results").on( "click", ".pagination a", function (e){
		e.preventDefault();
		
		// get customer id
		str = getCheckboxSelected();
		
		
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
		
		var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val()}

		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(){ 
			$(".loading-div").hide(); 
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

		
		str = getCheckboxSelected();
		var class_value;
		if ($('#viewstore').val()=='list-view') {
			class_value = 'list-view';
		}
		else
			class_value = '';
		
		$(".loading-div").show(); 
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val()}
		$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
			$(".loading-div").hide(); 
		});
		
		
		
	});
});

// ......................... For ordering  ............................
function filterProducts() {
	
	var page = 1;
	
	// get customer id
	str = getCheckboxSelected();
	
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
	
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val()}
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		//alert(html);
		$(".loading-div").hide(); //once done, hide loading element
	});
	
}

// ......................... For Filteration of customer  ............................
function filter_by_customer(customer_id,role) {

	str = getCheckboxSelected();
	
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
	
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val()}
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
	
	str = getCheckboxSelected();
	
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
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val()}
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); 		//once done, hide loading element
	});
	
}


function clearFilteration(param){
	str = getCheckboxSelected();
	
	if(param==1){
		// set price to zero
		min_price = '';
		max_price = '';	
		
		// uncheck all price range and blank text box
		$('#min_value').val('');
		$('#max_value').val('');
		
		$('.radiobutton').each(function() {	
			$(this).attr('checked',false);
		});	
	}
	else{
		$('#tilte_book').val('');
	}

	var page = 1;
	var class_value;
	if ($('#viewstore').val()=='list-view') {
		class_value = 'list-view';
	}
	else
		class_value = '';
	var sendData = {"class_value":class_value,"page":page,"orderby":$('#orderby').val(),"customer_filter":str,"min_price":min_price,"max_price":max_price,'title_book':$('#tilte_book').val()}
	$(".loading-div").show(); 
	$("#results").load("<?php echo SITE_URL;?>index.php?route=seller_product/market_list/loadcontent",sendData, function(html){
		$(".loading-div").hide(); //once done, hide loading element
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
    
    <div class="parallax-section parallax-image-1 cosmic_para" style="background-image: url('image/<?php echo $market_page_banner;?>');">
        <div class="row ">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="parallax-content clearfix">
			<?php echo html_entity_decode($market_page_banner_text);?>
                    <div style="clear:both"></div>
                    <a class="btn btn-discover" href="<?php echo $market_page_banner_link;?>" target="_blank"> <i class="fa fa-shopping-cart"></i> SHOP NOW </a>
		</div>
            </div>
        </div>
        <!--/.row-->
</div>

    <div class="row">

	<div class="row featuredPostContainer globalPadding style2">
		<h3 class="section-title style2 text-center"><span>FEATURES PRODUCT</span></h3>
		<div id="featuredslider" class="owl-carousel owl-theme">
		     
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
				<a href="<?php echo $each_feature_pro['href'];?>"><img src="<?php echo $each_feature_pro['thumb'];?>" alt="img" title="<?php echo ucwords($each_feature_pro['name']);?>" class="img-responsive"></a>
			   </div>
			   <div class="description">
				 <h4><a href="<?php echo $each_feature_pro['href'];?>"><?php echo ucwords($each_feature_pro['name']);?></a></h4>
				  <p><?php echo $each_feature_pro['grade'];?><br>
				  <?php echo $each_feature_pro['page_quality'];?><br>
				  <?php echo $each_feature_pro['author_name'];?></p>
				 <p><?php echo $each_feature_pro['description'];?></p>
				 <!--<span class="size"><b>Certification Number : </b><?php echo $each_feature_pro['certification_number'];?></span>-->
			   </div>
			   <div class="price"><span>$<?php echo number_format($each_feature_pro['price'],2);?></span></div>
			   <?php if($each_feature_pro['now_allow']==0) {?>
			   <div class="action-control"><a class="btn btn-primary" onClick="cart.add('<?php echo $each_feature_pro['product_id'];?>');" > <span class="add2cart"><i class="glyphicon glyphicon-shopping-cart"> </i> Add to cart </span> </a></div>
			   <?php }?>
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
                        <?php
                            if(!empty($price)){
                            for($i=1;$i<count($price);$i++){
                            
                        ?>
                        <div class="custelem">
                           <input type="radio" id="pricetag<?php echo $i;?>" class="radiobutton" name="price_filter" value="<?php echo $price[$i-1].'-'.$price[$i];?>"/>
                           <label for="pricetag<?php echo $i;?>">$<?php echo number_format($price[$i-1],2);?> - $<?php echo number_format($price[$i],2);?></label>
                          </div>
                        <?php
                            }
			    		}
                        ?>
			   
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
                <!--/price panel end-->

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><a data-toggle="collapse" href="#collapseBrand" class="collapseWill">Customer <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a></h4>
                    </div>
                    <div id="collapseBrand" class="panel-collapse collapse in">
                        <div class="panel-body smoothscroll maxheight300">
                        <?php
                        if(!empty($customer)){
                            foreach($customer as $each_cust){
                        ?>
                            <div class="block-element">
                            	<div class="custelem">
                                <input type="checkbox" class="checkbox1" name="customer_filter[]" id="customer_filter<?php echo $each_cust['id']?>" onClick="filter_by_customer('<?php echo $each_cust['id']?>','<?php echo $each_cust['role']?>')" value="<?php echo $each_cust['id'].'_'.$each_cust['role']?>"/> 
                                <label for="customer_filter<?php echo $each_cust['id']?>"> <?php echo $each_cust['name'].' ('.$each_cust['product_count'].')';?> </label>
                                </div>
                            </div>
                        <?php
                            }
                        }
                        ?>
                            
                        </div>
                    </div>
                </div>
                <!--/brand panel end-->
		
	      <div class="panel panel-default">
		<div class="panel-heading">
                     <h4 class="panel-title">
                        <a class="collapseWill" data-toggle="collapse" href="#collapseSearch">Search Customer <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
                        <span class="pull-right clearFilter label-danger" onClick="clearFilteration(3)"> Clear </span>                    
                     </h4>
                </div>
                <div id="collapseSearch" class="panel-collapse collapse in">
                    <div class="panel-body">       
                      <form class="form-inline" role="form">
                        <div class="form-group title_input">
				<input type="text" class="form-control" id="customer_name" name="customer_name" placeholder="Customer Name">
				<input type="text" class="form-control" id="customer_id" name="customer_id" placeholder="Customer Name">
			</div>
                        <div id="error_title" class="error"></div>
                        <button type="button" onClick="searchCustomer()" class="btn btn-default pull-right">Search</button>
                      </form>
                      </div>
                  </div>
	        </div>
               
               <div class="panel panel-default">
               <div class="panel-heading">
                     <h4 class="panel-title">
                        <a class="collapseWill" data-toggle="collapse" href="#collapseSearch">Search <span class="pull-left"> <i class="fa fa-caret-right"></i></span> </a>
                        <span class="pull-right clearFilter label-danger" onClick="clearFilteration(2)"> Clear </span>                    
                     </h4>
                </div>
                <div id="collapseSearch" class="panel-collapse collapse in">
                    <div class="panel-body">       
                      <form class="form-inline" role="form">
                        <div class="form-group title_input"><input type="text" class="form-control" id="tilte_book" name="tilte_book" placeholder="Title of Book"></div>
                        <div id="error_title" class="error"></div>
                        <button type="button" onClick="searchTitle()" class="btn btn-default pull-right">Search</button>
                      </form>
                      </div>
                  </div>
                </div>
                
                
            </div>
        </div>

        <!--right column-->
        <div class="col-lg-9 col-md-9 col-sm-12">
                        
	    <div class="w100 productFilter clearfix store-filter">
                
                <div class="pull-right ">
		  <div class="change-order pull-right">
		    <select class="form-control" name="orderby" id="orderby" onChange="filterProducts()">
		      <option value="sort_order-asc" selected="selected">Default sorting</option>
		      <option value="name-asc">Sort by Name(A-Z)</option>
		      <option value="name-desc">Sort by Name(Z-A)</option>
		      <option value="price-asc">Sort by price(low-high)</option>
		      <option value="price-desc">Sort by price(high-low)</option>
		      <option value="grade-asc">Sort by grade(low-high)</option>
		      <option value="grade-desc">Sort by grade(high-low)</option>
		      <option value="date_added-asc">Sort by Date(Oldest)</option>
		      <option value="date_added-desc">Sort by Date(Lates)</option>
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
					label: item['show_product'],
					value: item['name']
				}
			    }));
		    }
	    });
	  },
	  'select': function(item) {
		 $('input[name=\'tilte_book\']').val(item['value']);
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
	$('#viewstore').val(ClassName);
});

</script>

<?php echo $footer; ?>