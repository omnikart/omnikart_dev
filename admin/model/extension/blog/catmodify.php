<?php
class ModelExtensionBlogCatmodify extends Model {
	public function createCategory($data) {
		$this->event->trigger ( 'pre.admin.blog_category.add', $data );
		
		$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_category SET parent_id = '" . ( int ) $data ['parent_id'] . "', `top` = '" . (isset ( $data ['top'] ) ? ( int ) $data ['top'] : 0) . "', `column` = '" . ( int ) $data ['column'] . "', sort_order = '" . ( int ) $data ['sort_order'] . "', status = '" . $this->db->escape ( $data ['status'] ) . "', date_modified = NOW(), date_added = NOW()" );
		
		$category_id = $this->db->getLastId ();
		
		if (isset ( $data ['image'] )) {
			$this->db->query ( "UPDATE " . DB_PREFIX . "blog_category SET image = '" . $this->db->escape ( $data ['image'] ) . "' WHERE category_id = '" . ( int ) $category_id . "'" );
		}
		
		foreach ( $data ['cat_description'] as $language_id => $description ) {
			$query = "INSERT INTO " . DB_PREFIX . "blog_category_description SET ";
			$query .= "category_id = '" . ( int ) $category_id . "', ";
			$query .= "language_id = '" . ( int ) $language_id . "', ";
			$inc = 0;
			foreach ( $description as $key => $value ) {
				if ($inc == 0) {
					$query .= $key . "='" . $this->db->escape ( $value ) . "'";
				} else {
					$query .= ", " . $key . "='" . $this->db->escape ( $value ) . "' ";
				}
				$inc ++;
			}
			$this->db->query ( $query );
		}
		
		$level = 0;
		
		$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . ( int ) $data ['parent_id'] . "' ORDER BY `level` ASC" );
		
		foreach ( $query->rows as $result ) {
			$this->db->query ( "INSERT INTO `" . DB_PREFIX . "blog_category_path` SET `category_id` = '" . ( int ) $category_id . "', `path_id` = '" . ( int ) $result ['path_id'] . "', `level` = '" . ( int ) $level . "'" );
			
			$level ++;
		}
		
		$this->db->query ( "INSERT INTO `" . DB_PREFIX . "blog_category_path` SET `category_id` = '" . ( int ) $category_id . "', `path_id` = '" . ( int ) $category_id . "', `level` = '" . ( int ) $level . "'" );
		
		if (isset ( $data ['category_filter'] )) {
			foreach ( $data ['category_filter'] as $filter_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_category_filter SET category_id = '" . ( int ) $category_id . "', filter_id = '" . ( int ) $filter_id . "'" );
			}
		}
		
		if (isset ( $data ['category_store'] )) {
			foreach ( $data ['category_store'] as $store_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_category_to_store SET category_id = '" . ( int ) $category_id . "', store_id = '" . ( int ) $store_id . "'" );
			}
		}
		
		if (isset ( $data ['category_layout'] )) {
			foreach ( $data ['category_layout'] as $store_id => $layout_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_category_to_layout SET category_id = '" . ( int ) $category_id . "', store_id = '" . ( int ) $store_id . "', layout_id = '" . ( int ) $layout_id . "'" );
			}
		}
		
		if (isset ( $data ['keyword'] )) {
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blog_category_id=" . ( int ) $category_id . "', keyword = '" . $this->db->escape ( $data ['keyword'] ) . "'" );
		}
		
		$this->cache->delete ( 'blog_category' );
		
		$this->event->trigger ( 'post.admin.blog_category.add', $category_id );
		
