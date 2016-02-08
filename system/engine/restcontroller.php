<?php
abstract class RestController extends Controller {
	public function checkPlugin() {
		$this->config->set ( 'config_error_display', 0 );
		
		$this->response->addHeader ( 'Content-Type: application/json' );
		
		/* check rest api is enabled */
		if (! $this->config->get ( 'rest_api_status' )) {
			$json ["error"] = 'API is disabled. Enable it!';
		}
		
		if (isset ( $json ["error"] )) {
			echo (json_encode ( $json ));
			exit ();
		}
		
		$this->validateToken ();
		
		$token = $this->getTokenValue ();
		
		$this->update_session ( $token ['access_token'], json_decode ( $token ['data'], true ) );
		
		$headers = apache_request_headers ();
		
		// set language
		$osc_lang = "";
		if (isset ( $headers ['X-Oc-Merchant-Language'] )) {
			$osc_lang = $headers ['X-Oc-Merchant-Language'];
		} else if (isset ( $headers ['X-OC-MERCHANT-LANGUAGE'] )) {
			$osc_lang = $headers ['X-OC-MERCHANT-LANGUAGE'];
		}
		
		if ($osc_lang != "") {
			$this->session->data ['language'] = $osc_lang;
			$this->config->set ( 'config_language', $osc_lang );
			$languages = array ();
			$this->load->model ( 'localisation/language' );
			$all = $this->model_localisation_language->getLanguages ();
			
			foreach ( $all as $result ) {
				$languages [$result ['code']] = $result;
			}
			$this->config->set ( 'config_language_id', $languages [$osc_lang] ['language_id'] );
		}
		
		if (isset ( $headers ['X-Oc-Store-Id'] )) {
			$this->config->set ( 'config_store_id', $headers ['X-Oc-Store-Id'] );
		} else if (isset ( $headers ['X-OC-STORE-ID'] )) {
			$this->config->set ( 'config_store_id', $headers ['X-OC-STORE-ID'] );
		}
		
		$currency = "";
		
		if (isset ( $headers ['X-Oc-Currency'] )) {
			$currency = $headers ['X-Oc-Currency'];
		} else if (isset ( $headers ['X-OC-CURRENCY'] )) {
			$currency = $headers ['X-OC-CURRENCY'];
		}
		
		if (! empty ( $currency )) {
			$this->currency->set ( $currency );
		}
	}
	public function getOauthServer() {
		// $dsn = DB_DRIVER.':dbname='.DB_DATABASE.';host='.DB_HOSTNAME;
		$dsn = 'mysql:dbname=' . DB_DATABASE . ';host=' . DB_HOSTNAME;
		$username = DB_USERNAME;
		$password = DB_PASSWORD;
		
		// error reporting (this is a demo, after all!)
		// ini_set('display_errors',1);error_reporting(E_ALL);
		
		// Autoloading (composer is preferred, but for this example let's just do this)
		require_once (DIR_SYSTEM . 'oauth2-server-php/src/OAuth2/Autoloader.php');
		OAuth2\Autoloader::register ();
		
		$config = array (
				'id_lifetime' => $this->config->get ( 'rest_api_token_ttl' ),
				'access_lifetime' => $this->config->get ( 'rest_api_token_ttl' ) 
		);
		
		// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
		$storage = new OAuth2\Storage\Pdo ( array (
				'dsn' => $dsn,
				'username' => $username,
				'password' => $password 
		) );
		
		// Pass a storage object or array of storage objects to the OAuth2 server class
		$oauthServer = new OAuth2\Server ( $storage, $config );
		
		// Add the "Client Credentials" grant type (it is the simplest of the grant types)
		$oauthServer->addGrantType ( new OAuth2\GrantType\ClientCredentials ( $storage ) );
		
		return $oauthServer;
	}
	
	/* Validate Oauth token */
	public function validateToken() {
		// Handle a request to a resource and authenticate the access token
		$server = $this->getOauthServer ();
		
		if (! $server->verifyResourceRequest ( OAuth2\Request::createFromGlobals () )) {
			$serverResp = $server->getResponse ();
			$resp = array (
					'statusCode' => $serverResp->getStatusCode (),
					'statusText' => $serverResp->getStatusText (),
					'success' => false 
			);
			echo (json_encode ( $resp ));
			exit ();
		}
	}
	
	/* Get Oauth token */
	private function getTokenValue() {
		$server = $this->getOauthServer ();
		return $server->getAccessTokenData ( OAuth2\Request::createFromGlobals () );
	}
	public function sendResponse($json) {
		$this->load->model ( 'account/customer' );
		
		if (isset ( $this->session->data ['token_id'] ) || isset ( $_SESSION ['token_id'] )) {
			$token = $this->session->data ['token_id'];
			$this->model_account_customer->updateSession ( $this->session->data, $token );
			unset ( $_SESSION ['token_id'] );
		}
		
		if (isset ( $this->session->data ['customer_id'] ) && ! empty ( $this->session->data ['customer_id'] )) {
			$this->model_account_customer->updateCustomerData ( $this->session, $this->session->data ['customer_id'] );
		}
		
		$this->response->setOutput ( json_encode ( $json ) );
	}
	
	// update user session
	function update_session($token, $data) {
		if (! session_id ()) {
			session_id ( $token );
			
			// the following prevents unexpected effects when using objects as save handlers
			register_shutdown_function ( 'session_write_close' );
			
			session_start ();
		}
		
		if (! empty ( $data )) {
			$this->session->data = $data;
		}
		
		$this->session->data ['token_id'] = $token;
		
		/* Log customer in by Id */
		if (isset ( $data ['customer_id'] ) && ! empty ( $data ['customer_id'] )) {
			$this->load->model ( 'account/customer' );
			$customer_info = $this->model_account_customer->loginCustomerById ( $data ['customer_id'] );
			if ($customer_info) {
				unset ( $this->session->data ['cart'] );
				$this->customer->login ( $customer_info ['email'], "", true );
			}
		}
	}
	function clearTokensTable($token = null, $sessionid = null) {
		// delete all previous token to this session and delete all expired session
		$this->load->model ( 'account/customer' );
		$this->model_account_customer->clearTokens ( $token, $sessionid );
	}
	public function returnDeprecated() {
		$json ['success'] = false;
		$json ['error'] = "This service has been removed for security reasons.Please contact us for more information.";
		echo (json_encode ( $json ));
		exit ();
	}
}
if (! function_exists ( 'apache_request_headers' )) {
	function apache_request_headers() {
		$arh = array ();
		$rx_http = '/\AHTTP_/';
		
		foreach ( $_SERVER as $key => $val ) {
			if (preg_match ( $rx_http, $key )) {
				$arh_key = preg_replace ( $rx_http, '', $key );
				$rx_matches = array ();
				// do some nasty string manipulations to restore the original letter case
				// this should work in most cases
				$rx_matches = explode ( '_', $arh_key );
				
				if (count ( $rx_matches ) > 0 and strlen ( $arh_key ) > 2) {
					foreach ( $rx_matches as $ak_key => $ak_val ) {
						$rx_matches [$ak_key] = ucfirst ( $ak_val );
					}
					
					$arh_key = implode ( '-', $rx_matches );
				}
				
				$arh [$arh_key] = $val;
			}
		}
		
		return ($arh);
	}
}
