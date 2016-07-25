<?php   
class ControllerNewsletterNews extends Controller {   
	public function index() {

        $this->language->load('common/home');

		$this->document->setTitle("Email Template");

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => true
		);
		
		$data['breadcrumbs'][] = array(
			'text'      => 'Email Template',
			'href'      => $this->url->link('newsletter/news', 'token=' . $this->session->data['token'], 'SSL'),
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


		$this->load->model('news/addedit');
		
		
		$filter_data = array(
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
			
		);
		
		$post_total= $this->model_news_addedit->getTotalPost();
		$data['post_arr'] = $this->model_news_addedit->getAllPost($filter_data);
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


		
		$this->response->setOutput($this->load->view('newsletter/post.tpl', $data));
	}

        
        
        
	public function addNews()
        {
            $this->data['token'] = $this->session->data['token'];
            $this->load->model('news/addedit');
                
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

                if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
                    $this->data = $_POST;
                   $this->model_news_addedit->addNews($this->data);
                   $this->redirect($this->url->link('newsletter/news', 'token=' . $this->session->data['token'] . $url, 'SSL')); 
                }
                
		$this->response->setOutput($this->load->view('newsletter/add_news.tpl', $data));
           
        }
        
        
        public function editNews()
        {
		
			$data['token'] = $this->session->data['token'];
			$this->load->model('news/addedit');
			$this->document->setTitle("Edit Email Template");
			
			$data['column_left'] = $this->load->controller('common/column_left');
			$data['column_right'] = $this->load->controller('common/column_right');
			$data['content_top'] = $this->load->controller('common/content_top');
			$data['content_bottom'] = $this->load->controller('common/content_bottom');
			$data['footer'] = $this->load->controller('common/footer');
			$data['header'] = $this->load->controller('common/header');
			
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
			
			$data['breadcrumbs'] = array();
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
				'separator' => false
			);
		$data['cancel'] = $this->url->link('newsletter/news', 'token=' . $this->session->data['token'] , 'SSL');		
		$data['action'] = $this->url->link('newsletter/news/editNews', 'token=' . $this->session->data['token'] , 'SSL');		
			
			$post_arr = $this->model_news_addedit->getNews($_GET['post_id']);
			$data['post_arr'] = $post_arr;
			if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
				$this->data = $_POST;
				$this->model_news_addedit->editNews($_GET['post_id'],$this->data);
				
				$this->session->data['success'] = 'Email template edited sucessfully';
				$this->response->redirect($this->url->link('newsletter/news', 'token=' . $this->session->data['token'], 'SSL'));
			}
			
			$this->response->setOutput($this->load->view('newsletter/edit_news.tpl', $data));
			
           
        }
        
        
	public function permission() {
		if (isset($this->request->get['route'])) {
			$route = '';

			$part = explode('/', $this->request->get['route']);

			if (isset($part[0])) {
				$route .= $part[0];
			}

			if (isset($part[1])) {
				$route .= '/' . $part[1];
			}

			$ignore = array(
				'common/home',
				'common/login',
				'common/logout',
				'common/forgotten',
				'common/reset',
				'error/not_found',
				'error/permission'		
			);			

			if (!in_array($route, $ignore) && !$this->user->hasPermission('access', $route)) {
				return $this->forward('error/permission');
			}
		}
	}
	
	
