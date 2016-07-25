<?php
class ControllerCronList extends Controller {
	private $error = array();

	public function pull_list_reminder(){
		
		$month_last_date = date("Y-m-t");
		
		$send_mail_date = date('Y-m-d', strtotime('-7 days', strtotime($month_last_date)));
		$current_date = date("Y-m-d");
		
		$book_issue_date = date('Y-m-d', strtotime('-30 days', strtotime($current_date)));
		
		//if($send_mail_date == $current_date)
		{
		    $this->load->model('catalog/product');
			$this->model_catalog_product->pull_list_customers($book_issue_date,$current_date);
			
		}
	}
	
	
	
	
	
	
// **************************************** Automatic make feature product *************************************
	
	public function autoFeatureProduct() {
		
		$this->load->model('cron/cron');
		
		$setting_val = $this->model_cron_cron->getSettingByKey('automatic_feature_price');
		$setting_val1 = $this->model_cron_cron->getSettingByKey('automatic_feature_number_view');
		$setting_val2 = $this->model_cron_cron->getSettingByKey('maxmimum_number_feature');
		
		$feature_price = $setting_val['value'];
		$feature_num_view = $setting_val1['value'];
		$feature_max_num = $setting_val2['value'];

		$results = $this->model_cron_cron->setAutoFeatureProduct($feature_price,$feature_num_view,$feature_max_num);
	}	
	

// **************************************** Automatic change status of comics (Pre-order to new_release to back order) *************************************
	
	public function autoStatusChange() {
		
		$after_thirty_days = date('Y-m-d H:i:s',strtotime("+30 days"));
		$today = date('Y-m-d');
		
		$this->load->model('cron/cron');
		$all_pro = $this->model_cron_cron->getAllPro();
		
		if($all_pro){
			foreach($all_pro as $each_pro){
				if(strtotime($each_pro['date_available'])>strtotime($after_thirty_days)){
					$status = 2;
				}
				else if((strtotime($today)<=strtotime($each_pro['date_available']))&& (strtotime($each_pro['date_available'])<=strtotime($after_thirty_days))){
					$status = 1;
				}
				else{
					$status = 0;
				}
				
				// check if Status = 1 then check its version
				if($status==1){
					$new_status = $this->model_cron_cron->checkUpdatedVersion($each_pro['model'],$each_pro['product_id']);
					if($new_status)
					$status = 0;
				}
				
				
				//echo $each_pro['product_id'].'==>'.$each_pro['date_available'].'==>'.$status.'--'.$each_pro['new_release'].'<br>';
				// Update All Records
				$this->model_cron_cron->UpdateproductStatus($each_pro['product_id'],$status);
			}
		}
		//$this->response->redirect($this->url->link('catalog/product','token=' . $this->session->data['token'],'SSL'));
	}

// **************************************** Automatic Send Newsletter (Everyweek) *************************************
	
	public function automatic_sent_newsletter() {
		
		$this->load->model('cron/cron');
		
		$newsletter_details = $this->model_cron_cron->getLatestNewsletter();
		//print_r($newsletter_details);exit;
		$data['newsletter_id'] = $newsletter_details['id'];
		$data['newsletter_subject'] = html_entity_decode($newsletter_details['newsletter_subject']);
		$data['newsletter_message'] = html_entity_decode($newsletter_details['newsletter_message']);
		
		
		// Get All Subscriber Details
		$subscriber_users = $this->model_cron_cron->getAllNewsletterSubscriber();
		
		if(!empty($subscriber_users)){
			foreach($subscriber_users as $each_user){
				$subscriber_details = $this->model_cron_cron->getSubscriberDetails($each_user['Email_id']);
				//print_r($subscriber_details);exit;
				$data['user_type'] = $subscriber_details['user_type'];
				$to = $data['email'] = $subscriber_details['email'];
				
				
			// ********************************** Send Mail **********************************************************
						
				
				$subject = $data['newsletter_subject'];
				$message = $data['newsletter_message'];
			  
				// Always set content-type when sending HTML email
				$headers = "MIME-Version: 1.0" . "\r\n";
				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
				// More headers
				$headers .= 'From: '.SITE_NAME . "\r\n";
				$headers .= "Bcc: amit.unified@gmail.com\r\n";
				
				@mail($to,$subject,$message,$headers);	
						
						
			// ********************************** Send Mail **********************************************************
						
				// Add To Email List table
				$this->model_cron_cron->addSendNewsletter($data);
				
			}
		}
		
	}

// **************************************** Automatic Change status of coupon for each customer (Everyday) *************************************
	
