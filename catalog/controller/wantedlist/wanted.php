<?php
class ControllerWantedlistWanted extends Controller {
	private $error = array();

	public function autocompleteTitle() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('wantedlist/wantlist');

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

			$results = $this->model_wantedlist_wantlist->getProductsTitle($filter_data);

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

	public function getWantListTitle() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('wantedlist/wantlist');

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

			$results = $this->model_wantedlist_wantlist->getWantListTitle($filter_data);

			foreach ($results as $result) {
				
				$title = preg_replace('/\s+/', '+', $result['title']);
				$json[] = array(
					'show_title' => $title,
					'title'       => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getWantListIssue() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('wantedlist/wantlist');

			if (isset($this->request->get['title'])) {
				$title = $this->request->get['title'];
			} else {
				$title = '';
			}
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
				'title'  	=> $title,
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
				
			);

			$results = $this->model_wantedlist_wantlist->getWantlistIssue($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'issue_number' => $result['issue_number'],
					'name'       => strip_tags(html_entity_decode($result['issue_number'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function autocompleteIssue() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('wantedlist/wantlist');

			if (isset($this->request->get['title'])) {
				$title = $this->request->get['title'];
			} else {
				$title = '';
			}
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
				'title'  	=> $title,
				'filter_name'  => $filter_name,
				'start'        => 0,
				'limit'        => $limit
				
			);

			$results = $this->model_wantedlist_wantlist->getProductsIssue($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'issue_number' => $result['issue_number'],
					'name'       => strip_tags(html_entity_decode($result['issue_number'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login'));
		}
		
		$this->load->language('common/home');
		$this->load->model('wantedlist/wantlist');

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_product_limit');
			//$limit = 1;
		}
		$data['text_empty'] = 'No records found!!!';

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
			'text' => 'Want-list',
			'href' => $this->url->link('wantedlist/wanted')
		);
		
		if (isset($this->session->data['success_add_edit'])) {
			$data['success_add_edit'] = $this->session->data['success_add_edit'];

			unset($this->session->data['success_add_edit']);
		} else {
			$data['success_add_edit'] = '';
		}

	
		$this->document->setTitle('Want List');
		$data['heading_title'] = 'Want List';
		
		$url = '';
			
		if (isset($this->request->get['name'])) {
			$data['title'] = $title = $this->request->get['name'];
			$url .= '&name=' . preg_replace('/\s+/', '+',$this->request->get['name']);
			$data['hidden_title'] = preg_replace('/\s+/', '+', $this->request->get['name']);
		}
		else
			$data['hidden_title'] = $data['title'] = $title = '';
		
		if (isset($this->request->get['issue_number'])) {
			$data['issue_number'] = $issue_number = $this->request->get['issue_number'];
			$url .= '&issue_number=' . $this->request->get['issue_number'];
		}
		else
			$data['issue_number'] = $issue_number = '';

		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}

		$filter_data = array(
			'title'              => $title,
			'issue_number'       => $issue_number,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$total_want_list = $this->model_wantedlist_wantlist->getTotalWantList($filter_data);

		$data['all_want_list'] = $this->model_wantedlist_wantlist->getWantList($filter_data);
		
		
		$pagination = new Pagination();
		$pagination->total = $total_want_list;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('wantedlist/wanted', $url . '&page={page}');
		
		$data['want_list_url'] = $this->url->link('wantedlist/wanted');
		$data['add_wantlist'] = $this->url->link('wantedlist/wanted/addEdit');
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_want_list) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_want_list - $limit)) ? $total_want_list : ((($page - 1) * $limit) + $limit), $total_want_list, ceil($total_want_list / $limit));


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/wantedlist/wantlist.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/wantedlist/wantlist.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/wantedlist/wantlist.tpl', $data));
		}


// *************************************************************************** My Own Code *********************************************************
	}

	public function addEdit() {
		
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login'));
		}
		$this->load->language('common/home');
		$this->load->language('seller_product/product');

		$this->load->model('wantedlist/wantlist');
		$this->load->model('seller_product/product');

		if(isset($_REQUEST['wanted_id'])){
			$id = base64_decode($_REQUEST['wanted_id']);
			$data['wanted_id'] = base64_decode($_REQUEST['wanted_id']);
		}
		else
			$id = 0;

		if($id==0){
			$this->document->setTitle("Add WantList");
			$data['add_edit_text'] = "Add WantList";
		}
		else{
			$this->document->setTitle("Edit WantList");
			$data['add_edit_text'] = "Edit WantList";
		}
		$data['success_add_edit'] = '';
		
	// ************************ Custom Code (Get grade)*************************************************
	
		$data['all_grade'] = $all_grade = $this->model_seller_product_product->getgrade();
		
	// ************************ Custom Code ************************************************************
	
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) ) {	
			//echo "<pre>";print_r($this->request->post);exit; 
			if($id==0){
				
				// Add to DB
				$this->model_wantedlist_wantlist->addWantList($this->request->post);
				$this->session->data['success_add_edit'] = 'You have sucessfully added your want-list!!!';
			}
			else{
				$this->model_wantedlist_wantlist->editWantList($this->request->post,$id);
				$this->session->data['success_add_edit'] = 'You have sucessfully edit your want-list!!!';
			}
			$this->response->redirect($this->url->link('wantedlist/wanted'));
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
			'text' => 'Wantlist',
			'href' => $this->url->link('wantedlist/wanted')
		);
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['grade'])) {
			$data['error_grade'] = $this->error['grade'];
		} else {
			$data['error_grade'] = '';
		}

		if (isset($this->error['price'])) {
			$data['error_price'] = $this->error['price'];
		} else {
			$data['error_price'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}

		if (isset($this->error['issue_number'])) {
			$data['error_issue_number'] = $this->error['issue_number'];
		} else {
			$data['error_issue_number'] = '';
		}


		if (isset($this->request->get['wanted_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$want_info = $this->model_wantedlist_wantlist->getWantListById(base64_decode($this->request->get['wanted_id']));
		}

		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($want_info)) {
			$data['name'] = $want_info['title'];
		} else {
			$data['name'] = '';
		}

		if (isset($this->request->post['grade_from'])) {
			$data['grade_from'] = $this->request->post['grade_from'];
		} elseif (!empty($want_info)) {
			$data['grade_from'] = $want_info['grade_from'];
		} else {
			$data['grade_from'] = '';
		}
		
		if (isset($this->request->post['grade_to'])) {
			$data['grade_to'] = $this->request->post['grade_to'];
		} elseif (!empty($want_info)) {
			$data['grade_to'] = $want_info['grade_to'];
		} else {
			$data['grade_to'] = '';
		}
		
		if (isset($this->request->post['issue_number'])) {
			$data['issue_number'] = $this->request->post['issue_number'];
		} elseif (!empty($want_info)) {
			$data['issue_number'] = $want_info['issue_number'];
		} else {
			$data['issue_number'] = '';
		}


		if (isset($this->request->post['price_from'])) {
			$data['price_from'] = $this->request->post['price_from'];
		} elseif (!empty($want_info)) {
			$data['price_from'] = ($want_info['price_from']==0)?'':$want_info['price_from'];
		} else {
			$data['price_from'] = '';
		}
		

		if (isset($this->request->post['price_to'])) {
			$data['price_to'] = $this->request->post['price_to'];
		} elseif (!empty($want_info)) {
			$data['price_to'] = ($want_info['price_to']==0)?'':$want_info['price_to'];
		} else {
			$data['price_to'] = '';
		}


		$data['cancel'] = $this->url->link('wantedlist/wanted');
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/wantedlist/wanted_form.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/wantedlist/wanted_form.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/wantedlist/wanted_form.tpl', $data));
		}


// *************************************************************************** My Own Code *********************************************************
	}
	
	protected function validateForm() {
		if (!$this->customer->isLogged()) {
			$this->error['warning'] = 'You cannot add your preference until you logged in.';
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 255)) {
			$this->error['name'] = $this->language->get('error_name');
		}


		if ((utf8_strlen($this->request->post['issue_number']) < 1) || (utf8_strlen($this->request->post['issue_number']) > 64)) {
			$this->error['issue_number'] = 'Issue Number between 1 to 64';
		}
		
		// Check if the title and issue number exists
		if ((utf8_strlen($this->request->post['issue_number']) > 0) && (utf8_strlen($this->request->post['name']) > 0)) {
			
			$return_val = $this->model_wantedlist_wantlist->checkProductExists($this->request->post['name'],$this->request->post['issue_number']);
			//if($return_val==0){
			//	$this->error['warning'] = 'Title and issue number does not exists.';
			//}
			//else
			{
				// Check if duplicate product added
				if (!isset($this->request->get['wanted_id'])){
					$val = $this->model_wantedlist_wantlist->checkMyWantListByName($this->request->post['name'],$this->request->post['issue_number']);
					if($val==1){
						$this->error['warning'] = 'Title and issue number already exists in your want-list.';
					}
				}
			}
		}
		
		// Check if the from price is greater than to to price
		if((($this->request->post['grade_from'] !="" && $this->request->post['grade_to'] =="" )|| ($this->request->post['grade_from'] =="" && $this->request->post['grade_to'] !="" ))){
			$this->error['grade'] = 'You cannot select one of the grade.';
		}

		
		
		// Check if the from-grade is greater than to-grade
		if ((utf8_strlen($this->request->post['grade_from']) > 0) && (utf8_strlen($this->request->post['grade_to']) > 0)) {
			$return_val = $this->model_wantedlist_wantlist->checkGrade($this->request->post['grade_from'],$this->request->post['grade_to']);
			if($return_val==0){
				$this->error['grade'] = 'From-grade cannot greater than or equal to-grade.';
			}
		}
		
		// Check if the from price is greater than to to price
		if((($this->request->post['price_from'] !="" && $this->request->post['price_to'] =="" )|| ($this->request->post['price_from'] =="" && $this->request->post['price_to'] !="" ))){
			$this->error['price'] = 'You cannot fill one of the price.';
		}
		if (($this->request->post['price_from'] !="") && $this->request->post['price_to'] !="") {
			if($this->request->post['price_from'] >= $this->request->post['price_to'] ){
				$this->error['price'] = 'From price cannot greater than to price.';
			}
		}
		
		
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}

		return !$this->error;
	}


	public function delWant() {
		if (!$this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/login'));
		}
		
		$this->load->model('wantedlist/wantlist');
		$this->load->model('seller_product/product');
		
		// check its own product
		$val = $this->model_wantedlist_wantlist->checkMyWantList($this->request->get['wanted_id']);
		if($val){
			$this->model_wantedlist_wantlist->deleteWantList($this->request->get['wanted_id']);

			$this->session->data['success_add_edit'] = 'Want-list deleted sucessfully!!!';
			$this->response->redirect($this->url->link('wantedlist/wanted'));
		}

	}

	

}