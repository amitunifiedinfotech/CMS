<?php
class ControllerFaqFaqcategory extends Controller {
	public function index() {
		
		$this->document->setTitle("Faq Category");
		
		$this->load->model('faq/faqcategory');
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		$url = '';
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['results'] = array();
		$filter_data = array(
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
		);
		$faq_contact_query_total= $this->model_faq_faqcategory->getTotalCategories();
		$data['result_set'] = $this->model_faq_faqcategory->getCategoryies($filter_data); 
		
		//print_r($data['result_set']);
		
		$pagination = new Pagination();
		$pagination->total = $faq_contact_query_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('faq/faqcategory', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($faq_contact_query_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($faq_contact_query_total - $this->config->get('config_limit_admin'))) ? $faq_contact_query_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $faq_contact_query_total, ceil($faq_contact_query_total / $this->config->get('config_limit_admin')));

		
		$data['add'] = $this->url->link('faq/faqcategory/add_edit', 'token=' . $this->session->data['token'] , 'SSL');
		$data['delete'] = $this->url->link('faq/faqcategory/delete', 'token=' . $this->session->data['token'] , 'SSL');
		$data['token'] = $this->session->data['token'];
		
	
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/faq/faqcategory.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/faq/faqcategory.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('faq/faqcategory.tpl', $data));
		}
		
	}
	public function add_edit() {
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		else
			$id = 0;
		//echo $id;
		//$id = isset($_REQUEST['id']);
		if(isset($id)){
			$this->document->setTitle("Add Faq Category");
			$data['add_edit_text'] = "Add Faq category";
		}
		else{
			$this->document->setTitle("Edit Category");
			$data['add_edit_text'] = "Edit Faq category";
		}
		
		$this->load->model('faq/faqcategory');


		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if($id!=0){
				$this->model_faq_faqcategory->editFaqCategory($this->request->post,$id);
				$this->session->data['success'] = 'Faq Category edit sucessfully';
			}
			else{
				$this->model_faq_faqcategory->addFaqCategory($this->request->post);
				$this->session->data['success'] = 'Faq Category Added sucessfully';
				
			}

			$this->response->redirect($this->url->link('faq/faqcategory', 'token=' . $this->session->data['token'], 'SSL'));
		}

		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => 'Faq Category',
			'href' => $this->url->link('faq/faqcategory', 'token=' . $this->session->data['token'], 'SSL')
		);
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		$data['heading_title'] = "Add Faq Category";
		
		$data['entry_value'] = "Category";
		$data['entry_status'] = "Status";
		$data['value'] = '';
		$data['status'] = 1;
		
		$data['cancel'] = $this->url->link('faq/faqcategory', 'token=' . $this->session->data['token'] , 'SSL');		
		$data['action'] = $this->url->link('faq/add_edit', 'token=' . $this->session->data['token'] , 'SSL');		
		// For Edit
		
		if(isset($id) && $id!=""){
			
			$faq_cat= $this->model_faq_faqcategory->getCategoryById($id);	
			$data['value'] = $faq_cat['faq_category'];
			$data['status'] = $faq_cat['status'];
		}
		//echo $data['status'];
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('faq/faq_add_from.tpl', $data));
	}

	

	
}