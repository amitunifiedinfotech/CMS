<?php
class ControllerSellerProductcomicList extends Controller {
	private $error = array();

	public function autocompletePublisher() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('seller_product/product');
			$this->load->model('seller_product/comicproduct');
		
	
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			if (isset($this->request->get['param'])) {
				$filter_param = $this->request->get['param'];
			} else {
				$filter_param = 0;
			}

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_param'  => $filter_param,
				'start'        => 0,
				'limit'        => $limit
				
			);

			$results = $this->model_seller_product_comicproduct->getProductsPublisher($filter_data);

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
			$this->load->model('seller_product/product');
			$this->load->model('seller_product/comicproduct');
		
	
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			
			/*if (isset($this->request->get['param'])) {
				$filter_param = $this->request->get['param'];
			} else {
				$filter_param = 0;
			}*/
			$filter_param = '';

			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'filter_param'  => $filter_param,
				'start'        => 0,
				'limit'        => $limit
				
			);

			$results = $this->model_seller_product_comicproduct->getAllProductsTitle($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8').'~'.(($result['issue_number']==-1)?'NA':$result['issue_number']))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	
	public function index(){
		$this->load->model('seller_product/product');
		
		$this->document->setTitle('Comic Store');
		$this->load->model('seller_product/comicproduct');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Comic-List',
			'href' => $this->url->link('seller_product/comic_list', '', 'SSL')
		);
		$this->load->model('seller_product/product');
		$this->load->model('tool/image');
		
		
		// ***************************************** Site-Settings ***************************************************************
		//$this->load->model('setting/setting');
		//
		//// Banner
		//$data['comic_page_banner_link'] = $this->model_setting_setting->getContactSettingByKey('comic_page_banner_link');
		//$data['comic_page_banner_text'] = $this->model_setting_setting->getContactSettingByKey('comic_page_banner_text');
		//$data['comic_page_banner'] = $this->model_setting_setting->getContactSettingByKey('comic_page_banner');
		
		
		// ***************************************** Get Market Street banner  ***************************************************************
		
			$comic_banners = $this->model_seller_product_product->getComicBanner();
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
					'text_title'  			=> html_entity_decode($result['text_title']),
					'text'  			=> html_entity_decode($result['text']),
					'banner_type'  			=> $result['banner_type'],
					'link'       			=> $result['link'],
					'thumb_image' 			=> $image
				);
			  }
			}
		
		// ***************************************** End of get Market Street banner  ***************************************************************
		
		
		
		
		// ***************************************** New Arrivals ***************************************************************
			
			
			$data['products'] = array();
			$results = $this->model_seller_product_product->getFeautureProComic();
			
			if(!empty($results)){
			  foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					$model_main_img = $this->model_tool_image->resize('placeholder.png', 699, 875);
				}
				
				$now_allow = 0;
				// check for adult image
				if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
					$image = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
					$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
					$now_allow = 1;
				}
				if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
					$now_allow = 1;
				
				$author_name = '';
				$author_name = $result['user_fname'].' '.$result['user_lname'];
				
				$coming_value = $this->cart->checkComingButtons($result['product_id']);
				
				// Set all the products field
				$data['feature_products'][] = array(
					'product_id'  		=> $result['product_id'],
					'grade'  		=> $result['grade_value'],
					'special'  		=> $result['special'],
					'quantity'  		=> $result['quantity'],
					'publisher'  		=> $result['publisher'],
					'variant'  		=> $result['variant'],
					'author_name'       	=> $author_name,
					'now_allow'       	=> $now_allow,
					'coming_value'       	=> $coming_value,
					'thumb'       		=> $image,
					'model_main_img'       	=> $model_main_img,
					'certification_number'  => $result['certification_number'],
					'short_description'  	=> $result['short_description'],
					'page_quality'  	=> $result['page_quality'],
					'name'        		=> $result['name'].' #'.(($result['issue_number']==-1)?'N/A':$result['issue_number']),
					'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
					'price'       		=> $result['price'],
					'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
					'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'])
				);
			  }
			  
			}
			//print_r($data['products']);
			
		// ***************************************** New Arrivals ***************************************************************
		
		
	
		// ************************************ Get price range **********************************************************	
			
			//$data['price_range'] = $this->model_seller_product_comicproduct->getPriceRange();
			//$min_value = $data['price_range']['min_price'];
			//$max_value = $data['price_range']['max_price'];
			//
			//for($i=$min_value;$i<$max_value;$i=$i+500){
			//	$data['price'][] = $i;
			//}
			//$data['price'][] = $max_value;
			
			$price_range = $this->model_seller_product_comicproduct->getPriceRange();
			//print_r($price_range['min_price']);exit;
			if(!empty($price_range['min_price']) && !empty($price_range['max_price'])){
				$data['min_value'] = (int)$price_range['min_price'];
				$data['max_value'] = (int)$price_range['max_price'];	
			}
			else{
				$data['min_value'] = 0;
				$data['max_value'] = 0;
				
			}
			
			//echo "<pre>";print_r($data);exit;

		// ************************************ End of Get price range **********************************************************
		
			
			
		// ***************************************** Site-Settings ***************************************************************
			$this->load->model('setting/setting');
			$data['label_settings_dtl'] = $this->model_setting_setting->getContactSetting("label");	
			//print_r($data['label_settings_dtl']);exit;

		// ***************************************** Site-Settings ***************************************************************


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/comic_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/comic_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller_product/comic_list.tpl', $data));
		}
		
	}
	
	public function loadcontent(){
		
		$this->load->model('seller_product/product');
		$this->load->model('seller_product/comicproduct');
		$this->load->model('tool/image');
		
		
		//continue only if $_POST is set and it is a Ajax request
		if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
					
			// Initialize all variables
			$new_layout = 0; $comic_label = '';

			// Set Types of book at first
			if(isset($_POST["param"]) && $_POST["param"]!=""){
				
				if($_POST["param"]=='pre_order')
					$param = 2;
				else if($_POST["param"]=='new_release')
					$param = 1;
				else if($_POST["param"]=='backrelease')
					$param = 0;
				else{
					$param = 3;
					$comic_label = $_POST["param"];
				}
			}
			else{
				$param = 0;
			}
			

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
			if(isset($_POST["is_coming_soon"]) && $_POST["is_coming_soon"]!=""){
				$is_coming_soon = trim($_POST["is_coming_soon"]);					
			}
			else{
				$is_coming_soon = '';
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
				$new_layout = 1;
			}
			else{
				$title_book = '';
			}
			if(isset($_POST["publisher"]) && $_POST["publisher"]!=""){
				$publisher = trim($_POST["publisher"]);
			}
			else{
				$publisher = '';
			}
			if(isset($_POST["per_page_limit"]) && $_POST["per_page_limit"]!=""){
				$limit = $_POST["per_page_limit"];
			}
			else{
				$limit = 6;
			}
			
					
			$filter_data_search = array(
				'param'		     	=>$param,
				'is_coming_soon'	=>$is_coming_soon,
				'comic_label'		=>$comic_label,
				'min_price' 		=>$min_price,
				'max_price' 		=>$max_price,
				'title_book' 		=>$title_book,
				'publisher' 		=>$publisher
			);
			
		
			if($title_book=="")
			{

				// ********************************* Code *********************************************
				
				$product_total = $this->model_seller_product_comicproduct->getTotalAllComicProducts($filter_data_search);
				//print_r($product_total);exit;
				//$item_per_page = $limit = 3;
				$item_per_page = $limit;
				
				//break records into pages
				$total_pages = ceil($product_total/$item_per_page);
				
				
				$filter_data = array(
					'param'		    	 =>$param,
					'comic_label'		 =>$comic_label,
					'is_coming_soon'	 =>$is_coming_soon,
					'min_price' 	     => $min_price,
					'max_price' 	     => $max_price,
					'title_book' 	     =>$title_book,
					'publisher' 	     =>$publisher,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page_number - 1) * $limit,
					'limit'              => $limit
				);
				
				$all_records = $this->model_seller_product_comicproduct->getComicAllProducts($filter_data);		
				
				if(!empty($all_records)){
				  foreach ($all_records as $result) {
					if ($result['image']) {
						$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					}

					if ($result['image1']) {
						$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					} else {
						$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
					}
					
					$now_allow = 0;
					// check for adult image
					if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
						$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
						$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
						$now_allow = 1;
					}
					if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
						$now_allow = 1;
					
					$coming_value = $this->cart->checkComingButtons($result['product_id']);

					$author_name = '';
					$author_name = $result['user_fname'].' '.$result['user_lname'];
					

					$data['all_records'][] = array(
						'product_id'  		=> $result['product_id'],
						'certification_number'  => $result['certification_number'],
						'special'  			=> $result['special'],
						'issue_number'  	=> $result['issue_number'],
						'variant'  			=> $result['variant'],
						'quantity'  		=> $result['quantity'],
						'publisher'  		=> $result['publisher'],
						'short_description'  	=> $result['short_description'],
						'author_name'   	=> $author_name,
						'now_allow'   		=> $now_allow,
						'coming_value'       	=> $coming_value,
						'model_main_img'       	=> $model_main_img,
						'thumb'       		=> $image,
						'thumb1'       		=> $image1,
						'name'        		=> $result['name'],
						//'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
						'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0,350) . '..',
						'price'       		=> $result['price'],
						'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
						'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
					);
				  }
				}
				
				//Display records fetched from database.
				$data['item_per_page'] = $item_per_page;
				$data['page_number'] = $page_number;
				$data['get_total_rows'] = $product_total;
				$data['total_pages'] = $total_pages;
				
				$data['class_value'] = '';
				if($_POST["class_value"]!=""){
					$data['class_value'] = $_POST["class_value"];
				}

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/comic_list_view.tpl')) {
					$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/comic_list_view.tpl', $data));
				} else {
					$this->response->setOutput($this->load->view('default/template/seller_product/comic_list_view.tpl', $data));
				}
				
				// ********************************* Code *********************************************
				
			}
			else
			{

				// ********************************* Layout Code *********************************************

					$item_per_page = $limit;
					$data['item_per_page'] = $item_per_page;

				// =============================== For Comic Store Pre-order ===================
					
					if(isset($_POST["page"])){
						$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
						if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
					}else{
						$page_number = 1; //if there's no page number, set it to 1
					}

					$filter_data_search = array(
						'param'		     	=>$param,
						'posted_by'		 	 =>1,
						'new_release'		 =>2,
						'comic_label'		=>$comic_label,
						'min_price' 		=>$min_price,
						'max_price' 		=>$max_price,
						'title_book' 		=>$title_book,
						'publisher' 		=>$publisher
					);
					$pre_order_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
					//print_r($pre_order_product_total);exit;
					
					//break records into pages
					$pre_order_total_pages = ceil($pre_order_product_total/$item_per_page);
					
					
					$filter_data = array(
						'param'		    	 =>$param,
						'comic_label'		 =>$comic_label,
						'posted_by'		 	 =>1,
						'new_release'		 =>2,
						'min_price' 	     => $min_price,
						'max_price' 	     => $max_price,
						'title_book' 	     =>$title_book,
						'publisher' 	     =>$publisher,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page_number - 1) * $limit,
						'limit'              => $limit
					);
					
					$all_preorder_records = $this->model_seller_product_comicproduct->getAllTypesProducts($filter_data);		
					
					if(!empty($all_preorder_records)){
					  foreach ($all_preorder_records as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						if ($result['image1']) {
							$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$now_allow = 0;
						// check for adult image
						if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
							$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
							$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
							$now_allow = 1;
						}
						if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
							$now_allow = 1;
						
						$coming_value = $this->cart->checkComingButtons($result['product_id']);

						$author_name = '';
						$author_name = $result['user_fname'].' '.$result['user_lname'];
						

						$data['all_pre_order_records'][] = array(
							'product_id'  		=> $result['product_id'],
							'certification_number'  => $result['certification_number'],
							'special'  			=> $result['special'],
							'issue_number'  	=> $result['issue_number'],
							'variant'  			=> $result['variant'],
							'quantity'  		=> $result['quantity'],
							'publisher'  		=> $result['publisher'],
							'short_description' => $result['short_description'],
							'author_name'   	=> $author_name,
							'now_allow'   		=> $now_allow,
							'coming_value'      => $coming_value,
							'model_main_img'    => $model_main_img,
							'thumb'       		=> $image,
							'thumb1'       		=> $image1,
							'name'        		=> $result['name'],
							'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       		=> $result['price'],
							'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
							'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
						);
					  }
					}
					

					//Display records fetched from database.
					$data['page_number_pre'] = $page_number;
					$data['get_total_rows_pre'] = $pre_order_product_total;
					$data['total_pages_pre'] = $pre_order_total_pages;


				// =============================== For Comic Store New release ===================
									
					$filter_data_search = array(
						'param'		     	=>$param,
						'posted_by'		 	 =>1,
						'new_release'		 =>1,
						'comic_label'		=>$comic_label,
						'is_coming_soon'	=>$is_coming_soon,
						'min_price' 		=>$min_price,
						'max_price' 		=>$max_price,
						'title_book' 		=>$title_book,
						'publisher' 		=>$publisher
					);
					$new_release_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
					//print_r($new_release_product_total);exit;

					//break records into pages
					$new_release_total_pages = ceil($new_release_product_total/$item_per_page);
					
					//Get page number from Ajax POST
					if(isset($_POST["page1"])){
						$page1 = filter_var($_POST["page1"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
						if(!is_numeric($page1)){die('Invalid page number!');} //incase of invalid page number
					}else{
						$page1 = 1; //if there's no page number, set it to 1
					}
					

					$filter_data = array(
						'param'		    	 => $param,
						'comic_label'		 => $comic_label,
						'posted_by'		 	 => 1,
						'new_release'		 => 1,
						'min_price' 	     => $min_price,
						'max_price' 	     => $max_price,
						'is_coming_soon'	 => $is_coming_soon,
						'title_book' 	     => $title_book,
						'publisher' 	     => $publisher,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page1 - 1) * $limit,
						'limit'              => $limit
					);
					
					$all_new_release_records = $this->model_seller_product_comicproduct->getAllTypesProducts($filter_data);		
					
					if(!empty($all_new_release_records)){
					  foreach ($all_new_release_records as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						if ($result['image1']) {
							$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$now_allow = 0;
						// check for adult image
						if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
							$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
							$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
							$now_allow = 1;
						}
						if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
							$now_allow = 1;
						
						$coming_value = $this->cart->checkComingButtons($result['product_id']);

						$author_name = '';
						$author_name = $result['user_fname'].' '.$result['user_lname'];
						

						$data['all_new_release_records'][] = array(
							'product_id'  		=> $result['product_id'],
							'certification_number'  => $result['certification_number'],
							'special'  			=> $result['special'],
							'issue_number'  	=> $result['issue_number'],
							'variant'  			=> $result['variant'],
							'quantity'  		=> $result['quantity'],
							'publisher'  		=> $result['publisher'],
							'short_description'  	=> $result['short_description'],
							'author_name'   	=> $author_name,
							'now_allow'   		=> $now_allow,
							'coming_value'       	=> $coming_value,
							'model_main_img'       	=> $model_main_img,
							'thumb'       		=> $image,
							'thumb1'       		=> $image1,
							'name'        		=> $result['name'],
							'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       		=> $result['price'],
							'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
							'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
						);
					  }
					}
					

					//Display records fetched from database.
					$data['page_number_new'] = $page1;
					$data['get_total_rows_new'] = $new_release_product_total;
					$data['total_pages_new'] = $new_release_total_pages;


				// =============================== For Comic Store Back Issue ===================
					$filter_data_search = array(
						'param'		     	=>$param,
						'posted_by'		 	 =>1,
						'new_release'		 =>0,
						'comic_label'		=>$comic_label,
						'min_price' 		=>$min_price,
						'max_price' 		=>$max_price,
						'title_book' 		=>$title_book,
						'publisher' 		=>$publisher
					);
					$back_issue_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
					
					//break records into pages
					$back_issue_total_pages = ceil($back_issue_product_total/$item_per_page);
					
					//Get page number from Ajax POST
					if(isset($_POST["page2"])){
						$page2 = filter_var($_POST["page2"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
						if(!is_numeric($page2)){die('Invalid page number!');} //incase of invalid page number
					}else{
						$page2 = 1; //if there's no page number, set it to 1
					}

					$filter_data = array(
						'param'		    	 =>$param,
						'comic_label'		 =>$comic_label,
						'posted_by'		 	 =>1,
						'new_release'		 =>0,
						'min_price' 	     => $min_price,
						'max_price' 	     => $max_price,
						'title_book' 	     =>$title_book,
						'publisher' 	     =>$publisher,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page2 - 1) * $limit,
						'limit'              => $limit
					);
					
					$back_issue_release_records = $this->model_seller_product_comicproduct->getAllTypesProducts($filter_data);		
					
					if(!empty($back_issue_release_records)){
					  foreach ($back_issue_release_records as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						if ($result['image1']) {
							$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$now_allow = 0;
						// check for adult image
						if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
							$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
							$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
							$now_allow = 1;
						}
						if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
							$now_allow = 1;
						
						$coming_value = $this->cart->checkComingButtons($result['product_id']);

						$author_name = '';
						$author_name = $result['user_fname'].' '.$result['user_lname'];
						

						$data['back_issue_release_records'][] = array(
							'product_id'  		=> $result['product_id'],
							'certification_number'  => $result['certification_number'],
							'special'  			=> $result['special'],
							'issue_number'  	=> $result['issue_number'],
							'variant'  			=> $result['variant'],
							'quantity'  		=> $result['quantity'],
							'publisher'  		=> $result['publisher'],
							'short_description'  	=> $result['short_description'],
							'author_name'   	=> $author_name,
							'now_allow'   		=> $now_allow,
							'coming_value'       	=> $coming_value,
							'model_main_img'       	=> $model_main_img,
							'thumb'       		=> $image,
							'thumb1'       		=> $image1,
							'name'        		=> $result['name'],
							'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       		=> $result['price'],
							'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
							'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
						);
					  }
					}
					

					//Display records fetched from database.
					$data['page_number_back'] = $page2;
					$data['get_total_rows_back'] = $back_issue_product_total;
					$data['total_pages_back'] = $back_issue_total_pages;


				// =============================== For Market Place ===================
					$filter_data_search = array(
						'param'		     	=>$param,
						'posted_by'		 	 =>0,
						'new_release'		 =>0,
						'comic_label'		=>$comic_label,
						'min_price' 		=>$min_price,
						'max_price' 		=>$max_price,
						'title_book' 		=>$title_book,
						'publisher' 		=>$publisher
					);
					$market_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
					
					//break records into pages
					$market_total_pages = ceil($market_product_total/$item_per_page);
					
					//Get page number from Ajax POST
					if(isset($_POST["page3"])){
						$page3 = filter_var($_POST["page3"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
						if(!is_numeric($page3)){die('Invalid page number!');} //incase of invalid page number
					}else{
						$page3 = 1; //if there's no page number, set it to 1
					}

					$filter_data = array(
						'param'		    	 =>$param,
						'comic_label'		 =>$comic_label,
						'posted_by'		 	 =>0,
						'new_release'		 =>0,
						'min_price' 	     => $min_price,
						'max_price' 	     => $max_price,
						'title_book' 	     =>$title_book,
						'publisher' 	     =>$publisher,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page3 - 1) * $limit,
						'limit'              => $limit
					);
					
					$market_release_records = $this->model_seller_product_comicproduct->getAllTypesProducts($filter_data);		
					
					if(!empty($market_release_records)){
					  foreach ($market_release_records as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						if ($result['image1']) {
							$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$now_allow = 0;
						// check for adult image
						if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
							$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
							$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
							$now_allow = 1;
						}
						if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
							$now_allow = 1;
						
						$coming_value = $this->cart->checkComingButtons($result['product_id']);

						$author_name = '';
						$author_name = $result['user_fname'].' '.$result['user_lname'];
						

						$data['market_release_records'][] = array(
							'product_id'  		=> $result['product_id'],
							'certification_number'  => $result['certification_number'],
							'special'  			=> $result['special'],
							'issue_number'  	=> $result['issue_number'],
							'variant'  			=> $result['variant'],
							'quantity'  		=> $result['quantity'],
							'publisher'  		=> $result['publisher'],
							'short_description'  	=> $result['short_description'],
							'author_name'   	=> $author_name,
							'now_allow'   		=> $now_allow,
							'coming_value'       	=> $coming_value,
							'model_main_img'       	=> $model_main_img,
							'thumb'       		=> $image,
							'thumb1'       		=> $image1,
							'name'        		=> $result['name'],
							'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       		=> $result['price'],
							'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
							'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
						);
					  }
					}
					

					//Display records fetched from database.
					$data['page_number_market'] = $page3;
					$data['get_total_rows_market'] = $market_product_total;
					$data['total_pages_market'] = $market_total_pages;




				// =============================== For Comic Label product_label1 ===================
					$comic_label = 'product_label1';
					$filter_data_search = array(
						'param'		     	=>$param,
						'posted_by'		 	 =>1,
						'new_release'		 =>3,
						'comic_label'		=>$comic_label,
						'min_price' 		=>$min_price,
						'max_price' 		=>$max_price,
						'title_book' 		=>$title_book,
						'publisher' 		=>$publisher
					);
					$product_label1_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
					
					//break records into pages
					$product_label1_total_pages = ceil($product_label1_product_total/$item_per_page);
					
					//Get page number from Ajax POST
					if(isset($_POST["page4"])){
						$page4 = filter_var($_POST["page4"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
						if(!is_numeric($page4)){die('Invalid page number!');} //incase of invalid page number
					}else{
						$page4 = 1; //if there's no page number, set it to 1
					}

					$filter_data = array(
						'param'		    	 =>$param,
						'comic_label'		 =>$comic_label,
						'posted_by'		 	 =>1,
						'new_release'		 =>3,
						'min_price' 	     => $min_price,
						'max_price' 	     => $max_price,
						'title_book' 	     =>$title_book,
						'publisher' 	     =>$publisher,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page4 - 1) * $limit,
						'limit'              => $limit
					);
					
					$product_label1_release_records = $this->model_seller_product_comicproduct->getAllTypesProducts($filter_data);		
					
					if(!empty($product_label1_release_records)){
					  foreach ($product_label1_release_records as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						if ($result['image1']) {
							$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$now_allow = 0;
						// check for adult image
						if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
							$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
							$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
							$now_allow = 1;
						}
						if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
							$now_allow = 1;
						
						$coming_value = $this->cart->checkComingButtons($result['product_id']);

						$author_name = '';
						$author_name = $result['user_fname'].' '.$result['user_lname'];
						

						$data['product_label1_release_records'][] = array(
							'product_id'  		=> $result['product_id'],
							'certification_number'  => $result['certification_number'],
							'special'  			=> $result['special'],
							'issue_number'  	=> $result['issue_number'],
							'variant'  			=> $result['variant'],
							'quantity'  		=> $result['quantity'],
							'publisher'  		=> $result['publisher'],
							'short_description'  	=> $result['short_description'],
							'author_name'   	=> $author_name,
							'now_allow'   		=> $now_allow,
							'coming_value'       	=> $coming_value,
							'model_main_img'       	=> $model_main_img,
							'thumb'       		=> $image,
							'thumb1'       		=> $image1,
							'name'        		=> $result['name'],
							'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       		=> $result['price'],
							'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
							'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
						);
					  }
					}
					

					//Display records fetched from database.
					$data['page_number_label1'] = $page4;
					$data['get_total_rows_label1'] = $product_label1_product_total;
					$data['total_pages_label1'] = $product_label1_total_pages;

					
					$this->load->model('setting/setting');				
					$data['comic_label1'] = $this->model_setting_setting->getContactSettingByKey('product_label1');
					

				// =============================== For Comic Label product_label2 ===================
					$comic_label = 'product_label2';
					$filter_data_search = array(
						'param'		     	=>$param,
						'posted_by'		 	 =>1,
						'new_release'		 =>3,
						'comic_label'		=>$comic_label,
						'min_price' 		=>$min_price,
						'max_price' 		=>$max_price,
						'title_book' 		=>$title_book,
						'publisher' 		=>$publisher
					);
					$product_label2_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
					
					//break records into pages
					$product_label2_total_pages = ceil($product_label2_product_total/$item_per_page);
					
					//Get page number from Ajax POST
					if(isset($_POST["page5"])){
						$page5 = filter_var($_POST["page5"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
						if(!is_numeric($page5)){die('Invalid page number!');} //incase of invalid page number
					}else{
						$page5 = 1; //if there's no page number, set it to 1
					}

					$filter_data = array(
						'param'		    	 =>$param,
						'comic_label'		 =>$comic_label,
						'posted_by'		 	 =>1,
						'new_release'		 =>3,
						'min_price' 	     => $min_price,
						'max_price' 	     => $max_price,
						'title_book' 	     =>$title_book,
						'publisher' 	     =>$publisher,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page5 - 1) * $limit,
						'limit'              => $limit
					);
					
					$product_label2_release_records = $this->model_seller_product_comicproduct->getAllTypesProducts($filter_data);		
					
					if(!empty($product_label2_release_records)){
					  foreach ($product_label2_release_records as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						if ($result['image1']) {
							$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$now_allow = 0;
						// check for adult image
						if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
							$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
							$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
							$now_allow = 1;
						}
						if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
							$now_allow = 1;
						
						$coming_value = $this->cart->checkComingButtons($result['product_id']);

						$author_name = '';
						$author_name = $result['user_fname'].' '.$result['user_lname'];
						

						$data['product_label2_release_records'][] = array(
							'product_id'  		=> $result['product_id'],
							'certification_number'  => $result['certification_number'],
							'special'  			=> $result['special'],
							'issue_number'  	=> $result['issue_number'],
							'variant'  			=> $result['variant'],
							'quantity'  		=> $result['quantity'],
							'publisher'  		=> $result['publisher'],
							'short_description'  	=> $result['short_description'],
							'author_name'   	=> $author_name,
							'now_allow'   		=> $now_allow,
							'coming_value'       	=> $coming_value,
							'model_main_img'       	=> $model_main_img,
							'thumb'       		=> $image,
							'thumb1'       		=> $image1,
							'name'        		=> $result['name'],
							'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       		=> $result['price'],
							'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
							'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
						);
					  }
					}
					

					//Display records fetched from database.
					$data['page_number_label2'] = $page5;
					$data['get_total_rows_label2'] = $product_label2_product_total;
					$data['total_pages_label2'] = $product_label2_total_pages;

					
					$data['comic_label2'] = $this->model_setting_setting->getContactSettingByKey('product_label2');


				// =============================== For Comic Label product_label3 ===================
					$comic_label = 'product_label3';
					$filter_data_search = array(
						'param'		     	=>$param,
						'posted_by'		 	 =>1,
						'new_release'		 =>3,
						'comic_label'		=>$comic_label,
						'min_price' 		=>$min_price,
						'max_price' 		=>$max_price,
						'title_book' 		=>$title_book,
						'publisher' 		=>$publisher
					);
					$product_label3_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
					
					//break records into pages
					$product_label3_total_pages = ceil($product_label3_product_total/$item_per_page);
					
					//Get page number from Ajax POST
					if(isset($_POST["page6"])){
						$page6 = filter_var($_POST["page6"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
						if(!is_numeric($page5)){die('Invalid page number!');} //incase of invalid page number
					}else{
						$page6 = 1; //if there's no page number, set it to 1
					}

					$filter_data = array(
						'param'		    	 =>$param,
						'comic_label'		 =>$comic_label,
						'posted_by'		 	 =>1,
						'new_release'		 =>3,
						'min_price' 	     => $min_price,
						'max_price' 	     => $max_price,
						'title_book' 	     =>$title_book,
						'publisher' 	     =>$publisher,
						'sort'               => $sort,
						'order'              => $order,
						'start'              => ($page6 - 1) * $limit,
						'limit'              => $limit
					);
					
					$product_label3_release_records = $this->model_seller_product_comicproduct->getAllTypesProducts($filter_data);		
					
					if(!empty($product_label3_release_records)){
					  foreach ($product_label3_release_records as $result) {
						if ($result['image']) {
							$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
							$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}

						if ($result['image1']) {
							$image1= $this->model_tool_image->resize($result['image1'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						} else {
							$image1 = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
						}
						
						$now_allow = 0;
						// check for adult image
						if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
							$image = $image1 = $this->model_tool_image->resize('adults-only.jpg', 280, 280);
							$model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
							$now_allow = 1;
						}
						if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
							$now_allow = 1;
						
						$coming_value = $this->cart->checkComingButtons($result['product_id']);

						$author_name = '';
						$author_name = $result['user_fname'].' '.$result['user_lname'];
						

						$data['product_label3_release_records'][] = array(
							'product_id'  		=> $result['product_id'],
							'certification_number'  => $result['certification_number'],
							'special'  			=> $result['special'],
							'issue_number'  	=> $result['issue_number'],
							'variant'  			=> $result['variant'],
							'quantity'  		=> $result['quantity'],
							'publisher'  		=> $result['publisher'],
							'short_description'  	=> $result['short_description'],
							'author_name'   	=> $author_name,
							'now_allow'   		=> $now_allow,
							'coming_value'       	=> $coming_value,
							'model_main_img'       	=> $model_main_img,
							'thumb'       		=> $image,
							'thumb1'       		=> $image1,
							'name'        		=> $result['name'],
							'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
							'price'       		=> $result['price'],
							'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
							'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'] )
						);
					  }
					}
					

					//Display records fetched from database.
					$data['page_number_label3'] = $page6;
					$data['get_total_rows_label3'] = $product_label3_product_total;
					$data['total_pages_label3'] = $product_label3_total_pages;

					
					$data['comic_label3'] = $this->model_setting_setting->getContactSettingByKey('product_label3');
					

					
					$data['class_value'] = '';
					if($_POST["class_value"]!=""){
						$data['class_value'] = $_POST["class_value"];
					}

					if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/comic_all_list_view.tpl')) {
						$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/comic_all_list_view.tpl', $data));
					} else {
						$this->response->setOutput($this->load->view('default/template/seller_product/comic_all_list_view.tpl', $data));
					}
				
				// ********************************* Code *********************************************

			}
		
		
		
		}
	}
	
	
}