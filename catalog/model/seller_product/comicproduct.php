<?php
class ModelSellerProductComicProduct extends Model {
	
// ****************************************************************** My Code *********************************************************	
	
	
	public function getComicProducts($data=false) {
		//print_r($data);
		
		$sql = "SELECT p.*,pd.*,G.*,u.firstname as user_fname,u.lastname as user_lname,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = 1 AND p.feature = 0 AND new_release = '".$data['param']."'";
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}
		
		// Search by publisher
		if (isset($data['publisher']) && $data['publisher']!="") {
			
			$sql .= " AND publisher = '".$data['publisher']."'";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			//$sql .= " AND  p.model = '".$data['title_book']."'";
			$title_arr = array_reverse(explode("-",$data['title_book']));
			$issue_num = $title_arr[0];
			
			if($issue_num=="NA"){
				$title_book = preg_replace("/-NA/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."')";
			}
			else{
				$title_book = preg_replace("/-$issue_num/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";
				
			}
			
				
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
	
	
	public function getTotalComicProducts($data = false) {
		$sql = "SELECT p.product_id,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.feature = 0 AND  p.posted_by = 1 AND new_release = '".$data['param']."'";
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}

		// Search by publisher
		if (isset($data['publisher']) && $data['publisher']!="") {
			
			$sql .= " AND publisher = '".$data['publisher']."'";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			//$sql .= " AND  p.model  = '".$data['title_book']."'";
			$title_arr = array_reverse(explode("-",$data['title_book']));
			
			$issue_num = $title_arr[0];
			if($issue_num=="NA"){
				$title_book = preg_replace("/-NA/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."')";
			}
			else{
				$title_book = preg_replace("/-$issue_num/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";
				
			}
		}
		//echo $sql;
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	
	
	public function getComicAllProducts($data=false) {
		//print_r($data);
		
		$sql = "SELECT p.*,pd.*,G.*,u.firstname as user_fname,u.lastname as user_lname,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = 1 AND new_release = '".$data['param']."' AND p.quantity>0";
		
		// Search by comic_label
		if (isset($data['comic_label']) && $data['comic_label']!="") {
			
			$sql .= " AND p.comic_label LIKE '".$data['comic_label']."'";
		}

		// Search by is_coming_soon
		if (isset($data['is_coming_soon']) && $data['is_coming_soon']!="") {
			
			$sql .= " AND p.is_coming_soon LIKE '".$data['is_coming_soon']."'";
		}

		// Search by publisher
		if (isset($data['publisher']) && $data['publisher']!="") {
			
			$sql .= " AND p.publisher LIKE '%".$data['publisher']."%'";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			
			if(strpos($data['title_book'],"#") === false){
				$sql .= " AND  (p.model LIKE '%".$data['title_book']."%')";			
			}
			else{
				//$sql .= " AND  p.model = '".$data['title_book']."'";
				$title_arr = array_reverse(explode("#",$data['title_book']));
				$issue_num = $title_arr[0];
				
				if($issue_num=="NA"){
					$title_book = preg_replace("/#NA/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
				}
				else{
					$title_book = preg_replace("/#$issue_num/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";					
				}
			}
		}
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {			
			$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
			//$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}
		

		
		$sort_data = array(
			'name',
			'price',
			'grade',
			'sort_order',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {

			if($data['sort']=="price")
				$sql .= " ORDER BY IFNULL(special,price)";
			else
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
	
	
	public function getTotalAllComicProducts($data = false) {
		$sql = "SELECT p.product_id,p.price,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND  p.posted_by = 1 AND new_release = '".$data['param']."' AND p.quantity>0";
			
		// Search by publisher
		if (isset($data['publisher']) && $data['publisher']!="") {
			
			$sql .= " AND p.publisher LIKE '%".$data['publisher']."%'";
		}

		// Search by comic_label
		if (isset($data['comic_label']) && $data['comic_label']!="") {
			
			$sql .= " AND p.comic_label LIKE '".$data['comic_label']."'";
		}

		// Search by is_coming_soon
		if (isset($data['is_coming_soon']) && $data['is_coming_soon']!="") {
			
			$sql .= " AND p.is_coming_soon LIKE '".$data['is_coming_soon']."'";
		}

		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			
			if(strpos($data['title_book'],"#") === false){
				$sql .= " AND  (p.model LIKE '%".$data['title_book']."%')";			
			}
			else{
				//$sql .= " AND  p.model = '".$data['title_book']."'";
				$title_arr = array_reverse(explode("#",$data['title_book']));
				$issue_num = $title_arr[0];
				
				if($issue_num=="NA"){
					$title_book = preg_replace("/#NA/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
				}
				else{
					$title_book = preg_replace("/#$issue_num/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";					
				}
			}
		}

		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
			
			//$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}



		//echo $sql;
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	/* ======================================= For AllTypes of products =========================================================== */	
	public function getCountAllTypesProduct($data = false) {
		$sql = "SELECT p.product_id,p.price,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND  p.posted_by = '".$data['posted_by']."' AND new_release = '".$data['new_release']."'";
			
		// Search by publisher
		//if (isset($data['publisher']) && $data['publisher']!="") {
		//	
		//	$sql .= " AND p.publisher LIKE '%".$data['publisher']."%'";
		//}
		
		

		// Search by is_coming_soon
		if (isset($data['is_coming_soon']) && $data['is_coming_soon']!="") {
			
			$sql .= " AND p.is_coming_soon = ".$data['is_coming_soon']."";
		}

		// Search by comic_label
		if (isset($data['comic_label']) && $data['comic_label']!="") {
			
			$sql .= " AND p.comic_label LIKE '".$data['comic_label']."'";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			
			if(strpos($data['title_book'],"~") === false){
				$sql .= " AND  (p.model LIKE '%".$data['title_book']."%')";			
			}
			else{
				//$sql .= " AND  p.model = '".$data['title_book']."'";
				$title_arr = array_reverse(explode("~",$data['title_book']));
				$issue_num = $title_arr[0];
				
				if($issue_num=="NA"){
					$title_book = preg_replace("/~NA/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
				}
				else{
					$title_book = preg_replace("/~$issue_num/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";					
				}
			}
		}

		// Search by Price
		//if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
		//	
		//	//$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
		//	$sql .= " HAVING ( IFNULL(special,p.price) >= '".$data['min_price']."' AND IFNULL(special,p.price) <= '".$data['max_price']."' )";			
		//
		//	//$sql .= " AND ( p.price >= '".$data['min_price']."' AND p.price <= '".$data['max_price']."' )";			
		//}
		
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {			
			$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
			//$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}

		//echo '<br/><br/>'.$sql;
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}


	public function getAllTypesProducts($data=false) {
		//print_r($data);
		
		$sql = "SELECT p.*,pd.*,G.*,u.firstname as user_fname,u.lastname as user_lname,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = '".$data['posted_by']."' AND new_release = '".$data['new_release']."'";
		
		// Search by comic_label
		if (isset($data['comic_label']) && $data['comic_label']!="") {
			
			$sql .= " AND p.comic_label LIKE '".$data['comic_label']."'";
		}

		// Search by publisher
		//if (isset($data['publisher']) && $data['publisher']!="") {
		//	
		//	$sql .= " AND p.publisher LIKE '%".$data['publisher']."%'";
		//}
		
		if (isset($data['customer_filter']) && $data['customer_filter']!="") {			
			$sql .= " AND concat(customer_id,'_',role) IN (".$data['customer_filter'].")";
		}
		

		// Search by is_coming_soon
		if (isset($data['is_coming_soon']) && $data['is_coming_soon']!="") {
			
			$sql .= " AND p.is_coming_soon = ".$data['is_coming_soon']."";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			
			if(strpos($data['title_book'],"~") === false){
				$sql .= " AND  (p.model LIKE '%".$data['title_book']."%')";			
			}
			else{
				
				//$sql .= " AND  p.model = '".$data['title_book']."'";
				$title_arr = array_reverse(explode("~",$data['title_book']));
				$issue_num = $title_arr[0];
				
				if($issue_num=="NA"){
					$title_book = preg_replace("/#NA/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
				}
				else{
					$title_book = preg_replace("/#$issue_num/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_arr[1]."' AND p.issue_number = '".$issue_num."')";					
				}
			}
		}
		
		// Search by Price
		//if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {			
		//	$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
		//	//$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		//}
		
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {			
			$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
			//$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
		}
		
		
		
		
		$sort_data = array(
			'name',
			'price',
			'grade',
			'sort_order',
			'date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {

			if($data['sort']=="price")
				$sql .= " ORDER BY IFNULL(special,price)";
			else
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
		
		//if($data['new_release'] == 0 && $data['posted_by'] == 0)
		//{
		//	echo $sql.'<br/><br/>';
		//}
		
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}
	}
	/* ======================================= For Pre Order =========================================================== */	
	
	function getPriceRange(){
		//$sql = "SELECT (min(price)) as min_price,(max(price)) as max_price FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.posted_by = 1 ";
		
		$sql = "select MIN(tbl.pricing) as min_price ,MAX(tbl.pricing) as max_price from (SELECT IFNULL(ps.price,p.price) pricing FROM  " . DB_PREFIX . "product p LEFT JOIN  " . DB_PREFIX . "product_special ps  ON(ps.`product_id` = p.`product_id`) WHERE p.status = 1 AND p.posted_by = 1) as tbl";
		$query = $this->db->query($sql);
		return $query->row;
	}
	function getNonFeaturePriceRange(){
		$sql = "SELECT (min(price)) as min_price,(max(price)) as max_price FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.posted_by = 1 AND p.feature = 0 ";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getProductsTitle($data = array()) {
		//print_r($data);
		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (($data['filter_param'])!="") {
			$sql .= " AND p.new_release = '" . $this->db->escape($data['filter_param']) . "'";
		}


		$sql .= " GROUP BY pd.name,p.issue_number ORDER BY pd.name ASC";
		//echo $sql;

		
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

	public function getNonFeatureProductsPublisher($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 1 AND p.feature = 0 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.publisher LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (($data['filter_param'])!="") {
			$sql .= " AND p.new_release = '" . $this->db->escape($data['filter_param']) . "'";
		}


		$sql .= " GROUP BY publisher ORDER BY pd.name ASC";
		//echo $sql;

		
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
	
	
	public function getProductsPublisher($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 1";

		if (!empty($data['filter_name'])) {
			$sql .= " AND p.publisher LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (($data['filter_param'])!="") {
			$sql .= " AND p.new_release = '" . $this->db->escape($data['filter_param']) . "'";
		}


		$sql .= " GROUP BY publisher ORDER BY pd.name ASC";
		//echo $sql;

		
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
	
	
	public function getAllProductsTitle($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 1 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (($data['filter_param'])!="") {
			$sql .= " AND p.new_release = '" . $this->db->escape($data['filter_param']) . "'";
		}


		$sql .= " GROUP BY pd.name,p.issue_number ORDER BY pd.name ASC";
		//echo $sql;

		
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

	
	public function getNonFeatureProductsTitle($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 1 AND p.feature = 0 ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
		if (($data['filter_param'])!="") {
			$sql .= " AND p.new_release = '" . $this->db->escape($data['filter_param']) . "'";
		}


		$sql .= " GROUP BY pd.name,p.issue_number ORDER BY pd.name ASC";
		//echo $sql;

		
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

	
	
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ For Search result ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

	public function getSearchProducts($data=false) {
		//print_r($data);
		
		$sql = "SELECT p.*,pd.*,G.*,c.firstname as cust_fname,c.lastname as cust_lname,u.firstname as user_fname,u.lastname as user_lname,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id)  WHERE p.status = 1 ";
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			
			if(strpos($data['title_book'],'-')!== false)
			{
				$title_arr = array_reverse(explode("-",$data['title_book']));				
				$issue_num = $title_arr[0];
				if($issue_num=="NA"){
					$title_book = preg_replace("/-NA/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
				}
				else{
					$title_book = preg_replace("/-$issue_num/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";
					
				}
			}
			else{
				$sql .= " AND  (p.model LIKE '%".$data['title_book']."%')";
			}
		}
		
		// Search by Vendor
		if (isset($data['customer_filter']) && $data['customer_filter']!="") {			
			$sql .= " having concat(customer_id,'_',role) IN (".$data['customer_filter'].")";
		}

		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {			
			$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
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
		
		$query = $this->db->query($sql);

		if ($query->num_rows) {
			return $query->rows;
		} else {
			return 0;
		}
	}
	
	
	public function getTotalSearchProducts($data = false) {
		$sql = "SELECT p.product_id,p.customer_id,p.role FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "customer c ON (c.customer_id = p.customer_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 ";
		
		
		// Search by Title
		
		if (isset($data['title_book']) && $data['title_book']!="") {
			
			if(strpos($data['title_book'],"~") === false){
				$sql .= " AND  (p.model LIKE '%".$data['title_book']."%')";			
			}
			else{
				
				//$sql .= " AND  p.model = '".$data['title_book']."'";
				$title_arr = array_reverse(explode("~",$data['title_book']));
				$issue_num = $title_arr[0];
				
				if($issue_num=="NA"){
					$title_book = preg_replace("/#NA/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
				}
				else{
					$title_book = preg_replace("/#$issue_num/",'',$data['title_book'],'1');
					$sql .= " AND  (p.model = '".$title_arr[1]."' AND p.issue_number = '".$issue_num."')";					
				}
			}
		}
		
		// Search by Vendor
		if (isset($data['customer_filter']) && $data['customer_filter']!="") {			
			$sql .= " having concat(customer_id,'_',role) IN (".$data['customer_filter'].")";
		}
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {			
			$sql .= " HAVING ( IFNULL(special,price) >= '".$data['min_price']."' AND IFNULL(special,price) <= '".$data['max_price']."' )";
		}


		
		
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	public function getProTitleIssue($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' ";

		if (!empty($data['filter_name'])) {
			$sql .= " AND ((pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%' ) OR (p.issue_number LIKE '" . $this->db->escape($data['filter_name']) . "%' ) OR (p.publisher LIKE '" . $this->db->escape($data['filter_name']) . "%' ))";
		}

		$sql .= " GROUP BY pd.name,p.issue_number ORDER BY pd.name ASC";
		//echo $sql;

		
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
	
	public function getAllSearchCustomers($model_name){
		$sql = "SELECT c.customer_id as id,c.firstname as fname,c.lastname as lname, 'seller' as role FROM " . DB_PREFIX . "customer c WHERE c.status = 1 UNION SELECT u.user_id as id,u.firstname as fname,u.lastname as lname ,'admin' as role FROM " . DB_PREFIX . "user u ";
		$query = $this->db->query($sql);
		return $query->rows;
	}
	function getSearchProNumByCustomer($customer_id,$role,$model){
		$sql = "SELECT count(p.product_id) as total FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND customer_id = '".$customer_id."' AND role = '".$role."' AND model = '".$model."'";
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	function getPriceRangeSearch($data){
		if(strpos($data['title_book'],'-')=== false){
			$sql = "select MIN(tbl.pricing) as min_price ,MAX(tbl.pricing) as max_price from (SELECT IFNULL(ps.price,p.price) pricing FROM  " . DB_PREFIX . "product p LEFT JOIN  " . DB_PREFIX . "product_special ps  ON(ps.`product_id` = p.`product_id`) WHERE p.status = 1 AND p.model LIKE '%".$data."%') as tbl";

			$query = $this->db->query($sql);
			return $query->row;
		}
		else{
			$title_arr = array_reverse(explode("-",$data));
			$issue_num = $title_arr[0];
			
			if($issue_num=="NA"){
				$title_book = preg_replace("/-NA/",'',$data,'1');
			}
			else{
				$title_book = preg_replace("/-$issue_num/",'',$data,'1');
			}
			
			$sql = "select MIN(tbl.pricing) as min_price ,MAX(tbl.pricing) as max_price from (SELECT IFNULL(ps.price,p.price) pricing FROM  " . DB_PREFIX . "product p LEFT JOIN  " . DB_PREFIX . "product_special ps  ON(ps.`product_id` = p.`product_id`) WHERE p.status = 1 AND p.model LIKE '%".$title_book."%' AND issue_number = '".$issue_num."') as tbl";
					
			//$sql = "SELECT (min(price)) as min_price,(max(price)) as max_price FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.model = '".$title_book."' AND issue_number = '".$issue_num."'";
			$query = $this->db->query($sql);
			return $query->row;
		}
		
			
	}

		function getPriceRangeSearch_bk($data){
			
			
			if(strpos($data,"'")!= 0)
			{
				$title_book_arr = explode("'",$data);
				$book_name = $title_book_arr[0];
			}
			else
			{
				$book_name = $data;
			}
			
		if(strpos($book_name,'~')=== false){
			$sql = "select MIN(tbl.pricing) as min_price ,MAX(tbl.pricing) as max_price from (SELECT IFNULL(ps.price,p.price) pricing FROM  " . DB_PREFIX . "product p LEFT JOIN  " . DB_PREFIX . "product_special ps  ON(ps.`product_id` = p.`product_id`) WHERE p.status = 1 AND p.model LIKE '%".$book_name."%') as tbl";
			
			
			
			
			$query = $this->db->query($sql);
			return $query->row;
		}
		else{
			$title_arr = array_reverse(explode("~",$book_name));
			$issue_num = $title_arr[0];
			
			if($issue_num=="NA"){
				$title_book = preg_replace("/-NA/",'',$book_name,'1');
			}
			else{
				$title_book = preg_replace("/-$issue_num/",'',$book_name,'1');
			}
			
			$sql = "select MIN(tbl.pricing) as min_price ,MAX(tbl.pricing) as max_price from (SELECT IFNULL(ps.price,p.price) pricing FROM  " . DB_PREFIX . "product p LEFT JOIN  " . DB_PREFIX . "product_special ps  ON(ps.`product_id` = p.`product_id`) WHERE p.status = 1 AND p.model LIKE '%".$title_arr[1]."%' AND issue_number = '".$issue_num."') as tbl";
				
			
					
			//$sql = "SELECT (min(price)) as min_price,(max(price)) as max_price FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.model = '".$title_book."' AND issue_number = '".$issue_num."'";
			$query = $this->db->query($sql);
			return $query->row;
		}
		
			
	}
	
	
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ End of Search result ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	



// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ For Grading Service ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	public function getGraingPro($data=false) {
		//print_r($data);
		
		$sql = "SELECT p.*,pd.*,G.*,u.firstname as user_fname,u.lastname as user_lname,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND p.posted_by = 1 AND (p.new_release = 2 )";	//OR p.new_release = 1
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			//$sql .= " AND ( price >= '".$data['min_price']."' AND  price <= '".$data['max_price']."')";
			$sql .= " AND ( grading_price >= '".$data['min_price']."' AND  grading_price <= '".$data['max_price']."')";
		}
		
		// Search by publisher
		if (isset($data['publisher']) && $data['publisher']!="") {
			
			$sql .= " AND publisher = '".$data['publisher']."'";
		}
		
		if (isset($data['variant']) && $data['variant']!="") {
			//$sql .= " AND  p.model = '".$data['title_book']."'";
			$sql .= " AND  (p.variant = '".$data['variant']."')";
			
			
		}
		   // Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
		
			$title_arr = array_reverse(explode("-",$data['title_book']));
			$issue_num = $title_arr[0];
			
			
			if($issue_num=="NA"){
				$title_book = preg_replace("/-NA/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
			}
			else{
				$title_book = preg_replace("/-$issue_num/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";
				
			}
			
			
			//$title_book = preg_replace("/-$issue_num/",'',$data['title_book'],'1');
			//$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";	
		}
		
		
		$sort_data = array(
			'name',
			'price',
			'grade',
			'sort_order',
			'date_added'
		);

		
		
		if(($data['sort']!="") && ($data['order']!=""))
		{
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
		}
		else
		{
			$sql .= " ORDER BY p.sort_order DESC";
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
		
	
	public function getTotalGraingPro($data = false) {
		$sql = "SELECT p.product_id,(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "grade G ON (p.grade = G.id) LEFT JOIN " . DB_PREFIX . "user u ON (u.user_id = p.customer_id) WHERE p.status = 1 AND (p.new_release = 2 ) AND  p.posted_by = 1";	//OR p.new_release = 1
		
		// Search by Price
		if (isset($data['min_price']) && $data['min_price']!="" && isset($data['max_price']) && $data['max_price']!="") {
			
			$sql .= " AND ( grading_price >= '".$data['min_price']."' AND  grading_price <= '".$data['max_price']."')";
		}

		// Search by publisher
		if (isset($data['publisher']) && $data['publisher']!="") {
			
			$sql .= " AND publisher = '".$data['publisher']."'";
		}
		
		// Search by Title
		if (isset($data['title_book']) && $data['title_book']!="") {
			if($issue_num=="NA"){
				$title_book = preg_replace("/-NA/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = -1)";
			}
			else{
				$title_book = preg_replace("/-$issue_num/",'',$data['title_book'],'1');
				$sql .= " AND  (p.model = '".$title_book."' AND p.issue_number = '".$issue_num."')";
				
			}
		}
		//echo $sql;
		$query = $this->db->query($sql);
		return $query->num_rows;
	}
	
	


	
	function getGradePriceRange(){
		
		//echo $sql = "select MIN(tbl.pricing) as min_price ,MAX(tbl.pricing) as max_price from (SELECT IFNULL(p.grading_price,ps.price,p.price) pricing FROM  " . DB_PREFIX . "product p LEFT JOIN  " . DB_PREFIX . "product_special ps  ON(ps.`product_id` = p.`product_id`) WHERE p.status = 1 AND p.posted_by = 1 AND ( p.new_release = 2)) as tbl";
		
		$sql = "SELECT (min(grading_price)) as min_price,(max(grading_price)) as max_price FROM " . DB_PREFIX . "product p  WHERE p.status = 1 AND p.posted_by = 1 AND ( p.new_release = 2) ";	//p.new_release = 1 OR
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getGradingProTitle($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 1 AND (p.new_release = 2) ";	//p.new_release = 1 OR 

		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}


		$sql .= " GROUP BY pd.name,p.issue_number ORDER BY pd.name ASC";
		//echo $sql;

		
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
	
	public function getGradingPublisher($data = array()) {

		$sql = "SELECT * FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.posted_by = 1 AND ( p.new_release = 2)";	//p.new_release = 1 OR

		if (!empty($data['filter_name'])) {
			$sql .= " AND p.publisher LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}

		$sql .= " GROUP BY publisher ORDER BY pd.name ASC";
		//echo $sql;

		
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
	
	
	
// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^ End of  Grading Service ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
	

// ****************************************************************** My Code *********************************************************	
	
}