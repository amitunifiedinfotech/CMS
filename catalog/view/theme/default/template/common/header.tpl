<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>"> 
<!--<![endif]-->

<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--- J-query min--->
<script type="text/javascript" src="catalog/view/theme/default/assets/js/jquery/jquery-1.10.1.min.js"></script>
<!-- Jquery Ui js -->
<script type="text/javascript" src="catalog/view/theme/default/assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="catalog/view/theme/default/assets/js/jquery/jquery.validationEngine.js"></script>
<script type="text/javascript" src="catalog/view/theme/default/assets/js/jquery/additional-methods.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/datetimepicker/moment.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.js"></script>
<script src="catalog/view/theme/default/assets/bootstrap/js/bootstrap.min.js"></script> 
<script src="catalog/view/theme/default/assets/js/idangerous.swiper-2.1.min.js"></script>
<script src="catalog/view/theme/default/assets/js/imgLiquid-min.js"></script>

<link href="catalog/view/javascript/summernote/summernote.css" rel="stylesheet">
<script type="text/javascript" src="catalog/view/javascript/summernote/summernote.js"></script>

<!-- Fav and touch icons -->
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="catalog/view/theme/default/assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="catalog/view/theme/default/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="catalog/view/theme/default/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="catalog/view/theme/default/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="catalog/view/theme/default/assets/ico/favicon.png">
<title><?php echo $title; ?></title>

<base href="<?php echo $base; ?>" />
      <?php if ($description) { ?>
      <meta name="description" content="<?php echo $description; ?>" />
      <?php } ?>
      <?php if ($keywords) { ?>
      <meta name="keywords" content= "<?php echo $keywords; ?>" />
      <?php } ?>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <?php if ($icon) { ?>
      <link href="<?php echo $icon; ?>" rel="icon" />
      <?php } ?>
      <?php foreach ($links as $link) { ?>
      <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
      <?php } ?>
      <script src="catalog/view/javascript/common.js" type="text/javascript"></script>
      <?php foreach ($scripts as $script) { ?>
      <!--<script type="text/javascript" src="<?php echo $script; ?>"></script>-->
      <?php } ?>
      
<!-- Bootstrap core CSS -->
<link href="<?php echo SITE_URL;?>admin/view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet">

<!-- styles needed by swiper slider -->
<link href="catalog/view/theme/default/assets/css/idangerous.swiper.css" rel="stylesheet">

<!-- Font Awesome -->
<link href="<?php echo SITE_URL;?>admin/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet">

<!-- Jquery Ui csss -->
<link href="catalog/view/theme/default/assets/css/jquery-ui.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="catalog/view/theme/default/assets/css/style.css" rel="stylesheet">
  
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
    
<link href="catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">


<!-- Just for debugging purposes. -->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->

<!-- include pace script for automatic web page progress bar  -->

<script>
    paceOptions = {
        elements: true
    };
</script>
<script src="catalog/view/theme/default/assets/js/pace.min.js"></script>

<?php if(isset($pro_title) && isset($pro_url) && isset($thumb)){?>
<meta property="og:title" content="<?php echo $pro_title?>" />
<meta property="og:description" content='<?php echo $pro_description?>'/>
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo $pro_url?>" />
<meta property="og:image" content="<?php echo $thumb?>" />
<?php } ?>

</head>
<body>
 
