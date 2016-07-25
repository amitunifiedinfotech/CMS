<?php
class ControllerSellerProductProductList extends Controller {
	private $error = array();
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login'));
		}
		
		$this->load->language('product/category');
		
		$this->load->model('seller_product/product');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => 'Account',
			'href' => $this->url->link('account/account')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => 'My Products',
			'href' => $this->url->link('seller_product/product_list')
		);
		
// *************************************************************************** My Own Code *********************************************************

		if (isset($this->session->data['success_add_edit'])) {
			$data['success_add_edit'] = $this->session->data['success_add_edit'];

			unset($this->session->data['success_add_edit']);
		} else {
			$data['success_add_edit'] = '';
		}

	
		$this->document->setTitle('Product List');
		
		//$this->document->addLink($this->url->link('product/category', 'path=' . $this->request->get['path']), 'canonical');

		$data['heading_title'] = 'Product List';

		$data['text_refine'] = $this->language->get('text_refine');
		$data['text_empty'] = $this->language->get('text_empty1');
		$data['text_quantity'] = $this->language->get('text_quantity');
		$data['text_manufacturer'] = $this->language->get('text_manufacturer');
		$data['text_model'] = $this->language->get('text_model');
		$data['text_price'] = $this->language->get('text_price');
		$data['text_tax'] = $this->language->get('text_tax');
		$data['text_points'] = $this->language->get('text_points');
		$data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
		$data['text_sort'] = $this->language->get('text_sort');
		$data['text_limit'] = $this->language->get('text_limit');

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_list'] = $this->language->get('button_list');
		$data['button_grid'] = $this->language->get('button_grid');


		
		$data['thumb'] = '';

		$data['description'] = html_entity_decode('Test', ENT_QUOTES, 'UTF-8');

		$url = '';

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['products'] = array();

		$filter_data = array(
			'sort'               => $sort,
			'order'              => $order,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$product_total = $this->model_seller_product_product->getTotalProducts($filter_data);

		//$results = $this->model_catalog_product-> ($filter_data);
		$results = $this->model_seller_product_product->getProducts($filter_data);

		if(!empty($results)){
		foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
			}

			if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
				//$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				$price = $result['price'];
			} else {
				$price = false;
			}

			list($p_date,$p_time) = explode(" ",$result['date_added']);
			list($pyr,$pmon,$pday) = explode("-",$p_date);
			$posted_date = $pmon.'-'.$pday.'-'.$pyr.' '.$p_time;
			$data['products'][] = array(
				'product_id'  	=> $result['product_id'],
				'issue_number'  => $result['issue_number'],
				'grade_meta'  	=> $result['grade_meta'],
				'special'  	=> $result['special'],
				'grade_value'  	=> $result['grade_value'],
				'short_description'  => $result['short_description'],
				'posted_date'  => $posted_date,
				'thumb'       => $image,
				'name'        => $result['name'],
				'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'       => $price,
				'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        => $this->url->link('product/product',  '&product_id=' . $result['product_id'] . $url)
			);
		}


		}
		$url = '';

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$data['sorts'] = array();

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_default'),
			'value' => 'p.sort_order-ASC',
			'href'  => $this->url->link('seller_product/product_list', '&sort=p.sort_order&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_asc'),
			'value' => 'pd.name-ASC',
			'href'  => $this->url->link('seller_product/product_list', '&sort=pd.name&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_name_desc'),
			'value' => 'pd.name-DESC',
			'href'  => $this->url->link('seller_product/product_list','&sort=pd.name&order=DESC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_asc'),
			'value' => 'p.price-ASC',
			'href'  => $this->url->link('seller_product/product_list',  '&sort=p.price&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => $this->language->get('text_price_desc'),
			'value' => 'p.price-DESC',
			'href'  => $this->url->link('seller_product/product_list', '&sort=p.price&order=DESC' . $url)
		);
		$data['sorts'][] = array(
			'text'  => 'Grade (Lowest)',
			'value' => 'p.grade-DESC',
			'href'  => $this->url->link('seller_product/product_list',  '&sort=p.grade&order=DESC' . $url)
		);
		$data['sorts'][] = array(
			'text'  => 'Grade (Highest)',
			'value' => 'p.grade-ASC',
			'href'  => $this->url->link('seller_product/product_list',  '&sort=p.grade&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => 'Date (Oldest)',
			'value' => 'p.date_added-ASC',
			'href'  => $this->url->link('seller_product/product_list',  '&sort=p.date_added&order=ASC' . $url)
		);

		$data['sorts'][] = array(
			'text'  => 'Date (Latest)',
			'value' => 'p.date_added-DESC',
			'href'  => $this->url->link('seller_product/product_list',  '&sort=p.date_added&order=DESC' . $url)
		);

		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$data['limits'] = array();

		$limits = array_unique(array($this->config->get('config_product_limit'), 2, 50, 75, 100));

		sort($limits);

		foreach($limits as $value) {
			$data['limits'][] = array(
				'text'  => $value,
				'value' => $value,
				'href'  => $this->url->link('seller_product/product_list', $url . '&limit=' . $value)
			);
		}


		if (isset($this->request->get['filter'])) {
			$url .= '&filter=' . $this->request->get['filter'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('seller_product/product_list', $url . '&page={page}');
		
		$data['add_product'] = $this->url->link('seller_product/product_list/addEdit');
		$data['add_mass_upload_product'] = $this->url->link('tool/xls_import');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;

		$data['continue'] = $this->url->link('common/home');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/my_product_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/my_product_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller_product/my_product_list.tpl', $data));
		}


// *************************************************************************** My Own Code *********************************************************
	}

	public function addEdit() {
		

		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login'));
		}
		
		$this->load->language('product/category');
		$this->load->language('seller_product/product');
		
		$this->load->model('seller_product/product');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		
		if(isset($_REQUEST['product_id'])){
			$id = base64_decode($_REQUEST['product_id']);
			$data['product_id'] = base64_decode($_REQUEST['product_id']);
		}
		else
			$id = 0;
		//echo $id;

		if($id==0){
			$this->document->setTitle("Add Product");
			$data['add_edit_text'] = "Add Product";
		}
		else{
			$this->document->setTitle("Edit Product");
			$data['add_edit_text'] = "Edit Product";
		}
		$data['success_add_edit'] = '';
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) ) {	
			//echo "<pre>";print_r($this->request->post);exit; 
			
			if (isset($_FILES['image']['name']) && $_FILES['image']['name']!="") {

				$data['image'] = $_FILES['image'];
				$ext['extension'] = pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION);
				$ext = $ext['extension'];
				$image_name = time().'_'.rand(99,9999).'.'.$ext;
				$this->request->post['image'] = "catalog/".$image_name;
				move_uploaded_file($_FILES["image"]["tmp_name"],"image/catalog/".$image_name);
			} 
			else {
				$this->request->post['image'] = $this->request->post['old_image'];
			}
			
			
			
			
			if (isset($_FILES['image1']['name']) && $_FILES['image1']['name']!="") {
				$data['image1'] = $_FILES['image1'];
				$ext1['extension'] = pathinfo($_FILES["image1"]["name"],PATHINFO_EXTENSION);
				$ext1 = $ext1['extension'];
				$image1_name = time().'_'.rand(99,9999).'.'.$ext1;
				move_uploaded_file($_FILES["image1"]["tmp_name"],"image/catalog/".$image1_name);
				$this->request->post['image1'] = "catalog/".$image1_name;
			}
			else {
				$this->request->post['image1'] = $this->request->post['old_image1'];
			}

			// Set Custom Values
			$this->request->post['feature'] = 0;
			$this->request->post['new_release'] = 0;
			$this->request->post['status'] = 0;
			$this->request->post['sort_order'] = 1;
			list($dt,$mon,$yr) = explode("-",$this->request->post['date_available']);
			$this->request->post['date_available'] = $yr.'-'.$mon.'-'.$dt;
			
			//$this->request->post['date_available'] = date("Y-m-d");

			$this->request->post['length_class_id'] = 1;
			$this->request->post['subtract'] = 1;
			$this->request->post['minimum'] = 1;
			$this->request->post['posted_by'] = 0;
			
			if($this->request->post['issue_number'] =='' )
			{
				$this->request->post['issue_number'] = -1;
			}
			
			if($id==0){
				
				// Add to DB
				$pro_id = $this->model_seller_product_product->addProduct($this->request->post);
				$this->session->data['success_add_edit'] = 'You have sucessfully added!!!';
				
			// **************************************** Send Mail to preference *****************************************************
			//	$product_detail = $this->model_seller_product_product->getProduct($pro_id);
			//	//echo "<pre>";print_r($product_detail);exit;
			//	
			//	$title = '';
			//	$issue_number = '';
			//	if($product_detail){
			//		
			//		$title 		= trim($product_detail['model']);
			//		$issue_number 	= trim($product_detail['issue_number']);
			//		$grade 		= $product_detail['grade'];
			//		$price 		= $product_detail['price'];
			//		$weight 	= ($product_detail['weight']=="")?0:$product_detail['weight'];
			//		$keyword 	= $product_detail['keyword'];
			//	}
			//	
			//	// Check wantlist table with title and issue number
			//	$all_want_lists = $this->model_seller_product_product->getWantlistNameIssue($title,$issue_number);
			//	if(!empty($all_want_lists)){
			//		foreach($all_want_lists as $each_wantlist){
			//			$flag=1;						
			//			if($each_wantlist['price_from']!=0 && $each_wantlist['price_to']!=0 && ($each_wantlist['price_from']>$price || $each_wantlist['price_to'] <$price)){
			//				$flag = 0;
			//			}
			//			
			//			if($each_wantlist['grade_from']!=0 && $each_wantlist['grade_to']!=0 && ($each_wantlist['from_grade_weight']>$weight || $each_wantlist['to_grade_weight'] < $weight)){
			//				$flag = 0;
			//			}
			//			
			//		// Check if flag==1 send email to that user
			//		
			//			if($flag==1){
			//				
			//				$this->load->model('account/customer');
			//				$customer_info = $this->model_account_customer->getCustomer($each_wantlist['customer_id']);
			//				
			//			/****************************  SEND MAIL  ***************************/
			//		
			//			 $to = $customer_info['email'];
			//			 
			//			 
			//			 $this->load->model('account/newsletter');
			//			 $message_content = $this->model_account_newsletter->get_newsletter("2");
			//			 $subject = $message_content['subject'];
			//			 
			//			 $redirecturl= '<a href="'.SITE_URL.$keyword.'">Click Here.</a>';
			//			 $username = $customer_info['firstname'].' '.$customer_info['lastname'];
			//			 $message_content_body= html_entity_decode(str_replace("[clickhere]",$redirecturl,$message_content['content']));  
			//			
			//			$message = '<!doctype html>
			//				<html>
			//				<head>
			//				<meta charset="utf-8">
			//				<title>Newsletter sign Up email confirmation</title>
			//				</head>
			//				
			//				<body>
			//				<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin:auto; background:#f2f2f2; box-shadow:0 0 1px rgba(0, 0, 0, 0.2); text-align:left;">
			//				  <tbody>
			//				    <tr>
			//				      <th scope="col" style="background:#3498db; padding:15px;"><a href="'.SITE_URL.'"><img src="'.SITE_URL.'catalog/view/theme/default/images/logo.png" alt=""></a></th>
			//				    </tr>
			//				    <tr>
			//				      <td style="padding:15px;">
			//				      <table width="100%" border="0" cellspacing="0" cellpadding="0">
			//				  <tbody>
			//				    <tr>
			//				      <td style="padding:10px 0; font-family:Helvetica, Arial, sans-serif; font-size:14px; color:#423338;">Hello <strong>'.$username.'</strong>,</td>
			//				      </tr>
			//				      <tr>
			//				      <td style="padding:10px 0; text-align:center;">
			//					'.$message_content_body.'
			//				      </td>
			//				      </tr>
			//				      
			//				  </tbody>
			//				</table>
			//				      </td>
			//				    </tr>
			//				    <tr>
			//				      <td style="background:#3498db; padding:15px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#fff; text-align:center;">&copy; 2015 TSHOP</td>
			//				    </tr>
			//				  </tbody>
			//				</table>
			//				</body>
			//				</html>';
			//		  
			//				// Always set content-type when sending HTML email
			//				$headers = "MIME-Version: 1.0" . "\r\n";
			//				$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			//				
			//    
			//				// More headers
			//				$headers .= 'From:'.SITE_NAME . "\r\n";
			//				//$headers .= 'Cc: amit.unified@gmail.com' . "\r\n";
			//				$headers .= "Bcc: amit.unified@gmail.com\r\n";
			//				
			//				@mail($to,$subject,$message,$headers);
			//		
			//		
			///****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
			//				
			//				
			//			}				
			//		}
			//	}
		
			$cmd = "wget -bq --spider ".SITE_URL."index.php?route=seller_product/product_list/mytest&product_id=".$pro_id;
			shell_exec(escapeshellcmd($cmd));

			$this->response->redirect($this->url->link('seller_product/product_list'));

		// **************************************** Send Mail to preference *****************************************************
			
			}
			else{
				$this->model_seller_product_product->editProduct($this->request->post,$id);
				$this->session->data['success_add_edit'] = $this->language->get('text_success');
				$this->response->redirect($this->url->link('seller_product/product_list'));
			}
			
		}
		
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
// ************************************** My Own Code *********************************************************

		$data['entry_price'] = $this->language->get('entry_price');	
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_related'] = $this->language->get('entry_related');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_short_description'] = "short description";
			
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['publisher'])) {
			$data['error_publisher'] = $this->error['publisher'];
		} else {
			$data['error_publisher'] = array();
		}

		if (isset($this->error['certification_number'])) {
			$data['error_certification_number'] = $this->error['certification_number'];
		} else {
			$data['error_certification_number'] = array();
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = array();
		}

		if (isset($this->error['meta_title'])) {
			$data['error_meta_title'] = $this->error['meta_title'];
		} else {
			$data['error_meta_title'] = array();
		}

		if (isset($this->error['issue_number'])) {
			$data['error_issue_number'] = $this->error['issue_number'];
		} else {
			$data['error_issue_number'] = '';
		}

		if (isset($this->error['model'])) {
			$data['error_model'] = $this->error['model'];
		} else {
			$data['error_model'] = '';
		}
		
		if (isset($this->error['special'])) {
			$data['error_special'] = $this->error['special'];
		} else {
			$data['error_special'] = '';
		}

		if (isset($this->error['date_available'])) {
			$data['error_date_available'] = $this->error['date_available'];
		} else {
			$data['error_date_available'] = '';
		}

		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
		}
		if (isset($this->error['duplicate_name'])) {
			$data['error_duplicate_name'] = $this->error['duplicate_name'];
		} else {
			$data['error_duplicate_name'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'My Product',
			'href' => $this->url->link('seller_product/product_list')
		);

		if (isset($this->request->get['product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$product_info = $this->model_seller_product_product->getProduct(base64_decode($this->request->get['product_id']));
			//print_r($product_info);exit;
		}
	// ************************ Custom Code (Get grade)*************************************************
	
		$data['all_grade'] = $all_grade = $this->model_seller_product_product->getgrade();
		
	// ************************ Custom Code ************************************************************

		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		
		
		
		if (isset($this->request->post['short_description'])) {
			$data['short_description'] = $this->request->post['short_description'];
		}elseif(!empty($product_info)) {
			$data['short_description'] = trim($product_info['short_description']);
		} 
		else{
			$data['short_description'] = "";
		}

		
		if (isset($this->request->post['product_description'])) {
			$data['product_description'] = $this->request->post['product_description'];
		} elseif (!empty($product_info)) {
			$data['product_description'] = $product_info['description'];
		} else {
			$data['product_description'] = '';
		}

		if (isset($_FILES['image']['name'])) {
			$data['image'] = $_FILES['image'];
			$ext['extension'] = pathinfo($_FILES["image"]["name"],PATHINFO_EXTENSION);
			$ext = $ext['extension'];
			$image_name = time().'.'.$ext;
			$this->request->post['image'] = "catalog/".$image_name;
			move_uploaded_file($_FILES["image"]["tmp_name"],"image/cache/".$image_name);
		} elseif (!empty($product_info)) {
			$data['image'] = $product_info['image'];
		} else {
			$data['image'] = '';
		}
		
		if (isset($_FILES['image1']['name'])) {
			$data['image1'] = $_FILES['image1'];
			$ext1['extension'] = pathinfo($_FILES["image1"]["name"],PATHINFO_EXTENSION);
			$ext1 = $ext1['extension'];
			$image1_name = time().'.'.$ext1;
			move_uploaded_file($_FILES["image1"]["tmp_name"],"image/cache/".$image1_name);
			$this->request->post['image1'] = "catalog/".$image1_name;
		} elseif (!empty($product_info)) {
			$data['image1'] = $product_info['image1'];
		} else {
			$data['image1'] = '';
		}
		
		$this->load->model('tool/image');

		if (isset($this->request->post['old_image']) && is_file(DIR_IMAGE . $this->request->post['old_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['old_image'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($product_info['image'], 100, 100);
		} else {
			$data['thumb'] = '';
		}

		if (isset($this->request->post['old_image1']) && is_file(DIR_IMAGE . $this->request->post['old_image1'])) {
			$data['thumb1'] = $this->model_tool_image->resize($this->request->post['old_image1'], 100, 100);
		} elseif (!empty($product_info) && is_file(DIR_IMAGE . $product_info['image1'])) {
			$data['thumb1'] = $this->model_tool_image->resize($product_info['image1'], 100, 100);
		} else {
			$data['thumb1'] = '';
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($product_info)) {
			$data['name'] = $product_info['name'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['grade'])) {
			$data['grade'] = $this->request->post['grade'];
		} elseif (!empty($product_info)) {
			$data['grade'] = $product_info['grade'];
		} else {
			$data['grade'] = '';
		}

		if (isset($this->request->post['special'])) {
			$data['special'] = $this->request->post['special'];
		} elseif (!empty($product_info)) {
			$data['special'] = $product_info['special'];
		} else {
			$data['special'] = '';
		}

		if (isset($this->request->post['page_quality'])) {
			$data['page_quality'] = $this->request->post['page_quality'];
		} elseif (!empty($product_info)) {
			$data['page_quality'] = $product_info['page_quality'];
		} else {
			$data['page_quality'] = '';
		}

		if (isset($this->request->post['adult'])) {
			$data['adult'] = $this->request->post['adult'];
		} elseif (!empty($product_info)) {
			$data['adult'] = $product_info['adult'];
		} else {
			$data['adult'] = 0;
		}

		if (isset($this->request->post['new_release'])) {
			$data['new_release'] = $this->request->post['new_release'];
		} elseif (!empty($product_info)) {
			$data['new_release'] = $product_info['new_release'];
		} else {
			$data['new_release'] = 0;
		}

		if (isset($this->request->post['feature'])) {
			$data['feature'] = $this->request->post['feature'];
		} elseif (!empty($product_info)) {
			$data['feature'] = $product_info['feature'];
		} else {
			$data['feature'] = 0;
		}

		if (isset($this->request->post['publisher'])) {
			$data['publisher'] = $this->request->post['publisher'];
		} elseif (!empty($product_info)) {
			$data['publisher'] = $product_info['publisher'];
		} else {
			$data['publisher'] = '';
		}

		if (isset($this->request->post['certification_number'])) {
			$data['certification_number'] = $this->request->post['certification_number'];
		} elseif (!empty($product_info)) {
			$data['certification_number'] = $product_info['certification_number'];
		} else {
			$data['certification_number'] = '';
		}

		if (isset($this->request->post['issue_number'])) {
			$data['issue_number'] = $this->request->post['issue_number'];
		} elseif (!empty($product_info)) {
			$data['issue_number'] = ($product_info['issue_number']==-1)?'':$product_info['issue_number'];
		} else {
			$data['issue_number'] = '';
		}

		
		if (isset($this->request->post['keyword'])) {
			$data['keyword'] = $this->request->post['keyword'];
		} elseif (!empty($product_info)) {
			$data['keyword'] = $product_info['keyword'];
		} else {
			$data['keyword'] = '';
		}


		if (isset($this->request->post['price'])) {
			$data['price'] = $this->request->post['price'];
		} elseif (!empty($product_info)) {
			$data['price'] = $product_info['price'];
		} else {
			$data['price'] = '';
		}


		if (isset($this->request->post['date_available'])) {
			$data['date_available'] = $this->request->post['date_available'];
		} elseif (!empty($product_info)) {
			$date_available = ($product_info['date_available'] != '0000-00-00') ? $product_info['date_available'] : '';
			list($yr,$mon,$dt) = explode("-",$date_available);
			$data['date_available'] = $dt.'-'.$mon.'-'.$yr;
		} else {
			$data['date_available'] = '';
		}

		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} elseif (!empty($product_info)) {
			$data['quantity'] = $product_info['quantity'];
		} else {
			$data['quantity'] = 1;
		}


		if (isset($this->request->post['sort_order'])) {
			$data['sort_order'] = $this->request->post['sort_order'];
		} elseif (!empty($product_info)) {
			$data['sort_order'] = $product_info['sort_order'];
		} else {
			$data['sort_order'] = 1;
		}

		if (isset($this->request->post['product_related'])) {
			$products = $this->request->post['product_related'];
		} elseif (isset($this->request->get['product_id'])) {
			$products = $this->model_seller_product_product->getProductRelated(base64_decode($this->request->get['product_id']));
		} else {
			$products = array();
		}

		$data['product_relateds'] = array();

		foreach ($products as $product_id) {
			$related_info = $this->model_seller_product_product->getProduct($product_id);
			//print_r($related_info);exit;

			if ($related_info) {
				$data['product_relateds'][] = array(
					'product_id' => $related_info['product_id'],
					'name'       => $related_info['name'],
					'issue_number'       => $related_info['issue_number']
				);
			}
		}
		//echo "<pre>";print_r($data);exit;
		//print_r($data['product_relateds']);exit;
		$data['cancel'] = $this->url->link('seller_product/product_list');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/seller_product/product_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/seller_product/product_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/seller_product/product_form.tpl', $data));
		}


// *************************************************************************** My Own Code *********************************************************
	}
	
	protected function validateForm() {
		if (!$this->customer->isLogged()) {
			$this->error['warning'] = 'You cannot list any comic until you logged in.';
		}
		//$this->request->post['keyword'] = preg_replace('/\s+/', '-', $this->request->post['keyword']);
		$this->request->post['keyword'] = preg_replace('/[^a-z0-9]+/', '-', strtolower($this->request->post['name']));
			
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		
		if (utf8_strlen($this->request->post['certification_number']) > 0) {
			
			
			$certi_product = $this->model_seller_product_product->getProductByCertification($this->request->post['certification_number']);
			
			if ($certi_product && !isset($this->request->get['product_id'])) {
				$this->error['certification_number'] = sprintf('Certification number already exists.');
			}
			
			if ($certi_product && isset($this->request->get['product_id']) && $certi_product['product_id']!= base64_decode($this->request->get['product_id'])) {
				$this->error['certification_number'] = sprintf('Certification number already exists.');
			}
		}

		if (utf8_strlen($this->request->post['keyword']) > 0) {
			$this->load->model('catalog/url_alias');
			
			$this->request->post['keyword'] = $this->checkUrl($this->request->post['keyword'],0);
			
			//$this->request->post['keyword'] = preg_replace('/[^a-z0-9]+/', '-', strtolower($this->request->post['keyword']));
			
		}
		
		
		

		// Check for Special Price
		if (utf8_strlen($this->request->post['special']) > 0) {
			if ($this->request->post['special'] >= $this->request->post['price']) {
				$this->error['special'] = sprintf('Discount price should not greater than original price.');
			}
		}


		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		//print_r( $this->error);

		return !$this->error;
	}
	
	protected function checkUrl($value,$param,$my_result=false) {
		if(!$my_result)
		  $my_result = $value;
		
		$SQL = "SELECT * FROM " . DB_PREFIX . "url_alias WHERE keyword = '" . $this->db->escape($my_result) . "'";
		//echo '<br>'.$SQL;
		$result = $this->db->query($SQL);
		if($result->num_rows){
		    $param++;
	    
		  $my_result = $value.'-'.$param;
		  return $this->checkUrl($value,$param,$my_result);
		}
		else{
		  return $my_result;
		}
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
				'title_once'  	=> 1,
				'start'        => 0,
				'limit'        => $limit
				
			);

			$results = $this->model_seller_product_product->getProductsTitle($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocomplete() {
		$json = array();
		//print_r($this->request);
		if (isset($this->request->get['filter_name']) ) {
			$this->load->model('seller_product/product');
			
			if (isset($this->request->get['filter_name'])) {
				$filter_name = $this->request->get['filter_name'];
			} else {
				$filter_name = '';
			}
			if (isset($this->request->get['product_id'])) {
				$product_id = $this->request->get['product_id'];
			} else {
				$product_id = '';
			}

			
			if (isset($this->request->get['limit'])) {
				$limit = $this->request->get['limit'];
			} else {
				$limit = 10;
			}

			$filter_data = array(
				'filter_name'  => $filter_name,
				'product_id'  => $product_id,
				'start'        => 0,
				'limit'        => $limit
			);

			$results = $this->model_seller_product_product->getProductsTitle($filter_data);

			foreach ($results as $result) {

				$json[] = array(
					'product_id' => $result['product_id'],
					'name'       => strip_tags(html_entity_decode($result['name'].'-'.$result['issue_number'], ENT_QUOTES, 'UTF-8'))
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	


	public function delete_product() {
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login'));
		}
		$this->load->model('seller_product/product');
		$this->load->language('catalog/product');
		
		// check its own product
		$val = $this->model_seller_product_product->checkMyProduct($this->request->get['product_id']);
		if($val){
			$this->model_seller_product_product->deleteProduct($this->request->get['product_id']);

			$this->session->data['success_add_edit'] = 'Product deleted sucessfully!!!';
			$this->response->redirect($this->url->link('seller_product/product_list'));
		}
		
		print_r($this->request->get['product_id']);exit;
		

		$this->document->setTitle($this->language->get('heading_title'));
		
	}

	


	function testme(){
		//$cmd = "wget -bq --spider http://www.phppowerhousedemo.com/webroot/team13/CMSexpectations/index.php?route=seller_product/product_list/mytest";
		$cmd = "wget -bq --spider ".SITE_URL."index.php?route=seller_product/product_list/mytest&product_id=54";
		shell_exec(escapeshellcmd($cmd));
		
	}
	
	function mytest(){
		$pro_id = $_GET['product_id'];
		//$pro_id = 54;
		//mail('amit.unified@gmail.com','Subject','Messgaesss'.$pro_id);exit;
		
		$this->load->model('seller_product/product');
		$product_detail = $this->model_seller_product_product->getProduct($pro_id);
		//echo "<pre>";print_r($product_detail);exit;
		
		$title = '';
		$issue_number = '';
		if($product_detail){
			
			$title 		= trim($product_detail['model']);
			$issue_number 	= trim($product_detail['issue_number']);
			$grade 		= $product_detail['grade'];
			$price 		= $product_detail['price'];
			$weight 	= ($product_detail['weight']=="")?0:$product_detail['weight'];
			$keyword 	= $product_detail['keyword'];
		}
		
		// Check wantlist table with title and issue number
		$all_want_lists = $this->model_seller_product_product->getWantlistNameIssue($title,$issue_number);
		
		if(!empty($all_want_lists)){
			foreach($all_want_lists as $each_wantlist){
				$flag=1;			
				//if($each_wantlist['price_from']!=0 &&$each_wantlist['price_to']!=0 && ($each_wantlist['price_from']>$price && $each_wantlist['price_to'] <=$price)){
				if($each_wantlist['price_to']!=0 &&  $each_wantlist['price_to'] <=$price){
					$flag = 0;
				}
				
				if($each_wantlist['grade_from']!=0 && $each_wantlist['grade_to']!=0 && ($each_wantlist['from_grade_weight']>$weight && $each_wantlist['to_grade_weight'] < $weight)){
					$flag = 0;
				}

				// Check if flag==1 send email to that user
				if($flag==1){
					
					$this->load->model('account/customer');
					$customer_info = $this->model_account_customer->getCustomer($each_wantlist['customer_id']);
					
				/****************************  SEND MAIL  ***************************/
			
				 $to = $customer_info['email'];
				 
				 
				 $this->load->model('account/newsletter');
				 $message_content = $this->model_account_newsletter->get_newsletter("2");
				 $subject = $message_content['subject'];
				 
				 $redirecturl= '<a href="'.SITE_URL.$keyword.'">Click Here.</a>';
				 $username = $customer_info['firstname'].' '.$customer_info['lastname'];
				 $message_content_body= html_entity_decode(str_replace("[clickhere]",$redirecturl,$message_content['content']));  
				
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
					      <td style="padding:10px 0; font-family:Helvetica, Arial, sans-serif; font-size:14px; color:#423338;">Hello <strong>'.$username.'</strong>,</td>
					      </tr>
					      <tr>
					      <td style="padding:10px 0; text-align:center;">
						'.$message_content_body.'
					      </td>
					      </tr>
					      
					  </tbody>
					</table>
					      </td>
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
					$headers .= 'From: '.SITE_NAME .'<noreply@comicmarketstreet.com>'. "\r\n";
					//$headers .= 'Cc: amit.unified@gmail.com' . "\r\n";
					$headers .= "Bcc: amit.unified@gmail.com\r\n";
					
					@mail($to,$subject,$message,$headers);
                        
                        
        /****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
					
					
				}				
			}
		}
		mail('amit.unified@gmail.com','Subject','Wantlist'.$pro_id);
	
	}
	
}