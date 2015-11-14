<?php
class ControllerFeedGoogleSitemap extends Controller {
	private $j=0;
	private $i=0;
	private $outputd;
	private $output;
	private $indexing = array();
	public $timestamp;
	public $directory = DIR_APPLICATION."../sitemaps/";

	public function index() {
		if ($this->config->get('google_sitemap_status')) {
			$date = new DateTime();
			$this->timestamp = date_format($date, 'Y-m-d H:i:s');
			
			$this->outputd  = '<?xml version="1.0" encoding="UTF-8"?>';
			$this->outputd .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
			
			
			
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			
			

			$limit = $this->config->get('product_count');
			$product_total = $this->model_catalog_product->getTotalProducts();
			
			$counter = floor($product_total/$limit)+1;
			
			for ($page = 0; $page < $counter; $page++)	{
				
				$output = '';
				
				$filter_data=array('start'=>($page - 1) * $limit,'limit'=>$limit);
				$products = $this->model_catalog_product->getProducts($filter_data);

				foreach ($products as $product) {
					if ($product['image']) {
						$output  = '<url>';
						$output .= '<loc>' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . '</loc>';
						$output .= '<changefreq>weekly</changefreq>';
						$output .= '<priority>1.0</priority>';
						$output .= '<image:image>';
						$output .= '<image:loc>' . $this->model_tool_image->resize($product['image'], $this->config->get('config_image_popup_width'), $this->config->get('config_image_popup_height')) . '</image:loc>';
						$output .= '<image:caption>' . $product['name'] . '</image:caption>';
						$output .= '<image:title>' . $product['name'] . '</image:title>';
						$output .= '</image:image>';
						$output .= '</url>';
					}
				}
				$xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
				$filename = 'products-'.$page.'.xml';
				$xmlobj->asXML($this->directory.$filename);
				$this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
				echo "Created ".$filename."<br>";
			}
			
			
			$this->load->model('catalog/category');
			$count = 0;
			$this->getCategories(0,'',$count,$output);
			$this->j++;
			$xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			$filename = 'categories-'.$this->j.'.xml';
			$xmlobj->asXML($this->directory.$filename);
			$this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			echo "Created ".$filename."<br>";
			
			
			$output = '';
			$i=0;

			$this->load->model('catalog/manufacturer');

			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';
				$i++;
				if ($i>$limit){
					$xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
					$filename = 'manufacturer-'.$this->j.'.xml';
					$xmlobj->asXML($this->directory.$filename);
					$this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
					echo "Created ".$filename."<br>";
					$this->j++;
					$output = '';
					$i=0;
				}
				
				$products = $this->model_catalog_product->getProducts(array('filter_manufacturer_id' => $manufacturer['manufacturer_id']));

				foreach ($products as $product) {
					$output .= '<url>';
					$output .= '<loc>' . $this->url->link('product/product', 'manufacturer_id=' . $manufacturer['manufacturer_id'] . '&product_id=' . $product['product_id']) . '</loc>';
					$output .= '<changefreq>weekly</changefreq>';
					$output .= '<priority>1.0</priority>';
					$output .= '</url>';
					$i++;
					
					if ($i > $limit){
						$xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
						$filename = 'manufacturer-'.$this->j.'.xml';
						$xmlobj->asXML($this->directory.$filename);
						$this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
						echo "Created ".$filename."<br>";
						$this->j++;
						$output = '';
						$i=0;
					}
				}
			}
			
			$xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			$filename = 'manufacturer-'.$this->j.'.xml';
			$xmlobj->asXML($this->directory.$filename);
			$this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			echo "Created ".$filename."<br>";
			$this->j++;
			$output = '';
			$i=0;
			
			$this->load->model('catalog/information');

			$informations = $this->model_catalog_information->getInformations();

			foreach ($informations as $information) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('information/information', 'information_id=' . $information['information_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.5</priority>';
				$output .= '</url>';
			}

			$xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
			$filename = 'information-'.$this->j.'.xml';
			$xmlobj->asXML($this->directory.$filename);
			$this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
			echo "Created ".$filename."<br>";
			$this->j++;
			$output = '';
			$i=0;
						
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			foreach ($this->indexing as $filelink) {
					$output .='<sitemap>';
					$output .='<loc>'.$filelink.'</loc>';
					$output .='<lastmod>'.$this->timestamp.'</lastmod>';
					$output .='</sitemap>';
			}
			$output .= '</sitemapindex>';
			$xmlobj=new SimpleXMLElement($output);
			$xmlobj->asXML($this->directory.'sitemap_index-'.$this->timestamp.'.xml');
		}
	}

	protected function getCategories($parent_id, $current_path = '', &$count, &$output) {
		$results = $this->model_catalog_category->getCategories($parent_id);
		$limit = $this->config->get('product_count');

		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}

			$output .= '<url>';
			$output .= '<loc>' . $this->url->link('product/category', 'path=' . $new_path) . '</loc>';
			$output .= '<changefreq>weekly</changefreq>';
			$output .= '<priority>0.7</priority>';
			$output .= '</url>';
			
			$count++;

			$products = $this->model_catalog_product->getProducts(array('filter_category_id' => $result['category_id']));

			foreach ($products as $product) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('product/product', 'path=' . $new_path . '&product_id=' . $product['product_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>1.0</priority>';
				$output .= '</url>';
				
				$count++;
			}
			
			if ($count > $limit) {
					$count = 0;
					$this->j++;
					$xmlobj=new SimpleXMLElement($this->outputd.$output.'</urlset>');
					$filename = 'categories-'.$this->j.'.xml';
					$xmlobj->asXML($this->directory.$filename);
					$this->indexing[] = HTTP_SERVER.'/sitemaps/'.$filename;
					echo "Created ".$filename."<br>";
					$output = '';
			}
			$this->getCategories($result['category_id'], $new_path,$count,$output);
		}
	}
}