<!-- Modal Login start -->
  <div class="modal signUpContent fade" id="ModalLogin" tabindex="-1" role="dialog">
    <div class="modal-dialog ">
      <div class="modal-content">
      <form action="<?php echo SITE_URL; ?>?route=account/login" method="post" enctype="multipart/form-data" id="loginfrm" autocomplete="Off">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
          <h3 class="modal-title-site text-center"> Login to TSHOP</h3>
          
        </div>
        <div class="modal-body">
          <span id="login_msg" style="font-size:12px;color:red"></span>
          <div class="form-group login-username">
            <div><input name="email" id="login_email" class="form-control input" size="20" placeholder="Enter Email" type="text" value="<?php echo $remember_cookie_email;?>"></div>
          </div>
          <div class="form-group login-password">
            <div><input name="password" id="login_password" class="form-control input" size="20" placeholder="Password" type="password"></div>
          </div>
          <div class="form-group">
            <div>
                <div class="checkbox login-remember">
                    <label><input name="rememberme" value="forever" checked="checked" type="checkbox">Remember Me </label>
                </div>
            </div>
          </div>
          <div>
            <div><input name="login_btn" id="login_btn" class="btn  btn-block btn-lg btn-primary" value="LOGIN" type="submit"></div>
            <div class="loderimgbox" id="loderimgbox" style="display:none;"><img src="<?php SITE_URL ?>image/ajax-loader.gif"> </div>
          </div>
        <!--userForm--> 
      </div>
        
        <div class="modal-footer">
          <p class="text-center"> Not here before? <a href="<?php echo $register; ?>"> Sign Up. </a> <br>
            <a href="<?php echo $forgotten; ?>"> Lost your password? </a></p>
        </div>
    
      </form>
      </div>
          <!-- /.modal-content --> 
    </div>
    <!-- /.modal-dialog --> 
  </div>
<!-- /.Modal Login --> 

<!-- Modal Signup start -->
<div class="modal signUpContent fade" id="ModalSignup" tabindex="-1" role="dialog">
      <div class="modal-dialog">
    <div class="modal-content">
          <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"> &times; </button>
        <h3 class="modal-title-site text-center"> REGISTER </h3>
      </div>
          <div class="modal-body">
        
        <div class="form-group reg-username">
              <div>
            <input name="login" class="form-control input" size="20" placeholder="Enter Username"  type="text">
          </div>
            </div>
        <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
         <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
             <div class="form-group reg-email">
              <div>
            <input name="reg" class="form-control input" size="20" placeholder="Enter Email" type="text">
          </div>
            </div>
        <div class="form-group reg-password">
              <div>
            <input name="password" class="form-control input" size="20" placeholder="Password"
                               type="password">
          </div>
            </div>
        <div class="form-group">
              <div>
            <div class="checkbox login-remember">
                  <label>
                <input name="rememberme" id="rememberme" value="forever" checked="checked"
                                       type="checkbox">
                Remember Me </label>
                </div>
          </div>
            </div>
        <div>
              <div>
            <input name="submit" class="btn  btn-block btn-lg btn-primary" value="REGISTER" type="submit">
          </div>
            </div>
        <!--userForm--> 
        
      </div>
          <div class="modal-footer">
        <p class="text-center"> Already member? <a data-toggle="modal" data-dismiss="modal" href="#ModalLogin"> Sign in </a></p>
      </div>
        </div>
    <!-- /.modal-content --> 
    
  </div>
      <!-- /.modal-dialog --> 
      
    </div>
<!-- /.ModalSignup End --> 

