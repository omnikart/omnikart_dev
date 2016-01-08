<?php 
class ModelModuleExcelportproduct extends ModelModuleExcelport {
	
	public function importXLSProductsLight($language, &$allLanguages, $file, $importLimit, $addAsNew = false) {
		
		$this->language->load('module/excelport');
		if (!is_numeric($importLimit) || $importLimit < 10 || $importLimit > 800) throw new Exception($this->language->get('excelport_import_limit_invalid'));
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		
		$this->load->model('tool/nitro');
		
		$this->load->model('account/customerpartner');
		$sellerId = $this->model_account_customerpartner->getuserseller();
		
		$progress = $this->getProgress();
		
		$progress['importedCount'] = !empty($progress['importedCount']) ? $progress['importedCount'] : 0;
		$progress['done'] = false;
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		// Create new PHPExcel object
		
		require_once(IMODULE_ROOT.'vendors/phpexcel/CustomReadFilter.php');
		
		$chunkFilter = new CustomReadFilter(array("Products" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1)), "products" => array('A', ($progress['importedCount'] + 2), 'AM', (($progress['importedCount'] + $importLimit) + 1))), true); 
		
		$madeImports = false;
		$objReader = new PHPExcel_Reader_Excel2007();
		$objReader->setReadFilter($chunkFilter);
		$objReader->setReadDataOnly(true);
		$objReader->setLoadSheetsOnly(array("Products", "products"));
		$objPHPExcel = $objReader->load($file);
		$progress['importingFile'] = substr($file, strripos($file, '/') + 1);
		$productsSheet = 0;
		
		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productsSheet);
		
		$progress['all'] = -1; //(int)(($productSheetObj->getHighestRow() - 2)/$this->productSize);
		$this->setProgress($progress);
		
		$this->load->model('catalog/product');
		
		$map = array(
			'id' => 0,
			'product_id' 		=> 1,
			'name'				=> 2,
			'model' 			=> 3,
			'sku'				=> 4,
			'price'				=> 5,
			'quantity'			=> 6,
			'minimum'			=> 7,
			'stock_status' 		=> 8,
			'shipping'			=> 9,
			'date_available'	=> 10,
			'length'			=> 11,
			'width'				=> 12,
			'height'			=> 13,
			'length_class'		=> 14,
			'weight'			=> 15,
			'weight_class'		=> 16,
			'status'			=> 17,
			'edit'			=> 18,
			'viewed' 	=>19,
			'sort_order'		=> 20
		);
		
		$source = array(0,2 + ($progress['importedCount']));
		
		$this->load->model('localisation/stock_status');
		$product_stock_statusses = $this->model_localisation_stock_status->getStockStatuses();
		
		$this->load->model('localisation/length_class');
		$product_length_classes = $this->model_localisation_length_class->getLengthClasses();
		
		$this->load->model('localisation/weight_class');
		$product_weight_classes = $this->model_localisation_weight_class->getWeightClasses();

		do {
			$safe_mode = ini_get('safe_mode'); if ((empty($safe_mode) || strtolower($safe_mode) == 'off' ) && function_exists('set_time_limit') && stripos(ini_get('disable_functions'), 'set_time_limit') === FALSE) set_time_limit(60);

			$product_name = strval($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['name']) . ($source[1]))->getValue());
			
			$product_name = $this->special_chars($product_name);
			
			if (!empty($product_name)) {
				
				$product_model = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['model']) . ($source[1]))->getValue();
				
				$id = (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['id']) . ($source[1]))->getValue());
				
				$product_id = (int)trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['product_id']) . ($source[1]))->getValue());
				
				$product_price = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['price']) . ($source[1]))->getValue();
				
				$product_price = (float)str_replace(array(' ', ','), array('', '.'), $product_price);
				
				$product_quantity = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['quantity']) . ($source[1]))->getValue());
				
				$product_minimum = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['minimum']) . ($source[1]))->getValue());
				
				$found = false;
				foreach ($product_stock_statusses as $product_stock_status) {
					if ($product_stock_status['name'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['stock_status']) . ($source[1]))->getValue()) {
						$found = true;
						$product_stock_status_id = $product_stock_status['stock_status_id'];
						break;
					}
				}
				if (!$found) $product_stock_status_id = 0;
				
				$product_shipping = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['shipping']) . ($source[1]))->getValue() == 'Yes' ? 1 : 0;
				
				$product_length = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['length']) . ($source[1]))->getValue();
				$product_length = (float)str_replace(array(' ', ','), array('', '.'), $product_length);
				
				$product_width = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['width']) . ($source[1]))->getValue();
				$product_width = (float)str_replace(array(' ', ','), array('', '.'), $product_width);
				
				$product_height = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['height']) . ($source[1]))->getValue();
				$product_height = (float)str_replace(array(' ', ','), array('', '.'), $product_height);
				
				$found = false;
				foreach ($product_length_classes as $product_length_class) {
					if ($product_length_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['length_class']) . ($source[1]))->getValue()) {
						$found = true;
						$product_length_class_id = $product_length_class['length_class_id'];
						break;
					}
				}
				if (!$found) $product_length_class_id = 0;

				$product_weight = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['weight']) . ($source[1]))->getValue();
				$product_weight = (float)str_replace(array(' ', ','), array('', '.'), $product_weight);
				
				$found = false;
				foreach ($product_weight_classes as $product_weight_class) {
					if ($product_weight_class['title'] == $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['weight_class']) . ($source[1]))->getValue()) {
						$found = true;
						$product_weight_class_id = $product_weight_class['weight_class_id'];
						break;
					}
				}
				if (!$found) $product_weight_class_id = 0;
				
				$product_status = $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['status']) . ($source[1]))->getValue() == 'Enabled' ? 1 : 0;
				
				$product_sort_order = (int)str_replace(' ', '', $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sort_order']) . ($source[1]))->getValue());
				
				$product = array(
					'id' => $id,
					'product_id' => $product_id,
					'model' => $product_model,
					'name' => '',
					'sku' => $productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sku']) . ($source[1]))->getValue(),
					'price' => $product_price,
					'quantity' => trim($product_quantity),
					'minimum' => trim($product_minimum),
					'stock_status_id' => $product_stock_status_id,
					'shipping' => $product_shipping,
					'date_available' => trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['date_available']) . ($source[1]))->getValue()),
					'length' => $product_length,
					'width' => $product_width,
					'height' => $product_height,
					'length_class_id' => $product_length_class_id,
					'weight' => $product_weight,
					'weight_class_id' => $product_weight_class_id,
					'status' => $product_status,
					'edit' => '',
					'viewed' => '',
					'sort_order' => trim($productSheetObj->getCell(PHPExcel_Cell::stringFromColumnIndex($source[0] + $map['sort_order']) . ($source[1]))->getValue()),

				);
				if (!empty($product_id)) {
					$exists = false;
					$existsQuery = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product WHERE product_id = ".$product_id);
					$exists = $existsQuery->num_rows > 0;
					if ($exists) { 
							
						$existsQuery = $this->db->query("SELECT id FROM " . DB_PREFIX . "customerpartner_to_product WHERE product_id = " . $product_id . " AND id = ". $id . " AND customer_id = ".$sellerId);
						
						$exists = $existsQuery->num_rows > 0;
						
						
						if ($exists) {
							$this->editProduct($id, $product, $allLanguages, true);
							$this->model_tool_nitro->clearProductCache($product_id);
						}
					}
				}
				
				$progress['current']++;
				$progress['importedCount']++;
				$madeImports = true;
				$this->setProgress($progress);
			}
			$source[1] += 1;
		} while (!empty($product_name));
		
		$progress['done'] = true;
		if (!$madeImports) {
			$progress['importedCount'] = 0;
			array_shift($this->session->data['uploaded_files']);
		}
		$this->setProgress($progress);
		$this->config->set('config_language_id', $default_language);
	}

	
	public function exportXLSProductsLight($language, $store, $destinationFolder = '', $productNumber, $export_filters = array()) {
		$this->language->load('module/excelport');
		$this->folderCheck($destinationFolder);
		
		$progress = $this->getProgress();
		$progress['done'] = false;
		
		$this->load->model('account/customerpartner');
		$sellerId = $this->model_account_customerpartner->getuserseller();
		
		$file = IMODULE_ROOT . 'vendors/excelport/template_product_light_supplier.xlsx';
		
		$default_language = $this->config->get('config_language_id');
		$this->config->set('config_language_id', $language);
		require_once(IMODULE_ROOT.'vendors/phpexcel/PHPExcel.php');
		
		if (!empty($progress['populateAll'])) {
			$all = $this->db->query($this->getQuery($export_filters, $store, $sellerId, $language, true));
			$progress['all'] = $all->num_rows ? (int)$all->row['count'] : 0;
			unset($progress['populateAll']);
			$this->setProgress($progress);
		}
		
		
		$this->setData('Products', $destinationFolder, $language);
		
		$productsSheet = 0;
		$metaSheet = 1;

		$stockStatesStart = array(2,2);
		$this->load->model('localisation/stock_status');
		$stockStates = $this->model_localisation_stock_status->getStockStatuses();
		
		$lengthClassesStart = array(3,2);
		$this->load->model('localisation/length_class');
		$lengthClasses = $this->model_localisation_length_class->getLengthClasses();
		
		$weightClassesStart = array(4,2);
		$this->load->model('localisation/weight_class');
		$weightClasses = $this->model_localisation_weight_class->getWeightClasses();
		
		$storesStart = array(9,3);
		$this->load->model('setting/store');
		$stores = array_merge(array(0 => array('store_id' => 0, 'name' => 'Default', 'url' => NULL, 'ssl' => NULL)),$this->model_setting_store->getStores());
		
		$generals = array(
			'id' => 0,
			'product_id' 		=> 1,
			'name'				=> 2,
			'model' 			=> 3,
			'sku'				=> 4,
			'price'				=> 5,
			'quantity'			=> 6,
			'minimum'			=> 7,
			'stock_status' 		=> 8,
			'shipping'			=> 9,
			'date_available'	=> 10,
			'length'			=> 11,
			'width'				=> 12,
			'height'			=> 13,
			'length_class'		=> 14,
			'weight'			=> 15,
			'weight_class'		=> 16,
			'status'			=> 17,
			'edit'			=> 18,
			'viewed' 	=>19,
			'sort_order'		=> 20
		);
		
		// Extra fields
		$extras = array();
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['name']) && !empty($extra['column_light'])) {
				$extras[$extra['name']] = $extra['column_light'];
			}
		}
		
		$dataValidations = array(
			array(
				'type' => 'list',
				'field' => $generals['stock_status'],
				'data' => array($stockStatesStart[0], $stockStatesStart[1], $stockStatesStart[0], $stockStatesStart[1] + count($stockStates) - 1),
				'range' => '',
				'count' => count($stockStates)
			),
			array(
				'type' => 'list',
				'field' => $generals['shipping'],
				'data' => array(1,2,1,3),
				'range' => ''
			),
			array(
				'type' => 'list',
				'field' => $generals['length_class'],
				'data' => array($lengthClassesStart[0], $lengthClassesStart[1], $lengthClassesStart[0], $lengthClassesStart[1] + count($lengthClasses) - 1),
				'range' => '',
				'count' => count($lengthClasses)
			),
			array(
				'type' => 'list',
				'field' => $generals['weight_class'],
				'data' => array($weightClassesStart[0], $weightClassesStart[1], $weightClassesStart[0], $weightClassesStart[1] + count($weightClasses) - 1),
				'range' => '',
				'count' => count($weightClasses)
			),
			array(
				'type' => 'list',
				'field' => $generals['status'],
				'data' => array(5,2,5,4),
				'range' => ''
			),
		);
		
		$target = array(0,2);
		
		$this->load->model('localisation/language');
		$languageQuery = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		$name = 'products_excelport_light_' . $languageQuery['code'] . '_' . str_replace('/', '_', substr(DIR_APPLICATION, 7, strlen(DIR_APPLICATION) - 8)) . '_' . date("Y-m-d_H-i-s") . '_' . $progress['current'];
		$resultName = $name . '.xlsx';
		$result = $destinationFolder . '/' . $name . '.xlsx';

		$objPHPExcel = PHPExcel_IOFactory::load($file);
		
		// Set document properties
		$objPHPExcel->getProperties()
					->setCreator($this->customer->getFirstName())
					->setLastModifiedBy($this->customer->getFirstName())
					->setTitle($name)
					->setSubject($name)
					->setDescription("Backup for Office 2007 and later, generated using PHPExcel and ExcelPort.")
					->setKeywords("office 2007 2010 2013 xlsx openxml php phpexcel excelport")
					->setCategory("Backup");
		
		$objPHPExcel->getDefaultStyle()->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		
		$metaSheetObj = $objPHPExcel->setActiveSheetIndex($metaSheet);
		
		for ($i = 0; $i < count($stockStates); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($stockStatesStart[0]) . ($stockStatesStart[1] + $i), $stockStates[$i]['name'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($lengthClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($lengthClassesStart[0]) . ($lengthClassesStart[1] + $i), $lengthClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}
		for ($i = 0; $i < count($weightClasses); $i++) {
			$metaSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($weightClassesStart[0]) . ($weightClassesStart[1] + $i), $weightClasses[$i]['title'], PHPExcel_Cell_DataType::TYPE_STRING);
		}

		$this->load->model('catalog/product');
		
		$extra_select = "";
		
		$this->db->query("SET SESSION group_concat_max_len = 1000000;");
		$products = $this->db->query($this->getQuery($export_filters, $store, $sellerId, $language) . " ORDER BY p.product_id LIMIT ". $progress['current'] . ", " . $productNumber);
		
		$productSheetObj = $objPHPExcel->setActiveSheetIndex($productsSheet);
		foreach ($this->extraGeneralFields['Products'] as $extra) {
			if (!empty($extra['title']) && !empty($extra['column_light'])) {
				$productSheetObj->setCellValueExplicit($extra['column_light'] . '1', $extra['title'], PHPExcel_Cell_DataType::TYPE_STRING);
			}
		}
		if ($products->num_rows > 0) {
			foreach ($products->rows as $myProductIndex => $row) {
				$this->getData('Products', $row);
				// Prepare data

				foreach ($stockStates as $stockStatus) {
					if ($stockStatus['stock_status_id'] == $row['stock_status_id']) { $row['stock_status'] = $stockStatus['name']; }
					//if ($stockStatus['stock_status_id'] == $this->config->get('config_stock_status_id')) { $defaultStockStatus = $stockStatus['name']; }	
				}
				$defaultStockStatus = '';
				if (empty($defaultStockStatus) && !empty($stockStates[0]['name'])) {
						$defaultStockStatus = $stockStates[0]['name'];
				}
        
				if (empty($row['stock_status'])) $row['stock_status'] = $defaultStockStatus;
				
				$row['shipping'] = empty($row['shipping']) ? 'No' : 'Yes';
				
				$row['length_class'] = !empty($lengthClasses[0]['title']) ? $lengthClasses[0]['title'] : '';
				foreach ($lengthClasses as $lengthClass) {
					if ($lengthClass['length_class_id'] == $row['length_class_id']) { $row['length_class'] = $lengthClass['title']; break; }
				}
				
				$row['weight_class'] = !empty($weightClasses[0]['title']) ? $weightClasses[0]['title'] : '';
				foreach ($weightClasses as $weightClass) {
					if ($weightClass['weight_class_id'] == $row['weight_class_id']) { $row['weight_class'] = $weightClass['title']; break; }
				}
				$row['sort_order'] = empty($row['sort_order']) ? '0' : $row['sort_order'];
				
				$row['status'] = empty($row['status']) ? 'Disabled' : (($row['status']=='1')?'Enabled':'Pending Approval');
				
				if (empty($row['name'])) $row['name'] = '-';
				$row['name'] = $this->entity_decode($row['name']);
				
				// Extras
				foreach ($extras as $name => $position) {
					$productSheetObj->setCellValueExplicit($position . ($target[1]), empty($row[$name]) ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}
				
				// General
				foreach ($generals as $name => $position) {
					$productSheetObj->setCellValueExplicit(PHPExcel_Cell::stringFromColumnIndex($target[0] + $position) . ($target[1]), empty($row[$name]) && $row[$name] !== '0' ? '' : $row[$name], PHPExcel_Cell_DataType::TYPE_STRING);
				}

				// Data validations
				foreach ($dataValidations as $dataValidationIndex => $dataValidation) {
					if (isset($dataValidations[$dataValidationIndex]['count']) && $dataValidations[$dataValidationIndex]['count'] == 0) continue;
					$dataValidations[$dataValidationIndex]['range'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field']) . ($target[1]);
					if (empty($dataValidations[$dataValidationIndex]['root'])) $dataValidations[$dataValidationIndex]['root'] = PHPExcel_Cell::stringFromColumnIndex($target[0] + $dataValidation['field']) . ($target[1]);
				}
				
				$target[1] = $target[1] + 1;
				$progress['current']++;
				$progress['memory_get_usage'] = round(memory_get_usage(true)/(1024*1024));
				$progress['percent'] = 100 / ($products->num_rows / $progress['current']);
				
				$this->setProgress($progress);
			}
			$productSheetObj->protectCells(PHPExcel_Cell::stringFromColumnIndex($target[0] + $generals['id']).'2:'.PHPExcel_Cell::stringFromColumnIndex($target[0] + $generals['model']).$target[1], 'PHP');
			
			$productSheetObj->getStyle(PHPExcel_Cell::stringFromColumnIndex($target[0] + $generals['sku']).'2:'.PHPExcel_Cell::stringFromColumnIndex($target[0] + $generals['status']).$target[1], 'PHP')->getProtection()
->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);

			$productSheetObj->getProtection()->setSheet(true);
			
			foreach ($dataValidations as $dataValidationIndex => $dataValidation) {
				if (isset($dataValidations[$dataValidationIndex]['count']) && $dataValidations[$dataValidationIndex]['count'] == 0) continue;
				if ($dataValidations[$dataValidationIndex]['range'] != $dataValidations[$dataValidationIndex]['root']) {
					$dataValidations[$dataValidationIndex]['range'] = $dataValidations[$dataValidationIndex]['root'] . ':' . $dataValidations[$dataValidationIndex]['range'];
				}
			}
			
			//Apply data validation for:
			// Generals
			foreach ($dataValidations as $dataValidation) {
				$range = trim($dataValidation['range']);
				if (isset($dataValidation['count']) && $dataValidation['count'] == 0) continue;
				if ($dataValidation['type'] == 'list' && !empty($dataValidation['root']) && !empty($range)) {
					$objValidation = $productSheetObj->getCell($dataValidation['root'])->getDataValidation();
					$objValidation->setType( PHPExcel_Cell_DataValidation::TYPE_LIST );
					$objValidation->setErrorStyle( PHPExcel_Cell_DataValidation::STYLE_INFORMATION );
					$objValidation->setAllowBlank(false);
					$objValidation->setShowInputMessage(true);
					$objValidation->setShowErrorMessage(true);
					$objValidation->setShowDropDown(true);
					$objValidation->setErrorTitle('Input error');
					$objValidation->setError('Value is not in list.');
					$objValidation->setPromptTitle('Pick from list');
					$objValidation->setPrompt('Please pick a value from the drop-down list.');
					$objValidation->setFormula1($metaSheetObj->getTitle() . '!$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][0]) . '$' . ($dataValidation['data'][1]) . ':$' . PHPExcel_Cell::stringFromColumnIndex($dataValidation['data'][2]) . '$' . ($dataValidation['data'][3]));
					$productSheetObj->setDataValidation($range, $objValidation);
				}
			}
			
			unset($objValidation);
		} else {
			$progress['done'] = true;
		}
		
		$this->config->set('config_language_id', $default_language);
		
		$this->session->data['generated_file'] = $result;
		$this->session->data['generated_files'][] = $resultName;
		$this->setProgress($progress);
		
		try {
			$safe_mode = ini_get('safe_mode'); if ((empty($safe_mode) || strtolower($safe_mode) == 'off' ) && function_exists('set_time_limit') && stripos(ini_get('disable_functions'), 'set_time_limit') === FALSE) set_time_limit(60);
			
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->setPreCalculateFormulas(false);
			
			$objWriter->save($result);
			
			$progress['done'] = true;
		} catch (Exception $e) {
			$progress['message'] = $e->getMessage();
			$progress['error'] = true;
			$progress['done'] = false;
			$this->setProgress($progress);
		}
		$objPHPExcel->disconnectWorksheets();
		unset($metaSheetObj);
		unset($objWriter);
		unset($productSheetObj);
		unset($objPHPExcel);
		
		$progress['done'] = true;
		$this->setProgress($progress);
		
		return true;
	}
	
	public function getQuery($filters = array(), $store = 0, $sellerId, $language = 1, $count = false) {
				if ($count){
					$query = "SELECT count(*) AS count FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN ".DB_PREFIX."customerpartner_to_product cp2p ON (p.product_id = cp2p.product_id) WHERE cp2p.customer_id = " . (int)$sellerId;
				} else {
					$query = "SELECT cp2p.id, cp2p.edit, cp2p.viewed, p.sort_order, p.product_id, pd.name AS name, pd.description, pd.meta_title, pd.meta_description, pd.meta_keyword, pd.tag, p.model, cp2p.sku, p.upc, p.ean, p.jan, p.isbn, p.mpn, p.location, cp2p.quantity, IFNULL((SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = cp2p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "'), 'Not in Stock') AS stock_status, p.image, p.manufacturer_id, m.name AS manufacturer, IFNULL((SELECT cppd.price FROM " . DB_PREFIX . "cp_product_discount cppd WHERE (cppd.id = cp2p.id) AND (cppd.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AND (cppd.quantity = '1') AND ((cppd.date_start = '0000-00-00' OR cppd.date_start < NOW()) AND (cppd.date_end = '0000-00-00' OR cppd.date_end > NOW())) ORDER BY cppd.priority ASC, cppd.price ASC LIMIT 1),cp2p.price) AS cprice, p.price, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, p.points, p.tax_class_id, cp2p.date_available, IF((cp2p.weight=0),p.weight,cp2p.weight) AS weight, IF((cp2p.weight_class_id=0),p.weight_class_id,cp2p.weight_class_id) AS weight_class_id, IF((cp2p.length=0),p.length,cp2p.length) AS length, IF((cp2p.width=0),p.width,cp2p.width) AS width, IF((cp2p.height=0),p.height,cp2p.height) AS height, IF((cp2p.length_class_id=0),p.length_class_id,cp2p.length_class_id) AS length_class_id, p.subtract, (SELECT ROUND(AVG(rating)) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, IFNULL((SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id),0) AS reviews, IF((cp2p.minimum=0), p.minimum,cp2p.minimum) AS minimum, p.sort_order, cp2p.status AS status, cp2p.date_added, cp2p.date_modified, cp2p.viewed, cp2p.shipping, cp2p.stock_status_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) LEFT JOIN ".DB_PREFIX."customerpartner_to_product cp2p ON (p.product_id = cp2p.product_id) WHERE cp2p.customer_id = " . (int)$sellerId . " AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY p.product_id ";
				}
				return $query;
	}

	public function editProduct($id, $data, &$languages, $light = false) {
		$extra_select = '';
		
		$generals = array('sku','price','quantity','stock_status_id','shipping','date_available','length','width','height','length_class_id','weight','weight_class_id','status','sort_order');
		
		$implode = array();
		
		foreach ($generals as $general) {
			if (isset($data[$general])) {
				$implode[] = $general."='".$data[$general]."'";
			}
		}
		
		$this->db->query("UPDATE " . DB_PREFIX . "customerpartner_to_product SET " . implode(',',$implode) . ", date_modified = NOW() WHERE id = '" . (int)$id . "'");

		$this->cache->delete('product');
	}
	

	public function addProductLanguages(&$data, &$allLanguages) {
		// Add Product Description Languages
		if (!empty($data['product_description'])) {
			$entered_keys = array_keys($data['product_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['product_description'][$language['language_id']] = array(
						'name' => $data['product_description'][$entered_keys[0]]['name'],
						'meta_description' => '',
						'meta_keyword' => '',
						'meta_title' => '',
						'description' => '',
						'tag' => ''
					);
				}
			}
		}
		// Add Product Attributes Languages
		if (!empty($data['product_attribute'])) {
			$entered_keys = array_keys($data['product_attribute'][0]['product_attribute_description']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$current_keys = array_keys($data['product_attribute']);
					foreach ($current_keys as $data_product_attribute_id) {
						$data['product_attribute'][$data_product_attribute_id]['product_attribute_description'][$language['language_id']] = array(
							'text' => ''
						);
					}
				}
			}
		}
		// Add Product Description Languages
		if (!empty($data['product_tag'])) {
			$entered_keys = array_keys($data['product_tag']);
			foreach ($allLanguages as $language) {
				if (!in_array($language['language_id'], $entered_keys)) {
					$data['product_tag'][$language['language_id']] = '';
				}
			}
		}
	}
	
	public function deleteProducts() {
		$this->load->model('catalog/product');
		
		$ids = $this->db->query("SELECT product_id FROM " . DB_PREFIX . "product p");
		
		foreach ($ids->rows as $row) {
			$this->model_catalog_product->deleteProduct($row['product_id']);	
		}
	}
}
?>