		return $category_id;
	}
	public function editCategory($category_id, $data) {
		$this->event->trigger ( 'pre.admin.blog_category.edit', $data );
		
		// print_r($data); die();
		
		$this->db->query ( "UPDATE " . DB_PREFIX . "blog_category SET parent_id = '" . ( int ) $data ['parent_id'] . "', `top` = '" . (isset ( $data ['top'] ) ? ( int ) $data ['top'] : 0) . "', `column` = '" . ( int ) $data ['column'] . "', sort_order = '" . ( int ) $data ['sort_order'] . "', status = '" . $this->db->escape ( $data ['status'] ) . "', date_modified = NOW() WHERE category_id = '" . ( int ) $category_id . "'" );
		
		if (isset ( $data ['image'] )) {
			$this->db->query ( "UPDATE " . DB_PREFIX . "blog_category SET image = '" . $this->db->escape ( $data ['image'] ) . "' WHERE category_id = '" . ( int ) $category_id . "'" );
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . ( int ) $category_id . "'" );
		// print_r($data['post_description']);
		foreach ( $data ['cat_description'] as $language_id => $description ) {
			$query = "INSERT INTO " . DB_PREFIX . "blog_category_description SET ";
			$query .= "category_id = '" . ( int ) $category_id . "', ";
			$query .= "language_id = '" . ( int ) $language_id . "', ";
			$inc = 0;
			foreach ( $description as $key => $value ) {
				if ($inc == 0) {
					$query .= $key . "='" . $this->db->escape ( $value ) . "'";
				} else {
					$query .= ", " . $key . "='" . $this->db->escape ( $value ) . "' ";
				}
				$inc ++;
			}
			// print_r($query); die();
			$this->db->query ( $query );
		}
		
		// MySQL Hierarchical Data Closure Table Pattern
		$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE path_id = '" . ( int ) $category_id . "' ORDER BY level ASC" );
		
		if ($query->rows) {
			foreach ( $query->rows as $category_path ) {
				// Delete the path below the current one
				$this->db->query ( "DELETE FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . ( int ) $category_path ['category_id'] . "' AND level < '" . ( int ) $category_path ['level'] . "'" );
				
				$path = array ();
				
				// Get the nodes new parents
				$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . ( int ) $data ['parent_id'] . "' ORDER BY level ASC" );
				
				foreach ( $query->rows as $result ) {
					$path [] = $result ['path_id'];
				}
				
				// Get whats left of the nodes current path
				$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . ( int ) $category_path ['category_id'] . "' ORDER BY level ASC" );
				
				foreach ( $query->rows as $result ) {
					$path [] = $result ['path_id'];
				}
				
				// Combine the paths with a new level
				$level = 0;
				
				foreach ( $path as $path_id ) {
					$this->db->query ( "REPLACE INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . ( int ) $category_path ['category_id'] . "', `path_id` = '" . ( int ) $path_id . "', level = '" . ( int ) $level . "'" );
					
					$level ++;
				}
			}
		} else {
			// Delete the path below the current one
			$this->db->query ( "DELETE FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . ( int ) $category_id . "'" );
			
			// Fix for records with no paths
			$level = 0;
			
			$query = $this->db->query ( "SELECT * FROM `" . DB_PREFIX . "blog_category_path` WHERE category_id = '" . ( int ) $data ['parent_id'] . "' ORDER BY level ASC" );
			
			foreach ( $query->rows as $result ) {
				$this->db->query ( "INSERT INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . ( int ) $category_id . "', `path_id` = '" . ( int ) $result ['path_id'] . "', level = '" . ( int ) $level . "'" );
				
				$level ++;
			}
			
			$this->db->query ( "REPLACE INTO `" . DB_PREFIX . "blog_category_path` SET category_id = '" . ( int ) $category_id . "', `path_id` = '" . ( int ) $category_id . "', level = '" . ( int ) $level . "'" );
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_filter WHERE category_id = '" . ( int ) $category_id . "'" );
		
		if (isset ( $data ['category_filter'] )) {
			foreach ( $data ['category_filter'] as $filter_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_category_filter SET category_id = '" . ( int ) $category_id . "', filter_id = '" . ( int ) $filter_id . "'" );
			}
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_to_store WHERE category_id = '" . ( int ) $category_id . "'" );
		
		if (isset ( $data ['category_store'] )) {
			foreach ( $data ['category_store'] as $store_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_category_to_store SET  category_id = '" . ( int ) $category_id . "', store_id = '" . ( int ) $store_id . "'" );
			}
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . ( int ) $category_id . "'" );
		
		if (isset ( $data ['category_layout'] )) {
			foreach ( $data ['category_layout'] as $store_id => $layout_id ) {
				$this->db->query ( "INSERT INTO " . DB_PREFIX . "blog_category_to_layout SET category_id = '" . ( int ) $category_id . "', store_id = '" . ( int ) $store_id . "', layout_id = '" . ( int ) $layout_id . "'" );
			}
		}
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_category_id=" . ( int ) $category_id . "'" );
		
		if ($data ['keyword']) {
			$this->db->query ( "INSERT INTO " . DB_PREFIX . "url_alias SET query = 'blog_category_id=" . ( int ) $category_id . "', keyword = '" . $this->db->escape ( $data ['keyword'] ) . "'" );
		}
		
		$this->event->trigger ( 'post.admin.blog_category.edit', $category_id );
	}
	public function deleteCategory($category_id) {
		$this->event->trigger ( 'pre.admin.blog_category.delete', $category_id );
		
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category WHERE category_id = '" . ( int ) $category_id . "' LIMIT 1" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . ( int ) $category_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_filter WHERE category_id = '" . ( int ) $category_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_to_store WHERE category_id = '" . ( int ) $category_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_category_to_layout WHERE category_id = '" . ( int ) $category_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "blog_post_to_category WHERE category_id = '" . ( int ) $category_id . "'" );
		$this->db->query ( "DELETE FROM " . DB_PREFIX . "url_alias WHERE query = 'blog_category_id=" . ( int ) $category_id . "'" );
		
		$this->cache->delete ( 'blog_category' );
		
		$this->event->trigger ( 'post.admin.blog_category.delete', $category_id );
		
		return true;
	}
}