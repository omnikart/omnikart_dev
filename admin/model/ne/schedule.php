<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeSchedule extends Model {

    public function getTotal($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "ne_schedule` WHERE 1=1";

        if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
            $sql .= " AND LCASE(name) LIKE '" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
        }

        if (isset($data['filter_active']) && !is_null($data['filter_active'])) {
            $sql .= " AND `active` = '" . (int)$data['filter_active'] . "'";
        }

        if (isset($data['filter_recurrent']) && !is_null($data['filter_recurrent'])) {
            $sql .= " AND `recurrent` = '" . (int)$data['filter_recurrent'] . "'";
        }

        if (isset($data['filter_to']) && !is_null($data['filter_to'])) {
            $sql .= " AND `to` = '" . $this->db->escape($data['filter_to']) . "'";
        }

        if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
            $sql .= " AND `store_id` = '" . (int)$data['filter_store'] . "'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getList($data = array()) {
        if ($data) {
            $sql = "SELECT `schedule_id`, `recurrent`, `active`, `name`, `to`, `store_id` FROM " . DB_PREFIX . "ne_schedule WHERE 1=1";

            if (isset($data['filter_name']) && !is_null($data['filter_name'])) {
                $sql .= " AND LCASE(name) LIKE '" . $this->db->escape(mb_strtolower($data['filter_name'], 'UTF-8')) . "%'";
            }

            if (isset($data['filter_active']) && !is_null($data['filter_active'])) {
                $sql .= " AND `active` = '" . (int)$data['filter_active'] . "'";
            }

            if (isset($data['filter_recurrent']) && !is_null($data['filter_recurrent'])) {
                $sql .= " AND `recurrent` = '" . (int)$data['filter_recurrent'] . "'";
            }

            if (isset($data['filter_to']) && !is_null($data['filter_to'])) {
                $sql .= " AND `to` = '" . $this->db->escape($data['filter_to']) . "'";
            }

            if (isset($data['filter_store']) && !is_null($data['filter_store'])) {
                $sql .= " AND `store_id` = '" . (int)$data['filter_store'] . "'";
            }

            $sort_data = array(
                'schedule_id',
                'active',
                'recurrent',
                'name',
                'to',
                'store_id'
            );

            if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
                $sql .= " ORDER BY `" . $data['sort'] . "`";
            } else {
                $sql .= " ORDER BY datetime";
            }

            if (isset($data['order']) && ($data['order'] == 'ASC')) {
                $sql .= " ASC";
            } else {
                $sql .= " DESC";
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

            $results = $query->rows;

            return $results;
        } else {
            return false;
        }
    }

    public function save($data) {
        if (isset($data['id']) && $data['id']) {
            $this->db->query("UPDATE " . DB_PREFIX . "ne_schedule SET active = '" . (int)$data['active'] . "', `name` = '" . $this->db->escape($data['name']) . "', `date` = '" . $this->db->escape(isset($data['date']) ? $data['date'] : 0) . "', `date_next` = '" . $this->db->escape(isset($data['date_next']) ? $data['date_next'] : 0) . "', `marketing_list` = '" . $this->db->escape(isset($data['marketing_list']) ? serialize($data['marketing_list']) : '') . "', `defined_products` = '" . $this->db->escape(isset($data['defined_product']) ? serialize($data['defined_product']) : '') . "', `defined_products_more` = '" . $this->db->escape(isset($data['defined_product_more']) ? serialize($data['defined_product_more']) : '') . "', `skip_out_of_stock` = '" . $this->db->escape(isset($data['skip_out_of_stock']) ? serialize($data['skip_out_of_stock']) : '') . "', `defined_categories` = '" . $this->db->escape(isset($data['defined_category']) ? serialize($data['defined_category']) : '') . "', `defined_category_limit` = '" . (int)$data['defined_category_limit'] . "', `defined_category_sort` = '" . $this->db->escape($data['defined_category_sort']) . "', `defined_category_order` = '" . (int)$data['defined_category_order'] . "', `customer` = '" . $this->db->escape((isset($data['customer']) && $data['to'] == 'customer') ? serialize($data['customer']) : '') . "', `affiliate` = '" . $this->db->escape((isset($data['affiliate']) && $data['to'] == 'affiliate') ? serialize($data['affiliate']) : '') . "', `product` = '" . $this->db->escape((isset($data['product']) && $data['to'] == 'product') ? serialize($data['product']) : '') . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `customer_group_only_subscribers` = '" . (isset($data['customer_group_only_subscribers']) ? (int)$data['customer_group_only_subscribers'] : '0') . "', `only_selected_language` = '" . (isset($data['only_selected_language']) ? (int)$data['only_selected_language'] : '0') . "', `time` = '" . ((int)$data['recurrent'] ? (int)$data['rtime'] : (int)$data['time']) . "', `day` = '" . (int)(isset($data['day']) ? $data['day'] : 0)  . "', `frequency` = '" . (int)(isset($data['frequency']) ? $data['frequency'] : 0) . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', `currency` = '" . $this->db->escape($data['currency']) . "', template_id = '" . (int)$data['template_id'] . "', `to` = '" . $this->db->escape($data['to']) . "', `subject` = '" . $this->db->escape($data['subject']) . "', `message` = '" . $this->db->escape($data['message']) . "', `text_message` = '" . $this->db->escape($data['text_message']) . "', `text_message_products` = '" . (int)$data['text_message_products'] . "', `recurrent` = '" . (int)$data['recurrent'] . "', `random` = '" . (int)$data['random'] . "', `defined` = '" . (int)$data['defined'] . "', categories = '" . (int)$data['defined_categories'] . "', `random_count` = '" . ($data['random_count'] ? (int)$data['random_count'] : 8) . "', special = '" . (int)$data['special'] . "', latest = '" . (int)$data['latest'] . "', popular = '" . (int)$data['popular'] . "' WHERE schedule_id = '" . (int)$data['id'] . "'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "ne_schedule SET active = '" . (int)$data['active'] . "', `name` = '" . $this->db->escape($data['name']) . "', `date` = '" . $this->db->escape(isset($data['date']) ? $data['date'] : 0) . "', `date_next` = '" . $this->db->escape(isset($data['date_next']) ? $data['date_next'] : 0) . "', `marketing_list` = '" . $this->db->escape(isset($data['marketing_list']) ? serialize($data['marketing_list']) : '') . "', `defined_products` = '" . $this->db->escape(isset($data['defined_product']) ? serialize($data['defined_product']) : '') . "', `defined_products_more` = '" . $this->db->escape(isset($data['defined_product_more']) ? serialize($data['defined_product_more']) : '') . "', `skip_out_of_stock` = '" . $this->db->escape(isset($data['skip_out_of_stock']) ? serialize($data['skip_out_of_stock']) : '') . "', `defined_categories` = '" . $this->db->escape(isset($data['defined_category']) ? serialize($data['defined_category']) : '') . "', `defined_category_limit` = '" . (int)$data['defined_category_limit'] . "', `defined_category_sort` = '" . $this->db->escape($data['defined_category_sort']) . "', `defined_category_order` = '" . (int)$data['defined_category_order'] . "', `customer` = '" . $this->db->escape((isset($data['customer']) && $data['to'] == 'customer') ? serialize($data['customer']) : '') . "', `affiliate` = '" . $this->db->escape((isset($data['affiliate']) && $data['to'] == 'affiliate') ? serialize($data['affiliate']) : '') . "', `product` = '" . $this->db->escape((isset($data['product']) && $data['to'] == 'product') ? serialize($data['product']) : '') . "', `customer_group_id` = '" . (int)$data['customer_group_id'] . "', `customer_group_only_subscribers` = '" . (isset($data['customer_group_only_subscribers']) ? (int)$data['customer_group_only_subscribers'] : '0') . "', `only_selected_language` = '" . (isset($data['only_selected_language']) ? (int)$data['only_selected_language'] : '0') . "', `time` = '" . ((int)$data['recurrent'] ? (int)$data['rtime'] : (int)$data['time']) . "', `day` = '" . (int)(isset($data['day']) ? $data['day'] : 0) . "', `frequency` = '" . (int)(isset($data['frequency']) ? $data['frequency'] : 0) . "', store_id = '" . (int)$data['store_id'] . "', language_id = '" . (int)$data['language_id'] . "', `currency` = '" . $this->db->escape($data['currency']) . "', template_id = '" . (int)$data['template_id'] . "', `to` = '" . $this->db->escape($data['to']) . "', `subject` = '" . $this->db->escape($data['subject']) . "', `message` = '" . $this->db->escape($data['message']) . "', `text_message` = '" . $this->db->escape($data['text_message']) . "', `text_message_products` = '" . (int)$data['text_message_products'] . "', `recurrent` = '" . (int)$data['recurrent'] . "', `random` = '" . (int)$data['random'] . "', `defined` = '" . (int)$data['defined'] . "', categories = '" . (int)$data['defined_categories'] . "', `random_count` = '" . ($data['random_count'] ? (int)$data['random_count'] : 8) . "', special = '" . (int)$data['special'] . "', latest = '" . (int)$data['latest'] . "', popular = '" . (int)$data['popular'] . "'");
            $data['id'] = $this->db->getLastId();
        }
        return $data['id'];
    }

    public function get($schedule_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_schedule WHERE schedule_id = '" . (int)$schedule_id . "'");
        return $query->row;
    }

    public function delete($schedule_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "ne_schedule WHERE schedule_id = '" . (int)$schedule_id . "'");
    }
}