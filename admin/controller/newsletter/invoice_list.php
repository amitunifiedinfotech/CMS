<?php   
class ControllerNewsletterInvoiceList extends Controller {   
	public function index() {

        $this->language->load('common/home');

		$this->document->setTitle("Invoice List");

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => true
		);
		
		$data['breadcrumbs'][] = array(
			'text'      => 'Invoice List',
			'href'      => $this->url->link('newsletter/invoice_list', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);

		
		
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
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}


		$this->load->model('latestnews/addedit');
		
		//$this->config->get('config_limit_admin')
		$filter_data = array(
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => 10
			
		);
		$post_total= $this->model_latestnews_addedit->get_invoice_total();
		$data['post_arr'] = $this->model_latestnews_addedit->get_invoice_list($filter_data);
		//print_r($data['results_set']);
		
		$pagination = new Pagination();
		$pagination->total = $post_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('newsletter/news', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($post_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($post_total - $this->config->get('config_limit_admin'))) ? $post_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $post_total, ceil($post_total / $this->config->get('config_limit_admin')));
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		
		$this->response->setOutput($this->load->view('newsletter/invoice_list.tpl', $data));
	}

        
     public function del_invoice()
	 {
		$this->load->model('latestnews/addedit');
		$res = $this->model_latestnews_addedit->delete_invoice($_GET['post_id']);
		$this->response->redirect($this->url->link('newsletter/invoice_list', 'token=' . $this->session->data['token'] . $url, 'SSL'));
	 }
	

// *********************************** Newsletter Section *************************************************************************************
	
}
?>