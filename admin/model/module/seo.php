<?php
class ModelModuleSeo extends Controller {
	public function getProducts($data = array()) {
		$sql = "SELECT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias ua WHERE query = CONCAT('product_id=',p.product_id)) AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		if (!empty($data['filter_name'])) {
			$sql .= " AND (";
			$implode = array();
			$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));
			
			foreach ($words as $word) {
				$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
			}
			if ($implode) {
				$sql .= " " . implode(" AND ", $implode) . "";
			}
			$sql .= ")";
		}

		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}

		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}

		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}

		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
		
		if (isset($data['filter_tax_class']) && !is_null($data['filter_tax_class'])) {
			$sql .= " AND p.tax_class_id = '" . (int)$data['filter_tax_class'] . "'";
		}		
		
		if (isset($data['filter_image']) && !is_null($data['filter_image'])) {
			if ($data['filter_image']==0){
				$sql .= " AND (p.image ='no_image.png' OR p.image ='')";
			} else $sql .= " AND (p.image !='no_image.png' OR p.image !='')";
		}



		$sort_data = array(
			'pd.name',
			'p.model',
			'p.price',
			'p.quantity',
			'p.status',
			'p.sort_order'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pd.name";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}

		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalProducts($data = array()) {
		$sql = "SELECT COUNT(DISTINCT p.product_id) AS total FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id)";
	
		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		if (!empty($data['filter_name'])) {
			$sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
	
		if (!empty($data['filter_model'])) {
			$sql .= " AND p.model LIKE '" . $this->db->escape($data['filter_model']) . "%'";
		}
	
		if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
			$sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
		}
	
		if (isset($data['filter_quantity']) && !is_null($data['filter_quantity'])) {
			$sql .= " AND p.quantity = '" . (int)$data['filter_quantity'] . "'";
		}
	
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " AND p.status = '" . (int)$data['filter_status'] . "'";
		}
	
		$query = $this->db->query($sql);
	
		return $query->row['total'];
	}

	public function generateseo(){
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "product p WHERE 1 ";
		$total = $this->db->query($sql)->row['total'];
	
		$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p WHERE 1 ";
		$limit = 255;
		$pages = floor($total/$limit);
		for ($i=0;$i<($pages+1);$i++) {
			$start = ($i)*$limit;
			$sql2 = $sql." LIMIT " . (int)$start . "," . (int)$limit;
			$products = $this->db->query($sql2);
			foreach ($products->rows as $product) {
				$product_id = $product['product_id'];
				$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				$product_info = $query->row;
				if (isset($product_info['name'])) {
					$keyword = $this->url_slug($product_info['name'].'-'.$product_id).'.html';
					$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'product_id=" . (int)$product_id . "', keyword = '" . $this->db->escape($keyword) . "'");
					echo $keyword."<br>";
				}
			}
		}
	}

	public function generatemetat(){
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "product p WHERE 1 ";
		$total = $this->db->query($sql)->row['total'];
	
		$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p WHERE 1 ";
		$limit = 4;
		$pages = floor($total/$limit);
		for ($i=0;$i<($pages+1);$i++) {
			$start = ($i)*$limit;
			$sql2 = $sql." LIMIT " . (int)$start . "," . (int)$limit;
			$products = $this->db->query($sql2);
			foreach ($products->rows as $product) {
				$product_id = $product['product_id'];
				$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword, (SELECT m.name FROM " . DB_PREFIX . "manufacturer m WHERE m.manufacturer_id = p.manufacturer_id) AS manufacturer FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				$product_info = $query->row;
				
				if (isset($product_info['name'])) {
					$keyword = "Buy ".$product_info['manufacturer']." ".$product_info['name']." at Best Price Online in India | omnikart.com";
					echo $keyword."<br>";
					$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_title = '" . $this->db->escape($keyword) . "' WHERE product_id = '" . (int)$product_id . "'");
				}
			}
			echo "<br><br>";
		}
	}
	
	public function generatemetak(){
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "product p WHERE 1 ";
		$total = $this->db->query($sql)->row['total'];
	
		$sql = "SELECT p.product_id FROM " . DB_PREFIX . "product p WHERE 1 ";
		$limit = 4;
		$pages = floor($total/$limit);
		for ($i=0;$i<($pages+1);$i++) {
			$start = ($i)*$limit;
			$sql2 = $sql." LIMIT " . (int)$start . "," . (int)$limit;
			$products = $this->db->query($sql2);
			foreach ($products->rows as $product) {
				$product_id = $product['product_id'];
				$query = $this->db->query("SELECT DISTINCT *, (SELECT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'product_id=" . (int)$product_id . "') AS keyword, (SELECT m.name FROM " . DB_PREFIX . "manufacturer m WHERE m.manufacturer_id = p.manufacturer_id) AS manufacturer FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				$product_info = $query->row;
				if (isset($product_info['name'])){
					$keywords = ($product_info['manufacturer']?$product_info['manufacturer'].',':'').$product_info['name'].' Price,'.$product_info['name'].' Specs,'.$product_info['name'].' Features'.',India';
					$this->db->query("UPDATE " . DB_PREFIX . "product_description SET meta_keyword = '" . $this->db->escape($keywords) . "' WHERE product_id = '" . (int)$product_id . "'");
					echo $keywords."<br>";
				}
			}
		}
	}
	
	public function getTotalCategories() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");
	
		return $query->row['total'];
	}
	
	public function getCategories($data = array()) {
		$sql = "SELECT cp.category_id AS category_id, cd2.name as name_2, cd2.meta_title,cd2.meta_description,cd2.meta_keyword, (SELECT keyword FROM " . DB_PREFIX . "url_alias ua WHERE query = CONCAT('category_id=',cp.category_id)) AS keyword, GROUP_CONCAT(cd1.name ORDER BY cp.level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') AS name, c1.parent_id, c1.sort_order FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category c1 ON (cp.category_id = c1.category_id) LEFT JOIN " . DB_PREFIX . "category c2 ON (cp.path_id = c2.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id) LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (cp.category_id = cd2.category_id) WHERE cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'";
	
		if (!empty($data['filter_name'])) {
			$sql .= " AND cd2.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
		}
	
		$sql .= " GROUP BY cp.category_id";
	
		$sort_data = array(
				'name',
				'sort_order'
		);
	
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY sort_order";
		}
	
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
	
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
	
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
	
		$query = $this->db->query($sql);
	
		return $query->rows;
	}

	public function generatecseo(){
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "category c WHERE 1 ";
		$total = $this->db->query($sql)->row['total'];
	
		$sql = "SELECT c.category_id FROM " . DB_PREFIX . "category c WHERE 1 ";
		$limit = 255;
		$pages = floor($total/$limit);
		for ($i=0;$i<($pages+1);$i++) {
			$start = ($i)*$limit;
			$sql2 = $sql." LIMIT " . (int)$start . "," . (int)$limit;
			$categories = $this->db->query($sql2);
			foreach ($categories->rows as $category) {
				$category_id = $category['category_id'];
				$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				$category_info = $query->row;
				if (isset($category_info['name'])) {
					$keyword = $this->url_slug($category_info['name'].'-'.$category_id).'.html';
					echo $keyword."<br>";
					$this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "'");
					$this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = 'category_id=" . (int)$category_id . "', keyword = '" . $this->db->escape($keyword) . "'");
				}
			}
		}
	}
	
	public function generatecmetat(){
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "category c WHERE 1 ";
		$total = $this->db->query($sql)->row['total'];
	
		$sql = "SELECT c.category_id FROM " . DB_PREFIX . "category c WHERE 1 ";
		$limit = 255;
		$pages = floor($total/$limit);
		for ($i=0;$i<($pages+1);$i++) {
			$start = ($i)*$limit;
			$sql2 = $sql." LIMIT " . (int)$start . "," . (int)$limit;
			$categories = $this->db->query($sql2);
			foreach ($categories->rows as $category) {
				$category_id = $category['category_id'];
				$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				$category_info = $query->row;
	
				if (isset($category_info['name'])) {
					$keyword = $category_info['name'].", Buy ".$category_info['name']." Online in India - omnikart.com";
					echo $keyword."<br>";
					$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_title = '" . $this->db->escape($keyword) . "' WHERE category_id = '" . (int)$category_id . "'");
				}
			}
			echo "<br><br>";
		}
	}
	
	public function generatecmetak(){
		$sql = "SELECT count(*) as total FROM " . DB_PREFIX . "category c WHERE 1 ";
		$total = $this->db->query($sql)->row['total'];
	
		$sql = "SELECT c.category_id FROM " . DB_PREFIX . "category c WHERE 1 ";
		$limit = 255;
		$pages = floor($total/$limit);
		for ($i=0;$i<($pages+1);$i++) {
			$start = ($i)*$limit;
			$sql2 = $sql." LIMIT " . (int)$start . "," . (int)$limit;
			$categories = $this->db->query($sql2);
			foreach ($categories->rows as $category) {
				$category_id = $category['category_id'];
				$query = $this->db->query("SELECT DISTINCT *, (SELECT GROUP_CONCAT(cd1.name ORDER BY level SEPARATOR '&nbsp;&nbsp;&gt;&nbsp;&nbsp;') FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "category_description cd1 ON (cp.path_id = cd1.category_id AND cp.category_id != cp.path_id) WHERE cp.category_id = c.category_id AND cd1.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY cp.category_id) AS path, (SELECT DISTINCT keyword FROM " . DB_PREFIX . "url_alias WHERE query = 'category_id=" . (int)$category_id . "') AS keyword FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd2 ON (c.category_id = cd2.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd2.language_id = '" . (int)$this->config->get('config_language_id') . "'");
				$category_info = $query->row;
	
				if (isset($category_info['name'])) {
					$keyword = $category_info['name']." online, ".$category_info['name']." store online, buy ".$category_info['name']." online";
					echo $keyword."<br>";
					$this->db->query("UPDATE " . DB_PREFIX . "category_description SET meta_keyword = '" . $this->db->escape($keyword) . "' WHERE category_id = '" . (int)$category_id . "'");
				}
			}
		}
	}
	
	
	private function url_slug($str, $options = array()) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
	
		$defaults = array(
				'delimiter' => '-',
				'limit' => null,
				'lowercase' => true,
				'replacements' => array(),
				'transliterate' => false,
		);
	
		// Merge options
		$options = array_merge($defaults, $options);
	
		$char_map = array(
				// Latin
				'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
				'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
				'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
				'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
				'ß' => 'ss',
				'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
				'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
				'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
				'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
				'ÿ' => 'y',
				// Latin symbols
				'©' => '(c)',
				// Greek
				'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
				'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
				'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
				'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
				'Ϋ' => 'Y',
				'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
				'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
				'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
				'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
				'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
				// Turkish
				'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
				'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
				// Russian
				'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
				'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
				'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
				'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
				'Я' => 'Ya',
				'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
				'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
				'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
				'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
				'я' => 'ya',
				// Ukrainian
				'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
				'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
				// Czech
				'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
				'Ž' => 'Z',
				'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
				'ž' => 'z',
				// Polish
				'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
				'Ż' => 'Z',
				'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
				'ż' => 'z',
				// Latvian
				'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
				'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
				'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
				'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);
	
		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
	
		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}
	
		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
	
		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
	
		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
	
		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);
	
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}
}