<?php
class ControllerGradingServiceGradingService extends Controller {
	private $error = array();
	
	public function autocompletePublisher() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('seller_product/comicproduct');
		
	
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

			$results = $this->model_seller_product_comicproduct->getGradingPublisher($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['publisher'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	
	public function autocompleteTitle() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('seller_product/comicproduct');
	
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

			$results = $this->model_seller_product_comicproduct->getGradingProTitle($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8').'-'.$result['issue_number'])
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	
	
	public function index(){
		$this->document->setTitle('Grading Service');
		
		
		$this->load->model('catalog/product');
		
		if(isset($_GET['name']))
		{
		    $prod_id = base64_decode($_GET['name']);
			$pro_arr = $this->model_catalog_product->getProduct($prod_id);
			$pro_name = $pro_arr['name'].'-'.$pro_arr['issue_number'];
			$variant_name = $pro_arr['variant'];
		}
		else
		{
		    $pro_name = "";
			$variant_name = "";
		}
		
		
		$data['pro_name'] = $pro_name;
		$data['variant_name'] = $variant_name;
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		// Load Model
		$this->load->model('setting/setting');
		$this->load->model('seller_product/comicproduct');

		// Grading service 
		$data['grading_service_image'] = $this->model_setting_setting->getContactSettingByKey('grading_service_image');
		$data['grading_service_banner_text'] = $this->model_setting_setting->getContactSettingByKey('grading_service_banner_text');
		$data['grading_service_text'] = $this->model_setting_setting->getContactSettingByKey('grading_service_text');
		$data['grading_service_link'] = $this->model_setting_setting->getContactSettingByKey('grading_service_link');
		
	
		$price_range = $this->model_seller_product_comicproduct->getGradePriceRange();
		if(!empty($price_range)){
			$data['min_value'] = $price_range['min_price'];
			$data['max_value'] = $price_range['max_price'];	
		}
		else{
			$data['min_value'] = 0;
			$data['max_value'] = 0;
			
		}

		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/grading_service/list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/grading_service/list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/grading_service/list.tpl', $data));
		}

	}
	
	
	public function loadcontent(){
		
		$this->load->model('seller_product/product');
		$this->load->model('seller_product/comicproduct');
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
		
		if(isset($_POST["book_variant"]) && $_POST["book_variant"]!=""){
			$book_variant = trim($_POST["book_variant"]);
		}
		else{
			$book_variant = '';
		}
		if(isset($_POST["publisher"]) && $_POST["publisher"]!=""){
			$publisher = trim($_POST["publisher"]);
		}
		else{
			$publisher = '';
		}
		
				
		$filter_data_search = array(
			'min_price' 		=>$min_price,
			'max_price' 		=>$max_price,
			'title_book' 		=>$title_book,
			'variant' 		=>$book_variant,
			'publisher' 		=>$publisher
		);
		
		
	// ********************************* Code *********************************************
		
		$product_total = $this->model_seller_product_comicproduct->getTotalGraingPro($filter_data_search);
		//print_r($product_total);exit;
		$item_per_page = $limit = 15;
		
		//break records into pages
		$total_pages = ceil($product_total/$item_per_page);
		
		
		$filter_data = array(
			'min_price' 	     => $min_price,
			'max_price' 	     => $max_price,
			'title_book' 	     =>$title_book,
			'variant' 		    =>$book_variant,
			'publisher' 	     =>$publisher,
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page_number - 1) * $limit,
			'limit'              => $limit
		);
		
		$all_records = $this->model_seller_product_comicproduct->getGraingPro($filter_data);
		
		
		
		if(!empty($all_records)){
		  foreach ($all_records as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$image_big = $this->model_tool_image->resize($result['image'], 500, 500);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$image_big = $this->model_tool_image->resize('placeholder.png', 500, 500);
			}

			//if ($result['image1']) {
			//	$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			//} else {
			//	$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			//}
			
			$now_allow = 0;
			// check for adult image
			if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
				$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 228, 228);
				$image_big = $this->model_tool_image->resize('adults-only.jpg', 500, 500);
				$now_allow = 1;
			}
			if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
				$now_allow = 1;
			
			$author_name = '';
			$author_name = $result['user_fname'].' '.$result['user_lname'];
			

			if(($result['posted_by'] == 1) && (($result['new_release'] == 1) || ($result['new_release'] == 2)))
			{
				$pull_avalable = 1;
			}
			else
			{
				$pull_avalable = 0;
			}
			$this->load->model('catalog/product');
			
			$variants_arr = array();
			$variants_list = $this->model_catalog_product->get_all_variants($result['product_id']);
			if(!empty($variants_list))
			{
				$i=0;
				foreach($variants_list as $variants)
				{
					$variants_arr[$i]['product_id'] = $variants['product_id'];
					$variants_arr[$i]['name'] = $variants['name'];
					$variants_arr[$i]['issue_number'] = $variants['issue_number'];
					$variants_arr[$i]['variant'] = $variants['variant'];
					$url = $this->model_catalog_product->get_book_url($variants['product_id']);
					$variants_arr[$i]['url'] = $url;
					$i++;
				}
			}
			
			
			
			$data['all_records'][] = array(
				'product_id'  		=> $result['product_id'],
				'certification_number'  => $result['certification_number'],
				'issue_number'  	=> $result['issue_number'],
				'variant'  	=> $result['variant'],
				'publisher'  		=> $result['publisher'],
				'author_name'   	=> $author_name,
				'now_allow'   		=> $now_allow,
				'image_big'       	=> $image_big,
				'thumb'       		=> $image,
				'name'        		=> $result['name'],
				'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, (4*$this->config->get('config_product_description_length'))) . '..',
				'price'       		=> $result['price'],
				'grading_price'     => $result['grading_price'],
				'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] ),
				'pull_avalable'      => $pull_avalable,
				'variants_arr'      => $variants_arr,
				
			);
			
		  }
		}
		

		
		
	// ********************************* Code *********************************************
		
		//Display records fetched from database.
		
		$data['item_per_page'] = $item_per_page;
		$data['page_number'] = $page_number;
		$data['get_total_rows'] = $product_total;
		$data['total_pages'] = $total_pages;
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/grading_service/grading_list_view.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/grading_service/grading_list_view.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/grading_service/grading_list_view.tpl', $data));
		}
		
		}
	}
	
	
}