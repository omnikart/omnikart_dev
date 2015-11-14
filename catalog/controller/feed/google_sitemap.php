<?php
class ControllerFeedGoogleSitemap extends Controller {
	private $j=0;
	private $i=0;
	private $outputd;
	private $output;
	private $indexing = array();
	public $timestamp;
	public function index() {
		if ($this->config->get('google_sitemap_status')) {
			$date = new DateTime();
			$this->timestamp = date_format($date, 'Y-m-d H:i:s');
			$i=0;
			$outputd  = '<?xml version="1.0" encoding="UTF-8"?>';
			$outputd .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';
			$this->outputd = $outputd;
			$this->load->model('catalog/product');
			$this->load->model('tool/image');
			$directory = "/home/unbeatable/public_html/opencart/sitemaps/";
			$output = $this->outputd;

			$products = $this->model_catalog_product->getProducts();
			
			foreach ($products as $product) {
				if ($product['image']) {
					$output .= '<url>';
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
				$i++;
				if ($i>200){
					$output .= '</urlset>';
					$xmlobj=new SimpleXMLElement($output);
					$xmlobj->asXML($directory.'products-'.$this->j.'.xml');
					$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/products-'.$this->j.'.xml','lastmod'=>$this->timestamp);
					$output = $this->outputd;
					$this->j++;
					$i=0;
				}
			}
			$output .= '</urlset>';
			$xmlobj=new SimpleXMLElement($output);
			$xmlobj->asXML($directory.'products-'.$this->j.'.xml');
			$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/products-'.$this->j.'.xml','lastmod'=>$this->timestamp);
			$output = $outputd;
			$this->j++;
			$i=0;
			$this->i=0;
			
			$this->output = $outputd;
			$this->load->model('catalog/category');
			$this->getCategories(0,'');
			
			$this->output .= '</urlset>';
			$xmlobj=new SimpleXMLElement($this->output);
			$xmlobj->asXML($directory.'category-'.$this->j.'.xml');
			$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/category-'.$this->j.'.xml','lastmod'=>$this->timestamp);
			$this->output = $this->outputd;
			$this->j++;
			$this->i=0;
			
			$this->load->model('catalog/manufacturer');

			$manufacturers = $this->model_catalog_manufacturer->getManufacturers();

			foreach ($manufacturers as $manufacturer) {
				$output .= '<url>';
				$output .= '<loc>' . $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $manufacturer['manufacturer_id']) . '</loc>';
				$output .= '<changefreq>weekly</changefreq>';
				$output .= '<priority>0.7</priority>';
				$output .= '</url>';
				$i++;
				if ($i>40000){
					$output .= '</urlset>';
					$xmlobj=new SimpleXMLElement($output);
					$xmlobj->asXML($directory.'manf-'.$this->j.'.xml');
					$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/manf-'.$this->j.'.xml','lastmod'=>$this->timestamp);
					$output = $this->outputd;
					$this->j++;
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
					if ($i>40000){
						$output .= '</urlset>';
						$xmlobj=new SimpleXMLElement($output);
						$xmlobj->asXML($directory.'manf-'.$this->j.'.xml');
						$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/manf-'.$this->j.'.xml','lastmod'=>$this->timestamp);
						$output = $this->outputd;
						$this->j++;
						$i=0;
					}
					
				}
			}
			$output .= '</urlset>';
			$xmlobj=new SimpleXMLElement($output);
			$xmlobj->asXML($directory.'manf-'.$this->j.'.xml');
			$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/manf-'.$this->j.'.xml','lastmod'=>$this->timestamp);
			$output = $this->outputd;
			$this->j++;
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

			$output .= '</urlset>';
			$xmlobj=new SimpleXMLElement($output);
			$xmlobj->asXML($directory.'info-'.$this->j.'.xml');
			$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/info-'.$this->j.'.xml','lastmod'=>$this->timestamp);
			$output = $this->outputd;
			$this->j++;
			$i=0;
			
			
			$output  = '<?xml version="1.0" encoding="UTF-8"?>';
			$output .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">';

			foreach ($this->indexing as $filelink) {
					$output .='<sitemap>';
					$output .='<loc>'.$filelink['loc'].'</loc>';
					$output .='<lastmod>'.$filelink['lastmod'].'</lastmod>';
					$output .='</sitemap>';
			}
			$output .= '</sitemapindex>';
			$xmlobj=new SimpleXMLElement($output);
			$xmlobj->asXML($directory.'sitemap_index'.$this->timestamp.'.xml');
			
			//$this->response->addHeader('Content-Type: application/xml');
			//$this->response->setOutput($output);
		}
	}

	protected function getCategories($parent_id, $current_path = '') {
		echo $this->i."<br>";
		$results = $this->model_catalog_category->getCategories($parent_id);
		$directory = "/home/unbeatable/public_html/opencart/sitemaps/";
		$output = true;
		foreach ($results as $result) {
			if (!$current_path) {
				$new_path = $result['category_id'];
			} else {
				$new_path = $current_path . '_' . $result['category_id'];
			}

			$this->output .= '<url>';
			$this->output .= '<loc>' . $this->url->link('product/category', 'path=' . $new_path) . '</loc>';
			$this->output .= '<changefreq>weekly</changefreq>';
			$this->output .= '<priority>0.7</priority>';
			$this->output .= '</url>';
			$this->i++;
			echo $this->i."<br>";
			if ($this->i>40000){
					$this->output .= '</urlset>';
					$xmlobj=new SimpleXMLElement($this->output);
					$xmlobj->asXML($directory.'category-'.$this->j.'.xml');
					$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/category-'.$this->j.'.xml','lastmod'=>$this->timestamp);
					$this->output = $this->outputd;
					$this->j++;
					$this->i=0;
			}
			$products = $this->model_catalog_product->getProducts(array('filter_category_id' => $result['category_id']));

			foreach ($products as $product) {
				$this->output .= '<url>';
				$this->output .= '<loc>' . $this->url->link('product/product', 'path=' . $new_path . '&product_id=' . $product['product_id']) . '</loc>';
				$this->output .= '<changefreq>weekly</changefreq>';
				$this->output .= '<priority>1.0</priority>';
				$this->output .= '</url>';
				$this->i++;
				echo $this->i."<br>";
				if ($this->i>40000){
						$this->output .= '</urlset>';
						$xmlobj=new SimpleXMLElement($this->output);
						$xmlobj->asXML($directory.'category-'.$this->j.'.xml');
						$this->indexing[] = array('loc'=>HTTP_SERVER.'sitemaps/category-'.$this->j.'.xml','lastmod'=>$this->timestamp);
						$this->output = $this->outputd;
						$this->j++;
						$this->i=0;
				}				
			}
			$this->getCategories($result['category_id'], $new_path);
		}
	}
}
