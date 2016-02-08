<?php
class ControllerInformationCareers extends Controller {
	public function index() {
		$this->load->model ( 'catalog/information' );
		if (isset ( $this->request->get ['information_id'] )) {
			$information_id = ( int ) $this->request->get ['information_id'];
		} else {
			$information_id = 0;
		}
		
		$data ['description'] = '';
		
		$information_info = $this->model_catalog_information->getInformation ( 10 );
		
		if ($information_info) {
			$data ['description'] = html_entity_decode ( $information_info ['description'], ENT_QUOTES, 'UTF-8' );
		}
		
		/*
		 * $ch = curl_init();
		 * curl_setopt_array($ch, array(
		 * CURLOPT_HEADER => 0,
		 * CURLOPT_CONNECTTIMEOUT => 10,
		 * CURLOPT_RETURNTRANSFER => 1,
		 * CURLOPT_SSL_VERIFYHOST => 0,
		 * CURLOPT_SSL_VERIFYPEER => 0,
		 * CURLOPT_USERAGENT => "Omnikart Internatl Curl",
		 * CURLOPT_URL => "https://api.recruiterbox.com/v1/openings",
		 * CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
		 * CURLOPT_USERPWD => '5c7564073ceb477e881c6c1db951df9e:',
		 * ));
		 *
		 * $output = curl_exec($ch) or die(curl_error($ch));
		 * curl_close($ch);
		 * $positions = (json_decode($output, true));
		 */
		$this->document->setTitle ( "Omnikart Careers" );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => "Home",
				'href' => $this->url->link ( 'common/home' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => "Careers",
				'href' => $this->url->link ( 'information/careers' ) 
		);
		
		$data ['heading_title'] = "Omnikart Careers";
		
		// $data['positions'] = $positions;
		
		$data ['continue'] = $this->url->link ( 'common/home' );
		$data ['button_continue'] = "Home";
		
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['column_right'] = $this->load->controller ( 'common/column_right' );
		$data ['content_top'] = $this->load->controller ( 'common/content_top' );
		$data ['content_bottom'] = $this->load->controller ( 'common/content_bottom' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		$data ['header'] = $this->load->controller ( 'common/header' );
		
		if (file_exists ( DIR_TEMPLATE . $this->config->get ( 'config_template' ) . '/template/information/careers.tpl' )) {
			$this->response->setOutput ( $this->load->view ( $this->config->get ( 'config_template' ) . '/template/information/careers.tpl', $data ) );
		} else {
			$this->response->setOutput ( $this->load->view ( 'default/template/information/careers.tpl', $data ) );
		}
	}
}