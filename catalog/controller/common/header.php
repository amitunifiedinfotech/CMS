<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		$data['title'] = $this->document->getTitle();

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		if ($this->config->get('config_google_analytics_status')) {
			$data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		} else {
			$data['google_analytics'] = '';
		}

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$data['icon'] = '';
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');

		$data['text_home'] = $this->language->get('text_home');
		$data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
		$data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));

		$data['text_account'] = $this->language->get('text_account');
		$data['text_register'] = $this->language->get('text_register');
		$data['text_login'] = $this->language->get('text_login');
		$data['text_order'] = $this->language->get('text_order');
		$data['text_transaction'] = $this->language->get('text_transaction');
		$data['text_download'] = $this->language->get('text_download');
		$data['text_logout'] = $this->language->get('text_logout');
		$data['text_checkout'] = $this->language->get('text_checkout');
		$data['text_category'] = $this->language->get('text_category');
		$data['text_all'] = $this->language->get('text_all');

		$data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');
		$data['home'] = $this->url->link('common/home');
		$data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$data['logged'] = $this->customer->isLogged();
		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['register'] = $this->url->link('account/register', '', 'SSL');
		$data['login'] = $this->url->link('account/login', '', 'SSL');
		$data['order'] = $this->url->link('account/order', '', 'SSL');
		$data['transaction'] = $this->url->link('account/transaction', '', 'SSL');
		$data['download'] = $this->url->link('account/download', '', 'SSL');
		$data['logout'] = $this->url->link('account/logout', '', 'SSL');
		$data['shopping_cart'] = $this->url->link('checkout/cart');
		$data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');
		$data['contact'] = $this->url->link('information/contact');
		$data['telephone'] = $this->config->get('config_telephone');

		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", str_replace(array("\r\n", "\r"), "\n", trim($this->config->get('config_robots'))));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// Menu
		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach ($children as $child) {
					$filter_data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $this->model_catalog_product->getTotalProducts($filter_data) . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

		$data['language'] = $this->load->controller('common/language');
		$data['currency'] = $this->load->controller('common/currency');
		$data['search'] = $this->load->controller('common/search');
		$data['cart'] = $this->load->controller('common/cart');
		
		$this->load->model('setting/setting');
		$data['all_contact_settings'] = $this->model_setting_setting->getContactSetting('configuration');


		// For page specific css
		if (isset($this->request->get['route'])) {
			if (isset($this->request->get['product_id'])) {
				$class = '-' . $this->request->get['product_id'];
			} elseif (isset($this->request->get['path'])) {
				$class = '-' . $this->request->get['path'];
			} elseif (isset($this->request->get['manufacturer_id'])) {
				$class = '-' . $this->request->get['manufacturer_id'];
			} else {
				$class = '';
			}

			$data['class'] = str_replace('/', '-', $this->request->get['route']) . $class;
		} else {
			$data['class'] = 'common-home';
		}
		
		
	// =============== Class Active Code ==================
		$data['about_us_class'] = '';
		$data['terms_class'] = '';
		$data['home_class'] = '';
		$data['faq_class'] = '';
		$data['market_class'] = '';
		$data['grading_class'] = '';
		

		if (isset($this->request->get['_route_']) || isset($this->request->get['route'])) {
			if(isset($this->request->get['_route_']) && $this->request->get['_route_']=="about-us"){
				$data['about_us_class'] = 'active';
			}
			if(isset($this->request->get['_route_']) && $this->request->get['_route_']=="terms"){
				$data['terms_class'] = 'active';
			}
			if(isset($this->request->get['_route_']) && $this->request->get['_route_']=="faq"){
				$data['faq_class'] = 'active';
			}
			if(isset($this->request->get['_route_']) && $this->request->get['_route_']=="market-list"){
				$data['market_class'] = 'active';
			}
			if(isset($this->request->get['_route_']) && $this->request->get['_route_']=="comic-store"){
				$data['comic_class'] = 'active';
			}
			if(isset($this->request->get['_route_']) && $this->request->get['_route_']=="grading-service"){
				$data['grading_class'] = 'active';
			}
		}
		else{
			$data['home_class'] = 'active';
		}
		$data['remember_cookie_email'] = '';
		if(isset($_COOKIE['remember_email']))
			$data['remember_cookie_email'] = $_COOKIE['remember_email'];
		
	// =============== Class Active Code ==================
	
	
	// =============== Meta Set ==================
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$data['pro_title'] = '';
		$data['pro_image'] = '';
		$data['pro_url'] = '';
		$data['pro_description'] = '';
		$data['thumb'] ='';
		
		if(isset($this->request->get['product_id'])){
			$pid = $this->request->get['product_id'];
			
			$pro_dtls = $this->model_catalog_product->getProductDetails($pid);
			//print_r($pro_dtls);exit;
			if(!empty($pro_dtls)){
				$data['pro_url'] = $this->url->link('product/product','product_id='.$pid);
				
				$data['pro_title'] = $pro_dtls['model'];
				$data['pro_image'] = $pro_dtls['image'];
				$data['pro_description'] = ($pro_dtls['meta_description']!="") ? strip_tags(html_entity_decode($pro_dtls['meta_description'])):strip_tags(html_entity_decode($pro_dtls['description']));
				$data['thumb'] = $this->model_tool_image->resize($pro_dtls['image'],280, 534);
			}
		}
		
		/************* NOTIFICATION MESSAGE COUNT ***************/
		
		$this->load->model('message/messaging');
		$total_unread_message = $this->model_message_messaging->total_unread_message_notificaion();
		$data['total_unread_message'] = $total_unread_message;
		
		$notificaion_list = $this->model_message_messaging->message_notificaion_list();
		
		$data['notificaion_list'] = $notificaion_list;
	// =============== Meta Set ==================
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/common/header.tpl', $data);
		} else {
			return $this->load->view('default/template/common/header.tpl', $data);
		}
		
		
		
		
	}
}