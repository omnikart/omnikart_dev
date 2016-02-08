<?php
class ModelTotalShipping extends Model {
	public function getTotal(&$total_data, &$total, &$taxes, $vendor_id = 0) {
		if ($this->cart->hasShipping() && isset($this->session->data['shipping_method'])) {
			if (!$vendor_id){
					$cost = 0;
					foreach ($this->session->data['shipping_method']['cost'] as $vendor_id => $vendor_cost){
							if ($vendor_id){
								$cost += $this->session->data['shipping_method']['cost'][$vendor_id];
							}
					}
					$total_data[] = array(
						'code'       => 'shipping',
						'title'      => $this->session->data['shipping_method']['title'],
						'value'      => $cost,
						'sort_order' => $this->config->get('shipping_sort_order')
					);
				
			} else {
				$cost = $this->session->data['shipping_method']['cost'][$vendor_id];
				$total_data[] = array(
					'code'       => 'shipping',
					'title'      => $this->session->data['shipping_method']['title'],
					'value'      => $this->session->data['shipping_method']['cost'][$vendor_id],
					'sort_order' => $this->config->get('shipping_sort_order')
				);
			}
			if ($this->session->data['shipping_method']['tax_class_id']) {
				//$tax_rates = $this->tax->getRates($this->session->data['shipping_method']['cost'][$vendor_id], $this->session->data['shipping_method']['tax_class_id']);	
				$tax_rates = $this->tax->getRates($cost, $this->session->data['shipping_method']['tax_class_id']);

				foreach ($tax_rates as $tax_rate) {
					if (!isset($taxes[$tax_rate['tax_rate_id']])) {
						$taxes[$tax_rate['tax_rate_id']] = $tax_rate['amount'];
					} else {
						$taxes [$tax_rate ['tax_rate_id']] += $tax_rate ['amount'];
					}
				}
			}
			$total += $cost;/*$this->session->data['shipping_method']['cost'][$vendor_id];*/
		}
	}
}
