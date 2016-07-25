<?php
class ControllerMessageMessage extends Controller {
	private $error = array();
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('message/message', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/wishlist');
		$this->load->model('message/messaging');
		
		$this->document->setTitle('Inbox');

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
			'text' => 'Inbox',
			'href' => $this->url->link('message/message')
		);

		$data['heading_title'] = 'My Inbox';

		$data['text_empty'] = "No Record Found";
		$data['column_action'] = $this->language->get('column_action');
		$data['button_continue'] = 'Compose';

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$url = '';
		if (isset($this->request->get['limit'])) {
			$url .= '&limit=' . $this->request->get['limit'];
		}
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = 10;
		}
		
		$filter_data = array(
			'start'              => ($page - 1) * $limit,
			'limit'              => $limit
		);

		$all_msg = $this->model_message_messaging->inbox($filter_data);
		
		if(!empty($all_msg)){
		  foreach ($all_msg as $result) {
			
			$data['all_msg'][] = array(
				'name'  	=> $result['firstname'].' '.$result['lastname'],
				'already_seen_inbox'  	=> $result['already_seen_inbox'],
				'subject'  	=> $result['subject'],
				'message_id'  	=> $result['message_id'],
				'post_date'  	=> $result['post_date'],
				'view_msg'       => $this->url->link('message/message/veiwmsg',  '&message_id=' . $result['message_id'] ),
				'remove'        => $this->url->link('message/message/removemsg',  '&message_id=' . $result['message_id'] )
			);
		  }
		}
		
		
		
		// Get Count message
		$message_total = $this->model_message_messaging->getTotalinbox();

		$pagination = new Pagination();
		$pagination->total = $message_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('message/message', $url . '&page={page}');
		
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($message_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($message_total - $limit)) ? $message_total : ((($page - 1) * $limit) + $limit), $message_total, ceil($message_total / $limit));
		

		$data['continue'] = $this->url->link('message/message/compose', '', 'SSL');
		$data['outbox'] = $this->url->link('message/outbox', '', 'SSL');
		$data['inbox'] = $this->url->link('message/message', '', 'SSL');
		$data['trash'] = $this->url->link('message/outbox/trash', '', 'SSL');//outbox/trash

		$data['continue_class'] = '';
		$data['outbox_class'] = '';
		$data['inbox_class'] =  'msg-active';
		$data['trash_class'] =  '';

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/message/inbox.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/message/inbox.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/message/inbox.tpl', $data));
		}
	}

	public function compose() {
		$this->load->language('account/wishlist');

		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('message/message/compose', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		$this->load->language('account/wishlist');

		$this->load->model('message/messaging');
	
		$this->document->setTitle('Compose Message');

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
			'text' => 'compose',
			'href' => $this->url->link('message/message/compose')
		);
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['heading_title'] = 'Compose Message';

		$data['text_empty'] = 'No message found';

		$data['column_action'] = $this->language->get('column_action');

		$data['button_continue'] = 'Compose';
		
		$data['continue'] = $this->url->link('message/message/compose', '', 'SSL');
		$data['outbox'] = $this->url->link('message/outbox', '', 'SSL');
		$data['inbox'] = $this->url->link('message/message', '', 'SSL');
		$data['trash'] = $this->url->link('message/outbox/trash', '', 'SSL');
		
		$data['continue_class'] = 'msg-active';
		$data['outbox_class'] = '';
		$data['inbox_class'] =  '';
		$data['trash_class'] =  '';


		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		
		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} else {
			$data['username'] = '';
		}

		if (isset($this->request->post['subject'])) {
			$data['subject'] = $this->request->post['subject'];
		} else {
			$data['subject'] = '';
		}

		if (isset($this->request->post['content'])) {
			$data['content'] = $this->request->post['content'];
		} else {
			$data['content'] = '';
		}


		
		if (($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validateForm()) ) {
			
		/****************************  check for the username ***************************/
			$return_value = $this->model_message_messaging->getEmailByUsername($this->request->post['username']);
			$this->request->post['email'] = $return_value['email'];
		/**************************** END check for the username ***************************/
			
			// Add to message table
			$this->model_message_messaging->compose($this->request->post);
			
		/****************************  SEND NEWSLETTER MAIL  ***************************/
                       
			$to = $this->request->post['email'];
			
			$subject = $this->request->post['subject'];
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
					      <td style="padding:10px 0; font-family:Helvetica, Arial, sans-serif; font-size:14px; color:#423338; text-align:center;">
						'.html_entity_decode($this->request->post['content']).'
					      </td>
					      </tr>
					      
					  </tbody>
					</table>
					      </td>
					    </tr>
					    <tr>
					      <td style="background:#3498db; padding:15px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#fff; text-align:center;">&copy; 2015 '.SITE_NAME.'</td>
					    </tr>
					  </tbody>
					</table>
					</body>
					</html>';
                           
                        // Always set content-type when sending HTML email
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                            // More headers
                            $headers .= 'From: '.SITE_NAME . "\r\n";
                            
                          
                        @mail($to,$subject,$message,$headers);
                        
        /****************************  SEND MAIL  ***************************/

                   
			
			$this->session->data['success'] = 'You have sucessfully send your message!!!';
			$this->response->redirect($this->url->link('message/outbox'));
			
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['subject'])) {
			$data['error_subject'] = $this->error['subject'];
		} else {
			$data['error_subject'] = '';
		}

		if (isset($this->error['content'])) {
			$data['error_content'] = $this->error['content'];
		} else {
			$data['error_content'] = '';
		}


		$data['cancel'] = $this->url->link('message/message', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/message/compose.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/message/compose.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/message/compose.tpl', $data));
		}
		

	}

	protected function validateForm() {
		$this->load->model('message/messaging');
		if (!$this->customer->isLogged()) {
			$this->error['warning'] = 'You cannot send any message until you logged in.';
		}

		if ((utf8_strlen($this->request->post['username']) < 1)) {
			$this->error['email'] = 'username cannot be blank';
		}
		if ((utf8_strlen($this->request->post['username']) > 1)) {
			$return_value = $this->model_message_messaging->checkExistsUsername($this->request->post['username']);
			if($return_value==0)
				$this->error['email'] = 'username does not exists.';
		}

		if ((utf8_strlen($this->request->post['subject']) < 1)) {
			$this->error['subject'] = 'subject cannot be blank';
		}
		if ((utf8_strlen($this->request->post['content']) < 1)) {
			$this->error['content'] = 'Message cannot be blank';
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		//print_r( $this->error);

		return !$this->error;
	}
	
	public function autocompleteTitle() {
		$json = array();
		
		if (isset($this->request->get['filter_name'])) {
			$this->load->model('message/messaging');

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

			//$results = $this->model_message_messaging->getEmail($filter_data);
			$results = $this->model_message_messaging->getUsername($filter_data);

			foreach ($results as $result) {
				

				$json[] = array(
					'customer_id' => $result['customer_id'],
					'name'       => strip_tags(html_entity_decode($result['username'], ENT_QUOTES, 'UTF-8'))
					
				);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function veiwmsg() {
		$this->load->language('account/wishlist');
		$this->load->model('message/messaging');
		
		if (!$this->request->get['message_id']) {
			$this->response->redirect($this->url->link('message/message', '', 'SSL'));
		}
		
		// check logined in user
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('message/message/veiwmsg', '&message_id='.$this->request->get['message_id'], 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		// Update current message
		$this->model_message_messaging->getInboxSeenMessage($this->request->get['message_id']);
		
		// Check for user previledge
		$data['num_previdelge'] = $this->model_message_messaging->checkMsgPreviledge($this->request->get['message_id']);
		
		//print_r($this->request->get['message_id']);exit;

		$this->document->setTitle('View Message');

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
			'text' => 'Inbox',
			'href' => $this->url->link('message/message')
		);

		$data['column_action'] = $this->language->get('column_action');
		$data['button_continue'] = 'Compose';

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		$all_msg = $this->model_message_messaging->getMessageById($this->request->get['message_id']);
		
		$data['all_msg'] = array();
		if(!empty($all_msg)){
			$data['all_msg'] = array(
				'sender_email'  => $all_msg['sender_email'],
				'subject'  	=> $all_msg['subject'],
				'content'  	=> $all_msg['content'],
				'view_msg'      => $this->url->link('message/message/veiwmsg',  '&message_id=' . $all_msg['message_id'] ),
				'remove'        => $this->url->link('message/message/removemsg',  '&message_id=' . $all_msg['message_id'] )
			);
		  
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			
			if (strpos($this->request->post['subject'],'Re') === false) {
				$this->request->post['subject'] = 'Re '.$this->request->post['subject'];
			}
			
			/****************************  SEND NEWSLETTER MAIL  ***************************/
                       
			$to = $this->request->post['email'];
			
			$subject = $this->request->post['subject'];
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
				      <td style="padding:10px 0; font-family:Helvetica, Arial, sans-serif; font-size:14px; color:#423338; text-align:center;">
					'.html_entity_decode($this->request->post['content']).'
				      </td>
				      </tr>
				      
				  </tbody>
				</table>
				      </td>
				    </tr>
				    <tr>
				      <td style="background:#3498db; padding:15px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#fff; text-align:center;">&copy; 2015 '.SITE_NAME.'</td>
				    </tr>
				  </tbody>
				</table>
				</body>
				</html>';
                           
                        // Always set content-type when sending HTML email
                            $headers = "MIME-Version: 1.0" . "\r\n";
                            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                            // More headers
                            $headers .= 'From: '.SITE_NAME . "\r\n";
                            
                          
                        @mail($to,$subject,$message,$headers);
                        
        /****************************  SEND MAIL  ***************************/
			
			$this->model_message_messaging->compose($this->request->post);
			$this->session->data['success'] = 'You have sucessfully send your message!!!';
			$this->response->redirect($this->url->link('message/message'));
			
		}

		$data['cancel'] = $this->url->link('message/message', '', 'SSL');
		$data['continue'] = $this->url->link('message/message/compose', '', 'SSL');
		$data['outbox'] = $this->url->link('message/outbox', '', 'SSL');
		$data['inbox'] = $this->url->link('message/message', '', 'SSL');
		$data['trash'] = $this->url->link('message/outbox/trash', '', 'SSL');//outbox/trash

		$data['continue_class'] = '';
		$data['outbox_class'] = '';
		$data['inbox_class'] =  '';
		$data['trash_class'] =  '';


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/message/reply.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/message/reply.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/message/reply.tpl', $data));
		}
	}

	public function removemsg() {
		$this->load->language('account/wishlist');
		$this->load->model('message/messaging');
		
		if (!$this->request->get['message_id']) {
			$this->response->redirect($this->url->link('message/message', '', 'SSL'));
		}
		
		// Update current message
		$this->model_message_messaging->delInboxMessage($this->request->get['message_id']);
		$this->session->data['success'] = 'You have sucessfully delete your message!!!';
	
		$this->response->redirect($this->url->link('message/message', '', 'SSL'));
	}
	
	public function removeToTrash() {
		$this->load->language('account/wishlist');
		$this->load->model('message/outbox_messaging');
		
		if (!$this->request->get['message_id']) {
			$this->response->redirect($this->url->link('message/message', '', 'SSL'));
		}
		
		// Update current message
		$this->model_message_outbox_messaging->delTrashMessage($this->request->get['message_id']);
		$this->session->data['success'] = 'You have sucessfully delete your message!!!';
	
		$this->response->redirect($this->url->link('message/outbox/trash', '', 'SSL'));
	}
	

	
}