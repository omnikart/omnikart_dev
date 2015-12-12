<?php
require_once(DIR_SYSTEM . 'engine/restcontroller.php');

class ControllerRestTokencheck extends RestController {
	public function login() {
		$this->checkPlugin();
		$json = array('success' => true);
		$this->sendResponse($json);
	}
}