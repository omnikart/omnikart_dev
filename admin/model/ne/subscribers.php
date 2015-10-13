<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeSubscribers extends Model {

    public function getTotal($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(firstname, ' ', lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "LCASE(email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
            $implode[] = "`store_id` = '" . (int)$data['filter_store'] . "'";
        }

        if (isset($data['filter_language']) && !is_null($data['filter_language'])) {
            if ($data['filter_language'] === '') {
                $implode[] = "language_code IS NULL";
            } else {
                $implode[] = "language_code = '" . $this->db->escape($data['filter_language']) . "'";
            }
        }

        $implode[] = "approved = '1'";

        $sql .= " LEFT JOIN " . DB_PREFIX . "ne_language_map ON " . DB_PREFIX . "customer.email = " . DB_PREFIX . "ne_language_map.c_email";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getList($data = array()) {
        $sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id)";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }

        if (!empty($data['filter_email'])) {
            $implode[] = "LCASE(c.email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "%'";
        }

        if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
            $implode[] = "c.newsletter = '" . (int)$data['filter_newsletter'] . "'";
        }

        if (!empty($data['filter_customer_group_id'])) {
            $implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
        }

        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
            $implode[] = "`store_id` = '" . (int)$data['filter_store'] . "'";
        }

        if (isset($data['filter_language']) && !is_null($data['filter_language'])) {
            if ($data['filter_language'] === '') {
                $implode[] = "language_code IS NULL";
            } else {
                $implode[] = "language_code = '" . $this->db->escape($data['filter_language']) . "'";
            }
        }

        $implode[] = "c.approved = '1'";

        $sql .= " LEFT JOIN " . DB_PREFIX . "ne_language_map ON c.email = " . DB_PREFIX . "ne_language_map.c_email WHERE cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if ($implode) {
            $sql .= " AND " . implode(" AND ", $implode);
        }

        $sort_data = array(
            'name',
            'c.email',
            'customer_group',
            'c.newsletter',
            'store_id',
            'language_code'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY name";
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

    public function subscribe($id = 0) {
        $id = (int)$id;
        if ($id > 0) {
            $sql = "UPDATE " . DB_PREFIX . "customer SET newsletter = 1 WHERE customer_id = " . $id;
            $this->db->query($sql);
        }
    }

    public function unsubscribe($id = 0) {
        $id = (int)$id;
        if ($id > 0) {
            $sql = "UPDATE " . DB_PREFIX . "customer SET newsletter = 0 WHERE customer_id = " . $id;
            $this->db->query($sql);
        }
    }

}

?>