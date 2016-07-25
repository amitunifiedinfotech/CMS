<?php
class ModelCronCron extends Model {
	
	// From ADMIN Setting/Setting Model
	public function getSettingByKey($key) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "setting WHERE `key` = '" . $key . "'";
		
		$query = $this->db->query($sql);
		if($query->num_rows)
			return $query->row;
		else
			return 0;		
	}
	
	// From ADMIN Catalog/product Model
	function setAutoFeatureProduct($feature_price,$feature_num_view,$feature_max_num){
		
		if($feature_price!="" && $feature_num_view!=""){
			
			$all_eligible_comics = $this->getMaximumFeature($feature_price,$feature_num_view,$feature_max_num);
			//echo "<pre>";print_r($all_eligible_comics);exit;
			
			if(!empty($all_eligible_comics)){
				
				foreach($all_eligible_comics as $each_pro){
					// Set Feature
					$sql = "UPDATE " . DB_PREFIX . "product SET feature = 1 WHERE product_id = '" . (int)$each_pro['product_id'] . "'";
					$this->db->query($sql);
					
				}
			}
			
			// Set feature  = 0 if limit is exceed
			
			$all_features = $this->getAllFeatures();
			//echo "<pre>";print_r($all_features);exit;
			if(!empty($all_features)){
				for($i=0;$i<count($all_features);$i++){
	
					if($i<$feature_max_num)
					continue;					
					
					$sql = "UPDATE " . DB_PREFIX . "product SET feature = 0 WHERE product_id = '" . (int)$all_features[$i]['product_id'] . "'";
					$this->db->query($sql);
				}
			}
			
			
		}				
	}
	// From ADMIN Catalog/product Model
	public function getMaximumFeature($feature_price,$feature_num_view,$feature_max_num) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE price >=  '".$feature_price."' OR viewed >= '".$feature_num_view."' order by viewed DESC, price DESC LIMIT ".$feature_max_num;
		$query = $this->db->query($sql);
		if($query->num_rows)
		return $query->rows;
		return 0;
	}

	
	// From ADMIN Catalog/product Model
	public function getAllPro() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE role = 'admin' AND new_release!=0 order by date_available DESC");
		if($query->num_rows)
		return $query->rows;
		else
		return 0;
	}

	// From ADMIN Catalog/product Model
	public function checkUpdatedVersion($model,$product_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product WHERE model = '".$model."' AND new_release = 2 AND product_id!='".$product_id."'";
		$query = $this->db->query($sql);
		if($query->num_rows)
		return 1;
		return 0;
	}
	
	// From ADMIN Catalog/product Model
	public function UpdateproductStatus($product_id,$status) {
		$this->db->query("UPDATE " . DB_PREFIX . "product SET new_release = '" . $status . "' WHERE product_id = '" . (int)$product_id . "'");
	}


	// From ADMIN News/Addedit Model
 	public function getLatestNewsletter() {
		$date = date("Y-m-d");
		$sql = "SELECT * FROM " . DB_PREFIX . "newsletter_template where ('".$date."' >= newsletter_post_date AND '".$date."' <= newsletter_end_date AND auto_send_status = 1) ORDER BY id Desc LIMIT 1";
		$query = $this->db->query($sql);
		if($query->num_rows){
		return $query->row;
		}
		else
		return 0;
	
    }
	
 	// From ADMIN News/Addedit Model
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
	
 	// From ADMIN News/Addedit Model
	public function getSubscriberDetails($email) {
		$sql = "SELECT * FROM " . DB_PREFIX . "user_newsletter A  WHERE A.email = '".$email."'  ORDER BY id Asc";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->row;
    }
	
 	// From ADMIN News/Addedit Model
	public function addSendNewsletter($data) {
		$newsletter_post_date  = date("Y-m-d H:i:s");
		$this->db->query("INSERT INTO " . DB_PREFIX . "newsletter_email_list SET "
			. "newsletter_id = '" . $this->db->escape($data['newsletter_id']) . "', " 
			. "user_email = '" . $this->db->escape($data['email']) . "', "
			. "all_group = '" . $this->db->escape($data['all_group']) . "', "
			. "user_type = '" . $this->db->escape($data['user_type']) . "', " 
			. "newsletter_message = '" . $this->db->escape($data['newsletter_message']) . "', " 
			. "newsletter_subject = '" . $this->db->escape($data['newsletter_subject']) . "'," 
			. "post_date = '" . $newsletter_post_date . "'" 
			);
	}
    
	// Use only for this model
	public function getAbondoneCouponStatus() {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer A  WHERE A.status =  1 AND abondone_get_coupon_status = 1 ORDER BY customer_id Asc";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->rows;
    }
	

	// Use only for this model (Get All abondone cart)
	public function getAbondonecart() {
		$sql = "SELECT * FROM " . DB_PREFIX . "customer  WHERE `abondone_cart`!= 'NULL' AND abondone_block_status = 0 AND abondone_get_coupon_status = 0 ORDER BY customer_id Asc";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->rows;
    }
	// Use only for this model (Get Automated Coupon code)
	public function getAutomatedCouponCode() {
		$sql = "SELECT * FROM " . DB_PREFIX . "coupon  WHERE automated_send =  1 AND (`date_start` < NOW() AND `date_end` > NOW()) AND status = 1 LIMIT 1";
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->row;
    }
	
	// Use only for this model (Update abondone date and abondone coupon status)
	public function updateCustCouponStatus($customer_id,$status,$date) {
			$sql = "UPDATE " . DB_PREFIX . "customer SET abondone_get_coupon_status = '".$status."',abondone_get_coupon_date ='".$date."' WHERE customer_id = '" . (int)$customer_id . "'";
			$this->db->query($sql);	
	}

	
	// Use only for this model (Add Custom Coupon code)
	public function AddCustomAutoCoupon($customer_id,$coupon_id) {
		$newsletter_post_date  = date("Y-m-d");
		$this->db->query("INSERT INTO " . DB_PREFIX . "coupon_automated_send SET "
			. "coupon_id = '" . $coupon_id . "', " 
			. "customer_id = '" . $customer_id . "', " 
			. "date = '" . $newsletter_post_date . "'" 
			);
	}
	
	// Use only for this model (Add to oc_order_seller_payment)
	public function AddSellerPayment($data) {
		$newsletter_post_date  = date("Y-m-d");
		
		$sql = "INSERT INTO " . DB_PREFIX . "order_seller_payment SET "
			. "order_product_id = '" . $data['order_product_id'] . "', " 
			. "customer_id = '" . $data['customer_id'] . "', " 
			. "amount = '" . $data['payment_amount'] . "', " 
			. "transation_id = '" . $data['txn_id'] . "', " 
			. "receiver_email = '" . $data['receiver_email'] . "',"
			. "payer_email = '" . $data['payer_email'] . "',"
			. "pay_date = '" . $newsletter_post_date . "'" ;
		
		
		$this->db->query($sql);
	
		
		//mail("amit.unified@gmail.com", "PAYPAL DEBUGGING", "Verified Response<br />data = <pre>".print_r($sql, true)."</pre>");
	}
	
	
}