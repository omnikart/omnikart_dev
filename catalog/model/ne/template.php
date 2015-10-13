<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeTemplate extends Model {

    public function getProductsByCategoryId($category_id, $data) {
        $sql = "SELECT p.product_id as product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (p.product_id = p2c.product_id) WHERE p2c.category_id = '" . (int)$category_id . "'";

        $sql .= " ORDER BY p." . $data['sort'];
        if ($data['order']) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }
        $sql .= " LIMIT 0, " . (int)$data['limit'];

        $query = $this->db->query($sql);

        return $query->rows;
    }

    public function getPath($category_id, $language_id) {
        $query = $this->db->query("SELECT name, parent_id FROM " . DB_PREFIX . "category c LEFT JOIN " . DB_PREFIX . "category_description cd ON (c.category_id = cd.category_id) WHERE c.category_id = '" . (int)$category_id . "' AND cd.language_id = '" . (int)$language_id . "' ORDER BY c.sort_order, cd.name ASC");

        if ($query->row['parent_id']) {
            return $this->getPath($query->row['parent_id'], $language_id) . ' &raquo; ' . $query->row['name'];
        } else {
            return $query->row['name'];
        }
    }

    public function getTemplate($data) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_template AS t LEFT JOIN " . DB_PREFIX . "ne_template_data AS td ON (t.template_id = td.template_id) WHERE t.template_id = '" . (int)$data['template_id'] . "' AND td.language_id = '" . (int)$data['language_id'] . "'");

        return $query->row;
    }

    public function getProduct($data) {
        $customer_group_id = $data['customer_group_id'];

        $query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$customer_group_id . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND customer_group_id = '" . (int)$customer_group_id . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$data['language_id'] . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$data['language_id'] . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$data['language_id'] . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$data['product_id'] . "' AND pd.language_id = '" . (int)$data['language_id'] . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$data['store_id'] . "'");

        if ($query->num_rows) {
            $query->row['price'] = ($query->row['discount'] ? $query->row['discount'] : $query->row['price']);
            $query->row['rating'] = (int)$query->row['rating'];

            return $query->row;
        } else {
            return false;
        }
    }

    public function getTotalProductSpecials($data) {
        $customer_group_id = $data['customer_group_id'];

        if (isset($data['skip_out_of_stock'])) {
            $stock = " AND p.quantity > '0'";
        } else {
            $stock = '';
        }

        $query = $this->db->query("SELECT COUNT(DISTINCT ps.product_id) AS total FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1'" . $stock . " AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$data['store_id'] . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW()))");

        if (isset($query->row['total'])) {
            return $query->row['total'];
        } else {
            return 0;
        }
    }

    public function getProductSpecials($data) {
        $customer_group_id = $data['customer_group_id'];

        if (isset($data['skip_out_of_stock'])) {
            $stock = " AND p.quantity > '0'";
        } else {
            $stock = '';
        }

        $sql = "SELECT DISTINCT ps.product_id, (SELECT AVG(rating) FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = ps.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product_special ps LEFT JOIN " . DB_PREFIX . "product p ON (ps.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1'" . $stock . " AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$data['store_id'] . "' AND ps.customer_group_id = '" . (int)$customer_group_id . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) GROUP BY ps.product_id";

        $sort_data = array(
            'pd.name',
            'p.model',
            'ps.price',
            'rating',
            'p.sort_order'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
            } else {
                $sql .= " ORDER BY " . $data['sort'];
            }
        } else {
            $sql .= " ORDER BY p.sort_order";
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

        $product_data = array();

        $query = $this->db->query($sql);

        foreach ($query->rows as $result) {
            $data['product_id'] = $result['product_id'];
            $product_data[$result['product_id']] = $this->getProduct($data);
        }

        return $product_data;
    }

    public function getProducts($data) {
        $customer_group_id = $data['customer_group_id'];

        $cache = md5(http_build_query($data, '', '&'));

        $product_data = $this->cache->get('product.' . (int)$data['language_id'] . '.' . (int)$data['store_id'] . '.' . (int)$customer_group_id . '.' . $cache . (isset($data['skip_out_of_stock']) ? 'stock' : ''));

        if (!$product_data) {
            if (isset($data['skip_out_of_stock'])) {
                $stock = " AND p.quantity > '0'";
            } else {
                $stock = '';
            }

            $sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id)";

            $sql .= " WHERE pd.language_id = '" . (int)$data['language_id'] . "' AND p.status = '1'" . $stock . " AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$data['store_id'] . "' GROUP BY p.product_id";

            $sort_data = array(
                'pd.name',
                'p.model',
                'p.quantity',
                'p.price',
                'rating',
                'p.sort_order',
                'p.date_added',
                'RAND()'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
                    $sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
                } else {
                    $sql .= " ORDER BY " . $data['sort'];
                }
            } else {
                $sql .= " ORDER BY p.sort_order";
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

            $product_data = array();

            $query = $this->db->query($sql);

            foreach ($query->rows as $result) {
                $data['product_id'] = $result['product_id'];
                $product_data[$result['product_id']] = $this->getProduct($data);
            }

            $this->cache->set('product.' . (int)$data['language_id'] . '.' . (int)$data['store_id'] . '.' . (int)$customer_group_id . '.' . $cache . (isset($data['skip_out_of_stock']) ? 'stock' : ''), $product_data);
        }

        return $product_data;
    }

    public function getBestSellerProducts($data) {
        $product_data = $this->cache->get('product.bestseller.' . (int)$data['language_id'] . '.' . (int)$data['store_id'] . '.' . (int)$data['limit'] . (isset($data['skip_out_of_stock']) ? 'stock' : ''));

        if (!$product_data) {
            $product_data = array();

            if (isset($data['skip_out_of_stock'])) {
                $stock = " AND p.quantity > '0'";
            } else {
                $stock = '';
            }

            $query = $this->db->query("SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1'" . $stock . " AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$data['store_id'] . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$data['limit']);

            foreach ($query->rows as $result) {
                $data['product_id'] = $result['product_id'];
                $product_data[$result['product_id']] = $this->getProduct($data);
            }

            $this->cache->set('product.bestseller.' . (int)$data['language_id'] . '.' . (int)$data['store_id'] . '.' . (int)$data['limit'] . (isset($data['skip_out_of_stock']) ? 'stock' : ''), $product_data);
        }

        return $product_data;
    }
}

?>