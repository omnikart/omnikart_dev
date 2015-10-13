<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeMarketing extends Model {

    private $_name = 'ne';

    public function getList($data = array(), $for_send = false) {
        if ($this->config->get($this->_name . '_show_unloginned_subscribe') == '1') {
            $this->clean();
        }

        $sql = "SELECT *, CONCAT(firstname, ' ', lastname) AS name FROM " . DB_PREFIX . "ne_marketing";

        $implode = array();

        if (!empty($data['filter_name'])) {
            $implode[] = "LCASE(CONCAT(firstname, ' ', lastname)) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
        }

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_subscribed']) && !is_null($data['filter_subscribed'])) {
            $implode[] = "subscribed = '" . (int)$data['filter_subscribed'] . "'";
        }

        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
            $implode[] = "store_id = '" . (int)$data['filter_store'] . "'";
        }

        if (isset($data['filter_list']) && is_array($data['filter_list']) && $data['filter_list']) {
            $implode_or = array();
            foreach ($data['filter_list'] as $id) {
                if ($id) {
                    $list_data = explode('_', $id);
                    if (isset($list_data[0]) && isset($list_data[1])) {
                        $implode_or[] = "(marketing_list_id = '" . (int)$list_data[1] . "' AND store_id = '" . (int)$list_data[0] . "')";
                    }
                }
            }
            if ($implode_or) {
                $sql .= " INNER JOIN " . DB_PREFIX . "ne_marketing_to_list m2l ON (" . DB_PREFIX . "ne_marketing.marketing_id = m2l.marketing_id)";
                $implode[] = "(" . implode(" OR ", $implode_or) . ")";
            }
        }

        if ($for_send) {
            $implode[] = "NOT EXISTS (SELECT 1 FROM " . DB_PREFIX . "ne_blacklist bl WHERE bl.email = " . DB_PREFIX . "ne_marketing.email)";
        }

        $sql .= " LEFT JOIN " . DB_PREFIX . "ne_language_map ON " . DB_PREFIX . "ne_marketing.email = " . DB_PREFIX . "ne_language_map.c_email";

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        } else {
            $sql .= " WHERE 1";
        }

        $sort_data = array(
            'name',
            'email',
            'subscribed',
            'store_id'
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

        $result = array();

        foreach ($query->rows as $row) {
            $row['list'] = array();
            $query_list = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_marketing_to_list WHERE marketing_id = '" . (int)$row['marketing_id'] . "' ORDER BY marketing_list_id ASC");
            foreach ($query_list->rows as $row_list) {
                $row['list'][] = $row_list['marketing_list_id'];
            }
            $result[] = $row;
        }

        return $result;
    }

    private function clean() {
        $this->db->query("DELETE m.* FROM " . DB_PREFIX . "ne_marketing AS m INNER JOIN " . DB_PREFIX . "customer AS c ON (LCASE(m.email) = LCASE(c.email) AND m.store_id = c.store_id)");
    }

}

?>