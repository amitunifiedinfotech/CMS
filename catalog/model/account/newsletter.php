<?php
class ModelAccountNewsletter extends Model {
	

	
	public function signupNewsletter($user_type,$email)
        {
            
           $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_newsletter  WHERE email = '".$email."'");
           
           
            //echo $query->num_rows;
            if($query->num_rows == 0)  // check if user has already subscribed
            {
                
		// check if user has already register[register user ==> user_type='customer']
		$query_customer = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer  WHERE email = '".$email."'");
		if($query_customer->num_rows != 0){
			$user_type = 'customer';
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = 1  WHERE email = '".$email."'");
		}
		
		$query_update = "INSERT INTO " . DB_PREFIX . "user_newsletter (user_type,email) VALUES ('".$user_type."','".$email."')";
                if($this->db->query($query_update))
                {
                    
                     /****************************  SEND NEWSLETTER MAIL  ***************************/
                       
			$to = $email;
			
			
			$this->load->model('account/newsletter');
			$message_content = $this->model_account_newsletter->get_newsletter("6");
			$subject = $message_content['subject'];
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
      <td style="padding:10px 0; font-family:Helvetica, Arial, sans-serif; font-size:14px; color:#423338; text-align:center;">
      	'.html_entity_decode($message_content['content']).'
      </td>
      </tr>
      
  </tbody>
</table>
      </td>
    </tr>
    <tr>
      <td style="padding:10px; font-family:Helvetica, Arial, sans-serif; font-size:13px; color:#34495E; background:#fff; text-align:center;"><a href="'.SITE_URL.'?route=common/home/unsubscribe&id='.base64_encode($email).'">Click here</a> to unsubscribe.</td>
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
                            //$headers .= 'Cc: myboss@example.com' . "\r\n";
                            
                          
                        
                        
                         /****************************  SEND NEWSLETTER MAIL  ***************************/

                    if(@mail($to,$subject,$message,$headers))
                    {
                        return "<p style='color:green'>Successfully subscribed for Tshop.</p>";
                    }
                }
                else
                {
                    return "<p style='color:red'>Mail is not sent.You already got the email before!!!</p>";
                }
            }
            else
            {
                return "<p style='color:red'>You had already subscribed.</p>";
            }
                
        }
        
        public function unsubscribeNewsletter($email)
        {
            $this->db->query(" UPDATE ".DB_PREFIX."customer set newsletter = 0 where email = '".$email."'");
	    
	    $query_unsubscribe = "DELETE FROM " . DB_PREFIX . "user_newsletter where email = '".$email."'";
            $this->db->query($query_unsubscribe);
        }
        
        
        public function get_newsletter($id)
        {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter where id = '".$id."'");
            if($query->num_rows>0)
            {
                return $query->row;
            }
            
        }
        
        
}
?>
