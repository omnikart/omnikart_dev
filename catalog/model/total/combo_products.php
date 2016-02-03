<?php
class ModelTotalComboProducts extends Model {
	public function getTotal(&$total_data, &$total, &$taxes, $vendor_id = 0) {
		$this->load->language('total/combo_products');
		$this->load->model('catalog/product');
		$this->load->model('checkout/combo_products');
		
		$products_in_combo = array ();
		$products_in_combo = $this->model_checkout_combo_products->getProducts ();
		
		$products_in_cart = $this->cart->getProducts();
		$product_in_cart_id = array();
		
		foreach ($products_in_cart as $product) {
			for ($i = 0;$i<$product['quantity'];$i++) {
				$product_in_cart_id[] = $product['product_id'];
			}	
		}
		
		$amount = 0;
		$combo_name = array ();
		
		foreach ( $products_in_combo as $combo_id => $product_in_combo_id ) {
			$get_combo = $this->model_checkout_combo_products->getCombo ( $combo_id );
			while ( $this->check_exist_in_array ( $product_in_combo_id, $product_in_cart_id ) && (count ( $product_in_combo_id ) <= count ( $product_in_cart_id )) ) {
				if ($get_combo ['discount_type'] == 'fixed amount') {
					$diff = 0;
					foreach ( $product_in_combo_id as $value ) {
						$product_info = $this->model_catalog_product->getProduct ( $value );
						if ($product_info ['special'])
							$diff += $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) - $this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) );
					}
					if ($get_combo ['override'])
						$amount += $get_combo ['discount_number'] - $diff;
					else
						$amount += $get_combo ['discount_number'];
				} else {
					$price = 0;
					$diff = 0;
					foreach ( $product_in_combo_id as $value ) {
						$product_info = $this->model_catalog_product->getProduct ( $value );
						if ($product_info ['special'])
							$diff += $this->tax->calculate ( $product_info ['price'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) ) - $this->tax->calculate ( $product_info ['special'], $product_info ['tax_class_id'], $this->config->get ( 'config_tax' ) );
						$price += $product_info ['price'];
					}
					if ($get_combo ['override'])
						$amount += $price * $get_combo ['discount_number'] / 100 - $diff;
					else
						$amount += $price * $get_combo ['discount_number'] / 100;
				}
				$combo_name [] = $get_combo ['combo_name'];
				foreach ( $product_in_combo_id as $value ) {
					$key = array_search ( $value, $product_in_cart_id );
					unset ( $product_in_cart_id [$key] );
				}
			}
		}
		if ($amount) {
			$total_data [] = array (
					'code' => 'combo_products',
					'title' => $this->language->get ( 'text_combo' ) . " (" . implode ( " + ", $combo_name ) . ")",
					'value' => - $amount,
					'sort_order' => $this->config->get ( 'combo_products_sort_order' ) 
			);
			
			$total -= $amount;
		}
	}
	public function check_exist_in_array($check, $array) {
		$check_in = false;
		$flag = 0;
		foreach ( $check as $check_value ) {
			foreach ( $array as $array_value ) {
				if ($check_value == $array_value) {
					$flag ++;
					$key = array_search ( $check_value, $array );
					unset ( $array [$key] );
					break;
				}
			}
		}
		if ($flag == count ( $check )) {
			$check_in = true;
		}
		return $check_in;
	}
}	
?>
