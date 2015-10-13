<?php  
class ControllerToolNitro extends Controller {
	public function getwidget() {
        $this->load->model('tool/nitro');

        require_once DIR_SYSTEM . 'nitro/core/top.php';
        
		if (!empty($_SESSION['NitroRenderTime']) && !empty($_SESSION['NitroNameOfCacheFile'])) {
			if (decideToShowFrontWidget()) {
				$renderTime = $_SESSION['NitroRenderTime'];
				$nameOfCacheFile = $_SESSION['NitroNameOfCacheFile'];
				$originalRenderTime = (float)getLoadTime($nameOfCacheFile);
				$faster = (int)($originalRenderTime / $renderTime);
				require_once NITRO_FOLDER . 'core/frontwidget.php';
				exit;
			}
		}
	}

	public function get_pagecache_stack() {
		$this->load->model('tool/nitro');

		$this->model_tool_nitro->loadCore();

		$this->load->model('catalog/category');

		$this->load->model('catalog/information');

		if (!$this->model_tool_nitro->from_admin_panel() && !$this->model_tool_nitro->from_cron_url()) return;

		$standard_urls = array(
			array(
				'base' => true
			),
			array(
				'route' => 'common/home',
				'params' => ''
			),
			array(
				'route' => 'product/special',
				'params' => ''
			),
			array(
				'route' => 'information/contact',
				'params' => ''
			)
		);

		$result = array();

		foreach ($this->model_tool_nitro->getAllStores() as $store) {
			foreach ($standard_urls as $standard_url) {
				$result[] = $this->model_tool_nitro->url($standard_url);
			}	

			$categories = $this->model_tool_nitro->getCategoriesByStoreId($store['store_id']);

			foreach ($categories as $category) {
				$result[] = $this->model_tool_nitro->url(array(
					'route' => 'product/category',
					'params' => http_build_query($category)
				));
			}

			$informations = $this->model_tool_nitro->getInformationsByStoreId($store['store_id']);

			foreach ($informations as $information) {
				$result[] = $this->model_tool_nitro->url(array(
					'route' => 'information/information',
					'params' => 'information_id=' . $information['information_id']
				));
			}
		}

		$this->response->setOutput(json_encode($result));
	}
	
	public function cron() {
		$this->load->model('tool/nitro');

		$this->model_tool_nitro->loadCore();

		if (!$this->model_tool_nitro->from_cron_url()) return;

		if (!getNitroPersistence('CRON.Remote.Delete')) return;

		$tasks = array();
		$now = time();

		if (getNitroPersistence('CRON.Remote.Delete')) {
		  $period = getNitroPersistence('PageCache.ExpireTime');
		  $period = !empty($period) ? $period : NITRO_PAGECACHE_TIME;

		  $tasks[] = '- Delete files older than ' . date('Y-m-d H:i:s', $now - $period);

		  cleanNitroCacheFolders('index.html', $period);
		}

		if (getNitroPersistence('CRON.Remote.SendEmail')) {
		  $subject =  'NitroPack Remote CRON job';
		  $message =  'Time of execution: ' . date('Y-m-d H:i:s', $now) . PHP_EOL . PHP_EOL;
		  $message .= 'Executed tasks: ' . PHP_EOL . implode(PHP_EOL, $tasks) . PHP_EOL . PHP_EOL;
		  
		  sendNitroMail(getOpenCartSetting('config_email'), $subject, $message);
		}
	}
}