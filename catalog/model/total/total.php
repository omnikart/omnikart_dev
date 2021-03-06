<?php
class ModelTotalTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes, $vendor_id = 0) {
		$this->load->language('total/total');
		$total_data[] = array(
			'code'       => 'total',
			'title'      => $this->language->get('text_total'),
			'value'      => max(0, round($total)),
			'sort_order' => $this->config->get('total_sort_order')
		);
	}
}