// *********************************** Newsletter Section *************************************************************************************

	public function list_newsletter() {

        $this->language->load('common/home');

		$this->document->setTitle("Newsletter Template");

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token'], 'SSL'),
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


		$this->load->model('news/addedit');
		
		
		$filter_data = array(
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
			
		);
		
		$newsletter_total= $this->model_news_addedit->getTotalNewsletter();
		$data['newsletter_arr'] = $this->model_news_addedit->getAllNewsletter($filter_data);
		//print_r($data['results_set']);	
		
		//$data['cancel'] = $this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token'] , 'SSL');		
		$data['add_button'] = $this->url->link('newsletter/news/add_newsletter_template', 'token=' . $this->session->data['token'] , 'SSL');
		
		$pagination = new Pagination();
		$pagination->total = $newsletter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($newsletter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($newsletter_total - $this->config->get('config_limit_admin'))) ? $newsletter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $newsletter_total, ceil($newsletter_total / $this->config->get('config_limit_admin')));
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		
		$this->response->setOutput($this->load->view('newsletter/newsletter_template.tpl', $data));
	}

	public function add_newsletter_template()
        {
		$data['token'] = $this->session->data['token'];
		$this->load->model('news/addedit');
		$this->document->setTitle("Edit Email Template");
		
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		else
			$id = 0;
		//echo $id;
		//$id = isset($_REQUEST['id']);
		if($id==0){
			$this->document->setTitle("Add Newsletter");
			$data['add_edit_text'] = "Add Newsletter";
			$data['add_edit_text1'] = "Add";
		}
		else{
			$this->document->setTitle("Edit Newsletter");
			$data['add_edit_text'] = "Edit Newsletter";
			$data['add_edit_text1'] = "Edit";
		}
		
		$data['newsletter_subject'] = "";
		$data['newsletter_message'] = "";
		$data['auto_send_status'] = "";
		
		
		$data['cancel'] = $this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token'] , 'SSL');		
	
		// For Edit
		if($id!=0){
			
			$news_temp= $this->model_news_addedit->getNewsletterTemplateById($id);
			
			$data['newsletter_subject'] = $news_temp['newsletter_subject'];
			$data['newsletter_message'] = $news_temp['newsletter_message'];
			$data['auto_send_status'] = $news_temp['auto_send_status'];
			
		}
		//print_r($data);exit;
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
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
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		
		
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if(!isset($this->request->post['auto_send_status']))
				$this->request->post['auto_send_status'] = 0;
			
			if($id!=0){
				$this->model_news_addedit->editNewsletterTemplate($this->request->post,$id);
				$this->session->data['success'] = 'Newsletter template edit sucessfully';
			}
			else{
				$this->model_news_addedit->addNewsletterTemplate($this->request->post);
				$this->session->data['success'] = 'Newsletter template Added sucessfully';
				
			}
			
			$this->response->redirect($this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->response->setOutput($this->load->view('newsletter/add_edit_newsletter.tpl', $data));
			
           
        }
        
	public function del_newsletter_template() {
		
		$this->load->model('news/addedit');
		
		
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		else
			$id = 0;
		
		if($id!=0){
			$this->model_news_addedit->deleteNewsletterTemplate($id);
			$this->session->data['success'] = "Newsletter deleted sucessfully!!!";
		}
		
		$this->response->redirect($this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token'], 'SSL'));
	}
        
	public function list_subscriber() {

        $this->language->load('common/home');

		$this->document->setTitle("Newsletter Subscriber");

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
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


		$this->load->model('news/addedit');
		
		
		$filter_data = array(
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
		
		$newsletter_total= $this->model_news_addedit->getTotalNewsletterSubscriber();
		$data['newsletter_arr'] = $this->model_news_addedit->getAllNewsletterSubscriber($filter_data);
		//print_r($data['results_set']);	
		
		$data['add_button'] = $this->url->link('newsletter/news/add_newsletter_template', 'token=' . $this->session->data['token'] , 'SSL');
		
		$pagination = new Pagination();
		$pagination->total = $newsletter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('newsletter/news/list_subscriber', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($newsletter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($newsletter_total - $this->config->get('config_limit_admin'))) ? $newsletter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $newsletter_total, ceil($newsletter_total / $this->config->get('config_limit_admin')));
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		
		$this->response->setOutput($this->load->view('newsletter/newsletter_subscriber.tpl', $data));
	}

	public function del_newsletter_subscriber() {
		
		$this->load->model('sale/newsletter');
		
		
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		else
			$id = '';
		
		if($id!=''){
			
			$this->model_sale_newsletter->unsubscribeNewsletter($id);
			$this->session->data['success'] = "Newsletter deleted sucessfully!!!";
		}
		
		$this->response->redirect($this->url->link('newsletter/news/list_subscriber', 'token=' . $this->session->data['token'], 'SSL'));
	}
	
	
	public function send_newsletter()
        {
		$data['token'] = $this->session->data['token'];
		$this->load->model('news/addedit');
		$this->document->setTitle("Send Newsletter");
		$data['add_edit_text'] = "Send Newsletter";
		
		$data['newsletter_data'] = $this->model_news_addedit->getAllNewsletter();

		$data['all_user_arr'] = $this->model_news_addedit->getAllNewsletterSubscriber();
		$data['all_customer_arr'] = $this->model_news_addedit->getAllCustomerNewsletter();
		$data['all_visitor_arr'] = $this->model_news_addedit->getAllVisitorNewsletter();

		$data['newsletter_subject'] = "";
		$data['newsletter_message'] = "";
		
		
		$data['cancel'] = $this->url->link('newsletter/news/list_newsletter', 'token=' . $this->session->data['token'] , 'SSL');		
	
		
		//print_r($data);exit;
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');
		
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
		
		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			
			if($this->request->post['newsletter_id']!=""){
				
				$newsletter_details = $this->model_news_addedit->getNewsletterTemplateById($this->request->post['newsletter_id']);
				$this->request->post['newsletter_subject'] = $newsletter_details['newsletter_subject'];
				$this->request->post['newsletter_message'] = $newsletter_details['newsletter_message'];
				
				if(!empty($this->request->post['all_group_user_ids'])){
					$this->request->post['all_group'] = rand(100000,99999999);
					foreach($this->request->post['all_group_user_ids'] as $each_cust_id){
						$subscriber_details = $this->model_news_addedit->getSubscriberDetails($each_cust_id);
						
						$this->request->post['user_type'] = $subscriber_details['user_type'];
						$this->request->post['email'] = $each_cust_id;
						
						
			// ********************************** Send Mail **********************************************************
						
						$to = $each_cust_id;
						$subject = html_entity_decode($this->request->post['newsletter_subject']);
						$message = html_entity_decode($this->request->post['newsletter_message']);
					  
						// Always set content-type when sending HTML email
						$headers = "MIME-Version: 1.0" . "\r\n";
						$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		    
						// More headers
						$headers .= 'From: '.SITE_NAME. "\r\n";
						//$headers .= 'Cc: amit.unified@gmail.com' . "\r\n";
						$headers .= "Bcc: amit.unified@gmail.com\r\n";
						
						@mail($to,$subject,$message,$headers);	
						
						
			// ********************************** Send Mail **********************************************************
						
						// Add To Email List table
						$this->model_news_addedit->addSendNewsletter($this->request->post);
					}
				}
				
				
				
				$this->session->data['success'] = 'Newsletter template send sucessfully';
				
			}
			//echo "<pre>";print_r($this->request->post);exit;
			
			
				
			
			
			$this->response->redirect($this->url->link('newsletter/news/send_newsletter', 'token=' . $this->session->data['token'], 'SSL'));
		}
		
		$this->response->setOutput($this->load->view('newsletter/send_newsletter.tpl', $data));
			
           
        }
	
	public function getNewsletter() {
		
		
		$this->load->model('news/addedit');
		$newsletter_id = $_REQUEST['newsletter_id'];
		
		$result = $this->model_news_addedit->getNewsletterTemplateById($newsletter_id);
		echo $result['newsletter_subject'];exit;
	}
	
	
	function test_mail(){
		
		$this->load->model('news/addedit');
		$newsletter_details = $this->model_news_addedit->getNewsletterTemplateById(4);
		
		$to = 'amit.unified@gmail.com';
		$subject = $newsletter_details['newsletter_subject'];
		$message = html_entity_decode($newsletter_details['newsletter_message']);
	  
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

		// More headers
		$headers .= 'From: '.SITE_NAME. "\r\n";
		//$headers .= 'Cc: amit.unified@gmail.com' . "\r\n";
		$headers .= "Bcc: amit.unified@gmail.com\r\n";
		
		print_r($message);
		
		@mail($to,$subject,$message,$headers);
		echo '1';exit;
	}
	
	
	public function sent_news_mail() {

		$this->language->load('common/home');

		$this->document->setTitle(" Sent Newsletter Template");

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
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


		$this->load->model('news/addedit');
		
		
		$filter_data = array(
			'start'                    => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'                    => $this->config->get('config_limit_admin')
			
		);
		
		$newsletter_total= $this->model_news_addedit->getTotalNewsletterSentMail();
		$data['send_newsletter_arr'] = $this->model_news_addedit->getAllNewsletterSentMail($filter_data);
		//print_r($data['results_set']);	
		
		$data['add_button'] = $this->url->link('newsletter/news/add_newsletter_template', 'token=' . $this->session->data['token'] , 'SSL');
		
		$pagination = new Pagination();
		$pagination->total = $newsletter_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('newsletter/news/sent_news_mail', 'token=' . $this->session->data['token']. '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($newsletter_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($newsletter_total - $this->config->get('config_limit_admin'))) ? $newsletter_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $newsletter_total, ceil($newsletter_total / $this->config->get('config_limit_admin')));
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		
		$this->response->setOutput($this->load->view('newsletter/sent_newsletter_mail.tpl', $data));
	}

	public function view_all_mail() {

		$this->language->load('common/home');

		$this->document->setTitle("View All Email List");

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
		$data['breadcrumbs'][] = array(
			'text'      => 'Newsletter Email Sent',
			'href'      => $this->url->link('newsletter/news/sent_news_mail', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
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

		$data['token'] = $this->session->data['token'];
                
		$this->load->model('news/addedit');
		
		$data['send_newsletter_email_arr'] = $this->model_news_addedit->getSentEmailByGroup($this->request->get['all_group']);
		//print_r($data['send_newsletter_email_arr']);	
		
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');


		
		$this->response->setOutput($this->load->view('newsletter/view_all_mail.tpl', $data));
	}

	public function del_sent_newsletter_template_group() {
		
		$this->load->model('news/addedit');
		if($this->request->get['all_group']!=''){
			
			$this->model_news_addedit->delNewsSentMailByGroup($this->request->get['all_group']);
			$this->session->data['success'] = "Newsletter sent mail deleted sucessfully!!!";
		}
		
		$this->response->redirect($this->url->link('newsletter/news/sent_news_mail', '&token=' . $this->session->data['token'], 'SSL'));
	}
	public function del_sent_newsletter_template() {
		
		$this->load->model('news/addedit');
		
		
		if(isset($_REQUEST['id'])){
			$id = $_REQUEST['id'];
		}
		else
			$id = '';
		
		if($id!=''){
			
			$this->model_news_addedit->delNewsletterSentMail($id);
			$this->session->data['success'] = "Newsletter sent mail deleted sucessfully!!!";
		}
		
		$this->response->redirect($this->url->link('newsletter/news/view_all_mail','all_group='.$this->request->get['all_group']. '&token=' . $this->session->data['token'], 'SSL'));
	}
	
	
	public function automatic_sent_newsletter() {
		
		$this->load->model('news/addedit');
		
		$newsletter_details = $this->model_news_addedit->getLatestNewsletter();
		//print_r($newsletter_details);exit;
		if(!empty($newsletter_details)){
			$data['newsletter_id'] = $newsletter_details['id'];
			$data['newsletter_subject'] = html_entity_decode($newsletter_details['newsletter_subject']);
			$data['newsletter_message'] = html_entity_decode($newsletter_details['newsletter_message']);
		
		
			// Get All Subscriber Details
			$subscriber_users = $this->model_news_addedit->getAllNewsletterSubscriber();
			
			if(!empty($subscriber_users)){
				$data['all_group'] = rand(100000,99999999);
				foreach($subscriber_users as $each_user){
					$subscriber_details = $this->model_news_addedit->getSubscriberDetails($each_user['Email_id']);
					//print_r($subscriber_details);exit;
					$data['user_type'] = $subscriber_details['user_type'];
					
					$to = $data['email'] = $subscriber_details['email'];
					
					
				// ********************************** Send Mail **********************************************************
							
					
					$subject = $data['newsletter_subject'];
					$message = $data['newsletter_message'];
				  
					// Always set content-type when sending HTML email
					$headers = "MIME-Version: 1.0" . "\r\n";
					$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	    
					// More headers
					$headers .= 'From: '.SITE_NAME. "\r\n";
					$headers .= "Bcc: amit.unified@gmail.com\r\n";
					
					@mail($to,$subject,$message,$headers);	
							
							
				// ********************************** Send Mail **********************************************************
							
					// Add To Email List table
					$this->model_news_addedit->addSendNewsletter($data);
					
				}
			}
		}
		
	}
	
	
	

// *********************************** Newsletter Section *************************************************************************************
	
}
?>