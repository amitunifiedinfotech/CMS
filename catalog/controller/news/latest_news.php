<?php
class ControllerNewsLatestNews extends Controller {
	public function index() {
		$this->load->language('account/account');

		$this->document->setTitle('Latest News');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		$data['breadcrumbs'][] = array(
			'text' => 'List News',
			'href' => $this->url->link('news/latest_news/list_all_news')
		);
		
		// Get News
		if($this->request->get['latest_id']==""){
			$this->response->redirect($this->url->link('common/home', '', 'SSL'));
		}
		
		$this->load->model('news/latest_news');
		
		$news_details = $this->model_news_latest_news->getNewsById($this->request->get['latest_id']);
		
		$data['description'] = '';
		if(!empty($news_details))
		$data['description'] = $news_details['details'];

		$data['heading_title'] = 'Latest News';

		


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/list_news.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/news/list_news.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/news/list_news.tpl', $data));
		}
	}

	
	public function list_all_news() {
		
		$this->load->language('account/account');

		$this->document->setTitle('Latest News');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);
		
		

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
		
		// Get News
		$this->load->model('news/latest_news');		

		$url = '';
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		
		$filter_data = array(
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$news_total = $this->model_news_latest_news->getTotalNews();
		$all_news = $this->model_news_latest_news->getAllNews($filter_data);
		
		$data['latest_news'] = array();
		if(!empty($all_news)){
		  foreach ($all_news as $each_news) {
			
			$data['latest_news'][] = array(
				'heading'  		=> $each_news['heading'],
				'details'  		=> $each_news['details'],
				'href'        		=> $this->url->link('news/latest_news',  '&latest_id='. $each_news['id'])
			);
		  }
		  
		}
		
		
		//print_r($data['all_news']);exit;

		$pagination = new Pagination();
		$pagination->total = $news_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('news/latest_news/list_all_news', $url . '&page={page}');
		
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($news_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($news_total - $limit)) ? $news_total : ((($page - 1) * $limit) + $limit), $news_total, ceil($news_total / $limit));


		$this->load->model('setting/setting');
		$this->load->model('tool/image');
		$home_page_news_banner = $this->model_setting_setting->getContactSettingByKey('home_page_news_banner');
		$data['latest_news_banner'] = $this->model_tool_image->resize($home_page_news_banner, 1164, 1800);
		
		$data['heading_title'] = 'Latest News';
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/news/all_news_display.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/news/all_news_display.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/news/all_news_display.tpl', $data));
		}
	}

	
}