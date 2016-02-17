<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ControllerNeTemplate extends Controller {
	private $error = array ();
	public function index() {
		$this->load->language ( 'ne/template' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'ne/template' );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['edit'] = $this->url->link ( 'ne/template/update', 'token=' . $this->session->data ['token'] . '&id=', 'SSL' );
		$data ['insert'] = $this->url->link ( 'ne/template/insert', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['delete'] = $this->url->link ( 'ne/template/delete', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['copy'] = $this->url->link ( 'ne/template/copy', 'token=' . $this->session->data ['token'], 'SSL' );
		$data ['copy_default'] = $this->url->link ( 'ne/template/copy_default', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['templates'] = array ();
		
		$results = $this->model_ne_template->getList ();
		
		foreach ( $results as $result ) {
			$data ['templates'] [] = array_merge ( $result, array (
					'selected' => isset ( $this->request->post ['selected'] ) && is_array ( $this->request->post ['selected'] ) && in_array ( $result ['template_id'], $this->request->post ['selected'] ) 
			) );
		}
		unset ( $result );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['text_templates'] = $this->language->get ( 'text_templates' );
		$data ['text_no_results'] = $this->language->get ( 'text_no_results' );
		
		$data ['column_name'] = $this->language->get ( 'column_name' );
		$data ['column_last_change'] = $this->language->get ( 'column_last_change' );
		$data ['column_actions'] = $this->language->get ( 'column_actions' );
		
		$data ['button_delete'] = $this->language->get ( 'button_delete' );
		$data ['button_insert'] = $this->language->get ( 'button_insert' );
		$data ['button_copy'] = $this->language->get ( 'button_copy' );
		$data ['button_copy_default'] = $this->language->get ( 'button_copy_default' );
		$data ['button_edit'] = $this->language->get ( 'button_edit' );
		
		$data ['token'] = $this->session->data ['token'];
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = $this->config->get ( 'ne_warning' );
		}
		
		if (isset ( $this->session->data ['success'] )) {
			$data ['success'] = $this->session->data ['success'];
			
			unset ( $this->session->data ['success'] );
		} else {
			$data ['success'] = '';
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		if (isset ( $this->request->post ['selected'] )) {
			$data ['selected'] = ( array ) $this->request->post ['selected'];
		} else {
			$data ['selected'] = array ();
		}
		
		$this->response->setOutput ( $this->load->view ( 'ne/template.tpl', $data ) );
	}
	public function update() {
		$this->load->language ( 'ne/template' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'ne/template' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			$this->model_ne_template->save ( array_merge ( $this->request->post, array (
					'id' => $this->request->get ['id'] 
			) ) );
			$this->session->data ['success'] = $this->language->get ( 'text_success_save' );
			
			$this->response->redirect ( $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$this->form ();
	}
	public function insert() {
		$this->load->language ( 'ne/template' );
		
		$this->document->setTitle ( $this->language->get ( 'heading_title' ) );
		
		$this->load->model ( 'ne/template' );
		
		if (($this->request->server ['REQUEST_METHOD'] == 'POST') && $this->validateForm ()) {
			$this->model_ne_template->save ( $this->request->post );
			$this->session->data ['success'] = $this->language->get ( 'text_success_save' );
			
			$this->response->redirect ( $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' ) );
		}
		
		$this->form ();
	}
	private function form() {
		if (isset ( $this->request->get ['id'] )) {
			$template_id = $this->request->get ['id'];
			$template_info = $this->model_ne_template->get ( $template_id );
		} else {
			$template_info = array ();
		}
		
		$this->document->addScript ( 'view/javascript/ne/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js' );
		$this->document->addStyle ( 'view/javascript/ne/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css' );
		
		$data ['heading_title'] = $this->language->get ( 'heading_title' );
		
		$data ['entry_name'] = $this->language->get ( 'entry_name' );
		$data ['entry_show_prices'] = $this->language->get ( 'entry_show_prices' );
		$data ['entry_show_savings'] = $this->language->get ( 'entry_show_savings' );
		$data ['entry_description_length'] = $this->language->get ( 'entry_description_length' );
		$data ['entry_image_size'] = $this->language->get ( 'entry_image_size' );
		$data ['entry_columns'] = $this->language->get ( 'entry_columns' );
		$data ['entry_specials_count'] = $this->language->get ( 'entry_specials_count' );
		$data ['entry_latest_count'] = $this->language->get ( 'entry_latest_count' );
		$data ['entry_popular_count'] = $this->language->get ( 'entry_popular_count' );
		$data ['entry_heading_color'] = $this->language->get ( 'entry_heading_color' );
		$data ['entry_heading_style'] = $this->language->get ( 'entry_heading_style' );
		$data ['entry_name_color'] = $this->language->get ( 'entry_name_color' );
		$data ['entry_name_style'] = $this->language->get ( 'entry_name_style' );
		$data ['entry_model_color'] = $this->language->get ( 'entry_model_color' );
		$data ['entry_model_style'] = $this->language->get ( 'entry_model_style' );
		$data ['entry_price_color'] = $this->language->get ( 'entry_price_color' );
		$data ['entry_price_style'] = $this->language->get ( 'entry_price_style' );
		$data ['entry_price_color_special'] = $this->language->get ( 'entry_price_color_special' );
		$data ['entry_price_style_special'] = $this->language->get ( 'entry_price_style_special' );
		$data ['entry_price_color_when_special'] = $this->language->get ( 'entry_price_color_when_special' );
		$data ['entry_price_style_when_special'] = $this->language->get ( 'entry_price_style_when_special' );
		$data ['entry_description_color'] = $this->language->get ( 'entry_description_color' );
		$data ['entry_description_style'] = $this->language->get ( 'entry_description_style' );
		$data ['entry_savings_color'] = $this->language->get ( 'entry_savings_color' );
		$data ['entry_savings_style'] = $this->language->get ( 'entry_savings_style' );
		$data ['entry_template_body'] = $this->language->get ( 'entry_template_body' );
		$data ['entry_yes'] = $this->language->get ( 'entry_yes' );
		$data ['entry_no'] = $this->language->get ( 'entry_no' );
		$data ['entry_subject'] = $this->language->get ( 'entry_subject' );
		$data ['entry_defined_text'] = $this->language->get ( 'entry_defined_text' );
		$data ['entry_special_text'] = $this->language->get ( 'entry_special_text' );
		$data ['entry_latest_text'] = $this->language->get ( 'entry_latest_text' );
		$data ['entry_popular_text'] = $this->language->get ( 'entry_popular_text' );
		$data ['entry_categories_text'] = $this->language->get ( 'entry_categories_text' );
		$data ['entry_savings_text'] = $this->language->get ( 'entry_savings_text' );
		$data ['entry_product_template'] = $this->language->get ( 'entry_product_template' );
		$data ['entry_custom_css'] = $this->language->get ( 'entry_custom_css' );
		$data ['entry_text_message'] = $this->language->get ( 'entry_text_message' );
		
		$data ['help_image_size'] = $this->language->get ( 'help_image_size' );
		$data ['help_description_length'] = $this->language->get ( 'help_description_length' );
		$data ['help_specials_count'] = $this->language->get ( 'help_specials_count' );
		$data ['help_latest_count'] = $this->language->get ( 'help_latest_count' );
		$data ['help_popular_count'] = $this->language->get ( 'help_popular_count' );
		$data ['help_heading_color'] = $this->language->get ( 'help_heading_color' );
		$data ['help_heading_style'] = $this->language->get ( 'help_heading_style' );
		$data ['help_name_color'] = $this->language->get ( 'help_name_color' );
		$data ['help_name_style'] = $this->language->get ( 'help_name_style' );
		$data ['help_model_color'] = $this->language->get ( 'help_model_color' );
		$data ['help_model_style'] = $this->language->get ( 'help_model_style' );
		$data ['help_price_color'] = $this->language->get ( 'help_price_color' );
		$data ['help_price_style'] = $this->language->get ( 'help_price_style' );
		$data ['help_price_color_special'] = $this->language->get ( 'help_price_color_special' );
		$data ['help_price_style_special'] = $this->language->get ( 'help_price_style_special' );
		$data ['help_price_color_when_special'] = $this->language->get ( 'help_price_color_when_special' );
		$data ['help_price_style_when_special'] = $this->language->get ( 'help_price_style_when_special' );
		$data ['help_description_color'] = $this->language->get ( 'help_description_color' );
		$data ['help_description_style'] = $this->language->get ( 'help_description_style' );
		$data ['help_savings_color'] = $this->language->get ( 'help_savings_color' );
		$data ['help_savings_style'] = $this->language->get ( 'help_savings_style' );
		
		$data ['text_settings'] = $this->language->get ( 'text_settings' );
		$data ['text_styles'] = $this->language->get ( 'text_styles' );
		$data ['text_template'] = $this->language->get ( 'text_template' );
		$data ['text_message_info'] = $this->language->get ( 'text_message_info' );
		$data ['text_newsletter_template'] = $this->language->get ( 'text_newsletter_template' );
		
		$data ['button_save'] = $this->language->get ( 'button_save' );
		$data ['button_cancel'] = $this->language->get ( 'button_cancel' );
		
		$data ['breadcrumbs'] = array ();
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'text_home' ),
				'href' => $this->url->link ( 'common/home', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $this->language->get ( 'heading_title' ),
				'href' => $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		$data ['breadcrumbs'] [] = array (
				'text' => $template_info ? $template_info ['name'] : $this->language->get ( 'text_new_template' ),
				'href' => $template_info ? $this->url->link ( 'ne/template/update', 'token=' . $this->session->data ['token'] . '&id=' . ( int ) $template_id, 'SSL' ) : $this->url->link ( 'ne/template/insert', 'token=' . $this->session->data ['token'], 'SSL' ) 
		);
		
		if (isset ( $this->request->post ['name'] )) {
			$data ['name'] = $this->request->post ['name'];
		} elseif (! empty ( $template_info )) {
			$data ['name'] = $template_info ['name'];
		} else {
			$data ['name'] = '';
		}
		
		if (isset ( $this->request->post ['uri'] )) {
			$data ['uri'] = $this->request->post ['uri'];
		} elseif (! empty ( $template_info )) {
			$data ['uri'] = $template_info ['uri'];
		} else {
			$data ['uri'] = 'default';
		}
		
		if (isset ( $this->request->post ['product_image_width'] )) {
			$data ['product_image_width'] = $this->request->post ['product_image_width'];
		} elseif (! empty ( $template_info )) {
			$data ['product_image_width'] = $template_info ['product_image_width'];
		} else {
			$data ['product_image_width'] = '140';
		}
		
		if (isset ( $this->request->post ['product_image_height'] )) {
			$data ['product_image_height'] = $this->request->post ['product_image_height'];
		} elseif (! empty ( $template_info )) {
			$data ['product_image_height'] = $template_info ['product_image_height'];
		} else {
			$data ['product_image_height'] = '140';
		}
		
		if (isset ( $this->request->post ['product_show_prices'] )) {
			$data ['product_show_prices'] = $this->request->post ['product_show_prices'];
		} elseif (! empty ( $template_info )) {
			$data ['product_show_prices'] = $template_info ['product_show_prices'];
		} else {
			$data ['product_show_prices'] = '1';
		}
		
		if (isset ( $this->request->post ['product_show_savings'] )) {
			$data ['product_show_savings'] = $this->request->post ['product_show_savings'];
		} elseif (! empty ( $template_info )) {
			$data ['product_show_savings'] = $template_info ['product_show_savings'];
		} else {
			$data ['product_show_savings'] = '0';
		}
		
		if (isset ( $this->request->post ['product_description_length'] )) {
			$data ['product_description_length'] = $this->request->post ['product_description_length'];
		} elseif (! empty ( $template_info )) {
			$data ['product_description_length'] = $template_info ['product_description_length'];
		} else {
			$data ['product_description_length'] = '100';
		}
		
		if (isset ( $this->request->post ['product_cols'] )) {
			$data ['product_cols'] = $this->request->post ['product_cols'];
		} elseif (! empty ( $template_info )) {
			$data ['product_cols'] = $template_info ['product_cols'];
		} else {
			$data ['product_cols'] = '4';
		}
		
		if (isset ( $this->request->post ['specials_count'] )) {
			$data ['specials_count'] = $this->request->post ['specials_count'];
		} elseif (! empty ( $template_info )) {
			$data ['specials_count'] = $template_info ['specials_count'];
		} else {
			$data ['specials_count'] = '8';
		}
		
		if (isset ( $this->request->post ['latest_count'] )) {
			$data ['latest_count'] = $this->request->post ['latest_count'];
		} elseif (! empty ( $template_info )) {
			$data ['latest_count'] = $template_info ['latest_count'];
		} else {
			$data ['latest_count'] = '8';
		}
		
		if (isset ( $this->request->post ['popular_count'] )) {
			$data ['popular_count'] = $this->request->post ['popular_count'];
		} elseif (! empty ( $template_info )) {
			$data ['popular_count'] = $template_info ['popular_count'];
		} else {
			$data ['popular_count'] = '8';
		}
		
		if (isset ( $this->request->post ['heading_color'] )) {
			$data ['heading_color'] = $this->request->post ['heading_color'];
		} elseif (! empty ( $template_info )) {
			$data ['heading_color'] = $template_info ['heading_color'];
		} else {
			$data ['heading_color'] = '#222222';
		}
		
		if (isset ( $this->request->post ['heading_style'] )) {
			$data ['heading_style'] = $this->request->post ['heading_style'];
		} elseif (! empty ( $template_info )) {
			$data ['heading_style'] = $template_info ['heading_style'];
		} else {
			$data ['heading_style'] = '';
		}
		
		if (isset ( $this->request->post ['product_name_color'] )) {
			$data ['product_name_color'] = $this->request->post ['product_name_color'];
		} elseif (! empty ( $template_info )) {
			$data ['product_name_color'] = $template_info ['product_name_color'];
		} else {
			$data ['product_name_color'] = '#222222';
		}
		
		if (isset ( $this->request->post ['product_name_style'] )) {
			$data ['product_name_style'] = $this->request->post ['product_name_style'];
		} elseif (! empty ( $template_info )) {
			$data ['product_name_style'] = $template_info ['product_name_style'];
		} else {
			$data ['product_name_style'] = '';
		}
		
		if (isset ( $this->request->post ['product_model_color'] )) {
			$data ['product_model_color'] = $this->request->post ['product_model_color'];
		} elseif (! empty ( $template_info )) {
			$data ['product_model_color'] = $template_info ['product_model_color'];
		} else {
			$data ['product_model_color'] = '#999999';
		}
		
		if (isset ( $this->request->post ['product_model_style'] )) {
			$data ['product_model_style'] = $this->request->post ['product_model_style'];
		} elseif (! empty ( $template_info )) {
			$data ['product_model_style'] = $template_info ['product_model_style'];
		} else {
			$data ['product_model_style'] = '';
		}
		
		if (isset ( $this->request->post ['product_price_color'] )) {
			$data ['product_price_color'] = $this->request->post ['product_price_color'];
		} elseif (! empty ( $template_info )) {
			$data ['product_price_color'] = $template_info ['product_price_color'];
		} else {
			$data ['product_price_color'] = '#990000';
		}
		
		if (isset ( $this->request->post ['product_price_style'] )) {
			$data ['product_price_style'] = $this->request->post ['product_price_style'];
		} elseif (! empty ( $template_info )) {
			$data ['product_price_style'] = $template_info ['product_price_style'];
		} else {
			$data ['product_price_style'] = '';
		}
		
		if (isset ( $this->request->post ['product_price_color_special'] )) {
			$data ['product_price_color_special'] = $this->request->post ['product_price_color_special'];
		} elseif (! empty ( $template_info )) {
			$data ['product_price_color_special'] = $template_info ['product_price_color_special'];
		} else {
			$data ['product_price_color_special'] = '#990000';
		}
		
		if (isset ( $this->request->post ['product_price_style_special'] )) {
			$data ['product_price_style_special'] = $this->request->post ['product_price_style_special'];
		} elseif (! empty ( $template_info )) {
			$data ['product_price_style_special'] = $template_info ['product_price_style_special'];
		} else {
			$data ['product_price_style_special'] = '';
		}
		
		if (isset ( $this->request->post ['product_price_color_when_special'] )) {
			$data ['product_price_color_when_special'] = $this->request->post ['product_price_color_when_special'];
		} elseif (! empty ( $template_info )) {
			$data ['product_price_color_when_special'] = $template_info ['product_price_color_when_special'];
		} else {
			$data ['product_price_color_when_special'] = '#999999';
		}
		
		if (isset ( $this->request->post ['product_price_style_when_special'] )) {
			$data ['product_price_style_when_special'] = $this->request->post ['product_price_style_when_special'];
		} elseif (! empty ( $template_info )) {
			$data ['product_price_style_when_special'] = $template_info ['product_price_style_when_special'];
		} else {
			$data ['product_price_style_when_special'] = '';
		}
		
		if (isset ( $this->request->post ['product_description_color'] )) {
			$data ['product_description_color'] = $this->request->post ['product_description_color'];
		} elseif (! empty ( $template_info )) {
			$data ['product_description_color'] = $template_info ['product_description_color'];
		} else {
			$data ['product_description_color'] = '#999999';
		}
		
		if (isset ( $this->request->post ['product_description_style'] )) {
			$data ['product_description_style'] = $this->request->post ['product_description_style'];
		} elseif (! empty ( $template_info )) {
			$data ['product_description_style'] = $template_info ['product_description_style'];
		} else {
			$data ['product_description_style'] = '';
		}
		
		if (isset ( $this->request->post ['product_savings_color'] )) {
			$data ['product_savings_color'] = $this->request->post ['product_savings_color'];
		} elseif (! empty ( $template_info )) {
			$data ['product_savings_color'] = $template_info ['product_savings_color'];
		} else {
			$data ['product_savings_color'] = '#999999';
		}
		
		if (isset ( $this->request->post ['product_savings_style'] )) {
			$data ['product_savings_style'] = $this->request->post ['product_savings_style'];
		} elseif (! empty ( $template_info )) {
			$data ['product_savings_style'] = $template_info ['product_savings_style'];
		} else {
			$data ['product_savings_style'] = '';
		}
		
		if (isset ( $this->request->post ['custom_css'] )) {
			$data ['custom_css'] = $this->request->post ['custom_css'];
		} elseif (! empty ( $template_info )) {
			$data ['custom_css'] = $template_info ['custom_css'];
		} else {
			$data ['custom_css'] = '';
		}
		
		if (isset ( $this->request->post ['subject'] )) {
			$data ['subject'] = $this->request->post ['subject'];
		} elseif (! empty ( $template_info )) {
			$data ['subject'] = $template_info ['subject'];
		} else {
			$data ['subject'] = array ();
		}
		
		if (isset ( $this->request->post ['defined_text'] )) {
			$data ['defined_text'] = $this->request->post ['defined_text'];
		} elseif (! empty ( $template_info )) {
			$data ['defined_text'] = $template_info ['defined_text'];
		} else {
			$data ['defined_text'] = array ();
		}
		
		if (isset ( $this->request->post ['special_text'] )) {
			$data ['special_text'] = $this->request->post ['special_text'];
		} elseif (! empty ( $template_info )) {
			$data ['special_text'] = $template_info ['special_text'];
		} else {
			$data ['special_text'] = array ();
		}
		
		if (isset ( $this->request->post ['latest_text'] )) {
			$data ['latest_text'] = $this->request->post ['latest_text'];
		} elseif (! empty ( $template_info )) {
			$data ['latest_text'] = $template_info ['latest_text'];
		} else {
			$data ['latest_text'] = array ();
		}
		
		if (isset ( $this->request->post ['popular_text'] )) {
			$data ['popular_text'] = $this->request->post ['popular_text'];
		} elseif (! empty ( $template_info )) {
			$data ['popular_text'] = $template_info ['popular_text'];
		} else {
			$data ['popular_text'] = array ();
		}
		
		if (isset ( $this->request->post ['categories_text'] )) {
			$data ['categories_text'] = $this->request->post ['categories_text'];
		} elseif (! empty ( $template_info )) {
			$data ['categories_text'] = $template_info ['categories_text'];
		} else {
			$data ['categories_text'] = array ();
		}
		
		if (isset ( $this->request->post ['savings_text'] )) {
			$data ['savings_text'] = $this->request->post ['savings_text'];
		} elseif (! empty ( $template_info )) {
			$data ['savings_text'] = $template_info ['savings_text'];
		} else {
			$data ['savings_text'] = array ();
		}
		
		if (isset ( $this->request->post ['uri'] )) {
			$data ['uri'] = $this->request->post ['uri'];
		} elseif (! empty ( $template_info )) {
			$data ['uri'] = $template_info ['uri'];
		} else {
			$data ['uri'] = '';
		}
		
		if (isset ( $this->request->post ['body'] )) {
			$data ['body'] = $this->request->post ['body'];
		} elseif (! empty ( $template_info )) {
			$data ['body'] = $template_info ['body'];
		} else {
			$data ['body'] = array ();
		}
		
		if (isset ( $this->request->post ['text_message'] )) {
			$data ['text_message'] = $this->request->post ['text_message'];
		} elseif (! empty ( $template_info )) {
			$data ['text_message'] = $template_info ['text_message'];
		} else {
			$data ['text_message'] = array ();
		}
		
		if (file_exists ( DIR_CATALOG . 'view/theme/' . $this->config->get ( 'config_template' ) . '/template/ne/templates' )) {
			$parts = ( array ) glob ( DIR_CATALOG . 'view/theme/' . $this->config->get ( 'config_template' ) . '/template/ne/templates/*' );
		} else {
			$parts = ( array ) glob ( DIR_CATALOG . 'view/theme/default/template/ne/templates/*' );
		}
		
		$data ['parts'] = array ();
		
		if ($parts) {
			foreach ( $parts as $part ) {
				$data ['parts'] [basename ( $part )] = ucwords ( basename ( $part ) );
			}
		}
		
		$this->load->model ( 'localisation/language' );
		
		$data ['languages'] = $this->model_localisation_language->getLanguages ();
		
		if (! isset ( $template_id )) {
			$data ['action'] = $this->url->link ( 'ne/template/insert', 'token=' . $this->session->data ['token'], 'SSL' );
		} else {
			$data ['action'] = $this->url->link ( 'ne/template/update', 'token=' . $this->session->data ['token'] . '&id=' . $template_id, 'SSL' );
		}
		
		$data ['cancel'] = $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' );
		
		$data ['token'] = $this->session->data ['token'];
		
		if (isset ( $this->error ['warning'] )) {
			$data ['error_warning'] = $this->error ['warning'];
		} else {
			$data ['error_warning'] = $this->config->get ( 'ne_warning' );
		}
		
		$data ['header'] = $this->load->controller ( 'common/header' );
		$data ['column_left'] = $this->load->controller ( 'common/column_left' );
		$data ['footer'] = $this->load->controller ( 'common/footer' );
		
		$this->response->setOutput ( $this->load->view ( 'ne/template_detail.tpl', $data ) );
	}
	public function delete() {
		$this->load->language ( 'ne/template' );
		$this->load->model ( 'ne/template' );
		
		if (isset ( $this->request->post ['selected'] ) && $this->validate ()) {
			foreach ( $this->request->post ['selected'] as $template_id ) {
				if (! $this->model_ne_template->delete ( $template_id )) {
					$this->error ['warning'] = $this->language->get ( 'error_delete' );
				}
			}
			if (isset ( $this->error ['warning'] )) {
				$this->session->data ['success'] = $this->error ['warning'];
			} else {
				$this->session->data ['success'] = $this->language->get ( 'text_success' );
			}
		}
		
		$this->response->redirect ( $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' ) );
	}
	public function copy() {
		$this->load->language ( 'ne/template' );
		$this->load->model ( 'ne/template' );
		
		if (isset ( $this->request->post ['selected'] ) && $this->validate ()) {
			foreach ( $this->request->post ['selected'] as $template_id ) {
				if (! $this->model_ne_template->copy ( $template_id )) {
					$this->error ['warning'] = $this->language->get ( 'error_copy' );
				}
			}
			if (isset ( $this->error ['warning'] )) {
				$this->session->data ['success'] = $this->error ['warning'];
			} else {
				$this->session->data ['success'] = $this->language->get ( 'text_success_copy' );
			}
		}
		
		$this->response->redirect ( $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' ) );
	}
	public function copy_default() {
		$this->load->language ( 'ne/template' );
		$this->load->model ( 'ne/template' );
		
		if (! $this->model_ne_template->copy ( 1 )) {
			$this->session->data ['success'] = $this->language->get ( 'error_copy' );
		} else {
			$this->session->data ['success'] = $this->language->get ( 'text_success_copy' );
		}
		
		$this->response->redirect ( $this->url->link ( 'ne/template', 'token=' . $this->session->data ['token'], 'SSL' ) );
	}
	private function validateForm() {
		if (! $this->user->hasPermission ( 'modify', 'ne/template' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		return ! $this->error;
	}
	private function validate() {
		if (! $this->user->hasPermission ( 'modify', 'ne/template' )) {
			$this->error ['warning'] = $this->language->get ( 'error_permission' );
		}
		return ! $this->error;
	}
}