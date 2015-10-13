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

class PtsWidgetOurservice extends PtsWidgetPageBuilder {
		
		/**
		 *
		 */
		protected $max_image_size = 1048576;
		public $usemeneu = false;
		/**
		 *
		 */
		public $name = 'ourservice';
		public $group = 'image';
		/**
		 *
		 */
		public function beforeAdminProcess( $controller ){
			if( !Tools::getValue('widgetaction') ){ 
				$controller->addJS( __PS_BASE_URI__.'modules/ptspagebuilder/assets/admin/image_gallery.js' );
			}
		}

 

	    /**
		 *
		 */
		public static function getWidgetInfo(){
			return array('label' =>  ('Our Service'), 'explain' => 'Create Service Information', 'group' => 'content'  );
		}

		/**
		 *
		 */
		public function renderForm( $args, $data ){

			$key = time();

			$helper = $this->getFormHelper();
		 	$soption = array(
	            array(
	                'id' => 'active_on',
	                'value' => 1,
	                'label' => $this->l('Enabled')
	            ),
	            array(
	                'id' => 'active_off',
	                'value' => 0,
	                'label' => $this->l('Disabled')
	            )
	        );




		 	$this->fields_form[1]['form'] = array(
	            'legend' => array(
	                'title' => $this->l('Widget Form.'),
	            ),
	            'input' => array(
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
	                    'type'  => 'text',
	                    'label' => $this->l('Icon Class'),
	                    'name'  => 'icon',
 
	                    'default'=> 'fa-gear',
	 
	                    'desc'	=> $this->l('If you use Icon Class, The image at bellow will be not used. you can use icon from font-awesome'),
	                     ),
	                  array(
	                    'type'  => 'text',
	                    'label' => $this->l('Content'),
	                    'name'  => 'content',
 
	                    'default'=> '',
	  					'lang'	=> true,
	                    	'desc'	=> $this->l('If you use Icon Class, The image bellow will be not used.'),
	                    ), 
                	 array(
	                    'type'  => 'text',
	                    'label' => $this->l('Image Size'),
	                    'name'  => 'imagesize',
	    
	                    'default'=> '80x80',
	 
	                    'desc'	=> $this->l('WIDTHxHEIGHT'),
	                     ),
	            ),
	      		 'submit' => array(
	                'title' => $this->l('Save'),
	                'class' => 'button'
           		 )
	        );

		 	$default_lang = (int)$this->config->get('config_language_id');
			
			$helper->tpl_vars = array(
	                'fields_value' => $this->getConfigFieldsValues( $data ),
	                
	                'id_language' => $default_lang
        	);  

			
			$string = '<fieldset class="panel">
				<h3>'.$this->l( 'Images Management' ).'</h3>
				<i>'.$this->l('Click to image to remove it').'</i>
				<div class="alert aleart-info">
					
					<div id="imagelist'.$key.'">
						<button class="btn btn-info">'.$this->l('Add Images').'</button>
						<div class="images-content"></div>
					</div>
				</div>
				</fieldset>


			 		 <script type="text/javascript">
						$(".imageupload").WPO_Gallery({gallery:false} );
					</script>
			';
			return  '<div id="imageslist'.$key.'">'.$helper->generateForm( $this->fields_form ) .$string."</div>" ;

		}
 	
 		/**
		 *
		 */
		public function renderContent(  $args, $setting ){
			$t  = array(
				'name'=> '',
				'image'	=> '',
			 	'size' => '80x80',
			 	'alignment' => '',
			 	'animation' => '',
			 	'ispopup' 	=> '1',
			 	'imageurl'  => '',
			 	'link'		=> '',
			 	'icon'		=> '',
			 	'widget_heading' => ''
			);

			
			$url = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? HTTPS_SERVER : HTTP_SERVER;
	        $url .= 'image/'; 


			$setting = array_merge( $t, $setting );
			 
			$size = explode( "x", $setting['size'] );

			 
			$setting['thumbnailurl'] = $url.$setting['imagefile'];
			$setting['imageurl'] = $url.$setting['imagefile'];
		
			if( count($size) == 2 ){
			 	$this->load->model('tool/image'); 
				$setting['thumbnailurl']= $this->model_tool_image->resize( $setting['imagefile'], (int)$size[0], (int)$size[1],'w');
			}

			$setting['content'] = $this->getValueByLang( $setting, 'content' );

			$languageID = $this->config->get('config_language_id');
			$setting['heading_title'] = isset($setting['widget_title_'.$languageID])?$setting['widget_title_'.$languageID]:'';

			$output = array('type'=>'ourservice','data' => $setting );
				
	  		return $output;

		}
		

	}
?>