<footer>
  <div class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-3  col-md-3 col-sm-4 col-xs-6">
          <h3> Support </h3>
          <ul>
            <li class="supportLi">
              <p> <?php echo $all_contact_settings['support_text'];?> </p>
              <h4><a class="inline" href="callto:+<?php echo $all_contact_settings['support_text_phone'];?>"> <strong> <i class="fa fa-phone"> </i> <?php echo $all_contact_settings['support_text_phone'];?></strong> </a></h4>
              <h4><a class="inline" href="mailto:<?php echo $all_contact_settings['support_text_email'];?>"> <i class="fa fa-envelope-o"> </i> <?php echo $all_contact_settings['support_text_email'];?> </a></h4>
            </li>
          </ul>
        </div>
        <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
          <h3> Shop </h3>
          <ul>
            <li><a href="index.html"> Home </a></li>
            <li><a href="#"> Comic Street </a></li>
            <li><a href="#"> Market Street</a></li>
            <li><a href="#"> Grading Service</a></li>
          </ul>
        </div>
        <div style="clear:both" class="hide visible-xs"></div>
        <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
          <h3> Information </h3>
          <ul>
            <li><a href="<?php echo SITE_URL;?>help"> Help </a></li>
            <li><a href="<?php echo SITE_URL;?>about-us"> About us </a></li>
            <li><a href="<?php echo SITE_URL;?>contact-us"> Contact us </a></li>
            <li><a href="<?php echo SITE_URL;?>terms"> Terms &amp; Conditions </a></li>
          </ul>
        </div>
        <div class="col-lg-2  col-md-2 col-sm-4 col-xs-6">
          <h3> My Account </h3>
          <ul>
            <?php if(!$logged){?>
            <li><a href="<?php echo $login;?>"> Account Login </a></li>
            <?php }?>
            <li><a href="<?php echo $account;?>"> My Account </a></li>
            <li><a href="<?php echo $my_address;?>"> My Address </a></li>
            <li><a href="<?php echo $wishlist;?>"> Wish List </a></li>
            <li><a href="<?php echo $order;?>"> Order list </a></li>
           
          </ul>
        </div>
        <div style="clear:both" class="hide visible-xs"></div>
        <div class="col-lg-3  col-md-3 col-sm-6 col-xs-12 ">
          <h3> Stay in touch </h3>
          <ul>
            <li>
            <form method="post" action="" enctype="multipart-form" name="subscription_frm" id="subscription_frm">
              <div class="input-append newsLatterBox text-center">
                <div id="show_msg"></div>
                <input type="text" name="email" id="email" class="full text-center" placeholder="Email ">
                <button class="btn bg-gray" type="submit"> Subscribe <i class="fa fa-long-arrow-right"> </i></button>
                <div class="loderimgbox" id="loder_img" style="display:none;"><img src="<?php SITE_URL ?>image/ajax-loader.gif"> </div>
              </div>
            </form>  
            </li>
          </ul>
          <ul class="social">
            <li><a href="http://facebook.com"> <i class=" fa fa-facebook"> &nbsp; </i> </a></li>
            <li><a href="http://twitter.com"> <i class="fa fa-twitter"> &nbsp; </i> </a></li>
            <li><a href="https://plus.google.com"> <i class="fa fa-google-plus"> &nbsp; </i> </a></li>
            <li><a href="http://youtube.com"> <i class="fa fa-pinterest"> &nbsp; </i> </a></li>
            <li><a href="http://youtube.com"> <i class="fa fa-youtube"> &nbsp; </i> </a></li>
          </ul>
        </div>
      </div>
      <!--/.row--> 
    </div>
    <!--/.container--> 
  </div>
  <!--/.footer-->
  
  <div class="footer-bottom">
    <div class="container">
      <p class="pull-left"> &copy; TSHOP 2014. All right reserved. </p>
      <div class="pull-right paymentMethodImg">
        <img height="30" class="pull-right" src="catalog/view/theme/default/images/site/payment/master_card.png" alt="img">
        <img height="30" class="pull-right" src="catalog/view/theme/default/images/site/payment/visa_card.png" alt="img">
        <img height="30"  class="pull-right" src="catalog/view/theme/default/images/site/payment/paypal.png" alt="img">
        <img height="30" class="pull-right" src="catalog/view/theme/default/images/site/payment/american_express_card.png" alt="img">
        <img height="30" class="pull-right" src="catalog/view/theme/default/images/site/payment/discover_network_card.png" alt="img">
        <img height="30" class="pull-right" src="catalog/view/theme/default/images/site/payment/google_wallet.png" alt="img">
      </div>
    </div>
  </div>
  <!--/.footer-bottom--> 
