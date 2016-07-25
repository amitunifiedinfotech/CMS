<?php echo $header; ?>

<script type="text/javascript">
$(document).ready(function(){
	$.validator.addMethod("email", function(value, element) 
	{ 
	return this.optional(element) || /^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z.]{2,5}$/i.test(value); 
	}, "Please enter a valid email address.");
   $("#edit_frm").validate({
			rules: {
				firstname: "required",
				lastname: "required",
				email: {
							required: true,
							email: true
						},
				address1: "required",
				city: "required",
				country_id: "required",
				state: "required",
				telephone: {
								phoneUS: true,
								required: true
            				},
				work_phone: {
								phoneUS: true
							}
				},
			messages: {
				firstname: "Please enter your firstname",
				lastname: "Please enter your lastname",
				email: "Please enter your valid email id",
				address1: "Please enter your Address",
				city: "Please enter your city",
				country_id: "Please enter your country",
				state: "Please enter your state",
				telephone: {
								phoneUS: "Please enter correct phone number",
								required: "Please enter your Phone number"
            				},
				work_phone: {
								phoneUS: "Please enter correct phone number",
            				}
			}
	
   });
});
   
</script>
<body>
<!-- /.Fixed navbar  -->
<div class="container main-container headerOffset">
    <div class="row">
        <div class="breadcrumbDiv col-lg-12">
            <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
          </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-7">
            <h1 class="section-title-inner"><span><i class="glyphicon glyphicon-user"></i> My personal information </span></h1>

            <div class="row userInfo">
                <div class="col-lg-12">
                    <h2 class="block-title-2"> Please be sure to update your personal information if it has changed. </h2>
					<?php if ($error_warning) { ?>
                              <h2 class="block-title-2 text-danger"><?php echo $error_warning; ?></h2>
                              <?php } ?>
                    <p class="required"><sup>*</sup> Required field</p>
                </div>
                 <!--<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="edit_frm" name="edit_frm">-->
                 <form name='edit_frm' id='edit_frm' action='' method='post' enctype='multipart/form-data' >
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group required">
                            <label for="InputName">First Name <sup>*</sup> </label>
                            <input type="text" class="form-control" value="<?php echo $firstname; ?>"  id="firstname" name="firstname" required placeholder="First Name">
                        </div>
                        <div class="form-group required">
                            <label for="InputLastName">Company Name </label>
                            <input type="text" class="form-control"  value="<?php echo $companyname; ?>" name="companyname" id="companyname" required placeholder="Company Name">
                        </div>
                        <div class="form-group required">
                            <label for="InputPasswordnew"> Address1 <sup>*</sup></label>
                            <textarea class="form-control" id="address1" name="address1" placeholder="Address1"><?php echo $address1; ?></textarea>
                        </div>
                        <div class="form-group required">
                            <label for="InputPasswordnew"> City <sup>*</sup></label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="City"  value="<?php echo $city; ?>">
                        </div>
                        
                        <div class="form-group required">
                            <label for="InputPasswordnew"> Zip</label>
                            <input type="text" name="zip" id="zip" class="form-control" placeholder="Zip" value="<?php echo $zip; ?>">
                        </div>
                        <div class="form-group required">
                            <label for=""> Home phone<sup>*</sup></label>
                            <input type="text" name="telephone" class="form-control" id="telephone" value="<?php echo $telephone; ?>" placeholder="Telephone Number">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="form-group required">
                            <label for="InputLastName">Last Name <sup>*</sup> </label>
                            <input required type="text" class="form-control" value="<?php echo $lastname; ?>" name="lastname" id="lastname" placeholder="Last Name">
                        </div>
                         <div class="form-group required">
                            <label for="InputPasswordnew"> Email address <sup>*</sup></label>
                            <input type="text" value="<?php echo $email; ?>" name="email" id="email" class="form-control">
                            
                        </div>
                        <div class="form-group required">
                            <label for="InputPasswordnew"> Address2</label>
                            <textarea class="form-control" id="address2" name="address2"><?php echo $address2; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for=""> Country <sup>*</sup></label>
                            <select name="country_id" id="country_id">
                              <option value="">Choose Country></option>
                              <?php foreach ($countries as $country) { ?>
                              <?php if ($country['country_id'] == $country_id) { ?>
                              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                              <?php } else { ?>
                              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                              <?php } ?>
                              <?php } ?>
                            </select>
                            
                        </div>
                        <div class="form-group required">
                            <label for=""> State <sup>*</sup></label>
                            <input type="text" name="state" class="form-control" id="state">
                        </div>
                       
                        <div class="form-group required">
                            <label for=""> Work phone</label>
                            <input type="text" name="work_phone" class="form-control" id="work_phone" value="<?php echo $work_phone; ?>">
                        </div>
                        
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group ">
                            <p class=" clearfix">
                                <input type="checkbox" value="1" name="newsletter" id="newsletter">
                                <label for="newsletter">Sign up for our newsletter!</label>
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="submit" class="btn   btn-primary"><i class="fa fa-save"></i> &nbsp; Save</button>
                    </div>
                </form>
                <div class="col-lg-12 clearfix">
                  <ul class="pager">
                    <li class="previous pull-right"><a href="index.html"> <i class="fa fa-home"></i> Go to Shop </a></li>
                    <li class="next pull-left"><a href="account.html"> &larr; Back to My Account</a></li>
                  </ul>
                </div>
            </div>
            <!--/row end-->

        </div>
        <div class="col-lg-3 col-md-3 col-sm-5"></div>
    </div>
    <!--/row-->

    <div style="clear:both"></div>
    
</div>
<!-- /main-container -->

<?php echo $footer; ?>
