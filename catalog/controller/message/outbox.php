<?php
class ControllerMessageOutbox extends Controller {
	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('message/message', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/wishlist');
		$this->load->model('message/outbox_messaging');
		
		
		$this->document->setTitle('Outbox');

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
			'text' => 'Outbox',
			'href' => $this->url->link('message/outbox')
		);

		$data['heading_title'] = 'My Outbox';

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

		$all_msg = $this->model_message_outbox_messaging->outbox($filter_data);
		
		if(!empty($all_msg)){
		  foreach ($all_msg as $result) {
			
			$data['all_msg'][] = array(
				'name'  	=> $result['firstname'].' '.$result['lastname'],
				'subject'  	=> $result['subject'],
				'already_seen_outbox'  	=> $result['already_seen_outbox'],
				'message_id'  	=> $result['message_id'],
				'post_date'  	=> $result['post_date'],
				'view_msg'       => $this->url->link('message/outbox/veiwmsg',  '&message_id=' . $result['message_id'] ),
				'remove'        => $this->url->link('message/outbox/removemsg',  '&message_id=' . $result['message_id'] )
			);
		  }
		}
		
		
		
		// Get Count message
		$message_total = $this->model_message_outbox_messaging->getTotalOutbox();

		$pagination = new Pagination();
		$pagination->total = $message_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('message/outbox', '&page={page}');
		
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($message_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($message_total - $limit)) ? $message_total : ((($page - 1) * $limit) + $limit), $message_total, ceil($message_total / $limit));
		

		$data['continue'] = $this->url->link('message/message/compose', '', 'SSL');
		$data['inbox'] = $this->url->link('message/message', '', 'SSL');
		$data['outbox'] = $this->url->link('message/outbox', '', 'SSL');
		$data['trash'] = $this->url->link('message/outbox/trash', '', 'SSL');

		$data['continue_class'] = '';
		$data['outbox_class'] = 'msg-active';
		$data['inbox_class'] =  '';
		$data['trash_class'] =  '';

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/message/outbox.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/message/outbox.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/message/outbox.tpl', $data));
		}
	}

	public function veiwmsg() {
		if (!$this->request->get['message_id']) {
			$this->response->redirect($this->url->link('message/outbox', '', 'SSL'));
		}
		
		//print_r($this->request->get['message_id']);exit;

		$this->load->language('account/wishlist');
		$this->load->model('message/outbox_messaging');
		$this->load->model('message/messaging');
		
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
			'text' => 'Outbox',
			'href' => $this->url->link('message/outbox')
		);

		$data['heading_title'] = 'My Outbox';

		$data['text_empty'] = "No Record Found.";
		$data['column_action'] = $this->language->get('column_action');
		$data['button_continue'] = 'Compose';

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		// Check for user previledge
		$data['num_previdelge'] = $this->model_message_outbox_messaging->checkMsgPreviledge($this->request->get['message_id']);
		
		$all_msg = $this->model_message_outbox_messaging->getMessageById($this->request->get['message_id']);
		//print_r($all_msg); exit;
		
		$this->model_message_outbox_messaging->getOutboxSeenMessage($this->request->get['message_id']);
		
		if(!empty($all_msg)){
			$data['all_msg'] = array(
				'sender_email'  => $all_msg['email'],
				'subject'  	=> $all_msg['subject'],
				'already_seen_outbox'  	=> $all_msg['already_seen_outbox'],
				'content'  	=> $all_msg['content'],
				'view_msg'      => $this->url->link('message/message/veiwmsg',  '&message_id=' . $all_msg['message_id'] ),
				'remove'        => $this->url->link('message/message/removemsg',  '&message_id=' . $all_msg['message_id'] )
			);
		  
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
			
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

		$data['continue'] = $this->url->link('message/message/compose', '', 'SSL');
		$data['cancel'] = $this->url->link('message/outbox', '', 'SSL');

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
		$this->load->model('message/outbox_messaging');
		
		if (!$this->request->get['message_id']) {
			$this->response->redirect($this->url->link('message/outbox', '', 'SSL'));
		}
		
		// Update current message
		$this->model_message_outbox_messaging->delOutboxMessage($this->request->get['message_id']);
		$this->session->data['success'] = 'You have sucessfully delete your message!!!';
	
		$this->response->redirect($this->url->link('message/outbox', '', 'SSL'));
	}
	
	
	
	public function trash() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('message/message/trash', '', 'SSL');
			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

		$this->load->language('account/wishlist');
		$this->load->model('message/outbox_messaging');
		
		$this->document->setTitle('Trash');

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
			'text' => 'Trash',
			'href' => $this->url->link('message/message/trash')
		);

		$data['heading_title'] = 'My Trash';

		$data['text_empty'] = 'No Record found';
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

		$all_msg = $this->model_message_outbox_messaging->trash($filter_data);
		
		if(!empty($all_msg)){
		  foreach ($all_msg as $result) {
			$data['all_msg'][] = array(
				'name'  		=> $result['firstname'].' '.$result['lastname'],
				'already_seen_inbox'  	=> $result['already_seen_inbox'],
				'subject'  		=> $result['subject'],
				'message_id'  		=> $result['message_id'],
				'post_date'  		=> $result['post_date'],
				'view_msg'      	=> $this->url->link('message/message/veiwmsg',  '&message_id=' . $result['message_id'] ),
				'remove'        	=> $this->url->link('message/message/removemsg',  '&message_id=' . $result['message_id'] )
			);
		  }
		}
		
		
		
		// Get Count message
		$message_total = $this->model_message_outbox_messaging->getTotaltrash();

		$pagination = new Pagination();
		$pagination->total = $message_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('message/outbox/trash', $url . '&page={page}');
		
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($message_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($message_total - $limit)) ? $message_total : ((($page - 1) * $limit) + $limit), $message_total, ceil($message_total / $limit));
		

		$data['continue'] = $this->url->link('message/message/compose', '', 'SSL');
		$data['outbox'] = $this->url->link('message/outbox', '', 'SSL');
		$data['inbox'] = $this->url->link('message/message', '', 'SSL');
		$data['trash'] = $this->url->link('message/outbox/trash', '', 'SSL');

		$data['continue_class'] = '';
		$data['outbox_class'] = '';
		$data['inbox_class'] =  '';
		$data['trash_class'] =  'msg-active';
		
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/message/trash.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/message/trash.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/message/trash.tpl', $data));
		}
	}

	public function removeToTrash() {
		$this->load->language('account/wishlist');
		$this->load->model('message/messaging');
		
		if (!$this->request->get['message_id']) {
			$this->response->redirect($this->url->link('message/message', '', 'SSL'));
		}
		
		// Update current message
		$this->model_message_messaging->delInboxTrashMessage($this->request->get['message_id']);
		$this->session->data['success'] = 'You have sucessfully delete your message from trash!!!';
	
		$this->response->redirect($this->url->link('message/message/trash', '', 'SSL'));
	}


	
}