	public function automatic_change_coupon_status_customer() {
		
		$this->load->model('cron/cron');
		
		// Get Restart automatic coupon date
		$setting_val = $this->model_cron_cron->getSettingByKey('auto_coupoun_generation_month');
		
		$coupon_status = $this->model_cron_cron->getAbondoneCouponStatus();
		//print_r($coupon_status);exit;
		
		if(!empty($coupon_status)){
			foreach($coupon_status as $each_status){
				$abondone_get_coupon_date = $each_status['abondone_get_coupon_date'];
				if($abondone_get_coupon_date!="0000-00-00"){
					$next_restart_date =  strtotime("+".$setting_val['value']." months", strtotime($abondone_get_coupon_date));
					//echo strtotime(date("Y-m-d")).'>>>>>'.$next_restart_date;
					if(strtotime(date("Y-m-d"))>$next_restart_date){
						
						// Update abondone_get_coupon_status = 1 to 0
						$this->model_cron_cron->updateCustCouponStatus($each_status['customer_id'],'1','0000-00-00');
					}
				}			
			}
		}
		
		
	}

// **************************************** Automatic send coupon if items in cart more than 3 days (Everyday) *************************************
	
	public function automatic_send_coupon() {
		
		$this->load->model('cron/cron');
		
		
		// Get abondone cart
		$all_abondone_carts = $this->model_cron_cron->getAbondonecart();
		
		if(!empty($all_abondone_carts)){
			foreach($all_abondone_carts as $each_abondone_cart){
				$abondone_cart = unserialize($each_abondone_cart['abondone_cart']);
				//print_r($abondone_cart);exit;
				if(!empty($abondone_cart)){
					$flag = 0;
					foreach($abondone_cart as $key=> $item){
						$next_coupon_date =  strtotime("+3 days", strtotime($item));
						//echo "<pre>";print_r(unserialize(base64_decode($key)));echo $item;
						if(strtotime(date("Y-m-d"))>$next_coupon_date){
							$flag=1;
							break;
						}
					}
					if($flag==1){
						// Send Automated mail to that user
							
				/****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
                       
						$coupon_codes = $this->model_cron_cron->getAutomatedCouponCode();
						if(!empty($coupon_codes)){
						
						$to = $each_abondone_cart['email'];
						
						$this->load->model('account/newsletter');
						$message_content = $this->model_account_newsletter->get_newsletter("9");
						$subject = $message_content['subject'];
						
						$username = $each_abondone_cart['firstname'].' '.$each_abondone_cart['lastname'];
						$message_content_body= html_entity_decode(str_replace("[couponcode]",$coupon_codes['code'],$message_content['content']));  
						$message_content_body= html_entity_decode(str_replace("[username]",$username,$message_content_body));  
			
						$message = '<!doctype html>
							<html>
							<head>
							<meta charset="utf-8">
							<title>Newsletter sign Up email confirmation</title>
							</head>
							
							<body>
							<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin:auto; background:#f2f2f2; box-shadow:0 0 1px rgba(0, 0, 0, 0.2); text-align:left;">
							  <tbody>
								<tr>
								  <th scope="col" style="background:#3498db; padding:15px;"><a href="'.SITE_URL.'"><img src="'.SITE_URL.'catalog/view/theme/default/images/logo.png" alt=""></a></th>
								</tr>
								<tr>
								  <td style="padding:15px;">
								  <table width="100%" border="0" cellspacing="0" cellpadding="0">
							  <tbody>
								<tr>
								  <td style="padding:10px 0; font-family:Helvetica, Arial, sans-serif; font-size:14px; color:#423338;">Hello <strong>'.$username.'</strong>,</td>
								  </tr>
								  <tr>
								  <td style="padding:10px 0; text-align:center;">
									'.$message_content_body.'
								  </td>
								  </tr>
								  
							  </tbody>
							</table>
								  </td>
								</tr>
								<tr>
								  <td style="background:#3498db; padding:15px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#fff; text-align:center;">&copy; 2015 TSHOP</td>
								</tr>
							  </tbody>
							</table>
							</body>
							</html>';
                          
							// Always set content-type when sending HTML email
							$headers = "MIME-Version: 1.0" . "\r\n";
							$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
							
			    
							// More headers
							$headers .= 'From: '.SITE_NAME . "\r\n";
							//$headers .= 'Cc: amit.unified@gmail.com' . "\r\n";
						       $headers .= "Bcc: amit.unified@gmail.com\r\n";
							
							//echo $message;
										    
							    @mail($to,$subject,$message,$headers);
								    
							    // Update Abondone Status and abondane date	
							    
							    $this->model_cron_cron->updateCustCouponStatus($each_abondone_cart['customer_id'],'1',date("Y-m-d"));
							    
							    // Add To automated custom coupon table 
							    $this->model_cron_cron->AddCustomAutoCoupon($each_abondone_cart['customer_id'],$coupon_codes['coupon_id']);
								    
							    }
                        
                        
        	/****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
                
					}
				}
			}
		}		
	}

	
	
	
// **************************************** Send Email When any product has same title and has variant(Everyday) *************************************
	
	public function sendPullListVariantMail(){

		$pro_id = $_GET['product_id'];
		//$pro_id = 206;
		
		$this->load->model('account/newsletter');

		
		// Get product information
		$sql_pro = "SELECT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$pro_id . "') AS keyword FROM " . DB_PREFIX . "product WHERE `product_id` = '".$pro_id."'";
		$query_pro = $this->db->query($sql_pro);
		$pro_name = $query_pro->row['model'];
		$pro_variant = $query_pro->row['variant'];
		$issue_number = $query_pro->row['issue_number'];
		$keyword = $query_pro->row['keyword'];
		
		
		######################## send mail to pull list customer(start) ########################
		
		$sql_check = "SELECT * FROM " . DB_PREFIX . "product WHERE model LIKE '%".$this->db->escape($pro_name)."%' AND (new_release = '1' OR new_release = '2') AND posted_by = '1'";
		
		$query_check = $this->db->query($sql_check);
		$num_rows = $query_check->num_rows;
		
			
		if(($this->db->escape($pro_variant)!="") && ($num_rows>1)) //if new variant is added and book with same name is already available
		{
			$sql_pull_user = "SELECT pl.*,pr.variant,pr.issue_number,pr.model,c.firstname,c.lastname,c.email FROM " . DB_PREFIX . "pull_list as pl," . DB_PREFIX . "product as pr," . DB_PREFIX . "customer as c where c.customer_id = pl.customer_id and pr.product_id = pl.product_id AND pr.model like '%".$this->db->escape($pro_name)."%' GROUP BY pl.id";
			//echo $sql_pull_user;exit;
			
			$query_pull_user = $this->db->query($sql_pull_user);
			$num_pull_user = $query_pull_user->num_rows;
			if($num_pull_user>0)
			{
				$row_pull_users = $query_pull_user->rows;

				foreach($row_pull_users as $user)
				{
					
					$store_name = "";
					
					$message_row = $this->model_account_newsletter->get_newsletter("23");
					//echo "<pre>";
					//print_r($message_row); die;
								
					$message = $message_row['content'];
					
					$message = str_replace("[Product_Name]",$pro_name,$message);
					$message = str_replace("[Issue_Number]",$issue_number,$message);
					$message = str_replace("[Variant_Name]",$pro_variant,$message);
					
					$url = SITE_URL.$keyword;
					$message = str_replace("[URL]",$url,$message);
					
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
					$to = $user['email'];
					$subject = "A new comic ".$pro_name."-".$issue_number." has been uploaded.";
					// More headers
					$headers .= 'From: '.SITE_NAME . "\r\n";
					$headers .= "Bcc: amit.unified@gmail.com\r\n";
					
					@mail($to,$subject,$message,$headers);
					
				}
			}
		}
		mail('amit.unified@gmail.com','Subject','Messgaes'.$pro_id);
		
		######################## send mail to pull list customer(end) ########################
	}
	
	public function ipn() {
		
		$this->load->model('cron/cron');
		
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		foreach ($_POST as $key => $value) {
			$value = urlencode(stripslashes($value));
			$value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i','${1}%0D%0A${3}',$value);// IPN fix
			$req .= "&$key=$value";
		}
		
		// assign posted variables to local variables
		$data['item_name']		= $_POST['item_name'];
		$data['item_number'] 		= $_POST['item_number'];
		$data['payment_status'] 	= $_POST['payment_status'];
		$data['payment_amount'] 	= $_POST['mc_gross'];
		$data['payment_currency']	= $_POST['mc_currency'];
		$data['txn_id']			= $_POST['txn_id'];
		$data['receiver_email'] 	= $_POST['receiver_email'];
		$data['payer_email'] 		= $_POST['payer_email'];
		
		$custStrng			= $_POST['custom'];
		$custArray 			= explode("-",$custStrng);
		
		
		$data['cust_id'] 		= $custArray[0];
		$data['order_product_id'] 	= $custArray['1'];	
		
		$pay_date=date('l jS \of F Y h:i:s A');
		$recipient=$data['receiver_email'] = $data['payer_email'];
		
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";	
		$header .= "Host: www.sandbox.paypal.com\r\n";
		//$header .= "Host: www.paypal.com:443\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		
		$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);	
		//$fp = fsockopen ('ssl://www.paypal.com', 443, $errno, $errstr, 30);
		
		
		
		if (!$fp) 
		{
			// HTTP ERROR
		} 
		else 
		{
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp($res, "VERIFIED") == 0) 
				{
					// Used for debugging
					//@mail("amit.unified@gmail.com", "PAYPAL DEBUGGING BEFORE", "Verified Response<br />data = <pre>".print_r($data, true)."</pre>");//exit;
					
					//update status DB
					//$arr_data = array($data['order_product_id'],$data['cust_id'],$data['payment_amount'],$data['txn_id'],$data['receiver_email'],$data['payer_email']);
					//$this->model_cron_cron->AddSellerPayment($arr_data);
					
					$sql1 = "INSERT INTO " . DB_PREFIX . "order_seller_payment SET order_product_id = '" . $data['order_product_id'] . "',customer_id = '" . $data['cust_id'] . "', " . "amount = '" . $data['payment_amount'] . "', " . "transation_id = '" . $data['txn_id'] . "', " . "receiver_email = '" . $data['receiver_email'] . "',". "payer_email = '" . $data['payer_email'] . "',". "pay_date = '" . date("Y-m-d")."'";
					$this->db->query($sql1);
					
					
				
					
					$message="<table width='100%' border='0' cellspacing='0' cellpadding='0'>
						<tr>
						      <td align='left' valign='top'>
							      <fieldset style=' margin-left:8px; margin-right:8px;padding-left:10px; padding-right:10px;'>
								      <LEGEND  ACCESSKEY=L><span class='heading_txt'>Billing / Shipping </span></LEGEND>
								      <table width='100%' border='0' cellspacing='0' cellpadding='0'>
						<tr>
						      <td width='50%' align='left' valign='middle' ><table width='90%' border='1' align='left' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC' style=' border-collapse: collapse;'>
							<tr>
							      <td width='50%' align='right' valign='middle' class='td'>Name:&nbsp;</td>
							      <td width='50%' align='left' valign='middle'>".$data['item_name']."</td>
							</tr>
							<tr>
							      <td width='50%' align='right' valign='middle' class='td'>Address:&nbsp;</td>
							      <td width='50%' align='left' valign='middle'>".$payer_address."</td>
							</tr>
							<tr>
							      <td width='50%' align='right' valign='middle' class='td'>Phone:&nbsp;</td>
							      <td width='50%' align='left' valign='middle'>".$payer_phone."</td>
							</tr>
							<tr>
							      <td width='50%' align='right' valign='middle' class='td'> Payer Email:&nbsp;</td>
							      <td width='50%' align='left' valign='middle'>".$data['payer_email']."</td>
							</tr>
							<tr>
							      <td width='50%' align='right' valign='middle' class='td'>&nbsp;</td>
							      <td width='50%' align='left' valign='middle'>&nbsp;</td>
							</tr>
						      </table></td>
						      <td width='50%' align='right' valign='top'><table width='90%' border='1' align='right' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC' style=' border-collapse: collapse;'>
							<tr>
							      <td width='50%' align='right' valign='middle' class='td'>Order No. </td>
							      <td width='50%' class='td'>".$invoice."</td>
							</tr>
							<tr>
							      <td width='50%' align='right' valign='middle' class='td'>Order Date </td>
							      <td width='50%' class='td'>".$pay_date."</td>
							</tr>
					      
						      </table></td>
						</tr>
						
						
					      <tr>
						      <td width='50%' align='left' valign='middle' >&nbsp;</td>
						      <td width='50%'>&nbsp;</td>
						</tr>
						<tr>
						      <td colspan='2' align='left' valign='middle'><table width='100%' border='1' align='left' cellpadding='0' cellspacing='0' bordercolor='#CCCCCC' style=' border-collapse: collapse;'>
							<tr>
							      <td width='9%' class='td'>Sl.No</td>
							      <td width='38%' class='td'>Product Name </td>
							      <td width='19%' class='td'>Unit</td>
							      <td width='18%' class='td'>Unit Price </td>
							      <td width='16%' class='td'>Amount</td>
							</tr>
							<tr>
							      <td class='td'>1</td>
							      <td class='td'>".$item_name."</td>
							      <td class='td'>".$quantity."</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							</tr>
							<tr>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							</tr>
							<tr>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							</tr>
							<tr>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							      <td class='td'>&nbsp;</td>
							</tr>
						      </table></td>
						      </tr>
						<tr>
						      <td width='50%' align='right' valign='middle'>&nbsp;</td>
						      <td width='50%'>&nbsp;</td>
						</tr>
						<tr>
						      <td align='right' valign='middle'>&nbsp;</td>
						      <td><table width='90%' border='0' align='right' cellpadding='0' cellspacing='0'>
							<tr>
							      <td width='45%' align='right' valign='middle' class='td'>Total</td>
							      <td width='45%' align='left' valign='middle' class='td'> $".$data['payment_amount']."</td>
							</tr>
						      </table></td>
						</tr>
						<tr>
						      <td align='right' valign='middle'>&nbsp;</td>
						      <td>&nbsp;</td>
						</tr>
					      </table>
					      
							</fieldset>
						      </td>
						</tr>
						<tr>
						      <td align='left' valign='top'>&nbsp;</td>
						</tr>
						<tr>
						      <td align='left' valign='top'>&nbsp;</td>
						</tr>
						<tr>
						      <td align='left' valign='top'>&nbsp;</td>
						</tr>
					      </table>";
			      
					$subject="Payment Confirmation";
					$headers  = 'MIME-Version: 1.0' . "\r\n";
					$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
					$headers .= 'From: info@abcd.com' . "\r\n";
					$headers .= "\r\nReturn-Path: \r\n";  // Return path for errors 
					@mail($recipient, $subject, $message, $headers);
					@mail('amit.unified@gmail.com', $subject, $message, $headers);
								
				}
				
				if (strcmp ($res, "INVALID") == 0) {
					// PAYMENT INVALID & INVESTIGATE MANUALY! 
					// E-mail admin or alert user			
					$message = '
						Dear Administrator,
						A payment has been made but is flagged as INVALID.
						Please verify the payment manualy and contact the buyer.
						Buyer Email: '.$data['payer_email'].'
						';
					// Used for debugging
					@mail($recipient, "PAYPAL DEBUGGING".$message, "Invalid Response<br />data = <pre>".print_r($post, true)."</pre>");
		
				}		
			}	
			fclose ($fp);
		}		
		
	}
	
	public function success() {
		$this->load->language('error/not_found');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_not_found'] = $this->language->get('text_not_found');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('error/not_found', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/sucess_paypal.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/sucess_paypal.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/error/sucess_paypal.tpl', $data));
		}
		
	}
	
	
	public function testipn(){
		
	?>	
		
		<form  action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" name="_xclick" id="seller_payment" target="_blank">
		<table width="675" cellpadding="2" cellspacing="2" border="0" align="center">
		<tr><td>											
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="rm" value="2">
		<input type="hidden" name="business" value="amit_use@gmail.com">
		
		<input type="hidden" name="return" value="<?php echo SITE_URL ?>index.php?route=cron/list/success">
		<input type="hidden" name="cancel_return" value="<?php echo SITE_URL ?>index.php?route=cron/list/cancel">
		<input type="hidden" name="notify_url" value="<?php echo SITE_URL ?>index.php?route=cron/list/ipn">
		<input type="hidden" name="currency_code" value="USD" />
		<input type="hidden" name="item_name" value="Post Idea price">
		
		
		<input type="hidden" name="amount" value="10">
		<input type="hidden" name="custom" value="29-52" >
		<input type="submit" name="submit1" value="Submit" class="btn3"  />
		</td></tr></table>
		</form>

	<?php 
		
		
	}
	
	
	
}
?>