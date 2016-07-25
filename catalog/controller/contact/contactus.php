<?php
class ControllerContactContactus extends Controller {
	public function index() {
		
		$this->document->setTitle('Contact Us');
		$data['action'] = $this->url->link('contact/contactus', '', $this->request->server['HTTPS']);
		
		$this->load->model('setting/setting');
		$all_contact_settings = $this->model_setting_setting->getContactSetting('contact');
		//print_r($all_contact_settings);exit;
		$data['all_contact_settings'] = $all_contact_settings;
		
		if($this->customer->isLogged()) {
			
			$fname = $this->customer->getFirstName();
			$lname = $this->customer->getLastName();
			$data['email'] = $this->customer->getEmail();
			$data['phone'] = $this->customer->getTelephone();
			
			$data['name'] = $fname." ".$lname;
			
		} else {
			$data['email'] = '';
			$data['name'] = '';
			$data['phone'] = '';
		}
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			
			$this->load->model('contact/contactus');
			$this->model_contact_contactus->addContact($this->request->post);
			
			
			/****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
                       
			$to = $this->request->post['email'];
			$subject = "Enquiry form.";
			
			$message_content_body= html_entity_decode($this->request->post['message']);  
			
                       
                          
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: '.$this->request->post['name'] . "\r\n";
			//$headers .= 'Cc: amit.unified@gmail.com' . "\r\n";
			
			@mail($to,$subject,$message_content_body,$headers);
			$this->response->redirect($this->url->link('information/contact/success'));
			
                        
                        
        /****************************  SEND NEW USER CONFIRMATION MAIL  ***************************/
			
			
			//$mail = new Mail();
			//$mail->protocol = $this->config->get('config_mail_protocol');
			//$mail->parameter = $this->config->get('config_mail_parameter');
			//$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
			//$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			//$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			//$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			//$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');			
			//
			//$mail->setTo($this->config->get('config_email'));
			//$mail->setFrom($this->request->post['email']);
			//$mail->setSender($this->request->post['name']);
			//$mail->setSubject(sprintf($this->language->get('email_subject'), $this->request->post['name']));
			//$mail->setText($this->request->post['enquiry']);
			//$mail->send();

			
		}

		
		
		$this->load->language('common/currency');
		

		$data['text_currency'] = $this->language->get('text_currency');

		

		$data['code'] = $this->currency->getCode();

		$this->load->model('localisation/currency');

		$data['currencies'] = array();

		$results = $this->model_localisation_currency->getCurrencies();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code'],
					'symbol_left'  => $result['symbol_left'],
					'symbol_right' => $result['symbol_right']
				);
			}
		}

		if (!isset($this->request->get['route'])) {
			$data['redirect'] = $this->url->link('common/home');
		} else {
			$url_data = $this->request->get;

			unset($url_data['_route_']);

			$route = $url_data['route'];

			unset($url_data['route']);

			$url = '';

			if ($url_data) {
				$url = '&' . urldecode(http_build_query($url_data, '', '&'));
			}

			$data['redirect'] = $this->url->link($route, $url, $this->request->server['HTTPS']);
		}
		
		$data['continue'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/contact/contactus.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/contact/contactus.tpl', $data));
			
		} else {
			$this->response->setOutput($this->load->view('default/template/contact/contactus', $data));
		}
		
	}
	
	
	protected function validate() {
		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
			$this->error['name'] = $this->language->get('error_name');
		}

		if (!preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['message']) < 10) || (utf8_strlen($this->request->post['message']) > 3000)) {
			$this->error['enquiry'] = $this->language->get('error_enquiry');
		}

		

		return !$this->error;
	}

	
}