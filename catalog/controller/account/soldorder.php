<?php
class ControllerAccountSoldOrder extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/order', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/order');

		$this->document->setTitle('Sold History');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('account/soldorder', $url, 'SSL')
		);

		$data['heading_title'] = 'Sold History';

		$data['text_empty'] = $this->language->get('text_empty');

		$data['column_order_id'] = $this->language->get('column_order_id');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_customer'] = $this->language->get('column_customer');
		$data['column_product'] = $this->language->get('column_product');
		$data['column_total'] = $this->language->get('column_total');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['orders'] = array();

		$this->load->model('account/order');

		$order_total = $this->model_account_order->getMySoldTotalOrders();

		$results = $this->model_account_order->getMySoldOrders(($page - 1) * 10, 10);
		

		foreach ($results as $result) {
			$product_total = $this->model_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_account_order->getTotalOrderVouchersByOrderId($result['order_id']);
			
		// ============================================ Own Calcualtion =================================================
			$total_number_product = $this->model_account_order->getSoldTotalOrdersByOrder($result['order_id']);
			
			$totals = $this->model_account_order->getOrderTotals($result['order_id']);
			
			$coupon_value = 0;
			$shipping = 0;
			foreach ($totals as $total) {
				if($total['code']=='shipping'){
					$shipping = $total['value'];
				}
				if($total['code']=='coupon'){
					$coupon_value = $total['value'];
				}
			}
			$products = $this->model_account_order->getSoldOrderProducts($result['order_id']);
			$total_pro_val = 0;
			//$tracking_number = 0;
			foreach($products as $each_pro){
				$total_pro_val += $each_pro['total'];
				//$tracking_number = $each_pro['tracking_number'];
			}
			$tot = $total_pro_val + $shipping + $coupon_value;
		// ============================================ Own Calcualtion =================================================

			$data['orders'][] = array(
				'order_id'   	=> $result['order_id'],
				'name'       	=> $result['firstname'] . ' ' . $result['lastname'],
				'status'     	=> $result['status'],
				'shipping_carrier'     	=> $result['shipping_carrier'],
				'tracking_number'     => $result['tracking_number'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => $total_number_product,
				'total'      => $this->currency->format($tot, $result['currency_code'], $result['currency_value']),
				'href'       => $this->url->link('account/soldorder/info', 'order_id=' . $result['order_id'], 'SSL'),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/soldorder', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['account'] = $this->url->link('account/account', '', 'SSL');
		$data['continue'] = $this->url->link('common/home', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/sold_order_list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/sold_order_list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/sold_order_list.tpl', $data));
		}
	}

	public function info() {
		$this->load->language('account/order');

		if (isset($this->request->get['order_id'])) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = 0;
		}

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/soldorder/info', 'order_id=' . $order_id, 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->getSoldOrder($order_id);
		//echo "<pre>";print_r($order_info);exit;

		if ($order_info) {
			$this->document->setTitle($this->language->get('text_order'));

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/soldorder', $url, 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/soldorder/info', 'order_id=' . $this->request->get['order_id'] . $url, 'SSL')
			);

			$data['heading_title'] = 'Buyer Order Information';

			$data['text_order_detail'] = $this->language->get('text_order_detail');
			$data['text_invoice_no'] = $this->language->get('text_invoice_no');
			$data['text_order_id'] = $this->language->get('text_order_id');
			$data['text_date_added'] = $this->language->get('text_date_added');
			$data['text_shipping_method'] = $this->language->get('text_shipping_method');
			$data['text_shipping_address'] = $this->language->get('text_shipping_address');
			$data['text_payment_method'] = $this->language->get('text_payment_method');
			$data['text_payment_address'] = $this->language->get('text_payment_address');
			$data['text_history'] = $this->language->get('text_history');
			$data['text_comment'] = $this->language->get('text_comment');

			$data['column_name'] = $this->language->get('column_name');
			$data['column_model'] = $this->language->get('column_model');
			$data['column_quantity'] = $this->language->get('column_quantity');
			$data['column_price'] = $this->language->get('column_price');
			$data['column_total'] = $this->language->get('column_total');
			$data['column_action'] = $this->language->get('column_action');
			$data['column_date_added'] = $this->language->get('column_date_added');
			$data['column_status'] = $this->language->get('column_status');
			$data['column_comment'] = $this->language->get('column_comment');

			$data['button_reorder'] = $this->language->get('button_reorder');
			$data['button_return'] = $this->language->get('button_return');
			$data['button_continue'] = $this->language->get('button_continue');

			if (isset($this->session->data['error'])) {
				$data['error_warning'] = $this->session->data['error'];

				unset($this->session->data['error']);
			} else {
				$data['error_warning'] = '';
			}

			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];

				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$data['order_id'] = $this->request->get['order_id'];
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$data['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

			$data['shipping_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$data['shipping_method'] = $order_info['shipping_method'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$data['products'] = array();

			$products = $this->model_account_order->getSoldOrderProducts($this->request->get['order_id']);
			//echo "<pre>";print_r($products);exit;

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_account_order->getOrderOptions($this->request->get['order_id'], $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], 'SSL');
				} else {
					$reorder = '';
				}
				
				// Image
				$this->load->model('tool/image');
				if ($product_info['image']) {
				$image = $this->model_tool_image->resize($product_info['image'], 110, 150);
			} else {
				$image = $this->model_tool_image->resize('placeholder.png',110,150 );
			}
				

				$data['products'][] = array(
					'name'     => $product['name'].'-'.$product_info['issue_number'],
					'image'    => $image,
					'option'   => $option_data,
					'quantity' => $product['quan'],
					'raw_price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'price'    => $product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0),
					'raw_total'    => $product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
					'reorder'  => $reorder,
					'return'   => $this->url->link('account/return/add', 'order_id=' . $order_info['order_id'] . '&product_id=' . $product['product_id'], 'SSL')
				);
			}

			// Voucher
			$data['vouchers'] = array();

			$vouchers = $this->model_account_order->getOrderVouchers($this->request->get['order_id']);

			foreach ($vouchers as $voucher) {
				$data['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$data['totals'] = array();

			$totals = $this->model_account_order->getOrderTotals($this->request->get['order_id']);
			
		// ++++++++++++++++++++++++++++++++ My Code +++++++++++++++++++++++++++++++++++++++++
			$data['shipping'] = '';
			$data['coupon_title'] = '';
			$data['coupon_value'] = '';
			foreach ($totals as $total) {
				if($total['code']=='shipping'){
					$data['shipping'] = $total['value'];
				}
				if($total['code']=='coupon'){
					$data['coupon_value'] = $total['value'];
					$data['coupon_title'] = $total['title'];
				}
			}
			
			

			foreach ($totals as $total) {
				$data['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$data['comment'] = nl2br($order_info['comment']);

			// History
			$data['histories'] = array();

			$results = $this->model_account_order->getOrderHistories($this->request->get['order_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			$data['my_order'] = $this->url->link('account/soldorder', '', 'SSL');
			$data['account'] = $this->url->link('account/account', '', 'SSL');
			$data['continue'] = $this->url->link('common/home', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/sold_order_info.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/sold_order_info.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/account/sold_order_info.tpl', $data));
			}
		} else {
			$this->document->setTitle($this->language->get('text_order'));

			$data['heading_title'] = $this->language->get('text_order');

			$data['text_error'] = $this->language->get('text_error');

			$data['button_continue'] = $this->language->get('button_continue');

			$data['breadcrumbs'] = array();

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/home')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_account'),
				'href' => $this->url->link('account/account', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('account/order', '', 'SSL')
			);

			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('text_order'),
				'href' => $this->url->link('account/order/info', 'order_id=' . $order_id, 'SSL')
			);

			$data['continue'] = $this->url->link('account/order', '', 'SSL');

			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/error/not_found.tpl', $data));
			} else {
				$this->response->setOutput($this->load->view('default/template/error/not_found.tpl', $data));
			}
		}
	}
	
	
	public function addTracking() {
		$this->load->language('account/order');
		
		//print_r($this->request->post);exit;

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/soldorder/addTracking', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->model('account/order');

		$order_info = $this->model_account_order->addTracking($this->request->post);
		
		$this->session->data['success'] = "Tracking Number Added Sucessfully.";
		$this->response->redirect($this->url->link('account/soldorder'));

		
	}

	public function send_books() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/soldorder/send_books', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/order');

		$this->document->setTitle('Send Books To Admin');

		$data['breadcrumbs'] = array();
		$url = '';

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_account'),
			'href' => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => 'Send Books',
			'href' => $this->url->link('account/soldorder/send_books', $url, 'SSL')
		);


		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['heading_title'] = 'Send Books';

		$data['text_empty'] = $this->language->get('text_empty');

		$data['button_view'] = $this->language->get('button_view');
		$data['button_continue'] = $this->language->get('button_continue');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		$data['orders'] = array();

		$this->load->model('account/order');

		$order_total = $this->model_account_order->getTotalSendBooks();

		$results = $this->model_account_order->getSendBooks(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['send_books'][] = array(
				'id'   			=> $result['id'],
				'title'   		=> $result['title'],
				'issue_number'      	=> $result['issue_number'] ,
				'grade'     		=> $result['grade_value'],
				'page_quality'     	=> $result['page_quality'],
				'shipping_carrier'     	=> $result['shipping_carrier'],
				'tracking_number'     	=> $result['tracking_number'],
				'date_added'		=> date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'edit'       		=> $this->url->link('account/soldorder/addEdit_send_books', 'id=' . base64_encode($result['id']), 'SSL'),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		$pagination->url = $this->url->link('account/soldorder/send_books', 'page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($order_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($order_total - 10)) ? $order_total : ((($page - 1) * 10) + 10), $order_total, ceil($order_total / 10));

		$data['add'] = $this->url->link('account/soldorder/addEdit_send_books', '', 'SSL');
		$data['continue'] = $this->url->link('common/home', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/send_books.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/send_books.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/send_books.tpl', $data));
		}
	}

	public function addEdit_send_books() {
		
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login'));
		}

		$this->load->language('account/order');
		$this->load->model('account/order');
		$this->load->model('seller_product/product');

		if(isset($_REQUEST['id'])){
			$id = base64_decode($_REQUEST['id']);
		}
		else
			$id = 0;

		if($id==0){
			$this->document->setTitle("Send Books");
			$data['add_edit_text'] = "Send Books";
		}
		else{
			$this->document->setTitle("Edit Send Books");
			$data['add_edit_text'] = "Edit Send Books";
		}
		$data['success_add_edit'] = '';
		
	// ************************ Custom Code (Get grade)*************************************************
	
		$data['all_grade'] = $all_grade = $this->model_seller_product_product->getgrade();
		
	// ************************ Custom Code ************************************************************
	
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateBook()) ) {	
			//echo "<pre>";print_r($this->request->post);exit; 
			if($id==0){
				// Add to DB
				$this->model_account_order->addSendBook($this->request->post);
				$this->session->data['success'] = 'You have sucessfully added!!!';
			}
			else{
				$this->model_account_order->editSendBook($this->request->post,$id);
				$this->session->data['success'] = 'You have sucessfully edit!!!';
			}
			$this->response->redirect($this->url->link('account/soldorder/send_books'));
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
			'text' => 'Send Books List',
			'href' => $this->url->link('account/soldorder/send_books')
		);
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->request->get['id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$send_book_info = $this->model_account_order->getSellerSendBookById($id);
		}

		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($send_book_info)) {
			$data['title'] = $send_book_info['title'];
		} else {
			$data['title'] = '';
		}
		
		if (isset($this->request->post['issue_number'])) {
			$data['issue_number'] = $this->request->post['issue_number'];
		} elseif (!empty($send_book_info)) {
			$data['issue_number'] = $send_book_info['issue_number'];
		} else {
			$data['issue_number'] = '';
		}
		
		if (isset($this->request->post['grade'])) {
			$data['grade'] = $this->request->post['grade'];
		} elseif (!empty($send_book_info)) {
			$data['grade'] = $send_book_info['grade'];
		} else {
			$data['grade'] = '';
		}

		if (isset($this->request->post['page_quality'])) {
			$data['page_quality'] = $this->request->post['page_quality'];
		} elseif (!empty($send_book_info)) {
			$data['page_quality'] = $send_book_info['page_quality'];
		} else {
			$data['page_quality'] = '';
		}
		if (isset($this->request->post['tracking_number'])) {
			$data['tracking_number'] = $this->request->post['tracking_number'];
		} elseif (!empty($send_book_info)) {
			$data['tracking_number'] = $send_book_info['tracking_number'];
		} else {
			$data['tracking_number'] = '';
		}
		if (isset($this->request->post['shipping_carrier'])) {
			$data['shipping_carrier'] = $this->request->post['shipping_carrier'];
		} elseif (!empty($send_book_info)) {
			$data['shipping_carrier'] = $send_book_info['shipping_carrier'];
		} else {
			$data['shipping_carrier'] = '';
		}

		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}

		$data['cancel'] = $this->url->link('account/soldorder/send_books');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/seller_send_book_edit.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/seller_send_book_edit.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/seller_send_book_edit.tpl', $data));
		}


	}
	protected function validateBook() {
		if ((utf8_strlen($this->request->post['title']) < 3) || (utf8_strlen($this->request->post['title']) > 255)) {
				$this->error['title'] = "Title length Should be 4-254 characters";
		}

		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		return !$this->error;
	}

	public function del_seller_sendbook() {
		$this->load->language('account/order');
		$this->load->model('account/order');
		
		if($this->request->get['id']){
			$this->model_account_order->del_seller_send_book($this->request->get['id']);
			$this->session->data['success'] = 'You have sucessfully delete!!!';
		}
		$this->response->redirect($this->url->link('account/soldorder/send_books'));
		
	}
	
	public function cancel_seller_sendbook() {
		$this->load->language('account/order');
		$this->load->model('account/order');
		
		if($this->request->get['id']){
			
			// Send Notification to admin
			
			$this->session->data['success'] = 'Your Message is successfully send to admin!!!';
		}
		$this->response->redirect($this->url->link('account/soldorder/send_books'));
		
	}
	

}