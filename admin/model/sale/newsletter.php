<?php
class ModelSaleNewsletter extends Model {
	

	
	public function signupNewsletter($user_type,$email)
    {
            
           $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_newsletter  WHERE email = '".$email."'");
           
           
            //echo $query->num_rows;
            if($query->num_rows == 0)  // check if user has already subscribed
            {
                $query_update = "INSERT INTO " . DB_PREFIX . "user_newsletter (user_type,email) VALUES ('".$user_type."','".$email."')";
                if($this->db->query($query_update))
                {
                    
                     /****************************  SEND NEWSLETTER MAIL  ***************************/
                       
			$to = $email;
			
			
			$this->load->model('sale/newsletter');
			$message_content = $this->model_sale_newsletter->get_newsletter("6");
			$subject = $message_content['subject'];
                            $message = '<html xmlns="http://www.w3.org/1999/xhtml">
<link href="http://fonts.googleapis.com/css?family=PT+Sans+Caption" rel="stylesheet" type="text/css">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Newsletter sign Up email confirmation</title>
</head><body style="margin:0; padding:0;"><div style=" background:url('.SITE_URL.'newsletter_image/bg.jpg) left top repeat #ae3461; width:720px; margin:auto; padding:40px; position:relative;">
    	<div style="position:absolute; top:0; left:50px;">
        	<a href="'.SITE_URL.'"><img src="'.SITE_URL.'catalog/view/theme/default/images/logo.png"/></a>
        </div>
    	<div style="width:640px; background:#fff; border:#999999 solid 1px; padding:62px 40px 40px 40px; border-bottom:#666666 solid 4px;">
        	<div style="font-family:Arial; font-size:28px; color:#8F1752; font-family: PT Sans Caption, sans-serif; line-height:40px; font-weight:bold; font-style:italic;">
            	Hello Beau Bombshell,
            </div>
            <div style=" padding:10px; background:#f4f3f3; margin-top:10px; border-bottom:#666666 4px solid;;">
                '.html_entity_decode($message_content['content']).'
                
<p style="margin:0; padding:; color:#8F1752; font-weight:bold; font-family:Arial; font-size:18px; font-style:italic; font-family: PT Sans Caption, sans-serif; line-height:26px; padding-bottom:15px;">
               <a href="'.SITE_URL.'?route=common/home/unsubscribe&id='.base64_encode($email).'">Click here</a> to unsubscribe. 
                </p>

</div><div style="margin-top:30px; font-size:19px; font-weight:bold; color:#333333; font-family: PT Sans Caption, sans-serif; font-style:italic; ">Beau Exchange XOXO </div>
             <div style="clear:both;">&nbsp;</div>
        </div></div></body></html>';
                           
                                    // Always set content-type when sending HTML email
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                            // More headers
                            $headers .= 'From: Beau Exchange' . "\r\n";
                            //$headers .= 'Cc: myboss@example.com' . "\r\n";
                            
                          
                        
                        
                         /****************************  SEND NEWSLETTER MAIL  ***************************/

                    if(@mail($to,$subject,$message,$headers))
                    {
                        return "Successfully subscribed for Beau Journal And Special Offers.";
                    }
                }
                else
                {
                    return "Mail is not sent.Please try again!!";
                }
            }
            else
            {
                return "You have already subscribed.";
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
