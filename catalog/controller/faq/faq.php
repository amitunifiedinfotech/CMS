<?php
class ControllerFaqFaq extends Controller {
	public function index() {
		

		$this->load->language('common/home');

		$this->document->setTitle("Faq");
		$this->load->model('faq/faq');

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home')
		);

		
		
		
		$result_set = array();
		$all_active_category = $this->model_faq_faq->getActiveCategory();
		if(!empty($all_active_category)){
			$i=0;
			foreach($all_active_category as $each_cat){
				$all_faq_by_Category = $this->model_faq_faq->getFaqByCategory($each_cat['id']);
				if(!empty($all_faq_by_Category)){
					$result_set[$i] = array('category'=>$each_cat['faq_category']);
					foreach($all_faq_by_Category as $each_faq){
						
						$result_set[$i]['faq'][] = array('id'=>$each_faq['id'],'question'=>$each_faq['question'],'answer'=>$each_faq['answer']);
					}
					
				}
				$i++;
			}
			
			
		}
		$data['result_set'] = $result_set;
		//echo "<pre>";print_r($result_set);exit;


		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/faq/faq.tpl')) {
			$this->response->setOutput($this->load->view($this->config->get('config_template') . '/template/faq/faq.tpl', $data));
		} else {
			$this->response->setOutput($this->load->view('default/template/faq/faq.tpl', $data));
		}
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
}