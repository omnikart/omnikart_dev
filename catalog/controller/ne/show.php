<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ControllerNeShow extends Controller {
	public function index() {
		if (isset ( $this->request->get ['uid'] ) && $this->request->get ['uid']) {
			$uid = base64_decode ( urldecode ( $this->request->get ['uid'] ) );
			$test = explode ( '|', $uid );
			
			if (count ( $test ) == 2) {
				$data = array (
						'uid' => $test [1],
						'email' => $test [0] 
				);
				
				$uid = $test [1];
				$email = $test [0];
				
				$this->load->model ( 'ne/newsletter' );
				$data = $this->model_ne_newsletter->get ( $data );
				
				if ($data) {
					$this->load->model ( 'ne/template' );
					$template_info = $this->model_ne_template->getTemplate ( $data );
					
					$info = $this->model_ne_newsletter->info ( $email );
					
					if ($info) {
						$firstname = mb_convert_case ( $info ['firstname'], MB_CASE_TITLE, 'UTF-8' );
						$lastname = mb_convert_case ( $info ['lastname'], MB_CASE_TITLE, 'UTF-8' );
						
						$data ['subject'] = str_replace ( array (
								'{name}',
								'{lastname}',
								'{email}' 
						), array (
								$firstname,
								$lastname,
								$email 
						), $data ['subject'] );
						$data ['message'] = str_replace ( array (
								'{name}',
								'{lastname}',
								'{email}' 
						), array (
								$firstname,
								$lastname,
								$email 
						), $data ['message'] );
						
						if (isset ( $info ['reward'] )) {
							$data ['subject'] = str_replace ( '{reward}', $info ['reward'], $data ['subject'] );
							$data ['message'] = str_replace ( '{reward}', $info ['reward'], $data ['message'] );
						}
					}
					
					$this->load->model ( 'localisation/language' );
					$language = $this->model_localisation_language->getLanguage ( $data ['language_id'] );
					
					$html = '<html dir="ltr" lang="en">' . PHP_EOL;
					$html .= '<head>' . PHP_EOL;
					$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . PHP_EOL;
					$html .= '<title>' . $data ['subject'] . '</title>' . PHP_EOL;
					if (! empty ( $template_info ['custom_css'] )) {
						$html .= html_entity_decode ( $template_info ['custom_css'], ENT_COMPAT, 'UTF-8' ) . PHP_EOL;
					}
					$html .= '</head>' . PHP_EOL;
					$html .= '<body style="padding:0;margin:0;">' . (strpos ( $data ['message'], '&amp;amp;' ) === false ? $data ['message'] : html_entity_decode ( $data ['message'], ENT_QUOTES, 'UTF-8' )) . '</body>' . PHP_EOL;
					$html .= '</html>' . PHP_EOL;
					
					$html = str_replace ( array (
							chr ( 3 ) 
					), '', $html );
					
					libxml_use_internal_errors ( true );
					$dom = new DOMDocument ();
					$dom->loadHTML ( $html );
					foreach ( $dom->getElementsByTagName ( 'a' ) as $node ) {
						if ($node->hasAttribute ( 'href' )) {
							$link = $node->getAttribute ( 'href' );
							if ((strpos ( $link, 'http://' ) === 0) || (strpos ( $link, 'https://' ) === 0)) {
								$add_key = ((strpos ( $link, '{key}' ) !== false) || (strpos ( $link, '%7Bkey%7D' ) !== false));
								$node->setAttribute ( 'href', $this->url->link ( 'ne/track/click', 'link=' . urlencode ( base64_encode ( $link ) ) . '&uid=' . $this->request->get ['uid'] . '&language=' . $language ['code'] . ($add_key ? '&key={key}' : '') ) );
							}
						}
					}
					
					if ($this->config->get ( 'ne_embedded_images' )) {
						if (isset ( $this->request->server ['HTTPS'] ) && (($this->request->server ['HTTPS'] == 'on') || ($this->request->server ['HTTPS'] == '1'))) {
							$server = defined ( 'HTTPS_IMAGE' ) ? HTTPS_IMAGE : HTTPS_SERVER . 'image/';
						} else {
							$server = defined ( 'HTTP_IMAGE' ) ? HTTP_IMAGE : HTTP_SERVER . 'image/';
						}
						
						foreach ( $dom->getElementsByTagName ( 'img' ) as $node ) {
							if ($node->hasAttribute ( 'src' )) {
								$src = $node->getAttribute ( 'src' );
								$src = str_replace ( $server, DIR_IMAGE, $src );
								$node->setAttribute ( 'src', $this->base64_encode_image ( $src ) );
							}
						}
					}
					
					$html = $dom->saveHTML ();
					libxml_clear_errors ();
					
					$html = str_replace ( array (
							'{key}',
							'%7Bkey%7D' 
					), md5 ( $this->config->get ( 'ne_key' ) . $email ), $html );
					$html = str_replace ( array (
							'{uid}',
							'%7Buid%7D' 
					), urlencode ( base64_encode ( $email . '|' . $uid ) ), $html );
					
					$html = html_entity_decode ( $html, ENT_QUOTES, 'UTF-8' );
					
					$html .= '<div><img width="0" height="0" alt="" src="' . $this->url->link ( 'ne/track/gif', 'uid=' . $this->request->get ['uid'] ) . '" /></div>';
					
					$this->response->setOutput ( $html );
				} else {
					$this->response->redirect ( $this->url->link ( 'common/home' ) );
				}
			} else {
				$this->response->redirect ( $this->url->link ( 'common/home' ) );
			}
		} elseif (isset ( $this->request->get ['id'] ) && $this->request->get ['id']) {
			$this->load->model ( 'ne/newsletter' );
			$data = $this->model_ne_newsletter->get_show ( $this->request->get ['id'] );
			if ($data) {
				$this->load->model ( 'ne/template' );
				$template_info = $this->model_ne_template->getTemplate ( $data );
				
				$html = '<html dir="ltr" lang="en">' . PHP_EOL;
				$html .= '<head>' . PHP_EOL;
				$html .= '<title>' . $data ['subject'] . '</title>' . PHP_EOL;
				$html .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . PHP_EOL;
				if (! empty ( $template_info ['custom_css'] )) {
					$html .= html_entity_decode ( $template_info ['custom_css'], ENT_COMPAT, 'UTF-8' ) . PHP_EOL;
				}
				$html .= '</head>' . PHP_EOL;
				$html .= '<body style="padding:0;margin:0;">' . html_entity_decode ( $data ['message'], ENT_QUOTES, 'UTF-8' ) . '</body>' . PHP_EOL;
				$html .= '</html>' . PHP_EOL;
				$html = html_entity_decode ( $html, ENT_QUOTES, 'UTF-8' );
				$this->response->setOutput ( $html );
			} else {
				$this->response->redirect ( $this->url->link ( 'common/home' ) );
			}
		} else {
			$this->response->redirect ( $this->url->link ( 'common/home' ) );
		}
	}
	private function base64_encode_image($image) {
		if (file_exists ( $image )) {
			$filename = htmlentities ( $image );
		} else {
			return '';
		}
		
		$imgtype = array (
				'jpg' => 'jpeg',
				'jpeg' => 'jpeg',
				'gif' => 'gif',
				'png' => 'png' 
		);
		
		$filetype = pathinfo ( $filename, PATHINFO_EXTENSION );
		
		if (array_key_exists ( $filetype, $imgtype )) {
			$imgbinary = fread ( fopen ( $filename, "r" ), filesize ( $filename ) );
		} else {
			return '';
		}
		
		return 'data:image/' . $imgtype [$filetype] . ';base64,' . base64_encode ( $imgbinary );
	}
}