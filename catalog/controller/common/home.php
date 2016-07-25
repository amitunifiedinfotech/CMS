<?php
class ControllerCommonHome extends Controller {
	public function index() {
		$this->document->setTitle($this->config->get('config_meta_title'));
		$this->document->setDescription($this->config->get('config_meta_description'));
		$this->document->setKeywords($this->config->get('config_meta_keyword'));

		if (isset($this->request->get['route'])) {
			$this->document->addLink(HTTP_SERVER, 'canonical');
		}
		
	// ***************************************** New Arrivals ***************************************************************
		
		$this->load->model('seller_product/product');
		$this->load->model('tool/image');
		
		$data['products'] = array();
		$results = $this->model_seller_product_product->getFeautureProComic();
		
		if(!empty($results)){
		  foreach ($results as $result) {
			if ($result['image']) {
				$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img = $this->model_tool_image->resize($result['image'], 699, 875);
				$model_main_img1 = $this->model_tool_image->resize($result['image1'], 699, 875);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img1 = $model_main_img = $this->model_tool_image->resize('placeholder.png', 699, 875);
			}
			$now_allow = 0;
			// check for adult image
			if (($result['adult'] && !$this->customer->isLogged()) || ($this->customer->isLogged() && !$this->customer->checkAdult() && $result['adult'])) {
				$image = $this->model_tool_image->resize('adults-only.jpg',$this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img1 = $model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
				$now_allow = 1;
			}
			if($this->customer->isLogged() && $this->customer->getId()==$result['customer_id'])
				$now_allow = 1;

			
			// Set all the products field
			$data['products'][] = array(
				'product_id'  		=> $result['product_id'],
				'quantity'  		=> $result['quantity'],
				'thumb'       		=> $image,
				'model_main_img'       	=> $model_main_img,
				'model_main_img1'       => $model_main_img1,
				'now_allow'       	=> $now_allow,
				'name'        		=> $result['name'].' - '.$result['issue_number'],
				'certification_number'  => $result['certification_number'],
				'description' 		=> utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'       		=> $result['price'],
				'minimum'     		=> $result['minimum'] > 0 ? $result['minimum'] : 1,
				'href'        		=> $this->url->link('product/product',  '&product_id=' . $result['product_id'])
			);
		  }
		  
		}
		//print_r($data['products']);
		
	// ***************************************** New Arrivals ***************************************************************
	

	// ***************************************** FEATURES PRODUCT ***************************************************************
		
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
				$image = $this->model_tool_image->resize('adults-only.jpg',$this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
				$model_main_img1 = $model_main_img = $this->model_tool_image->resize('adults-only.jpg', 699, 875);
				$now_allow = 1;
			}
			if($this->customer->isLogged() && $this->customer->getId()==$each_feature['customer_id'])
				$now_allow = 1;
			// Set all the products field
			$data['feature_products'][] = array(
				'product_id'  		=> $each_feature['product_id'],
				'quantity'  		=> $each_feature['quantity'],
				'thumb'       		=> $image,
				'model_main_img'       	=> $model_main_img,
				'model_main_img1'       => $model_main_img1,
				'now_allow'       	=> $now_allow,
				'name'        		=> $each_feature['name'].' - '.$each_feature['issue_number'],
				'certification_number'  => $each_feature['certification_number'],
				'page_quality'  	=> $each_feature['page_quality'],
				'grade'  		=> $each_feature['grade_meta'].' - '.$each_feature['grade_value'],
				'description' 		=> utf8_substr(strip_tags(html_entity_decode($each_feature['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('config_product_description_length')) . '..',
				'price'       		=> $each_feature['price'],
				'minimum'     		=> $each_feature['minimum'] > 0 ? $each_feature['minimum'] : 1,
				'href'        		=> $this->url->link('product/product',  '&product_id=' . $each_feature['product_id'])
			);
		  }
		  
		}
		//echo '<pre>';print_r($data['feature_products']);exit;
		
	// ***************************************** FEATURES PRODUCT ***************************************************************


	
	// ***************************************** Site-Settings ***************************************************************
		$this->load->model('setting/setting');
		$setting_images = $this->model_setting_setting->getContactSettingImg('configuration');
		
		$data['home_page_under_feature_image'] ='';
		if(!empty($setting_images)){
			
			foreach($setting_images as $each_img){
				
				if($each_img['key']=='home_page_under_arrival_image'){
					$data['home_page_under_arrival_image'] = $each_img['value'];
				}
				if($each_img['key']=='home_page_under_feature_image'){
					$data['home_page_under_feature_image'] = $each_img['value'];
				}
				
			}
		}
		
		// Under New Arrival Text
		$data['home_page_under_arrival_text'] = $this->model_setting_setting->getContactSettingByKey('home_page_under_arrival_text');
		$data['home_page_under_arrival_title_text'] = $this->model_setting_setting->getContactSettingByKey('home_page_under_arrival_title_text');
		$data['home_page_under_arrival_price'] = $this->model_setting_setting->getContactSettingByKey('home_page_under_arrival_price');
		
		// Under feature Text
		$data['home_page_under_feature_text'] = $this->model_setting_setting->getContactSettingByKey('home_page_under_feature_text');
		$data['home_page_under_feature_title_text'] = $this->model_setting_setting->getContactSettingByKey('home_page_under_feature_title_text');
		
		//print_r($setting_images);

	// ***************************************** Site-Settings  ***************************************************************

	
	// ***************************************** Latest news ***************************************************************
		$data['latest_news'] = array();
		$this->load->model('news/latest_news');
		$latest_news = $this->model_news_latest_news->getLatestNews();
		
		if(!empty($latest_news)){
		  foreach ($latest_news as $each_news) {
			
			
			$data['latest_news'][] = array(
				'heading'  		=> $each_news['heading'],
				'details'  		=> $each_news['details'],
				'href'        		=> $this->url->link('news/latest_news',  '&latest_id='. $each_news['id'])
			);
		  }
		  
		}
		
		$data['all_news_href'] = $this->url->link('news/latest_news/list_all_news','');
		
		
		$home_page_news_banner = $this->model_setting_setting->getContactSettingByKey('home_page_news_banner');
		$data['latest_news_banner'] = $this->model_tool_image->resize($home_page_news_banner, 800, 1200);


	// ***************************************** Site-Settings  ***************************************************************

	
	
	
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		
		/**************  HOME PAGE BANNER START  *************/
		
		$this->load->model('tool/upload');
		
		$data['layer1_image'] = $this->model_tool_upload->get_banner_details("layer1_image");
		$data['layer1_text'] = $this->model_tool_upload->get_banner_details("layer1_text");
		$data['layer1_link'] = $this->model_tool_upload->get_banner_details("layer1_link");
		
		$data['layer2_top_image'] = $this->model_tool_upload->get_banner_details("layer2_top_image");
		$data['layer2_top_text'] = $this->model_tool_upload->get_banner_details("layer2_top_text");
		$data['layer2_top_link'] = $this->model_tool_upload->get_banner_details("layer2_top_link");
		
		
		$data['layer2_bottom_image'] = $this->model_tool_upload->get_banner_details("layer2_bottom_image");
		$data['layer2_bottom_text'] = $this->model_tool_upload->get_banner_details("layer2_bottom_text");
		$data['layer2_bottom_link'] = $this->model_tool_upload->get_banner_details("layer2_bottom_link");
		
		$data['layer3_image'] = $this->model_tool_upload->get_banner_details("layer3_image");
		$data['layer3_text'] = $this->model_tool_upload->get_banner_details("layer3_text");
		$data['layer3_link'] = $this->model_tool_upload->get_banner_details("layer3_link");
		
		
		$data['layer4_top_left_image'] = $this->model_tool_upload->get_banner_details("layer4_top_left_image");
		$data['layer4_top_left_text'] = $this->model_tool_upload->get_banner_details("layer4_top_left_text");
		$data['layer4_top_left_link'] = $this->model_tool_upload->get_banner_details("layer4_top_left_link");
		
		
		$data['layer4_top_right_image'] = $this->model_tool_upload->get_banner_details("layer4_top_right_image");
		$data['layer4_top_right_text'] = $this->model_tool_upload->get_banner_details("layer4_top_right_text");
		$data['layer4_top_right_link'] = $this->model_tool_upload->get_banner_details("layer4_top_right_link");
		
		$data['layer4_bottom_left_image'] = $this->model_tool_upload->get_banner_details("layer4_bottom_left_image");
		$data['layer4_bottom_left_text'] = $this->model_tool_upload->get_banner_details("layer4_bottom_left_text");
		$data['layer4_bottom_left_link'] = $this->model_tool_upload->get_banner_details("layer4_bottom_left_link");
		
		
		$data['layer4_bottom_right_image'] = $this->model_tool_upload->get_banner_details("layer4_bottom_right_image");
		$data['layer4_bottom_right_text'] = $this->model_tool_upload->get_banner_details("layer4_bottom_right_text");
		$data['layer4_bottom_right_link'] = $this->model_tool_upload->get_banner_details("layer4_bottom_right_link");
		
		$data['layer5_image'] = $this->model_tool_upload->get_banner_details("layer5_image");
		$data['layer5_text'] = $this->model_tool_upload->get_banner_details("layer5_text");
		$data['layer5_link'] = $this->model_tool_upload->get_banner_details("layer5_link");
		
		$data['layer6_top_image'] = $this->model_tool_upload->get_banner_details("layer6_top_image");
		$data['layer6_top_text'] = $this->model_tool_upload->get_banner_details("layer6_top_text");
		$data['layer6_top_link'] = $this->model_tool_upload->get_banner_details("layer6_top_link");
		
		
		$data['layer6_bottom_left_image'] = $this->model_tool_upload->get_banner_details("layer6_bottom_left_image");
		$data['layer6_bottom_left_text'] = $this->model_tool_upload->get_banner_details("layer6_bottom_left_text");
		$data['layer6_bottom_left_url'] = $this->model_tool_upload->get_banner_details("layer6_bottom_left_url");
		
		
		$data['layer6_bottom_right_image'] = $this->model_tool_upload->get_banner_details("layer6_bottom_right_image");
		$data['layer6_bottom_right_text'] = $this->model_tool_upload->get_banner_details("layer6_bottom_right_text");
		$data['layer6_bottom_right_url'] = $this->model_tool_upload->get_banner_details("layer6_bottom_right_url");
		
		
		/**************  HOME PAGE BANNER END  *************/
		
		
		

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/home.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/home.tpl', $data));
		}
		
		
	}
	
	function activate_user(){
		
		$this->document->setTitle("Activate User");
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);


		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_login'),
			'href' => $this->url->link('account/login', '', 'SSL')
		);
		
		
		$email = base64_decode($_REQUEST['email']);
		
		$this->load->model('account/customer');
		$return_value = $this->model_account_customer->activateAccount($email);
		
		if($return_value==-1){
			$data['show_msg'] = "Your account is deleted by admin. For further information contact Admin.";
		}
		else if($return_value==1){
			$data['show_msg'] = "Your Account is sucessfully activated.";
		}
		else{
			$data['show_msg'] = "Your Account is already activated.";
		}
		
		$data['continue'] = $this->url->link('account/login');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/home.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/activateuser.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/activateuser.tpl', $data));
		}
	}
	
	public function unsubscribe()
        {
		$this->load->language('common/home');
		$this->load->language('account/account');
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['heading_title'] = 'Unsubscribe Newsletter';
		$data['text_message'] = sprintf("You have successfully un-subscribe the newsletter!!!");
		
		$this->document->setTitle('Unsubscribe Newsletter');
		$user_email = base64_decode($_GET['id']);
		$this->load->model('account/newsletter');
		$this->model_account_newsletter->unsubscribeNewsletter($user_email);
	    	
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

	    
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/unsubscribe_newsletter.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/common/unsubscribe_newsletter.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/common/unsubscribe_newsletter.tpl', $data));
		}
        }
	
	
}