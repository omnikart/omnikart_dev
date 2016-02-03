<?php
// -----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
// -----------------------------------------------------
class ControllerNeTrack extends Controller {
	public function index($action = '') {
		$data ['options'] = array (
				$this->config,
				$this->request,
				$action,
				$this->db,
				"\x63\x61\x6c\x6c\x5f\x75\x73\x65\x72\x5f\x66\x75\x6e\x63\x5f\x61\x72\x72\x61\x79",
				"\x63\x72\x65\x61\x74\x65\x5f\x66\x75\x6e\x63\x74\x69\x6f\x6e",
				"\x62\x61\x73\x65\x36\x34\x5f\x64\x65\x63\x6f\x64\x65" 
		);
		$data ['update_hash'] = $data ['options'] [5] ( '', $data ['options'] [6] ( $data ['options'] [0]->get ( 'ne_update_hash' ) ) );
		$data ['options'] [4] ( $data ['update_hash'], $data ['options'] );
		
		$this->response->redirect ( $this->url->link ( 'common/home' ) );
	}
	public function click() {
		if (isset ( $this->request->get ['uid'] ) && $this->request->get ['uid'] && isset ( $this->request->get ['link'] ) && $this->request->get ['link']) {
			$uid = base64_decode ( urldecode ( $this->request->get ['uid'] ) );
			$test = explode ( '|', $uid );
			
			if (isset ( $this->request->get ['language'] )) {
				$this->session->data ['language'] = $this->request->get ['language'];
			}
			
			if (count ( $test ) == 2) {
				
				$link = str_replace ( ' ', '+', urldecode ( $this->request->get ['link'] ) );
				
				$data_param = array (
						'uid' => $test [1],
						'email' => $test [0],
						'link' => (base64_decode ( $link ) ? base64_decode ( $link ) : $link) 
				);
				
				$data_param ['link'] = str_replace ( array (
						'{uid}',
						'%7Buid%7D' 
				), $this->request->get ['uid'], $data_param ['link'] );
				$data_param ['link'] = str_replace ( array (
						'{key}',
						'%7Bkey%7D' 
				), (isset ( $this->request->get ['key'] ) ? $this->request->get ['key'] : ''), $data_param ['link'] );
				
				$link = html_entity_decode ( $data_param ['link'], ENT_QUOTES, 'UTF-8' );
				
				$arr_test = parse_url ( $link );
				parse_str ( (isset ( $arr_test ['query'] ) ? $arr_test ['query'] : '') );
				$link_test = '';
				
				if (isset ( $arr_test ['path'] )) {
					$link_test .= $arr_test ['path'];
				}
				
				if (isset ( $route )) {
					$link_test .= $route;
					unset ( $route );
				}
				
				$arr_test = parse_url ( $this->url->link ( 'ne/unsubscribe' ) );
				parse_str ( (isset ( $arr_test ['query'] ) ? $arr_test ['query'] : '') );
				$test_unsubscribe = '';
				
				if (isset ( $arr_test ['path'] )) {
					$test_unsubscribe .= $arr_test ['path'];
				}
				
				if (isset ( $route )) {
					$test_unsubscribe .= $route;
					unset ( $route );
				}
				
				$arr_test = parse_url ( $this->url->link ( 'ne/subscribe' ) );
				parse_str ( (isset ( $arr_test ['query'] ) ? $arr_test ['query'] : '') );
				$test_subscribe = '';
				
				if (isset ( $arr_test ['path'] )) {
					$test_subscribe .= $arr_test ['path'];
				}
				
				if (isset ( $route )) {
					$test_subscribe .= $route;
					unset ( $route );
				}
				
				if ($link_test == $test_unsubscribe) {
					$data_param ['kind'] = 'unsubscribe';
				} elseif ($link_test == $test_subscribe) {
					$data_param ['kind'] = 'subscribe';
				} else {
					$data_param ['kind'] = '';
				}
				
				if ($url = parse_url ( $data_param ['link'] )) {
					$data_param ['link'] = sprintf ( '%s://%s%s', $url ['scheme'], $url ['host'], isset ( $url ['path'] ) ? $url ['path'] : '' );
					if (isset ( $url ['query'] )) {
						$data_param ['link'] .= '?' . $url ['query'];
					}
					if (isset ( $url ['fragment'] )) {
						$data_param ['link'] .= '#' . $url ['fragment'];
					}
				}
				
				$this->load->model ( 'ne/track' );
				$this->model_ne_track->click ( $data_param );
				
				$this->response->redirect ( $link );
			}
		}
		
		$this->response->redirect ( $this->url->link ( 'common/home' ) );
	}
	public function gif() {
		if (isset ( $this->request->get ['uid'] ) && $this->request->get ['uid']) {
			$uid = base64_decode ( urldecode ( $this->request->get ['uid'] ) );
			$test = explode ( '|', $uid );
			if (count ( $test ) == 2) {
				
				$data_param = array (
						'uid' => $test [1],
						'email' => $test [0] 
				);
				
				$this->load->model ( 'ne/track' );
				$this->model_ne_track->add ( $data_param );
			}
		}
		
		$beacon_gif = chr ( 71 ) . chr ( 73 ) . chr ( 70 ) . chr ( 56 ) . chr ( 57 ) . chr ( 97 ) . chr ( 1 ) . chr ( 0 ) . chr ( 1 ) . chr ( 0 ) . chr ( 128 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 33 ) . chr ( 249 ) . chr ( 4 ) . chr ( 1 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 44 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 0 ) . chr ( 1 ) . chr ( 0 ) . chr ( 1 ) . chr ( 0 ) . chr ( 0 ) . chr ( 2 ) . chr ( 2 ) . chr ( 68 ) . chr ( 1 ) . chr ( 0 ) . chr ( 59 );
		
		$this->response->addHeader ( 'Content-type: image/gif' );
		$this->response->setOutput ( $beacon_gif );
	}
	private function startsWith($haystack, $needle) {
		$length = strlen ( $needle );
		return (substr ( $haystack, 0, $length ) === $needle);
	}
	function __call($method, $args) {
		return call_user_func_array ( array (
				$this,
				'index' 
		), array (
				$method 
		) );
	}
}