<?php
class ControllerSellerProductSearchResult extends Controller {
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

			$results = $this->model_seller_product_comicproduct->getProTitleIssue($filter_data);

			foreach ($results as $result) {
				$name = preg_replace('/\s+/', '+', $result['name']);

				//$json[] = array(
				//	'show_product' 	=> strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8')).' '.$result['issue_number'],
				//	'name'       	=> strip_tags(html_entity_decode($name, ENT_QUOTES, 'UTF-8')).'-'.$result['issue_number']
				//	
				//);
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
		$this->document->setTitle('Search Result');
		$this->load->model('seller_product/product');
		$this->load->model('seller_product/comicproduct');
		$this->load->model('tool/image');
				
		if(isset($this->request->get['param']) && $this->request->get['param']==""){			
		// redirect to home page
			$this->response->redirect($this->url->link('common/home','',SSL));
		}

		$param = preg_replace('/\s+/', '+', $this->request->get['param']);
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Search',
			'href' => $this->url->link('seller_product/search_result', '&param='.$param, 'SSL')
		);

		$data['title_book1'] = addslashes($this->request->get['param']);
		
	// ************************************ Get All Customer Name **********************************************************	
		
		$customers = $this->model_seller_product_product->getAllCustomers();
		if(!empty($customers)){
			foreach($customers as $each_customer){
				$no_pro = $this->model_seller_product_comicproduct->getSearchProNumByCustomer($each_customer['id'],$each_customer['role'],$this->request->get['param']);
				$data['customer'][] = array('id'=>$each_customer['id'],'name'=>$each_customer['fname'].' '.$each_customer['lname'],'role'=>$each_customer['role'],'product_count'=>$no_pro);
			}
		}
		//print_r($data['customer']);exit;

	// ************************************ Get All Customer Name **********************************************************	
	
	// ************************************ Get price range **********************************************************	
		
		$price_range = $this->model_seller_product_comicproduct->getPriceRangeSearch_bk($this->request->get['param']);
		//echo "<pre>";print_r($price_range);
		if(!empty($price_range)){
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
	
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/search_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/search_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller_product/search_list.tpl', $data));
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
		
		
		if(isset($_POST["class_value"]) && $_POST["class_value"]!=""){
			$class_value = trim($_POST["class_value"]);
		}
		else{
			$class_value = '';
		}
		
		
		if(isset($_POST["title_book"]) && $_POST["title_book"]!=""){
			$title_book = trim($_POST["title_book"]);
		}
		else{
			$title_book = '';
		}
		$item_per_page = $limit = 3;
		$data['item_per_page'] = $item_per_page;
		$data['class_value'] = $class_value;
		
		
		// =============================== For Comic Store Pre-order ===================
				$count = 0;
				if(isset($_POST["page"])){
					$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number
					if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number
				}else{
					$page_number = 1; //if there's no page number, set it to 1
				}
				
				$param = 2;
				
				
				
				//$publisher = $customer_filter; // edited on 4-04-2016

				$filter_data_search = array(
					'param'		     	=>$param,
					'posted_by'		 	 =>1,
					'new_release'		 =>2,
					'comic_label'		=>$comic_label,
					'min_price' 		=>$min_price,
					'max_price' 		=>$max_price,
					'title_book' 		=>$title_book,
					'customer_filter' 	=>$customer_filter,
				);
				$pre_order_product_total = $this->model_seller_product_comicproduct->getCountAllTypesProduct($filter_data_search);
				
				
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
					'customer_filter' 	=>$customer_filter,
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
				else
				{
					$count = $count+1;
				}
				

				//Display records fetched from database.
				$data['page_number_pre'] = $page_number;
				$data['get_total_rows_pre'] = $pre_order_product_total;
				$data['total_pages_pre'] = $pre_order_total_pages;
				
				
		// =============================== For Comic Store New release ===================
				$param = 1;				
				$filter_data_search = array(
					'param'		     	=>$param,
					'posted_by'		 	 =>1,
					'new_release'		 =>1,
					'comic_label'		=>$comic_label,
					'is_coming_soon'	=>$is_coming_soon,
					'min_price' 		=>$min_price,
					'max_price' 		=>$max_price,
					'title_book' 		=>$title_book,
					'customer_filter' 	=>$customer_filter,
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
					'customer_filter' 	=>$customer_filter,
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
				else
				{
					$count = $count+1;
				}

				//Display records fetched from database.
				$data['page_number_new'] = $page1;
				$data['get_total_rows_new'] = $new_release_product_total;
				$data['total_pages_new'] = $new_release_total_pages;
				
			// =============================== For Comic Store Back Issue ===================
			
			$param = 0;
				$filter_data_search = array(
					'param'		     	=>$param,
					'posted_by'		 	 =>1,
					'new_release'		 =>0,
					'comic_label'		=>$comic_label,
					'min_price' 		=>$min_price,
					'max_price' 		=>$max_price,
					'title_book' 		=>$title_book,
					'customer_filter' 	=>$customer_filter,
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
					'customer_filter' 	=>$customer_filter,
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
				else
				{
					$count = $count+1;
				}
				
				

				//Display records fetched from database.
				$data['page_number_back'] = $page2;
				$data['get_total_rows_back'] = $back_issue_product_total;
				$data['total_pages_back'] = $back_issue_total_pages;
				
				
						// =============================== For Market Place ===================
						
					$param = 3;	
						
				$filter_data_search = array(
					'param'		     	=>$param,
					'posted_by'		 	 =>0,
					'new_release'		 =>0,
					'comic_label'		=>$comic_label,
					'min_price' 		=>$min_price,
					'max_price' 		=>$max_price,
					'title_book' 		=>$title_book,
					'customer_filter' 	=>$customer_filter,
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
					'customer_filter' 	=>$customer_filter,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page3 - 1) * $limit,
					'limit'              => $limit
				);
				
				//print_r($filter_data);
				
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
				else
				{
					$count = $count+1;
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
				else
				{
					$count = $count+1;
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
				else
				{
					$count = $count+1;
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
				else
				{
					$count=$count+1;
				}

				//Display records fetched from database.
				$data['page_number_label3'] = $page5;
				$data['get_total_rows_label3'] = $product_label3_product_total;
				$data['total_pages_label3'] = $product_label3_total_pages;

				$data['record_message'] = "";
				if($count == 7)
				{
					$data['record_message'] = "No Record Found";
				}
				
				$data['comic_label3'] = $this->model_setting_setting->getContactSettingByKey('product_label3');
				

		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/search_view_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/search_view_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller_product/search_view_list.tpl', $data));
		}
		
		}
	}
	
	
}