<?php
class ControllerSellerProductmarketList extends Controller {
	private $error = array();

	public function autocompleteCustomer() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('seller_product/product');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
				
			);

			$results = $this->model_seller_product_product->getCustomerList($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					//'show_name' 	=> $result['fname'].' '.$result['lname'].'( '.$result['username'].' )',
					'show_name' 	=> $result['username'],
					'value'       	=> $result['id'].'_'.$result['role']
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	
	public function autocompleteTitle() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('seller_product/product');

			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
				
			);

			$results = $this->model_seller_product_product->getMarketProductsTitle($filter_data);

			foreach ($results as $result) {
				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8').'#'.(($result['issue_number']==-1)?'NA':$result['issue_number']))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	
	public function index(){
		$this->load->model('seller_product/product');
		
		$this->document->setTitle('Market Street');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Market-List',
			'href' => $this->url->link('seller_product/market_list', '', 'SSL')
		);

		$this->load->model('seller_product/product');
		$this->load->model('tool/image');
		
	
	// ***************************************** Get Market Street banner  ***************************************************************
	
		$comic_banners = $this->model_seller_product_product->getMarketBanner();
		if(!empty($comic_banners)){
		  foreach ($comic_banners as $result) {			
			if (is_file(DIR_IMAGE.'banner_types_image/' . $result['images'])) {
				//$image = $this->model_tool_image->resize('banner_types_image/'.$result['images'], 1000, 600);
				$image = 'image/banner_types_image/' . $result['images'];
			} else {
				$image = $this->model_tool_image->resize('no_image.png', 1000, 600);
			}
			
			$data['comic_banners'][] = array(
				'id'  				=> $result['banner_id'],
				'text'  			=> html_entity_decode($result['text']),
				'banner_type'  			=> $result['banner_type'],
				'link'       			=> $result['link'],
				'thumb_image' 			=> $image
			);
		  }
		}
	
	
		
	// ***************************************** End of get Market Street banner  ***************************************************************
		// ***************************************** New Arrivals ***************************************************************
		
		
		$data['feature_products'] = array();
		$results_features = $this->model_seller_product_product->getFeautureMarket();
		
		if(!empty($results_features)){
		  foreach ($results_features as $each_feature) {
			if ($each_feature['image']) {
				$image = $this->model_tool_image->resize($each_feature['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img = $this->model_tool_image->resize($each_feature['image'], 699, 875);
				$model_main_img1 = $this->model_tool_image->resize($each_feature['image1'], 699, 875);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img1 = $model_main_img = $this->model_tool_image->resize('placeholder.png', 699, 875);
			}
			$now_allow = 0;
			// check for adult image
			if (($each_feature['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $each_feature['adult'])) {
				$image =  $this->model_tool_image->resize('adults-only.jpg', 280, 280);
				$model_main_img1 = $model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
				$now_allow = 1;
			}
			if($this->customer->isLogged() && $this->customer->getId()==$each_feature['customer_id'])
				$now_allow = 1;


			$author_name = '';
			//if ($each_feature['cust_fname'] && $each_feature['cust_lname']) {
			if ($each_feature['username']) {
				$author_name = $each_feature['username'];
			}
			if ($each_feature['user_fname'] && $each_feature['user_lname']) {
				$author_name = $each_feature['user_fname'].' '.$each_feature['user_lname'];
			}


			
			// Set all the products field
			$data['feature_products'][] = array(
				'product_id'  		=> $each_feature['product_id'],
				'quantity'  		=> $each_feature['quantity'],
				'special'  		=> $each_feature['special'],
				'variant'  		=> ($each_feature['variant']!="")?'('.$each_feature['variant'].')':'',
				'short_description'  	=> $each_feature['short_description'],
				'certification_number'  => $each_feature['certification_number'],
				'publisher'  		=> $each_feature['publisher'],
				'thumb'       		=> $image,
				'author_name'       	=> $author_name,
				'now_allow'       	=> $now_allow,
				'model_main_img'       	=> $model_main_img,
				'model_main_img1'       => $model_main_img1,
				'name'        		=> $each_feature['name'].' #'.$each_feature['issue_number'],
				'certification_number'  => $each_feature['certification_number'],
				'page_quality'  	=> $each_feature['page_quality'],
				'grade'  		=> $each_feature['grade_value'],
				'description' 		=> utf8_substr(strip_tags(html_entity_decode($each_feature['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'       		=> $each_feature['price'],
				'minimum'     		=> $each_feature['minimum'] > 0 ? $each_feature['minimum'] : 1,
				'href'        		=> $this->url->link('product/product',  '&product_id=' . $each_feature['product_id'])
			);
		  }
		  
		}
		
		
		// ***************************************** New Arrivals ***************************************************************
		
	// ************************************ Get All Customer Name **********************************************************	
		
		$customers = $this->model_seller_product_product->getAllCustomers();
		if(!empty($customers)){
			foreach($customers as $each_customer){
				$no_pro = $this->model_seller_product_product->getProNumByCustomer($each_customer['id'],$each_customer['role']);
				//$data['customer'][] = array('id'=>$each_customer['id'],'name'=>$each_customer['fname'].' '.$each_customer['lname'],'role'=>$each_customer['role'],'product_count'=>$no_pro);
				$data['customer'][] = array('id'=>$each_customer['id'],'name'=>$each_customer['username'],'role'=>$each_customer['role'],'product_count'=>$no_pro);
			}
		}
		

	// ************************************ Get All Customer Name **********************************************************	
	
	// ************************************ Get price range **********************************************************	
		
		$price_range = $this->model_seller_product_product->getPriceRange();
		if(!empty($price_range['min_price']) && !empty($price_range['max_price'])){
			$data['min_value'] = (int)$price_range['min_price'];
			$data['max_value'] = (int)$price_range['max_price'];	
		}
		else{
			$data['min_value'] = 0;
			$data['max_value'] = 0;
			
		}
	
		
		
		
		

	// ************************************ Get price range **********************************************************
	
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/index.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/index.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller_product/index.tpl', $data));
		}
		
	}
	
	public function loadcontent(){
		
		
		$this->load->model('seller_product/product');
		$this->load->model('tool/image');
		
		
		//continue only if $_POST is set and it is a Ajax request
		if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		
		//Get page number from Ajax POST
		if(isset($_POST["page"])){
			$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
			if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
		}else{
			$page_number = 1; //if there's no page number, set it to 1
		}
		
		if(isset($_POST["orderby"])){
			list($sort,$order) = explode("-",$_POST["orderby"]);
		}
		else{
			$order = '';
			$sort = '';
		}
		if(isset($_POST["customer_filter"]) && $_POST["customer_filter"]!=""){
			
			$str = '';
			$customer_filter = explode(",",$_POST["customer_filter"]);
			foreach($customer_filter as $each_customer1){
				$str .= "'".$each_customer1."',";
			}
			$customer_filter = rtrim($str,',');
		}
		else{
			$customer_filter = '';
		}
		
		if(isset($_POST["min_price"]) && $_POST["min_price"]!=""){
			$min_price = $_POST["min_price"];
		}
		else{
			$min_price = '';
		}
		if(isset($_POST["max_price"]) && $_POST["max_price"]!=""){
			$max_price = $_POST["max_price"];
		}
		else{
			$max_price = '';
		}
		if(isset($_POST["title_book"]) && $_POST["title_book"]!=""){
			$title_book = trim($_POST["title_book"]);
		}
		else{
			$title_book = '';
		}
		
		if((isset($_POST["book_quantity_status"])) && ($_POST["book_quantity_status"]!="")){
			$book_quantity_status = $_POST["book_quantity_status"];
		}
		else{
			$book_quantity_status = 1;
		}
		
		if(isset($_POST["per_page_limit"]) && $_POST["per_page_limit"]!=""){
			$limit = $_POST["per_page_limit"];
		}
		else{
			$limit = 6;
		}
		
		
		$filter_data_search = array(
			'customer_filter' 	=>$customer_filter,
			'min_price' 		=>$min_price,
			'max_price' 		=>$max_price,
			'title_book' 		=>$title_book,
			'book_quantity_status' =>$book_quantity_status,
		);
		
		
	// ********************************* Code *********************************************
		$whereClause = '';
		
		$product_total = $this->model_seller_product_product->getTotalgetMarketStreetProducts($filter_data_search);
		$item_per_page = $limit;
		
		//break records into pages
		$total_pages = ceil($product_total/$item_per_page);
		
		
		$filter_data = array(
			'customer_filter'    => $customer_filter,
			'min_price' 	     => $min_price,
			'max_price' 	     => $max_price,
			'title_book' 	     => $title_book,
			'book_quantity_status' =>$book_quantity_status,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page_number - 1) * $limit,
			'limit'              => $limit
		);
		
		$all_records = $this->model_seller_product_product->getMarketStreetProducts($filter_data);
		
		
		
		if(!empty($all_records)){
		  foreach ($all_records as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);

			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img = $this->model_tool_image->resize('placeholder.png', 699, 875);
			}

			if ($result['image1']) {
				$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img1 = $this->model_tool_image->resize($result['image1'], 699, 875);
			} else {
				$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img1 = $this->model_tool_image->resize('placeholder.png', 699, 875);
			}
			$now_allow = 0;
			// check for adult image
			if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'] )) {
				$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
				$model_main_img = $model_main_img1 = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
				$now_allow = 1;
			}
			
			if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
				$now_allow = 1;

			
			$author_name = '';
			//if ($result['cust_fname'] && $result['cust_lname']) {
			if ($result['username']) {
				$author_name = $result['username'];
			}
			if ($result['user_fname'] || $result['user_lname']) {
				$author_name = $result['user_fname'].' '.$result['user_lname'];
			}

			$data['all_records'][] = array(
				'product_id'  		=> $result['product_id'],
				'quantity'  		=> $result['quantity'],
				'posted_by'  		=> $result['posted_by'],
				'grade_meta'  		=> $result['grade_meta'],
				'special'  		=> $result['special'],
				'short_description'  	=> $result['short_description'],
				'grade_value'  		=> $result['grade_value'],
				'variant'  		=> ($result['variant']!="")?' ('.$result['variant'].')':'',
				'page_quality'  	=> $result['page_quality'],
				'issue_number'  	=> $result['issue_number'],
				'publisher'  		=> $result['publisher'],
				'certification_number'  => $result['certification_number'],
				'quantity'  		=> $result['quantity'],
				'author_name'   	=> $author_name,
				'now_allow'   		=> $now_allow,
				'thumb'       		=> $image,
				'thumb1'       		=> $image1,
				'model_main_img'       	=> $model_main_img,
				'model_main_img1'      => $model_main_img1,
				'name'        		=> $result['name'],
				'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'       		=> $result['price'],
				'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
			);
		  }
		}
		

		
		
	// ********************************* Code *********************************************
		
		
		
		
		//Display records fetched from database.
		
		$data['item_per_page'] = $item_per_page;
		$data['page_number'] = $page_number;
		$data['get_total_rows'] = $product_total;
		$data['total_pages'] = $total_pages;
		
		$data['class_value'] = '';
		if($_POST["class_value"]!=""){
			$data['class_value'] = $_POST["class_value"];
		}
		
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/market_list_view.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/market_list_view.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller_product/market_list_view.tpl', $data));
		}
		
		}
	}
	
	
}