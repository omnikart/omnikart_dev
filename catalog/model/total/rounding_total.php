<?php
class ModelTotalRoundingTotal extends Model {
	public function getTotal(&$total_data, &$total, &$taxes) {
		$this->load->language ( 'total/total' );
		$total_data [] = array (
				'code' => 'rounding_total',
				'title' => 'Rounding Adjust',
				'text' => $this->currency->format ( round ( $total ) - $total ),
				'value' => round ( $total ) - $total,
				'sort_order' => $this->config->get ( 'rounding_total_sort_order' ) 
		);
	}
}
