<?php
class ControllerAccountEdit extends Controller {
	private $error = array();

	public function index() {
		if (!$this->customer->isLogged()) {
			$this->session->data['redirect'] = $this->url->link('account/edit', '', 'SSL');

			$this->response->redirect($this->url->link('account/login', '', 'SSL'));
		}
		
		$this->load->model('localisation/country');
		$data['countries'] = $this->model_localisation_country->getCountries();
		

		$this->load->language('account/edit');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/moment.js');
		$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');

		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			$this->model_account_customer->editCustomer($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			// Add to activity log
			$this->load->model('account/activity');

			$activity_data = array(
				'customer_id' => $this->customer->getId(),
				'name'        => $this->customer->getFirstName() . ' ' . $this->customer->getLastName()
			);

			$this->model_account_activity->addActivity('edit', $activity_data);

			$this->response->redirect($this->url->link('account/account', '', 'SSL'));
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_edit'),
			'href'      => $this->url->link('account/edit', '', 'SSL')
		);

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_your_details'] = $this->language->get('text_your_details');
		$data['text_additional'] = $this->language->get('text_additional');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_loading'] = $this->language->get('text_loading');

		$data['entry_firstname'] = $this->language->get('entry_firstname');
		$data['entry_lastname'] = $this->language->get('entry_lastname');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_telephone'] = $this->language->get('entry_telephone');
		$data['entry_fax'] = $this->language->get('entry_fax');

		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_back'] = $this->language->get('button_back');
		$data['button_upload'] = $this->language->get('button_upload');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}

		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_username'] = $this->error['username'];
		} else {
			$data['error_username'] = '';
		}

		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}

		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}

		$data['action'] = $this->url->link('account/edit', '', 'SSL');

		if ($this->request->server['REQUEST_METHOD'] != 'POST') {
			$customer_info = $this->model_account_customer->getCustomer($this->customer->getId());
		}

// ============ To populate results ===================================================		
		//print_r($customer_info);exit;
		
		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($customer_info)) {
			$data['firstname'] = $customer_info['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($customer_info)) {
			$data['lastname'] = $customer_info['lastname'];
		} else {
			$data['lastname'] = '';
		}

		if (isset($this->request->post['username'])) {
			$data['username'] = $this->request->post['username'];
		} elseif (!empty($customer_info)) {
			$data['username'] = $customer_info['username'];
		} else {
			$data['username'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($customer_info)) {
			$data['email'] = $customer_info['email'];
		} else {
			$data['email'] = '';
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($customer_info)) {
			$data['telephone'] = $customer_info['telephone'];
		} else {
			$data['telephone'] = '';
		}

		if (isset($this->request->post['companyname'])) {
			$data['companyname'] = $this->request->post['companyname'];
		} elseif (!empty($customer_info)) {
			$data['companyname'] = $customer_info['companyname'];
		} else {
			$data['companyname'] = '';
		}

		if (isset($this->request->post['address1'])) {
			$data['address1'] = $this->request->post['address1'];
		} elseif (!empty($customer_info)) {
			$data['address1'] = $customer_info['address1'];
		} else {
			$data['address1'] = '';
		}

		if (isset($this->request->post['address2'])) {
			$data['address2'] = $this->request->post['address2'];
		} elseif (!empty($customer_info)) {
			$data['address2'] = $customer_info['address2'];
		} else {
			$data['address2'] = '';
		}

		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (!empty($customer_info)) {
			$data['city'] = $customer_info['city'];
		} else {
			$data['city'] = '';
		}
		if (isset($this->request->post['country_id'])) {
			$data['country_id'] = $this->request->post['country_id'];
		} elseif (!empty($customer_info)) {
			$data['country_id'] = $customer_info['country_id'];
		} else {
			$data['country_id'] = '';
		}
		if (isset($this->request->post['state'])) {
			$data['state'] = $this->request->post['state'];
		} elseif (!empty($customer_info)) {
			$data['state'] = $customer_info['state'];
		} else {
			$data['state'] = '';
		}

		if (isset($this->request->post['zip'])) {
			$data['zip'] = $this->request->post['zip'];
		} elseif (!empty($customer_info)) {
			$data['zip'] = $customer_info['zip'];
		} else {
			$data['zip'] = '';
		}

		if (isset($this->request->post['work_phone'])) {
			$data['work_phone'] = $this->request->post['work_phone'];
		} elseif (!empty($customer_info)) {
			$data['work_phone'] = $customer_info['work_phone'];
		} else {
			$data['work_phone'] = '';
		}

		if (isset($this->request->post['fax'])) {
			$data['fax'] = $this->request->post['fax'];
		} elseif (!empty($customer_info)) {
			$data['fax'] = $customer_info['fax'];
		} else {
			$data['fax'] = '';
		}
		
		if (isset($this->request->post['dob'])) {
			$data['dob'] = $this->request->post['dob'];
		} elseif (!empty($customer_info)) {
			
			list($dob_yr,$dob_mon,$dob_dt) = explode("-",$customer_info['dob']);
			
			$data['dob'] = $dob_dt.'-'.$dob_mon.'-'.$dob_yr;
		} else {
			$data['dob'] = '';
		}
		
		
		if (isset($this->request->post['tot_yrs'])) {
			$data['tot_yrs'] = $this->request->post['tot_yrs'];
			$data['flag_over_eighteen'] = $this->request->post['flag_over_eighteen'];
		} elseif (!empty($customer_info)) {
			$data['tot_yrs'] = $customer_info['years'];
			if($customer_info['over_eighteen']==1)
				$data['flag_over_eighteen'] = 1;
			else
				$data['flag_over_eighteen'] = 0;
		} else {
			$data['tot_yrs'] = '';
			$data['flag_over_eighteen'] = '';
		}
		//if(!empty($customer_info)){
		//	$data['tot_yrs'] = $customer_info['years'];
		//	
		//	if($customer_info['over_eighteen']==1)
		//		$data['flag_over_eighteen'] = 1;
		//	else
		//		$data['flag_over_eighteen'] = 0;
		//}

		// Custom Fields
		$this->load->model('account/custom_field');

		$data['custom_fields'] = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		if (isset($this->request->post['custom_field'])) {
			$data['account_custom_field'] = $this->request->post['custom_field'];
		} elseif (isset($customer_info)) {
			$data['account_custom_field'] = unserialize($customer_info['custom_field']);
		} else {
			$data['account_custom_field'] = array();
		}
// ============ To populate results ===================================================		
//print_r($data['error_warning']);exit;
		$data['back'] = $this->url->link('account/account', '', 'SSL');

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/edit.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/account/edit.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/account/edit.tpl', $data));
		}
	}

	protected function validate() {
		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['warning'] = $this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['warning'] = $this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			$this->error['warning'] = $this->error['email'] = $this->language->get('error_email');
		}

		if (($this->customer->getEmail() != $this->request->post['email']) && $this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_exists');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}
		
		// Username Duplicate Check
		if (utf8_strlen($this->request->post['username']) < 0)
		{
			$this->error['warning'] = $this->error['username'] = 'Username is mandatory field';
		}
		else{
			$flag = $this->model_account_customer->checkDupliUsername($this->request->post['username']);
			if($flag)
				$this->error['username'] = 'Username is already exists.';
		}
		

		// Custom field validation
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if (($custom_field['location'] == 'account') && $custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['custom_field_id']])) {
				$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
			}
		}

		return !$this->error;
	}
}