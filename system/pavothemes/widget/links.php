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

class PtsWidgetLinks extends PtsWidgetPageBuilder {

		public $name = 'link';

		
		public static function getWidgetInfo(){
			return array('label' =>  ('Block Links'), 'explain' => 'Create List Block Links', 'group' => 'others'  ) ;
		}


		public function renderForm( $args, $data ){

			$helper = $this->getFormHelper();

			$this->fields_form[1]['form'] = array(
	            'legend' => array(
	                'title' => $this->l('Widget Form.'),
	            ),
	            'input' => array(
	                
	                array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 1'),
	                    'name'  => 'text_link_1',
	                    'default'=> 'link 1',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 1'),
	                    'name'  => 'link_1',
	                    'default'=> '#link1',
	                    'desc'	=> $this->l('Enter Content Link 1')
	                ),
	 				
	 				array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 2'),
	                    'name'  => 'text_link_2',
	                    'default'=> 'link 2',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 2'),
	                    'name'  => 'link_2',
	                    'default'=> '#link2',
	                    'desc'	=> $this->l('Enter Content Link 2')
	                ),

	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 3'),
	                    'name'  => 'text_link_3',
	                    'default'=> 'link3',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 3'),
	                    'name'  => 'link_3',
	                    'default'=> '#link3',
	                    'desc'	=> $this->l('Enter Content Link 3')
	                ),


	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 4'),
	                    'name'  => 'text_link_4',
	                    'default'=> '',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 4'),
	                    'name'  => 'link_4',
	                    'default'=> '',
	                    'desc'	=> $this->l('Enter Content Link 4')
	                ),



	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 5'),
	                    'name'  => 'text_link_5',
	                    'default'=> '',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 5'),
	                    'name'  => 'link_5',
	                    'default'=> '',
	                    'desc'	=> $this->l('Enter Content Link 5')
	                ),



	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 6'),
	                    'name'  => 'text_link_6',
	                    'default'=> '',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 6'),
	                    'name'  => 'link_6',
	                    'default'=> '',
	                    'desc'	=> $this->l('Enter Content Link 6')
	                ),



	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 7'),
	                    'name'  => 'text_link_7',
	                    'default'=> '',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 7'),
	                    'name'  => 'link_7',
	                    'default'=> '',
	                    'desc'	=> $this->l('Enter Content Link 7')
	                ),



	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 8'),
	                    'name'  => 'text_link_8',
	                    'default'=> '',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 8'),
	                    'name'  => 'link_8',
	                    'default'=> '',
	                    'desc'	=> $this->l('Enter Content Link 8')
	                ),



	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 9'),
	                    'name'  => 'text_link_9',
	                    'default'=> '',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 9'),
	                    'name'  => 'link_9',
	                    'default'=> '',
	                    'desc'	=> $this->l('Enter Content Link 9')
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Text Link 10'),
	                    'name'  => 'text_link_10',
	                    'default'=> '',
	                    'lang'	=> true
	                ),
	 				 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Link 10'),
	                    'name'  => 'link_9',
	                    'default'=> '',
	                    'desc'	=> $this->l('Enter Content Link 10')
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
			return  $helper->generateForm( $this->fields_form );
		}
		
		public function renderContent(  $args, $setting ){
			
			$t  = array(
				'name'=> '',
				'html'   => '',
			);

			$setting = array_merge( $t, $setting );

	 		$ac = array();
	 		$languageID = $this->config->get('config_language_id');

	 		for( $i=1; $i<=10; $i++ ){
	 			if( isset($setting['link_'.$i]) && trim($setting['link_'.$i]) )  { 
	 				$link = isset($setting['text_link_'.$i.'_'.$languageID])?html_entity_decode($setting['text_link_'.$i.'_'.$languageID],ENT_QUOTES,'UTF-8'): "No Link Title";

	 				$ac[] = array( 'text'=>$link, 'link' => trim($setting['link_'.$i]) );
	 			}
	 		}
 			
	 		$setting['id'] = rand();
	 	 	$setting['links'] = $ac;

			$languageID = $this->config->get('config_language_id');
			$setting['heading_title'] = isset($setting['widget_title_'.$languageID])?$setting['widget_title_'.$languageID]:'';
	 
			$output = array('type'=>'links','data' => $setting );

	  		return $output;
		}
		 
	}
?>