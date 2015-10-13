<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeSubscribeBox extends Model {

    public function refreshModules($name) {
        $query_layout_modules = $this->db->query("SELECT * FROM " . DB_PREFIX . "layout_module WHERE `code` LIKE 'ne.%'");

        $were_added = array();

        foreach ($query_layout_modules->rows as $result) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "module WHERE `module_id` = '" . (int)substr($result['code'], 3) . "'");

            if ($query->row) {
                $data = unserialize($query->row['setting']);

                if (isset($data['subscribe_box_id'])) {
                    $were_added[$data['subscribe_box_id']] = $result;
                }
            }
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "module WHERE `code` = 'ne'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "layout_module WHERE `code` LIKE 'ne.%'");

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_subscribe_box");

        foreach ($query->rows as $result) {
            $data = unserialize($result['data']);

            $data['subscribe_box_id'] = $result['subscribe_box_id'];
            $data['name'] = $name . $result['name'];
            $data['status'] = $result['status'];

            $this->db->query("INSERT INTO " . DB_PREFIX . "module SET `code` = 'ne', `name` = '" . $this->db->escape($name . $result['name']) . "', `setting` = '" . $this->db->escape(serialize($data)) . "'");

            $id = $this->db->getLastId();

            if (array_key_exists($result['subscribe_box_id'], $were_added)) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "layout_module SET `code` = 'ne." . (int)$id . "', `layout_id` = '" . (int)$were_added[$result['subscribe_box_id']]['layout_id'] . "', `position` = '" . $this->db->escape($were_added[$result['subscribe_box_id']]['position']) . "', `sort_order` = '" . (int)$were_added[$result['subscribe_box_id']]['sort_order'] . "'");
            }
        }
    }

    public function getList() {
        $query = $this->db->query("SELECT subscribe_box_id, name, datetime, status FROM " . DB_PREFIX . "ne_subscribe_box");
        return $query->rows;
    }

    public function delete($subscribe_box_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "ne_subscribe_box WHERE subscribe_box_id = '" . (int)$subscribe_box_id . "'");
        return $this->db->countAffected();
    }

    public function save($data) {
        $name = $data['name'] ? $data['name'] : date('Y-m-d H:i:s');
        unset($data['name']);
        $status = $data['status'] ? $data['status'] : '0';
        unset($data['status']);

        $data['modal_bg_color'] = empty($data['modal_bg_color']) ? '#ffffff' : $data['modal_bg_color'];
        $data['modal_line_color'] = empty($data['modal_line_color']) ? '#e5e5e5' : $data['modal_line_color'];
        $data['modal_heading_color'] = empty($data['modal_heading_color']) ? '#222222' : $data['modal_heading_color'];

        if (isset($data['id']) && $data['id']) {
            $id = $data['id'];
            unset($data['id']);
            $this->db->query("UPDATE " . DB_PREFIX . "ne_subscribe_box SET `name` = '" . $this->db->escape($name) . "', `status` = '" . (int)$status . "', `data` = '" . $this->db->escape(serialize($data)) . "' WHERE subscribe_box_id = '" . (int)$id . "'");
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "ne_subscribe_box SET `name` = '" . $this->db->escape($name) . "', `status` = '" . (int)$status . "', `data` = '" . $this->db->escape(serialize($data)) . "'");
            $id = $this->db->getLastId();
        }

        $this->cache->delete('ne_subscribe_box.' . (int)$id);

        return $id;
    }

    public function get($subscribe_box_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_subscribe_box WHERE subscribe_box_id = '" . (int)$subscribe_box_id . "'");
        $subscribe_box_info = $query->row;

        if ($subscribe_box_info) {
            $subscribe_box_info = array_merge($subscribe_box_info, unserialize($subscribe_box_info['data']));
            unset($subscribe_box_info['data']);

            return $subscribe_box_info;
        } else {
            return false;
        }
    }

    public function copy($subscribe_box_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "ne_subscribe_box (`name`, `data`) SELECT `name`, `data` FROM " . DB_PREFIX . "ne_subscribe_box WHERE `subscribe_box_id` = '" . (int)$subscribe_box_id . "'");
        return $this->db->getLastId();
    }

}