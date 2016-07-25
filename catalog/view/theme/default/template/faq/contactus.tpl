<?php echo $header; ?>

<script type="text/javascript">
   $(document).ready(function(){
   
    $('#contact_us').click(function(){
	
			$.validator.addMethod("email", function(value, element) 
			{ 
			return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
			}, "Please enter a valid email address.");

			$("#contact_form").validate({
				rules: {
						name: "required",
						email: {
									required: true,
									email: true
								},
						phone: {
									required: true,
									minlength: 10
								},
						message:{ required: true}
						
					},
					messages: {
						name: "Please enter your name",
						email: "Please enter your valid email id",
						phone: "Please enter your valid phone number",
						message:"Please enter message"
						
					},
					submitHandler: function(form) {
						
						/*var error = 0;
						var error1 = 0;
						//alert(pass); alert(cpass);
						
						
						var sub_catcha_val=$('#user_captcha').val();
						var sess_cap_val=$('#user_captcha_old').val();
						if (sub_catcha_val != '' && sub_catcha_val != sess_cap_val)
						{
						    $("#msg").html("<i style='color:red; border: 1px solid red;'>Wrong Captcha</i>");
						
							error1 =1; 
						}
						else
						{
							error1 =0;
					
						}
						
						if (error==0 && error1==0)
						{
							$('#user_register_btn').attr('disabled','disabled');
							form.submit();
						}
						else
						{
							$('#user_register_btn').removeAttr('disabled');
						}
						*/
					}
			});
		});
   });
</script>

<div class="parallaxOffset no-padding fixedContent contact-intro">
    <div class="w100 map">
        <iframe width="100%" height="450" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"
                src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=+&amp;q=london&amp;ie=UTF8&amp;hq=&amp;hnear=London,+United+Kingdom&amp;ll=51.511214,-0.119824&amp;spn=0.007264,0.021136&amp;t=m&amp;z=14&amp;output=embed">
        </iframe>
    </div>
</div>
<!--/.contact-intro || map end-->

<div class="wrapper whitebg contact-us">
    <div class="container main-container ">
        <div class="row innerPage">
            <div class="col-lg-12 col-md-12 col-sm-12">

                <div class="row userInfo">

                    <div class="col-xs-12 col-sm-12">

                        <h1 class="title-big text-center section-title-style2">
                            <span> Contact us </span>
                        </h1>

                        <p class="lead text-center">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac augue at erat hendrerit
                            dictum. Praesent porta, purus eget sagittis imperdiet, nulla mi ullamcorper metus, id
                            hendrerit metus diam vitae est. Class aptent taciti sociosqu ad litora torquent per conubia
                            nostra, per inceptos himenaeos.
                        </p>

                        <hr>

                        <div class="row">

                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

                                <h3 class="block-title-5">
                                    Customer care
                                </h3>

                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit

                                    <br>
                                    <strong>
                                        Phone number
                                    </strong>
                                    : +88-01680531352
                                    <br>
                                    <strong>
                                        Email us
                                    </strong>
                                    : contact@tanimdesign.net
                                </p>

								<div style="clear:both"></div>
                                <hr>
                                <div style="clear:both"></div>
                            
                            	<h3 class="block-title-5">
                                    Feedback
                                </h3>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ac augue at erat
                                    hendrerit dictum. Praesent porta, purus eget sagittis imperdiet, nulla mi
                                    ullamcorper metus
                                    <email>
                                        feedback@tanimdesign.net
                                    </email>

                                    <br>
                                    <strong>
                                        Email us
                                    </strong>
                                    : feedback@tanimdesign.net
                                </p>
                            </div>

                            
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <h3 class="block-title-5">
                                    Other Enquiries
                                </h3>

                                <div class="form-horizontal">
                                	<form name='contact_form' id='contact_form' action='' method='post' enctype='multipart/form-data' >
                                          <div class="form-group">
                                            <label for="name" class="col-sm-2 control-label">Name</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control" id="name" name="name">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="email" class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                              <input type="email" class="form-control" id="email" name="email">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="phone" class="col-sm-2 control-label">Phone</label>
                                            <div class="col-sm-10">
                                              <input type="text" class="form-control" id="phone" name="phone">
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <label for="message" class="col-sm-2 control-label">Message</label>
                                            <div class="col-sm-10">
                                              <textarea class="form-control" id="message" name="message"></textarea>
                                            </div>
                                          </div>
                                          <div class="form-group">
                                            <div class="col-sm-offset-2 col-sm-10">
                                              <button class="btn btn-default" id="contact_us">Submit</button>
                                            </div>
                                          </div>
                                        </form>
                                </div>

                            </div>

                        </div>
                        <!--/.row-->
                    </div>


                </div>
                <!--/.row  ||  -->

            </div>
        </div>
        <!--/row || innerPage end-->
        <div style="clear:both"></div>
    </div>
    <!-- ./main-container -->
    <div class="gap"></div>
</div>
<!-- /main-container -->

<?php echo $footer; ?>