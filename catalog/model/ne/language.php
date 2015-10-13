<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeLanguage extends Model {

    public function set($data) {
        $query = $this->db->query("SELECT history_id FROM `" . DB_PREFIX . "ne_stats_personal` WHERE stats_personal_id = '" . (int)$data['uid'] . "' AND email = '" . $this->db->escape($data['email']) . "'");
        if ($query->row) {
            $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE code = '" . $this->db->escape($data['language_code']) . "'");
            return $query->row && $this->db->query("INSERT INTO `" . DB_PREFIX . "ne_language_map` (c_email, language_code) VALUES ('" . $this->db->escape($data['email']) . "', '" . $this->db->escape($data['language_code']) . "') ON DUPLICATE KEY UPDATE language_code = '" . $this->db->escape($data['language_code']) . "'");
        } else {
            return false;
        }
    }
}

?>