<!-- Fixed navbar start -->
<div class="navbar navbar-tshop navbar-fixed-top megamenu" role="navigation">
   <div class="navbar-top">
    <div class="container">
     <div class="row">
        <div class="col-lg-4 col-sm-6 col-xs-5 col-md-6">
           <div class="pull-left ">
            <ul class="userMenu ">
              <li><a href="<?php echo $base; ?>help"> <span class="hidden-xs">HELP</span><i class="glyphicon glyphicon-info-sign hide visible-xs "></i> </a></li>
              <li class="phone-number"><a href="callto:+<?php echo $all_contact_settings['support_text_phone'];?>"> <span> <i class="glyphicon glyphicon-phone-alt "></i></span> <span class="hidden-xs" style="margin-left:5px"> <?php echo $all_contact_settings['support_text_phone'];?> </span> </a></li>
            </ul>
          </div>
         </div>
        <div class="col-lg-8 col-sm-6 col-xs-7 col-md-6 no-margin no-padding">
          <div class="pull-right">
            <ul class="userMenu">
                
                    <?php
                    if($total_unread_message>0)
                    {
                        ?>
                        <!--<li><a href="javascript:void(0);" onclick="check_notification();">Notification (<?php echo $total_unread_message; ?>)</a></li>-->
                        
                        <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              <i class="fa fa-bell"></i> 
              <span class="hidden-xs hidden-sm hidden-md">Notification</span> 
              <span class="badge"><?php echo $total_unread_message; ?></span></a>
              <ul class="dropdown-menu noti-box" role="menu">
                    <?php
                    if(!empty($notificaion_list))
                    {
                        foreach($notificaion_list as $notificaion)
                        {
                        ?>
                            <li><a href="<?php echo SITE_URL; ?>index.php?route=message/message/veiwmsg&message_id=<?php echo $notificaion['message_id']; ?>"><?php echo $notificaion['firstname'].' '.$notificaion['lastname'].' sent you a message.'; ?></a></li>
                        <?php
                        }
                    }
                    ?>

              </ul>
            </li>
                                                
                        
                        <?php
                    }
                    else
                    {
                        ?>
                        <li><a href="javascript:void(0);"><i class="fa fa-bell"></i> <span class="hidden-xs hidden-sm hidden-md">Notification</span> </a></li>
                        <?php
                    }
                    ?>
                    
                
            <li>
            
            <a href="<?php echo $account; ?>" title="My Account"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md">My Account</span></a>
            
            </li>
            <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
              
              <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
    <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
              <?php if ($logged) { ?>
              <li><a href="<?php echo $logout; ?>" title="<?php echo $text_logout; ?>"><i class="fa fa-sign-out"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span></a></li>
              <?php } else { ?>
              <li><a href="#" id="clickme" data-toggle="modal" data-target="#ModalLogin"> <span class="hidden-xs">Sign In</span> <i class="glyphicon glyphicon-log-in hide visible-xs "></i> </a></li>
              <!--<li class="hidden-xs"><a href="#" data-toggle="modal" data-target="#ModalSignup"> Create Account </a></li>-->
              <li class="hidden-xs"><a href="<?php echo $register; ?>"> Create Account </a></li>
              <?php } ?>
             </ul>
            
           
          </div>
       </div>
      </div>
    </div>
  </div>
    <!--<div id="notific" style="display: none; background-color: #ffffff;">
        <ul>
            <?php
            if(!empty($notificaion_list))
            {
                foreach($notificaion_list as $notificaion)
                {
                ?>
                    <li><a href="<?php echo SITE_URL; ?>index.php?route=message/message/veiwmsg&message_id=<?php echo $notificaion['message_id']; ?>"><?php echo $notificaion['firstname'].' '.$notificaion['lastname'].' sent you a message.'; ?></a></li>
                <?php
                }
            }
            ?>
            
        </ul>
        
    </div>-->
      <!--/.navbar-top-->
      
      <div class="container">
    <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse"><span
                    class="sr-only"> Toggle navigation </span> <span class="icon-bar"> </span> <span
                    class="icon-bar"> </span> <span class="icon-bar"> </span></button>
                    
          <!--<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-cart"><i
                    class="fa fa-shopping-cart colorWhite"> </i> <span
                    class="cartRespons colorWhite"> Cart ($210.00) </span></button>-->
                    
                <div class="visible-xs mob-cart-view"><?php echo $cart; ?></div>
                    
          <a class="navbar-brand " href="<?php echo $base; ?>"> <img src="catalog/view/theme/default/images/logo.png" alt="TSHOP"> </a> 
          
          <!-- this part for mobile -->
          <div class="search-box pull-right hidden-lg hidden-md hidden-sm">
        <div class="input-group">
              <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
            </div>
        <!-- /input-group --> 
        
      </div>
        </div>
    
    <!-- this part is duplicate from cartMenu  keep it for mobile -->
    <div class="navbar-cart collapse">
      <div class="cartMenu  col-lg-4 col-xs-12 col-md-4">
        
        <?php echo $cart; ?>
      </div>
          <!--/.cartMenu--> 
        </div>
    <!--/.navbar-cart-->
    
    <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="<?php echo $home_class;?>"><a href="<?php echo $base; ?>"> Home </a></li>
        <li class="<?php echo $comic_class;?>"><a href="<?php echo $base; ?>comic-store"> Comic Store </a></li>
        <li class="<?php echo $market_class;?>"><a href="<?php echo $base; ?>market-list"> Market Street </a></li>
        <li><a href="#"> Grading Service </a></li>
        <li class="<?php echo $faq_class;?>"><a href="<?php echo $base; ?>faq"> FAQ </a></li>
        <li class="<?php echo $about_us_class;?>"><a href="<?php echo $base; ?>about-us"> About Us </a></li>
        <li class="<?php echo $terms_class;?>"><a href="<?php echo $base; ?>terms"> Terms & Conditions </a></li>
      </ul>
          
      <!--- this part will be hidden for mobile version -->
      <div class="nav navbar-nav navbar-right hidden-xs">
          <?php echo $cart; ?>
          
        <!--/.cartMenu-->
        <div class="search-box">
            <div class="input-group">
                <button class="btn btn-nobg getFullSearch" type="button"><i class="fa fa-search"> </i></button>
            </div>
              <!-- /input-group -->       
        </div>
        <!--/.search-box --> 
      </div>
          <!--/.navbar-nav hidden-xs--> 
    </div>
    <!--/.nav-collapse --> 
    
  </div>
      <!--/.container -->
      
    <div class="search-full text-right"><a class="pull-right search-close"> <i class=" fa fa-times-circle"> </i> </a>
        <div class="searchInputBox pull-right">
          <input type="search" data-searchurl="search?=" name="title_book" id="title_book" placeholder="start typing and hit enter to search" class="search-input pull-left">
		  <button class="btn-nobg search-btn" onClick="getsearch()" type="button"><i class="fa fa-search"> </i></button>
          <input type="hidden" name="title_book_hidden" id="title_book_hidden"  class="search-input">
          
        </div>
    </div>
      <!--/.search-full--> 
      
    </div>
