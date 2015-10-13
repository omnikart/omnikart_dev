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

class PtsWidgetNewsletter extends PtsWidgetPageBuilder {

		public $name = 'map';

		public $usemeneu = false;
		
		public static function getWidgetInfo(){
			return array('label' => ('Newsletter Form'), 'explain' => 'Create Newsletter Form Working With Newsletter Block Of Opencart.', 'group' => 'opencart'  );
		}


		public function renderForm( $args, $data ){
			$helper = $this->getFormHelper();
			
			$key = time();

			$this->fields_form[1]['form'] = array(
	            'legend' => array(
	                'title' => $this->l('Widget Form.'),
	            ),
	            'input' => array(
 					array(
	                    'type'  => 'text',
	                    'label' => $this->l('Css Class'),
	                    'name'  => 'class',
	                    'default'=> "pts-newsletter",
	                ),
	                array(
	                    'type'  => 'text',
	                    'label' => $this->l('Image File'),
	                    'name'  => 'imagefile',
	                    'class' => 'imageupload',
	                    'default'=> '',
	                    'id'	 => 'imagefile'.$key,
	                    'desc'	=> 'Put image folder in the image folder ROOT_SHOP_DIR/img/'
	                ),
	                array(
	                    'type' => 'textarea',
	                    'label' => $this->l('Information'),
	                    'name' => 'information',
	                    'cols' => 20,
	                    'rows' => 10,
	                    'value' => true,
	                    'lang'  => true,
	                    'default'=> 'Sign up to our newsletter and get exclusive deals you wont find anywhere else straight to your inbox!',
	                    'autoload_rte' => true,
	                ),
	            ),
	      		 'submit' => array(
	                'title' => $this->l('Save'),
	                'class' => 'button'
           		 )
	        );

 
		 	$default_lang = (int)$this->config->get('config_language_id');
			
			$helper->tpl_vars = array(
	                'fields_value' => $this->getConfigFieldsValues( $data  ),
	                
	                'id_language' => $default_lang
        	);  

			$string = '
				<script type="text/javascript">
						$(".imageupload").WPO_Gallery({gallery:false} );
				</script>
			';
			return  $helper->generateForm( $this->fields_form ).$string;
		}

		public function renderContent( $args, $setting ){

			if( is_dir(DIR_APPLICATION.'model/pavnewsletter') ){
				$t = array(
					'class' => "pts-newsletter",
					'imagefile'=> '',
					'information'	=> '',
					'position'		=> rand(),
					'module'		=> rand()
				);
				$setting = array_merge( $t, $setting );
				
				$languageID = $this->config->get('config_language_id');
				$setting['heading_title'] = isset($setting['widget_title_'.$languageID])?$setting['widget_title_'.$languageID]:'';

				$setting['description']= isset($setting['information_'.$languageID])?html_entity_decode($setting['information_'.$languageID],ENT_QUOTES,'UTF-8'): "";			 
				$setting['background'] = _PAGEBUILDER_IMAGE_URL_.''.$setting['imagefile']	;
	 			
				$output = array('type'=>'newsletter','data' => $setting );

				return $output;
			}
		}

	}
?>