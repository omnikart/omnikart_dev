<?php
class Controllermodulepdforders extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'module/pdforders' );
		$this->document->setTitle ( $this->language->get ( 'title' ) );
		$this->document->addLink ( "view/stylesheet/imdev.css", "stylesheet" );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validate ()) {
			$this->load->model ( 'setting/setting' );
			
			$this->model_setting_setting->editSetting ( 'pdforders', $this->request->post );
			
			$this->session->data ['success'] = $this->language->get ( 'text_success' );
			
			$this->response->redirect ( $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['headerinfo'] = $this->language->get ( 'headerinfo' );
		$data ['text_support'] = $this->language->get ( 'text_support' );
		$data ['text_extensionlink'] = $this->language->get ( 'text_extensionlink' );
		
		$data ['text_format'] = $this->language->get ( 'text_format' );
		$data ['text_width'] = $this->language->get ( 'text_width' );
		$data ['text_height'] = $this->language->get ( 'text_height' );
		$data ['text_orientation'] = $this->language->get ( 'text_orientation' );
		$data ['text_custom_size'] = $this->language->get ( 'text_custom_size' );
		$data ['text_fontsize'] = $this->language->get ( 'text_fontsize' );
		$data ['text_fontstyle'] = $this->language->get ( 'text_fontstyle' );
		$data ['text_logo'] = $this->language->get ( 'text_logo' );
		$data ['text_addextrarows'] = $this->language->get ( 'text_addextrarows' );
		$data ['text_numberproducts'] = $this->language->get ( 'text_numberproducts' );
		$data ['text_order_status'] = $this->language->get ( 'text_order_status' );
		$data ['text_numberextrarows'] = $this->language->get ( 'text_numberextrarows' );
		$data ['text_showimage'] = $this->language->get ( 'text_showimage' );
		$data ['text_attachemail'] = $this->language->get ( 'text_attachemail' );
		$data ['text_vattin'] = $this->language->get ( 'text_vattin' );
		$data ['text_autogenerate'] = $this->language->get ( 'text_autogenerate' );
		$data ['text_footer'] = $this->language->get ( 'text_footer' );
		
		$data ['help_format'] = $this->language->get ( 'help_format' );
		$data ['help_width'] = $this->language->get ( 'help_width' );
		$data ['help_height'] = $this->language->get ( 'help_height' );
		$data ['help_orientation'] = $this->language->get ( 'help_orientation' );
		$data ['help_custom_size'] = $this->language->get ( 'help_custom_size' );
		$data ['help_fontsize'] = $this->language->get ( 'help_fontsize' );
		$data ['help_fontstyle'] = $this->language->get ( 'help_fontstyle' );
		$data ['help_logo'] = $this->language->get ( 'help_logo' );
		$data ['help_addextrarows'] = $this->language->get ( 'help_addextrarows' );
		$data ['help_order_status'] = $this->language->get ( 'help_order_status' );
		$data ['help_numberproducts'] = $this->language->get ( 'help_numberproducts' );
		$data ['help_numberextrarows'] = $this->language->get ( 'help_numberextrarows' );
		$data ['help_showimage'] = $this->language->get ( 'help_showimage' );
		$data ['help_attachemail'] = $this->language->get ( 'help_attachemail' );
		$data ['help_vattin'] = $this->language->get ( 'help_vattin' );
		$data ['help_autogenerate'] = $this->language->get ( 'help_autogenerate' );
		$data ['help_footer'] = $this->language->get ( 'help_footer' );
		
		$data ['token'] = $this->session->data ['token'];
		$data ['text_default'] = $this->language->get ( 'text_default' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		$data ['text_enabled'] = $this->language->get ( 'text_enabled' );
		$data ['text_disabled'] = $this->language->get ( 'text_disabled' );
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = '';
		}
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ),
				'text' => $this->language->get ( 'text_home' ),
				'separator' => FALSE 
		);
		
		$data ['breadcrumbs'] [] = array (
				'href' => $this->url->link ( 'module/pdforders', 'token=' . $this->session->data ['token'], 'SSL' ),
				'text' => $this->language->get ( 'heading_title' ),
				'separator' => ' :: ' 
		);
		
		$data ['action'] = $this->url->link ( 'module/pdforders', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['token'] = $this->session->data ['token'];
		
		$data ['cancel'] = $this->url->link ( 'extension/module', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['orders'] = $this->url->link ( 'sale/order', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['formats'] = array (
				"A4" => "A4 - 210mm x 297mm",
				"A0" => "A0 - 841mm x 1189mm",
				"A1" => "A1 - 594mm x 841mm",
				"A2" => "A2 - 420mm x 594mm",
				"A3" => "A3 - 297mm x 420mm",
				"A5" => "A5 - 148mm x 210mm",
				"LETTER" => "LETTER -  216mm x 279mm",
				"USLEGAL" => "USLEGAL - 216mm x 356mm" 
		);
		
		if (isset ( $this->request->post ['pdforders_format'] )) {
			$data ['pdforders_format'] = $this->request->post ['pdforders_format'];
		} else {
			$data ['pdforders_format'] = $this->config->get ( 'pdforders_format' );
		}
		
		$data ['fontstyles'] = array (
				"helvetica" => "HELVETICA",
				"times" => "TIMES",
				"courier" => "COURIER" 
		);
		
		if (isset ( $this->request->post ['pdforders_fontstyle'] )) {
			$data ['pdforders_fontstyle'] = $this->request->post ['pdforders_fontstyle'];
		} else {
			$data ['pdforders_fontstyle'] = $this->config->get ( 'pdforders_fontstyle' );
		}
		
		if (isset ( $this->request->post ['pdforders_fontsize'] )) {
			$data ['pdforders_fontsize'] = $this->request->post ['pdforders_fontsize'];
		} else {
			$data ['pdforders_fontsize'] = $this->config->get ( 'pdforders_fontsize' );
		}
		if (! $data ['pdforders_fontsize']) {
			$data ['pdforders_fontsize'] = 14;
		}
		
		$data ['orientations'] = array (
				"P" => "POTRAIT",
				"L" => "LANDSCAPE" 
		);
		
		if (isset ( $this->request->post ['pdforders_orientation'] )) {
			$data ['pdforders_orientation'] = $this->request->post ['pdforders_orientation'];
		} else {
			$data ['pdforders_orientation'] = $this->config->get ( 'pdforders_orientation' );
		}
		
		if (isset ( $this->request->post ['pdforders_addextrarows'] )) {
			$data ['pdforders_addextrarows'] = $this->request->post ['pdforders_addextrarows'];
		} else {
			$data ['pdforders_addextrarows'] = $this->config->get ( 'pdforders_addextrarows' );
		}
		
		if (isset ( $this->request->post ['pdforders_numberproducts'] )) {
			$data ['pdforders_numberproducts'] = $this->request->post ['pdforders_numberproducts'];
		} else {
			$data ['pdforders_numberproducts'] = $this->config->get ( 'pdforders_numberproducts' );
		}
		
		if (isset ( $this->request->post ['pdforders_numberextrarows'] )) {
			$data ['pdforders_numberextrarows'] = $this->request->post ['pdforders_numberextrarows'];
		} else {
			$data ['pdforders_numberextrarows'] = $this->config->get ( 'pdforders_numberextrarows' );
		}
		
		if (isset ( $this->request->post ['pdforders_logo'] )) {
			$data ['pdforders_logo'] = $this->request->post ['pdforders_logo'];
		} else {
			$data ['pdforders_logo'] = $this->config->get ( 'pdforders_logo' );
		}
		
		if (isset ( $this->request->post ['pdforders_showimage'] )) {
			$data ['pdforders_showimage'] = $this->request->post ['pdforders_showimage'];
		} else {
			$data ['pdforders_showimage'] = $this->config->get ( 'pdforders_showimage' );
		}
		
		if (isset ( $this->request->post ['pdforders_attachemail'] )) {
			$data ['pdforders_attachemail'] = $this->request->post ['pdforders_attachemail'];
		} else {
			$data ['pdforders_attachemail'] = $this->config->get ( 'pdforders_attachemail' );
		}
		
		if (isset ( $this->request->post ['pdforders_vattin'] )) {
			$data ['pdforders_vattin'] = $this->request->post ['pdforders_vattin'];
		} else {
			$data ['pdforders_vattin'] = $this->config->get ( 'pdforders_vattin' );
		}
		
		if (isset ( $this->request->post ['pdforders_autogenerateinvoiceno'] )) {
			$data ['pdforders_autogenerateinvoiceno'] = $this->request->post ['pdforders_autogenerateinvoiceno'];
		} else {
			$data ['pdforders_autogenerateinvoiceno'] = $this->config->get ( 'pdforders_autogenerateinvoiceno' );
		}
		
		$this->load->model ( 'localisation/language' );
		
		$data ['languages'] = $this->model_localisation_language->getLanguages ();
		
		if (isset ( $this->request->post ['pdforders_textfooter'] )) {
			$data ['pdforders_textfooter'] = $this->request->post ['pdforders_textfooter'];
		} else {
			$data ['pdforders_textfooter'] = $this->config->get ( 'pdforders_textfooter' );
		}
		
		$this->load->model ( 'localisation/order_status' );
		
		$data ['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses ();
		
		if (isset ( $this->request->post ['pdforders_order_status_customer'] )) {
			$data ['pdforders_order_status_customer'] = $this->request->post ['pdforders_order_status_customer'];
		} elseif ($this->config->get ( 'pdforders_order_status_customer' )) {
			$data ['pdforders_order_status_customer'] = $this->config->get ( 'pdforders_order_status_customer' );
		} else {
			$data ['pdforders_order_status_customer'] = array ();
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'module/pdforders.tpl', $data ) );
	}
	private function validate() {
		if (! $this->user->hasPermission ( 'modify', 'module/pdforders' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		
		return ! $this->error;
	}
}
?>