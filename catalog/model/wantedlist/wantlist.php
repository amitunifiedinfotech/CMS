<?php
class ModelWantedlistWantlist extends Model {
	
// ****************************************************************** My Code *********************************************************	
	
	public function getProducts($data=false) {
		
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) WHERE p.role = 'seller' AND customer_id = '". $_SESSION['customer_id']."'";
		
			$sort_data = array(
				'pd.name',
				'p.price',
				'p.grade',
				'p.sort_order',
				'p.date_added'
			);
	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				if ($data['sort'] == 'pd.name') {
					$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
				} else {
					$sql .= " ORDER BY " . $data['sort'];
				}
			} else {
				$sql .= " ORDER BY p.sort_order";
			}
	
			if (isset($data['order']) && ($data['order'] == 'DESC')) {
				$sql .= " DESC, LCASE(pd.name) DESC";
			} else {
				$sql .= " ASC, LCASE(pd.name) ASC";
			}
	
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

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}
	}
	
	public function getTotalProducts($data=false) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.role = 'seller' AND customer_id = '". $_SESSION['customer_id']."'");

		return $query->num_rows;
	}
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT p.*,pd.name,pd.description,pd.meta_title,(SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword  FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE  p.product_id = '". $product_id."'");
		if($query->num_rows)
			return $query->row;
		else
			return 0;
	}
	
	public function getgrade() {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "grade WHERE status =  1  ORDER BY id");

		return $query->rows;
	}
	
	public function getProductDescriptions($product_id) {
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		foreach ($query->rows as $result) {
			$product_description_data[$result['language_id']] = array(
				'name'             => $result['name'],
				'description'      => $result['description'],
				'meta_title'       => $result['meta_title'],
				'meta_description' => $result['meta_description'],
				'meta_keyword'     => $result['meta_keyword'],
				'tag'              => $result['tag']
			);
		}

		return $product_description_data;
	}
	
	public function getProductsTitle($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 0 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		
		$sql .= " GROUP BY pd.name,p.issue_number ORDER BY pd.name ASC";

		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}

	

	public function addProduct($data) { 
		
		
		
		//echo "<pre>";print_r($data);exit;
		
		$sql = "INSERT INTO " . DB_PREFIX . "product SET customer_id = '" . $_SESSION['customer_id'] . "',role = 'seller',posted_by = '".$data['posted_by']."',model = '" . $this->db->escape($data['name']) . "',issue_number = '" . $this->db->escape($data['issue_number']) . "',grade = '" . $this->db->escape($data['grade']) . "',page_quality = '" . $this->db->escape($data['page_quality']) . "',publisher = '" . $this->db->escape($data['publisher']) . "',certification_number = '" . $this->db->escape($data['certification_number']) . "',feature = '" . $this->db->escape($data['feature']) . "',adult = '" . $this->db->escape($data['adult']) . "',new_release = '" . $this->db->escape($data['new_release']) . "',length_class_id = '" . $this->db->escape($data['length_class_id']) . "',subtract = '" . $this->db->escape($data['subtract']) . "',minimum = '" . $this->db->escape($data['minimum']) . "', quantity = '" . (int)$data['quantity'] . "',  date_available = '" . $this->db->escape($data['date_available']) . "', price = '" . (float)$data['price'] . "',  status = '" . (int)$data['status'] . "', sort_order = '" . (int)$data['sort_order'] . "', date_added = NOW()";

		//echo $sql;exit;
		$this->db->query($sql);

		$product_id = $this->db->getLastId();

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
	// ******************************************** Insert the second image if exists ************************************************
		if (isset($data['image1'])) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image1 = '" . $this->db->escape($data['image1']) . "' WHERE product_id = '" . (int)$product_id . "'");
			
		}
		
	// ******************************************** Insert the second image if exists ************************************************
			
		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = 1, name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['product_description']) . "', meta_title = '" . $this->db->escape($data['name']) . "'");
		
		// Update Model Name
		$this->db->query("UPDATE " . DB_PREFIX . "product SET model = '" . $this->db->escape($data['name']) . "' WHERE product_id = '" . (int)$product_id . "'");
		

		

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}


		if (isset($data['keyword'])) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		
		return $product_id;
	}

	


	public function editProduct($data,$product_id) {
		$this->event->trigger('pre.admin.product.edit', $data);
		
		//echo "<pre>";print_r($data);exit;

		$this->db->query("UPDATE " . DB_PREFIX . "product SET issue_number = '" . $this->db->escape($data['issue_number']) . "',grade = '" . $this->db->escape($data['grade']) . "',page_quality = '" . $this->db->escape($data['page_quality']) . "',publisher = '" . $this->db->escape($data['publisher']) . "',certification_number = '" . $this->db->escape($data['certification_number']) . "',adult = '" . $this->db->escape($data['adult']) . "',model = '" . $this->db->escape($data['name']) . "',  quantity = '" . (int)$data['quantity'] . "', price = '" . (float)$data['price'] . "', date_available = '" . $this->db->escape($data['date_available']) . "', date_modified = NOW() WHERE product_id = '" . (int)$product_id . "'");

		if (isset($data['image'])) {
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image = '" . $this->db->escape($data['image']) . "' WHERE product_id = '" . (int)$product_id . "'");
		}
		
	// ******************************************** Insert the second image if exists ************************************************
		if (isset($data['image1'])) {
			
			$this->db->query("UPDATE " . DB_PREFIX . "product SET image1 = '" . $this->db->escape($data['image1']) . "' WHERE product_id = '" . (int)$product_id . "'");
			
		}
		
	// ******************************************** Insert the second image if exists ************************************************

		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");

		$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product_id . "', language_id = 1, name = '" . $this->db->escape($data['name']) . "', description = '" . $this->db->escape($data['product_description']) . "', meta_title = '" . $this->db->escape($data['name']) . "'");
	
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");

		if (isset($data['product_related'])) {
			foreach ($data['product_related'] as $related_id) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "' AND related_id = '" . (int)$related_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$product_id . "', related_id = '" . (int)$related_id . "'");
				$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$related_id . "' AND related_id = '" . (int)$product_id . "'");
				$this->db->query("INSERT INTO " . DB_PREFIX . "product_related SET product_id = '" . (int)$related_id . "', related_id = '" . (int)$product_id . "'");
			}
		}

		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		if ($data['keyword']) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($data['keyword']) . "'");
		}

		
	}

	

	
	public function getProductRelated($product_id) {
		$product_related_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");

		foreach ($query->rows as $result) {
			$product_related_data[] = $result['related_id'];
		}

		return $product_related_data;
	}

	public function checkMyProduct($product_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE product_id =  $product_id AND customer_id = '". $_SESSION['customer_id']."'");

		return $query->num_rows;
	}
	
	public function deleteProduct($product_id) {
		
		$this->db->query("DELETE FROM " . DB_PREFIX . "product WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_attribute WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_description WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_discount WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_filter WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_image WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_option_value WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_related WHERE related_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_reward WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_special WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_category WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_download WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_layout WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_to_store WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "review WHERE product_id = '" . (int)$product_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "product_recurring WHERE product_id = " . (int)$product_id);
		$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");

		
	}
	
	
	public function getProductByCertification($certification_number) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE certification_number = '".  $certification_number."'");
		if($query->num_rows)
			return $query->row;
		else
			return 0;
	}
	
	
	// Get feature products from comic store
	public function getFeautureProComic() {
		
		
		$sql = "SELECT p.*,pd.*,G.*,c.firstname as cust_fname,c.lastname as cust_lname,u.firstname as user_fname,u.lastname as user_lname FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.feature = 1 AND p.status = 1 AND p.posted_by = 1 AND p.role = 'admin' AND p.customer_id = 1 ORDER BY p.sort_order DESC ";
		
	
		//echo $sql;
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}
	}
	
	// Get feature products from Market store
	public function getFeautureMarket() {
		$sql = "SELECT p.*,pd.*,G.*,c.firstname as cust_fname,c.lastname as cust_lname,u.firstname as user_fname,u.lastname as user_lname FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.feature = 1 AND p.status = 1 AND p.posted_by = 0 ORDER BY p.sort_order DESC";
		
	
		//echo $sql;
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}
	}
	
	public function checkDuplicate($name,$issue_number,$grade) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product WHERE model =  '".$name."' AND issue_number = '".$issue_number."' AND `grade` = '".$grade."' ");
		if($query->num_rows)
		return $query->row;
		return 0;
	}

	
	public function getMarketStreetProducts($data=false) {
		//print_r($data);
		
		$sql = "SELECT p.*,pd.*,G.*,c.firstname as cust_fname,c.lastname as cust_lname,u.firstname as user_fname,u.lastname as user_lname FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = 0 ";
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			$sql .= " AND  p.model = '".$data['title_book']."'";
		}

		// Search by Vendor
		if (isset($data['customer_filter']) && $data['customer_filter']!="") {			
			$sql .= " having concat(customer_id,'_',role) IN (".$data['customer_filter'].")";
		}
		
		
			$sort_data = array(
				'name',
				'price',
				'grade',
				'sort_order',
				'date_added'
			);
	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY p.sort_order";
			}
	
			if (isset($data['order']) && ($data['order'] == 'desc')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
	
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

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}
	}
	
	public function getTotalgetMarketStreetProducts($data = false) {
		$sql = "SELECT p.product_id,p.customer_id,p.role FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = 0 ";
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			$sql .= " AND  p.model  = '".$data['title_book']."'";
		}

		// Search by Vendor
		if (isset($data['customer_filter']) && $data['customer_filter']!="") {			
			$sql .= " having concat(customer_id,'_',role) IN (".$data['customer_filter'].")";
		}
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	
	
	
	public function getNonFeatureMarketProducts($data=false) {
		//print_r($data);
		
		$sql = "SELECT p.*,pd.*,G.*,c.firstname as cust_fname,c.lastname as cust_lname,u.firstname as user_fname,u.lastname as user_lname FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = 0 AND p.feature = 0 ";
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			list($title_book,$issue_num) = explode("-",$data['title_book']);
			$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";
		}

		// Search by Vendor
		if (isset($data['customer_filter']) && $data['customer_filter']!="") {			
			$sql .= " having concat(customer_id,'_',role) IN (".$data['customer_filter'].")";
		}
		
		
			$sort_data = array(
				'name',
				'price',
				'grade',
				'sort_order',
				'date_added'
			);
	
			if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
				$sql .= " ORDER BY " . $data['sort'];
			} else {
				$sql .= " ORDER BY p.sort_order";
			}
	
			if (isset($data['order']) && ($data['order'] == 'desc')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
	
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

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}
	}
	
	public function getTotalNonFeatureMarketProducts($data = false) {
		$sql = "SELECT p.product_id,p.customer_id,p.role FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = 0 AND p.feature = 0";
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			$sql .= " AND  p.model  = '".$data['title_book']."'";
		}

		// Search by Vendor
		if (isset($data['customer_filter']) && $data['customer_filter']!="") {			
			$sql .= " having concat(customer_id,'_',role) IN (".$data['customer_filter'].")";
		}
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	
	
	
	
	public function getAllCustomers(){
		$sql = "SELECT c.customer_id as id,c.firstname as fname,c.lastname as lname, 'seller' as role FROM " . DB_PREFIX . "customer c WHERE c.status = 1 UNION SELECT u.user_id as id,u.firstname as fname,u.lastname as lname ,'admin' as role FROM " . DB_PREFIX . "user u ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	function getPriceRange(){
		$sql = "SELECT (min(price)) as min_price,(max(price)) as max_price FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.posted_by = 0 ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	function getNonFeaturePriceRange(){
		$sql = "SELECT (min(price)) as min_price,(max(price)) as max_price FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.posted_by = 0 AND p.feature = 0";
		$query = $this->db->query($sql);
		return $query->row;
	}
	function getProNumByCustomer($customer_id,$role){
		$sql = "SELECT count(p.product_id) as total FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.posted_by = 0 AND customer_id = '".$customer_id."' AND role = '".$role."'";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
	
	public function getMarketProductsTitle($data = array()) {
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 0 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ((pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%' ) OR  (p.issue_number LIKE '" . $this->db->escape($data['filter_name']) . "%' ))";
		}
		
		if (!empty($data['nonfeature'])) {
			$sql .= " AND (p.feature = 0)";
		}
		
		$sql .= " GROUP BY pd.name,p.issue_number ORDER BY pd.name ASC";
			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	
	
	
	public function getCustomerList($data = array()) {
		$sql = "SELECT C.customer_id as id,C.firstname as fname,C.lastname as lname,C.username as username,'seller' as role FROM " . DB_PREFIX . "customer C WHERE C.status = 1 ANd C.approved = 1";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ((CONCAT(C.`firstname`, ' ', C.`lastname`)  LIKE '" . $this->db->escape($data['filter_name']) . "%' ) OR  (C.username LIKE '" . $this->db->escape($data['filter_name']) . "%' ))";
		}
		
		$sql .= " UNION SELECT U.user_id as id,U.firstname as fname,U.lastname as lname,'admin' as username,'admin' as role  FROM " . DB_PREFIX . "user U";
		if (!empty($data['filter_name'])) {
			$sql .= " WHERE ((CONCAT(U.`firstname`, ' ', U.`lastname`)  LIKE '" . $this->db->escape($data['filter_name']) . "%' ))";
		}

		$sql .= " GROUP BY CONCAT(`firstname`, ' ', `lastname`),username ORDER BY fname ASC";
		
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
		}

		if ($data['limit'] < 1) {
			$data['limit'] = 20;
		}

		$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		//echo $sql;exit;

		$query = $this->db->query($sql);

		return $query->rows;
	}

// ****************************************************************** My Code *********************************************************	
	
}