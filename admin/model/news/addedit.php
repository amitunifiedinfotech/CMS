<?php
class ModelNewsAddedit extends Model {
    
    public function getAllPost($data) {
	
	$sql = "SELECT * FROM " . DB_PREFIX . "newsletter where status = '1' ORDER BY id Asc";
		
	if (isset($data['start']) || isset($data['limit'])) {
	    if ($data['start'] < 0) {
		    $data['start'] = 0;
	    }
	    if ($data['limit'] < 1) {
		    $data['limit'] = 20;
	    }

	    $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	//echo $sql;
	$query = $this->db->query($sql);

	return $query->rows;
    }

    public function getTotalPost() {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter where status = '1' ");
	return $query->num_rows;
	
    }
        
    public function getNews($post_id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter WHERE id = '".$post_id."'");
	if($query->num_rows)
	{
	    return $query->row;
	}
	
    }
        
        
        
        
    public function addNews($data) {
	
	    $this->db->query("INSERT INTO " . DB_PREFIX . "newsletter SET "
		    . "subject = '" . $this->db->escape($data['post_title']) . "', " 
		    . "`content` = '" . $this->db->escape($data['content']) . "'"
		    );

	    return $post_id = $this->db->getLastId();

	    
    }

    public function editNews($post_id, $data) {
	    $this->db->query("UPDATE " . DB_PREFIX . "newsletter SET "
		    . "subject = '" . $this->db->escape($data['post_title']) . "', "
		    . "`content` = '" .$this->db->escape($data['content']) .  "'
		    WHERE id = '".$post_id."'");
    }

    public function deleteNews($post_id) {
       
	    $this->db->query("DELETE FROM " . DB_PREFIX . "newsletter WHERE id IN(" . $post_id. ")");
    } 
    public function deleteComment($comment_id) {
       
	    $this->db->query("DELETE FROM " . DB_PREFIX . "post_comment WHERE comment_id = '".$comment_id."'");
    }
    
// *********************************** Newsletter Section *************************************************************************************
    
    
    public function getAllNewsletter($data=false) {
	
	$sql = "SELECT * FROM " . DB_PREFIX . "newsletter_template ORDER BY id Asc";
		
	if (isset($data['start']) || isset($data['limit'])) {
	    if ($data['start'] < 0) {
		    $data['start'] = 0;
	    }
	    if ($data['limit'] < 1) {
		    $data['limit'] = 20;
	    }

	    $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	//echo $sql;
	$query = $this->db->query($sql);

	return $query->rows;
    }

    public function getTotalNewsletter() {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter_template ");
	return $query->num_rows;
	
    }
       
    
    public function getNewsletterTemplateById($id) {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter_template WHERE id = '".$id."'");
	if($query->num_rows){
	    return $query->row;
	}
	else
	    return 0;
    }
        
    public function addNewsletterTemplate($data) {
	
	$newsletter_post_date  = date("Y-m-d");
	$newsletter_end_date = date( "Y-m-d", strtotime("+7 day", strtotime($newsletter_post_date)));
	
	$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter_template SET "
		. "newsletter_subject = '" . $this->db->escape($data['newsletter_subject']) . "', " 
		. "`newsletter_message` = '" . $this->db->escape($data['newsletter_message']) . "', "
		. "newsletter_post_date = '" . $this->db->escape($newsletter_post_date) . "', " 
		. "newsletter_end_date = '" . $this->db->escape($newsletter_end_date) . "'" 
		);
    }
    
    public function editNewsletterTemplate($data,$id) {
	    $this->db->query("UPDATE " . DB_PREFIX . "newsletter_template SET "
		    . "newsletter_subject = '" . $this->db->escape($data['newsletter_subject']) . "', "
		    . "`newsletter_message` = '" .$this->db->escape($data['newsletter_message']) .  "'
		    WHERE id = '".$id."'");
    }
    
    
    public function deleteNewsletterTemplate($id) {
       
	    $this->db->query("DELETE FROM " . DB_PREFIX . "newsletter_template WHERE id = '".$id."'");
    }

    
    public function getAllNewsletterSubscriber($data=false) {
	
	$sql = "SELECT *,A.email as Email_id FROM " . DB_PREFIX . "user_newsletter A LEFT JOIN " . DB_PREFIX . "customer B ON (A.email=B.email) WHERE ((B.newsletter=1 AND A.user_type='customer') OR (A.user_type='visitor')) ORDER BY customer_id Asc";
		
	if (isset($data['start']) || isset($data['limit'])) {
	    if ($data['start'] < 0) {
		    $data['start'] = 0;
	    }
	    if ($data['limit'] < 1) {
		    $data['limit'] = 20;
	    }

	    $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	//echo $sql;
	$query = $this->db->query($sql);

	return $query->rows;
    }

    public function getTotalNewsletterSubscriber() {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "user_newsletter A LEFT JOIN " . DB_PREFIX . "customer B ON (A.email=B.email) WHERE ((B.newsletter=1 AND A.user_type='customer') OR (A.user_type='visitor')) ORDER BY customer_id Asc");
	return $query->num_rows;
	
    }
    
    
    public function getAllCustomerNewsletter() {
	
	$sql = "SELECT *,A.email as Email_id FROM " . DB_PREFIX . "user_newsletter A LEFT JOIN " . DB_PREFIX . "customer B ON (A.email=B.email) WHERE B.newsletter=1 AND A.user_type='customer'  ORDER BY customer_id Asc";
	
	//echo $sql;
	$query = $this->db->query($sql);

	return $query->rows;
    }

    public function getAllVisitorNewsletter() {
	
	$sql = "SELECT A.email as Email_id FROM " . DB_PREFIX . "user_newsletter A  WHERE  A.user_type='visitor' ORDER BY id Asc";
	
	//echo $sql;
	$query = $this->db->query($sql);

	return $query->rows;
    }

    public function getSubscriberDetails($email) {
	
	$sql = "SELECT * FROM " . DB_PREFIX . "user_newsletter A  WHERE A.email = '".$email."'  ORDER BY id Asc";
	
	//echo $sql;
	$query = $this->db->query($sql);
	return $query->row;
    }

    
    public function addSendNewsletter($data) {
	
	$newsletter_post_date  = date("Y-m-d");
	
	
	$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter_email_list SET "
		. "newsletter_id = '" . $this->db->escape($data['newsletter_id']) . "', " 
		. "user_email = '" . $this->db->escape($data['email']) . "', "
		. "user_type = '" . $this->db->escape($data['user_type']) . "', " 
		. "newsletter_message = '" . $this->db->escape($data['newsletter_message']) . "', " 
		. "newsletter_subject = '" . $this->db->escape($data['newsletter_subject']) . "'," 
		. "post_date = '" . $newsletter_post_date . "'" 
		);
    }
    
    public function getAllNewsletterSentMail($data=false) {
	
	$sql = "SELECT * FROM " . DB_PREFIX . "newsletter_email_list ORDER BY post_date Desc";
		
	if (isset($data['start']) || isset($data['limit'])) {
	    if ($data['start'] < 0) {
		    $data['start'] = 0;
	    }
	    if ($data['limit'] < 1) {
		    $data['limit'] = 20;
	    }

	    $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
	}
	//echo $sql;
	$query = $this->db->query($sql);

	return $query->rows;
    }

    public function getTotalNewsletterSentMail() {
	$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "newsletter_email_list ORDER BY post_date Desc");
	return $query->num_rows;
	
    }
    
    public function delNewsletterSentMail($id) {
       
	$this->db->query("DELETE FROM " . DB_PREFIX . "newsletter_email_list WHERE id = '".$id."'");
    }

    public function getLatestNewsletter() {
	$date = date("Y-m-d");
	$sql = "SELECT * FROM " . DB_PREFIX . "newsletter_template where ('".$date."' >= newsletter_post_date AND '".$date."' <= newsletter_end_date ) ORDER BY id Desc LIMIT 1";
	$query = $this->db->query($sql);
    if($query->num_rows){
	return $query->row;
    }
    else
	return 0;
	
    }
    
    
		
}
?>