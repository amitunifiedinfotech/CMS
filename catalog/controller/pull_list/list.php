<?php
class ControllerPullListList extends Controller {
	private $error = array();

	public function index(){
		
		if (!$this->customer->isLogged()) {
		    
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		$this->document->setTitle('Pull List');
		
		
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
		
		if (isset($this->request->get['name'])) {
			$data['title'] = $title = $this->request->get['name'];
			//$url .= '&name=' . preg_replace('/\s+/', '+',$this->request->get['name']);
			$data['hidden_title'] = preg_replace('/\s+/', '+', $this->request->get['name']);
		}
		else
			$data['hidden_title'] = $data['title'] = $title = '';
			

		$customer_id = $this->customer->getId();
		
		if (isset($this->request->get['name'])) {
			$name = $this->request->get['name'];
		} else {
			$name = "";
		}
		
		//$limit = 1;
		$filter_data = array(
			'title'              => $title,
			'customer_id'  		 => $customer_id,
			'name'  			 => $name,
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		
		
		
		$product_arr = array();
		$this->load->model('catalog/product');
		
		
		$all_product = $this->model_catalog_product->get_pull_list($filter_data);
		
		$total_want_list = $this->model_catalog_product->get_total_pull_list($filter_data);
		
		
		$pagination = new Pagination();
		$pagination->total = $total_want_list;
		$pagination->page = $page;
		$pagination->limit = $limit;
		if ((isset($this->request->get['name'])) && ($this->request->get['name']!="")) 
		{
			$name = str_replace(" ","+",$this->request->get['name']);
			$pagination->url = $this->url->link('pull_list/list&name='.$name,  '&page={page}');
			
		}
		else
		{
			$pagination->url = $this->url->link('pull_list/list',  '&page={page}');
		}
		
		
		//$data['want_list_url'] = $this->url->link('wantedlist/wanted');
		///$data['add_wantlist'] = $this->url->link('wantedlist/wanted/addEdit');
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($total_want_list) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($total_want_list - $limit)) ? $total_want_list : ((($page - 1) * $limit) + $limit), $total_want_list, ceil($total_want_list / $limit));

		
		$this->load->model('tool/image');
		
		if(!empty($all_product))
		{
			$i=0;
			foreach($all_product as $product_list)
			{
				$product_arr[$i]['id'] = $product_list['id'];
				$product_arr[$i]['image'] = $this->model_tool_image->resize($product_list['image'],228,228);
				$product_arr[$i]['image1'] = $this->model_tool_image->resize($product_list['image1'],228,228);
				$product_arr[$i]['name'] = $product_list['name'];
				$product_arr[$i]['description'] = strip_tags(html_entity_decode($product_list['description']));
				$product_arr[$i]['quantity'] = $product_list['quantity'];
				$product_arr[$i]['variant'] = $product_list['variant'];
				$product_arr[$i]['issue_number'] = $product_list['issue_number'];
				$product_arr[$i]['price'] = $product_list['price'];
				$product_arr[$i]['grading_price'] = $product_list['grading_price'];
				$product_arr[$i]['list_type'] = $product_list['list_type'];
				$i++;
			}
		}
		
		$data['product_arr'] = $product_arr;
		//echo "<pre>";
		//print_r($product_arr);
		//die;
		//$total_want_list = $this->model_wantedlist_wantlist->getTotalWantList($filter_data);
		//$data['all_want_list'] = $this->model_wantedlist_wantlist->getWantList($filter_data);
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/pull_list/list.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/pull_list/list.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/pull_list/list.tpl', $data));
		}
		
		//$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/pull_list/list.tpl', $data));
		
	}
	public function add()
	{
		if (!$this->customer->isLogged()) {
		    $this->session->data['redirect'] = $_SERVER['HTTP_REFERER'];
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
			
		}
		else
		{
			if (isset($this->request->post))
			{
				/*echo "<pre>";
				print_r($this->request->post);*/
				//die;
				$product_arr = array();
				$customer_id = $this->customer->getId();
				
				$product_arr[] = $this->request->post['main_product_id'];
				if(!empty($this->request->post['variant_list']))
				{
					$variant_arr = $this->request->post['variant_list'];
					if(!empty($variant_arr))
					{
						foreach($variant_arr as $variant_list)
						{
							$product_arr[] = $variant_list;
						}
					}
					
				}
				
				$this->load->model('catalog/product');
				$this->model_catalog_product->add_to_pull_list($customer_id,$product_arr,$this->request->post['list_type']);
				
				$this->response->redirect($this->url->link('pull_list/list', '', 'SSL'));
			}
			
		}
	}
	public function add_to_pull_list()
	{
		if (!$this->customer->isLogged()) {
		    $this->session->data['redirect'] = $_SERVER['HTTP_REFERER'];
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		else
		{
			$product_arr[] = $this->request->get['product_id'];
			$list_type = $this->request->get['list_type'];
			$customer_id = $this->customer->getId();
			
			$this->load->model('catalog/product');
			$this->model_catalog_product->add_to_pull_list($customer_id,$product_arr,$list_type);
			
			$this->response->redirect($this->url->link('pull_list/list', '', 'SSL'));
		}
	}
	public function update_pull_list()
	{
		$pull_id = $this->request->post['pull_id'];
		$quantity = $this->request->post['quantity'];
		$price_val = $this->request->post['price_val'];
 
		$this->load->model('catalog/product');
		$all_product = $this->model_catalog_product->update_pull_list($pull_id,$quantity);
		echo number_format(($price_val*$quantity),2);
	}
	public function check_product_availability()
	{
		$pull_id = $this->request->post['pull_id'];
		$this->load->model('catalog/product');
		echo $get_product_details = $this->model_catalog_product->get_product_quantity($pull_id);
		
	}
	public function delete_pull_list()
	{
		$pull_id = $this->request->get['pull_id'];
		$this->load->model('catalog/product');
		$this->model_catalog_product->delete_pull_list($pull_id);
		$this->response->redirect($this->url->link('pull_list/list', '', 'SSL'));
	}
	
	public function getWantListTitle() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('catalog/product');

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

			//$results = $this->model_wantedlist_wantlist->getWantListTitle($filter_data);
			$results = $this->model_catalog_product->getWantListTitle($filter_data);

			foreach ($results as $result) {
				
				$title = preg_replace('/\s+/', '+', $result['name']);
				$json[] = array(
					'show_title' => $title,
					'title'       => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}