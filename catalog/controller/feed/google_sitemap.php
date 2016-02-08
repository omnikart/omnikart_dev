<?php
class ControllerFeedGoogleSitemap extends Controller {
	private $j = 0;
	private $i = 0;
	private $outputd;
	private $output;
	private $indexing = array ();
	public $timestamp;
	public $directory;
	public function index() {
		if ($this->config->get ( 'google_sitemap_status' )) {
			$this->directory = str_replace ( 'catalog', 'sitemaps', DIR_APPLICATION );
			ini_set ( 'max_execution_time', 300 );
			$date = new DateTime ();
			$this->timestamp = date_format ( $date, 'Y-m-d H:i:s' );
			
			$this->outputd = '<?xml version="1.0" encoding="UTF-8"?>';
			$this->outputd .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
			
			$this->load->model ( 'tool/image' );
			$filter_data = array (
					'filter_category_id' => '',
					'filter_sub_category' => true 
			);
			$limit = $this->config->get ( 'product_count' );
			
			$sql = $this->db->query ( "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND p.date_available <= NOW() AND p2s.store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "'" );
			
			$product_total = $sql->row ['total'];
			$counter = floor ( $product_total / $limit ) + 1;
			
			echo "Total Prodcuts Pages " . ($counter + 1) . "<br>" . "Total Prodcuts  " . ($product_total) . "<br>";
			
			for($page = 0; $page < $counter; $page ++) {
				
				$output = '';
				echo "Creating Prodcuts Image" . $page . "<br>";
				
				$filter_data = array (
						'start' => $page * $limit,
						'limit' => $limit 
				);
				
				$products = $this->getProducts ( $filter_data );
				
				foreach ( $products as $product ) {
					if ($product ['image']) {
						$output = '<url>';
						$output .= '<loc>' . $this->url->link ( 'product/product', 'product_id=' . $product ['product_id'] ) . '</loc>';
						$output .= '<changefreq>weekly</changefreq>';
						$output .= '<priority>1.0</priority>';
						$output .= '<image:image>';
						$output .= '<image:loc>' . $this->model_tool_image->resize ( $product ['image'], $this->config->get ( 'config_image_popup_width' ), $this->config->get ( 'config_image_popup_height' ) ) . '</image:loc>';
						$output .= '<image:caption>' . $product ['name'] . '</image:caption>';
						$output .= '<image:title>' . $product ['name'] . '</image:title>';
						$output .= '</image:image>';
						$output .= '</url>';
					}
				}
				
				$xmlobj = new SimpleXMLElement ( $this->outputd . $output . '</urlset>' );
				$filename = 'products_image-' . $page . '.xml';
				$xmlobj->asXML ( $this->directory . $filename );
				$this->indexing [] = HTTP_SERVER . '/sitemaps/' . $filename;
				echo "Created " . $filename . "<br>";
			}
			/*
			 * for ($page = 0; $page < $counter; $page++) {
			 *
			 * $output = '';
			 *
			 * echo "Creating Prodcuts ".$page."<br>";
			 *
			 * $filter_data=array('start'=>$page*$limit,'limit'=>$limit);
			 * $products = $this->getProducts($filter_data);
			 *
			 * foreach ($products as $product) {
			 * $output .= '<url>';
			 * $output .= '<loc>' . $this->url->link('product/product', '&product_id=' . $product['product_id']) . '</loc>';
			 * $output .= '<changefreq>weekly</changefreq>';
			 * $output .= '<priority>1.0</priority>';
			 * $output .= '</url>';
			 * }
			 *
			 * $xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			 * $filename = 'products-'.$page.'.xml';
			 * $xmlobj->asXML($this->directory.$filename);
			 * $this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			 * echo "Created ".$filename."<br>";
			 * }
			 */
			
			/* ---- Creating Categories ---- */
			$filename = 'categories.xml';
			echo "Creating " . $filename . "<br>";
			
			$this->load->model ( 'catalog/category' );
			$output = $this->getCategories ( 0, '' );
			
			$xmlobj = new SimpleXMLElement ( $this->outputd . $output . '</urlset>' );
			$xmlobj->asXML ( $this->directory . $filename );
			$this->indexing [] = HTTP_SERVER . '/sitemaps/' . $filename;
			echo "Created " . $filename . "<br>";
			/* ---- End Categories ---- */
			
			$output = '';
			$i = 0;
			
			/*
			 * $this->load->model('catalog/manufacturer');
			 * $manufacturers = $this->model_catalog_manufacturer->getManufacturers();
			 * foreach ($manufacturers as $manufacturer) {
			 * $output .= '<url>';
			 * $output .= '<loc>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>';
			 * $output .= '<changefreq>weekly</changefreq>';
			 * $output .= '<priority>0.7</priority>';
			 * $output .= '</url>';
			 * $i++;
			 * if ($i>$limit){
			 * $xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			 * $filename = 'manufacturer-'.$this->j.'.xml';
			 * $xmlobj->asXML($this->directory.$filename);
			 * $this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			 * echo "Created ".$filename."<br>";
			 * $this->j++;
			 * $output = '';
			 * $i=0;
			 * }
			 *
			 * $products = $this->getProducts(array('filter_manufacturer_id' => $manufacturer['manufacturer_id']));
			 *
			 * foreach ($products as $product) {
			 * $output .= '<url>';
			 * $output .= '<loc>' . $this->url->link('product/product', 'manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id']) . '</loc>';
			 * $output .= '<changefreq>weekly</changefreq>';
			 * $output .= '<priority>1.0</priority>';
			 * $output .= '</url>';
			 * $i++;
			 *
			 * if ($i > $limit){
			 * $xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			 * $filename = 'manufacturer-'.$this->j.'.xml';
			 * $xmlobj->asXML($this->directory.$filename);
			 * $this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			 * echo "Created ".$filename."<br>";
			 * $this->j++;
			 * $output = '';
			 * $i=0;
			 * }
			 * }
			 * }
			 *
			 * $xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			 * $filename = 'manufacturer-'.$this->j.'.xml';
			 * $xmlobj->asXML($this->directory.$filename);
			 * $this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			 * echo "Created ".$filename."<br>";
			 * $this->j++;
			 * $output = '';
			 * $i=0;
			 */
			/*
			 * $this->load->model('catalog/information');
			 *
			 * $informations = $this->model_catalog_information->getInformations();
			 *
			 * foreach ($informations as $information) {
			 * $output .= '<url>';
			 * $output .= '<loc>' . $this->url->link('information/information', 'information_id=' . $information['information_id']) . '</loc>';
			 * $output .= '<changefreq>weekly</changefreq>';
			 * $output .= '<priority>0.5</priority>';
			 * $output .= '</url>';
			 * }
			 *
			 * $xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			 * $filename = 'information-'.$this->j.'.xml';
			 * $xmlobj->asXML($this->directory.$filename);
			 * $this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			 * echo "Created ".$filename."<br>";
			 * $this->j++;
			 * $output = '';
			 * $i=0;
			 */
			/*
			 * $output = '<?xml version="1.0" encoding="UTF-8"?>';
			 * $output .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
			 *
			 * foreach ($this->indexing as $filelink) {
			 * $output .='<sitemap>';
			 * $output .='<loc>'.$filelink.'</loc>';
			 * $output .='<lastmod>'.$this->timestamp.'</lastmod>';
			 * $output .='</sitemap>';
			 * }
			 * $output .= '</sitemapindex>';
			 * $xmlobj=new SimpleXMLElement($output);
			 * $xmlobj->asXML($this->directory.'sitemap_index-'.$this->timestamp.'.xml');
			 */
		}
	}
	protected function getCategories($parent_id, $current_path = '') {
		$output = '';
		
		$results = $this->model_catalog_category->getCategories ( $parent_id );
		
		foreach ( $results as $result ) {
			if (! $current_path) {
				$new_path = $result ['category_id'];
			} else {
				$new_path = $current_path . '_' . $result ['category_id'];
			}
			
			$output .= '<url>';
			$output .= '<loc>' . $this->url->link ( 'product/category', 'path=' . $new_path ) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';
			
			$output .= $this->getCategories ( $result ['category_id'], $new_path );
		}
		
		return $output;
	}
	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, p.image, p.manufacturer_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . ( int ) $this->config->get ( 'config_language_id' ) . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . ( int ) $this->config->get ( 'config_store_id' ) . "' GROUP BY p.product_id ORDER BY p.sort_order ASC, LCASE(pd.name) ASC LIMIT " . ( int ) $data ['start'] . "," . ( int ) $data ['limit'];
		
		$query = $this->db->query ( $sql );
		
		return $query->rows;
	}
	
	/* "(SELECT GROUP_CONCAT(cd1.path_id ORDER BY level SEPARATOR '_') FROM " . DB_PREFIX . "category_path cp WHERE cp.category_id = c.category_id GROUP BY cp.category_id) AS path" */
}