<!-- /.Fixed navbar  -->


<script>
 
  
 $(document).ready(function(){

	$.validator.addMethod("email", function(value, element) 
		{ 
		return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
		}, "Please enter a valid email address.");
	
	$("#loginfrm").validate({
	 rules: {
			 
			 email: {
							 required: true,
							 email: true
						 },
			 password:{ required: true}
			 
	 },
	 submitHandler: function(form) {
		$.ajax({
		   url: 'index.php?route=account/account/check_login_info',
		   type: 'post',
		   data: {email:$('#login_email').val(),password:$('#login_password').val()},
		   dataType: '',
		   success: function(msg) {
			$('#loderimgbox').hide();
				if(msg > 0)
				{
					$('#login_msg').html("");
					form.submit();
					
				}
				else
				{
					$('#login_msg').html("Incorrect Login Information!! Please Try Again.");
					$('#login_email').val('');
					$('#login_password').val('');
					setTimeout('$("#login_msg").html("")',3000);
					
				}
		   }
		});
		 
	 }
	});

});
 
function getsearch(){
    if ($('#title_book').val()!="") {
        window.location = '<?php echo SITE_URL?>index.php?route=seller_product/search_result&param='+$('#title_book_hidden').val();
    }
}
$('#title_book').keydown(function(event){ 
    var keyCode = (event.keyCode ? event.keyCode : event.which);   
    if (keyCode == 13) {
        getsearch();
    }
});
</script>

<script type="text/javascript">
$(document).ready(function(){
	// Product Name
	$('input[name=\'title_book\']').autocomplete({
	  'source': function(request, response) {
	   $('input[name=\'title_book\']').addClass('input-loader');
	   //alert(<?php echo $param_val;?>)
	    $.ajax({
		    url: 'index.php?route=seller_product/search_result/autocompleteTitle&filter_name=' +  encodeURIComponent(request),
		    dataType: 'json',			
		    success: function(json) {
		       $('input[name=\'title_book\']').removeClass('input-loader');
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
		 $('input[name=\'title_book\']').val(item['label']);
		 $('input[name=\'title_book_hidden\']').val(item['value']);
		 $('input[name=\'title_book\']').focus();
	  }	
	});
})

function check_notification()
{
    $('#notific').toggle();
}

</script>