</footer>

<!-- Le javascript
================================================== --> 

<!-- Placed at the end of the document so the pages load faster --> 

<script>
    var mySwiper = new Swiper('.swiper-container', {
        pagination: '.box-pagination',
        keyboardControl: true,
        paginationClickable: true,
        slidesPerView: 'auto',
        autoResize: true,
        resizeReInit: true,
    })

    $('.prevControl').on('click', function (e) {
        e.preventDefault()
        mySwiper.swipePrev()
    })
    $('.nextControl').on('click', function (e) {
        e.preventDefault()
        mySwiper.swipeNext()
    })
</script>
<script type="text/javascript">
$(document).ready(function(){
    $.validator.addMethod("email", function(value, element) 
    { 
    return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
    }, "Please enter a valid email address.");

   $("#subscription_frm").validate({
        rules: {
                
                email: {
                                required: true,
                                email: true
                        }
                },
        messages: {
                email: "Please enter your valid email id",
                
        },
        submitHandler: function (form) {
          $('#loder_img').show();
          
          var sendData = {"email":$('#email').val()};
          $.ajax({
                url: 'index.php?route=common/footer/add_subscription',
                method:'POST',
                data:sendData,			
                success: function(html) {
                
                  $('#loder_img').hide();
                  //alert(html);
                  $("#show_msg").html(html);
                  setTimeout('$("#show_msg").html("")',3000);
                  $('#email').val('');
                }
          });
          return false;
        }
	
   });
});
   
</script>


<!-- include jqueryCycle plugin --> 
<script src="catalog/view/theme/default/assets/js/jquery.cycle2.min.js"></script> 

<!-- include easing plugin --> 
<script src="catalog/view/theme/default/assets/js/jquery.easing.1.3.js"></script> 

<!-- include  parallax plugin --> 
<script src="catalog/view/theme/default/assets/js/jquery.parallax-1.1.js"></script> 

<!-- optionally include helper plugins --> 
<script src="catalog/view/theme/default/assets/js/helper-plugins/jquery.mousewheel.min.js"></script> 

<!-- include mCustomScrollbar plugin //Custom Scrollbar  --> 

<script src="catalog/view/theme/default/assets/js/jquery.mCustomScrollbar.js"></script> 

<!-- include checkRadio plugin //Custom check & Radio  --> 
<script src="catalog/view/theme/default/assets/js/ion-checkRadio/ion.checkRadio.min.js"></script> 

<!-- include grid.js // for equal Div height  --> 
<script src="catalog/view/theme/default/assets/js/grids.js"></script> 

<!-- include carousel slider plugin  --> 
<script src="catalog/view/theme/default/assets/js/owl.carousel.min.js"></script> 

<!-- jQuery minimalect // custom select   --> 
<script src="catalog/view/theme/default/assets/js/jquery.minimalect.min.js"></script> 

<!-- include touchspin.js // touch friendly input spinner component   --> 
<script src="catalog/view/theme/default/assets/js/bootstrap.touchspin.js"></script> 

<!-- include custom script for only homepage  --> 
<script src="catalog/view/theme/default/assets/js/home.js"></script> 

<!-- include custom script for site  --> 
<script src="catalog/view/theme/default/assets/js/script.js"></script>


</body>
</html>