<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data) {
		$this->event->trigger('pre.customer.add', $data);

		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		
		
		
	//******************************* calucate no of years from DOB ****************************************************
	
		//echo $data['dob'];
		list($date,$month,$year) = explode("-",$data['dob']);
		$change_dob = $year.'-'.$month.'-'.$date;
		
		$from = new DateTime($data['dob']);
		$to   = new DateTime('today');
		$years =  $from->diff($to)->y;
		//echo 'years='.$years;
		
		$over_eighteen = 0;
		if($years>17){
			$over_eighteen = 1;
		}
		
	//******************************* calucate no of years from DOB **************************************************** 

		$sql = "INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "',work_phone = '" . $this->db->escape($data['work_phone']) . "',dob = '" . $this->db->escape($change_dob) . "',years = '" . $this->db->escape($years) . "',over_eighteen = '" . $over_eighteen . "', fax = '" . $this->db->escape($data['fax']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? serialize($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '0', approved = 1, date_added = NOW()";
		//echo $sql;exit;
		$this->db->query($sql);

		$customer_id = $this->db->getLastId();

		$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['address']) ? serialize($data['custom_field']['address']) : '') . "'");

		$address_id = $this->db->getLastId();

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
		
	/****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/	
		if($data['newsletter']==1)
		{
                    $this->load->model('account/newsletter');
                    $this->model_account_newsletter->signupNewsletter('customer',$data['email']);
        }
	/****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
	
	
		$this->load->language('mail/customer');

		/*$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));

		$message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

		if (!$customer_group_info['approval']) {
			$message .= $this->language->get('text_login') . "\n";
		} else {
			$message .= $this->language->get('text_approval') . "\n";
		}

		$message .= $this->url->link('account/login', '', 'SSL') . "\n\n";
		$message .= $this->language->get('text_services') . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= $this->config->get('config_name');

		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject($subject);
		$mail->setText($message);
		//$mail->send();

		// Send to main admin email if new account email is enabled
		if ($this->config->get('config_account_mail')) {
			$message  = $this->language->get('text_signup') . "\n\n";
			$message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";
			$message .= $this->language->get('text_firstname') . ' ' . $data['firstname'] . "\n";
			$message .= $this->language->get('text_lastname') . ' ' . $data['lastname'] . "\n";
			$message .= $this->language->get('text_customer_group') . ' ' . $customer_group_info['name'] . "\n";
			$message .= $this->language->get('text_email') . ' '  .  $data['email'] . "\n";
			$message .= $this->language->get('text_telephone') . ' ' . $data['telephone'] . "\n";

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			
			$mail->setSubject($this->language->get('text_new_customer'));
			$mail->setText($message);
			//$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $email)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}*/
		
		 
        /****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
                       
			$to = $data['email'];
			
			
			$this->load->model('account/newsletter');
			$message_content = $this->model_account_newsletter->get_newsletter("1");
			$subject = $message_content['subject'];
			
			$redirecturl= '<a href="'.SITE_URL.'?route=common/home/activate_user&email='.base64_encode($data['email']).'">Click Here.</a>';
			$username = $data['firstname'].' '.$data['lastname'];
			$message_content_body= html_entity_decode(str_replace("[clickhere]",$redirecturl,$message_content['content']));  
			//$message_content_body= html_entity_decode(str_replace("[username]",$username,$message_content_body));  
			
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
                            $headers .= 'From: Tshop' . "\r\n";
                            //$headers .= 'Cc: amit.unified@gmail.com' . "\r\n";
				$headers .= "Bcc: amit.unified@gmail.com\r\n";
                            
                            @mail($to,$subject,$message,$headers);
                        
                        
        /****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
                
		
		
		

		$this->event->trigger('post.customer.add', $customer_id);

		return $customer_id;
	}



	public function editCustomer($data) {
		//print_r($data);exit;
		
		$over_eighteen = 0;
		if(isset($data['over_eighteen'])){
			$over_eighteen = 1;
		}

//******************************* calucate no of years from DOB ****************************************************
	
		list($date,$month,$year) = explode("-",$data['dob']);
		$change_dob = $year.'-'.$month.'-'.$date;
		
		$from = new DateTime($data['dob']);
		$to   = new DateTime('today');
		$years =  $from->diff($to)->y;
		//echo 'years='.$years;
		
	//******************************* calucate no of years from DOB **************************************************** 
		
		$sql = "UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', work_phone = '" . $this->db->escape($data['work_phone']) . "',dob = '" . $this->db->escape($change_dob) . "',years = '" . $this->db->escape($years) . "',fax = '" . $this->db->escape($data['fax']) . "', over_eighteen = '" . $over_eighteen. "' WHERE customer_id = '" . (int)$this->customer->getId() . "'";
		$this->db->query($sql);
	}
	
// ................................................... My Edited Code .......................................................
	/*public function editCustomer($data) {
		$this->event->trigger('pre.customer.edit', $data);
		if(!isset($data['fax'])){
			$data['fax'] = '';
		}

		$customer_id = $this->customer->getId();
		$sql = "UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', fax = '" . $this->db->escape($data['fax']) . "', companyname = '" . $this->db->escape($data['companyname']) . "', address1 = '" . $this->db->escape($data['address1']) . "', address2 = '" . $this->db->escape($data['address2']) . "', city = '" . $this->db->escape($data['city']) . "', country_id = '" . $this->db->escape($data['country_id']) . "', state = '" . $this->db->escape($data['state']) . "', zip = '" . $this->db->escape($data['zip']) . "', work_phone = '" . $this->db->escape($data['work_phone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']) ? serialize($data['custom_field']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'";
		$this->db->query($sql);

		$this->event->trigger('post.customer.edit', $customer_id);
	}*/
// ................................................... My Edited Code .......................................................
	public function editPassword($email, $password) {
		$this->event->trigger('pre.customer.edit.password');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		$this->event->trigger('post.customer.edit.password');
	}

	public function editNewsletter($newsletter) {
		$this->event->trigger('pre.customer.edit.newsletter');

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");

		$this->event->trigger('post.customer.edit.newsletter');
	}

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}

	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function isBanIp($ip) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ban_ip` WHERE ip = '" . $this->db->escape($ip) . "'");

		return $query->num_rows;
	}
	
	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");
		
		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
		}			
	}	
	
	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}
	
	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}
	
	
// ==================================== Our Own Code ..... Activate User .............  ============================================

	public function activateAccount($email){
		
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
		if(!$query->num_rows){
			return -1;
		}
		$query_data =  $query->row;
		if($query_data['status']==1){
			return 0;
		}
		else{
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET status = 1 WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
			return 1;
		}
		
		
	}
	
	
	
	
	
}