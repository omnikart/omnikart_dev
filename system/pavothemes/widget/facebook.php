<?php
/******************************************************
 *  Leo Opencart Theme Framework for Opencart 1.5.x
 *
 * @package   leotempcp
 * @version   3.0
 * @author    http://www.leotheme.com
 * @copyright Copyright (C) October 2013 LeoThemes.com <@emai:leotheme@gmail.com>
 *               <info@leotheme.com>.All rights reserved.
 * @license   GNU General Public License version 2
 * ******************************************************/
class PtsWidgetFacebook extends PtsWidgetPageBuilder {
	public $name = 'facebook';
	public static function getWidgetInfo() {
		return array (
				'label' => ('Facebook'),
				'explain' => 'Facebook Like Box',
				'group' => 'social' 
		);
	}
	public function renderForm($args, $data) {
		$helper = $this->getFormHelper ();
		$soption = array (
				array (
						'id' => 'active_on',
						'value' => 1,
						'label' => $this->l ( 'Enabled' ) 
				),
				array (
						'id' => 'active_off',
						'value' => 0,
						'label' => $this->l ( 'Disabled' ) 
				) 
		);
		
		$this->fields_form [1] ['form'] = array (
				'legend' => array (
						'title' => $this->l ( 'Widget Form.' ) 
				),
				'input' => array (
						
						array (
								'type' => 'text',
								'label' => $this->l ( 'Page URL' ),
								'name' => 'page_url',
								'default' => 'https://www.facebook.com/Pavothemes' 
						),
						array (
								'type' => 'switch',
								'label' => $this->l ( 'Is Border' ),
								'name' => 'border',
								'values' => $soption,
								'default' => "1" 
						)
						,
						array (
								'type' => 'select',
								'label' => $this->l ( 'Color' ),
								'name' => 'target',
								'options' => array (
										'query' => array (
												array (
														'id' => 'dark',
														'name' => $this->l ( 'Dark' ) 
												),
												array (
														'id' => 'light',
														'name' => $this->l ( 'Light' ) 
												) 
										),
										'id' => 'id',
										'name' => 'name' 
								),
								'default' => "_self" 
						),
						
						array (
								'type' => 'text',
								'label' => $this->l ( 'Width' ),
								'name' => 'width',
								'default' => '400' 
						),
						
						array (
								'type' => 'text',
								'label' => $this->l ( 'Height' ),
								'name' => 'height',
								'default' => '600' 
						),
						array (
								'type' => 'switch',
								'label' => $this->l ( 'Show Stream' ),
								'name' => 'show_stream',
								'values' => $soption,
								'default' => "1" 
						)
						,
						array (
								'type' => 'switch',
								'label' => $this->l ( 'Show Faces' ),
								'name' => 'show_faces',
								'values' => $soption,
								'default' => "1" 
						)
						,
						array (
								'type' => 'switch',
								'label' => $this->l ( 'Show Header' ),
								'name' => 'show_header',
								'values' => $soption,
								'default' => "1" 
						)
						 
				),
				'submit' => array (
						'title' => $this->l ( 'Save' ),
						'class' => 'button' 
				) 
		);
		
		$default_lang = ( int ) $this->config->get ( 'config_language_id' );
		
		$helper->tpl_vars = array (
				'fields_value' => $this->getConfigFieldsValues ( $data ),
				
				'id_language' => $default_lang 
		);
		
		return $helper->generateForm ( $this->fields_form );
	}
	public function renderContent($args, $setting) {
		$t = array (
				'name' => '',
				'application_id' => '',
				'page_url' => 'https://www.facebook.com/Pavothemes',
				'border' => 0,
				'color' => 'light',
				'width' => 290,
				'height' => 200,
				'show_stream' => 1,
				'show_faces' => 1,
				'show_header' => 1,
				'displaylanguage' => 'en' 
		)
		;
		$setting = array_merge ( $t, $setting );
		
		$languageID = $this->config->get ( 'config_language_id' );
		$setting ['heading_title'] = isset ( $setting ['widget_title_' . $languageID] ) ? $setting ['widget_title_' . $languageID] : '';
		
		$output = array (
				'type' => 'facebook',
				'data' => $setting 
		);
		return $output;
	}
}
?>