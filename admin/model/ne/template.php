<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeTemplate extends Model {

    public function getList() {
        $query = $this->db->query("SELECT template_id, name, datetime FROM " . DB_PREFIX . "ne_template");
        return $query->rows;
    }

    public function delete($template_id) {
        $total = 0;

        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "ne_history WHERE template_id = '" . (int)$template_id . "'");
        $total += $query->row['total'];

        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "ne_draft WHERE template_id = '" . (int)$template_id . "'");
        $total += $query->row['total'];

        $query = $this->db->query("SELECT COUNT(*) AS `total` FROM " . DB_PREFIX . "ne_schedule WHERE template_id = '" . (int)$template_id . "'");
        $total += $query->row['total'];

        if ($total) {
            return false;
        } else {
            $this->db->query("DELETE FROM " . DB_PREFIX . "ne_template_data WHERE template_id = '" . (int)$template_id . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "ne_template WHERE template_id = '" . (int)$template_id . "'");

            return $this->db->countAffected();
        }
    }

    public function save($data) {
        if (isset($data['id']) && $data['id']) {
            $this->db->query("UPDATE " . DB_PREFIX . "ne_template SET `name` = '" . $this->db->escape($data['name'] ? $data['name'] : date('Y-m-d H:i:s')) . "', `uri` = '" . $this->db->escape($data['uri']) . "', product_image_width = '" . (int)($data['product_image_width'] ? $data['product_image_width'] : 140) . "', product_image_height = '" . (int)($data['product_image_height'] ? $data['product_image_height'] : 140) . "', product_show_prices = '" . (int)$data['product_show_prices'] . "', product_show_savings = '" . (int)$data['product_show_savings'] . "', product_description_length = '" . (int)$data['product_description_length'] . "', product_cols = '" . (int)($data['product_cols'] ? $data['product_cols'] : 4) . "', heading_color = '" . $this->db->escape($data['heading_color'] ? $data['heading_color'] : '#222222') . "', heading_style = '" . $this->db->escape($data['heading_style']) . "', product_name_color = '" . $this->db->escape($data['product_name_color'] ? $data['product_name_color'] : '#222222') . "', product_name_style = '" . $this->db->escape($data['product_name_style']) . "', product_model_color = '" . $this->db->escape($data['product_model_color'] ? $data['product_model_color'] : '#222222') . "', product_model_style = '" . $this->db->escape($data['product_model_style']) . "', product_price_color = '" . $this->db->escape($data['product_price_color'] ? $data['product_price_color'] : '#222222') . "', product_price_style = '" . $this->db->escape($data['product_price_style']) . "', product_price_color_special = '" . $this->db->escape($data['product_price_color_special'] ? $data['product_price_color_special'] : '#222222') . "', product_price_style_special = '" . $this->db->escape($data['product_price_style_special']) . "', product_price_color_when_special = '" . $this->db->escape($data['product_price_color_when_special'] ? $data['product_price_color_when_special'] : '#222222') . "', product_price_style_when_special = '" . $this->db->escape($data['product_price_style_when_special'] ? $data['product_price_style_when_special'] : '') . "', product_description_color = '" . $this->db->escape($data['product_description_color'] ? $data['product_description_color'] : '#222222') . "', product_description_style = '" . $this->db->escape($data['product_description_style']) . "', product_savings_color = '" . $this->db->escape($data['product_savings_color'] ? $data['product_savings_color'] : '#999999') . "', product_savings_style = '" . $this->db->escape($data['product_savings_style']) . "', custom_css = '" . $this->db->escape($data['custom_css']) . "', specials_count = '" . (int)$data['specials_count'] . "', latest_count = '" . (int)$data['latest_count'] . "', popular_count = '" . (int)$data['popular_count'] . "' WHERE template_id = '" . (int)$data['id'] . "'");
            $this->db->query("DELETE FROM " . DB_PREFIX . "ne_template_data WHERE template_id = '" . (int)$data['id'] . "'");
            foreach ($data['body'] as $language_id => $body) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "ne_template_data SET `body` = '" . $this->db->escape($body) . "', `text_message` = '" . $this->db->escape(isset($data['text_message'][$language_id]) ? $data['text_message'][$language_id] : '') . "', `subject` = '" . $this->db->escape(isset($data['subject'][$language_id]) ? $data['subject'][$language_id] : '') . "', `defined` = '" . $this->db->escape(isset($data['defined_text'][$language_id]) ? $data['defined_text'][$language_id] : '') . "', `special` = '" . $this->db->escape(isset($data['special_text'][$language_id]) ? $data['special_text'][$language_id] : '') . "', `latest` = '" . $this->db->escape(isset($data['latest_text'][$language_id]) ? $data['latest_text'][$language_id] : '') . "', `popular` = '" . $this->db->escape(isset($data['popular_text'][$language_id]) ? $data['popular_text'][$language_id] : '') . "', `categories` = '" . $this->db->escape(isset($data['categories_text'][$language_id]) ? $data['categories_text'][$language_id] : '') . "', `savings` = '" . $this->db->escape(isset($data['savings_text'][$language_id]) ? $data['savings_text'][$language_id] : '') . "', `language_id` = '" . (int)$language_id . "', `template_id` = '" . (int)$data['id'] . "'");
            }
        } else {
            $this->db->query("INSERT INTO " . DB_PREFIX . "ne_template SET `name` = '" . $this->db->escape($data['name'] ? $data['name'] : date('Y-m-d H:i:s')) . "', `uri` = '" . $this->db->escape($data['uri']) . "', product_image_width = '" . (int)($data['product_image_width'] ? $data['product_image_width'] : 140) . "', product_image_height = '" . (int)($data['product_image_height'] ? $data['product_image_height'] : 140) . "', product_show_prices = '" . (int)$data['product_show_prices'] . "', product_show_savings = '" . (int)$data['product_show_savings'] . "', product_description_length = '" . (int)$data['product_description_length'] . "', product_cols = '" . (int)($data['product_cols'] ? $data['product_cols'] : 4) . "', heading_color = '" . $this->db->escape($data['heading_color'] ? $data['heading_color'] : '#222222') . "', heading_style = '" . $this->db->escape($data['heading_style']) . "', product_name_color = '" . $this->db->escape($data['product_name_color'] ? $data['product_name_color'] : '#222222') . "', product_name_style = '" . $this->db->escape($data['product_name_style']) . "', product_model_color = '" . $this->db->escape($data['product_model_color'] ? $data['product_model_color'] : '#222222') . "', product_model_style = '" . $this->db->escape($data['product_model_style']) . "', product_price_color = '" . $this->db->escape($data['product_price_color'] ? $data['product_price_color'] : '#222222') . "', product_price_style = '" . $this->db->escape($data['product_price_style']) . "', product_price_color_special = '" . $this->db->escape($data['product_price_color_special'] ? $data['product_price_color_special'] : '#222222') . "', product_price_style_special = '" . $this->db->escape($data['product_price_style_special']) . "', product_price_color_when_special = '" . $this->db->escape($data['product_price_color_when_special'] ? $data['product_price_color_when_special'] : '#222222') . "', product_price_style_when_special = '" . $this->db->escape($data['product_price_style_when_special'] ? $data['product_price_style_when_special'] : '') . "', product_description_color = '" . $this->db->escape($data['product_description_color'] ? $data['product_description_color'] : '#222222') . "', product_description_style = '" . $this->db->escape($data['product_description_style']) . "', product_savings_color = '" . $this->db->escape($data['product_savings_color'] ? $data['product_savings_color'] : '#999999') . "', product_savings_style = '" . $this->db->escape($data['product_savings_style']) . "', custom_css = '" . $this->db->escape($data['custom_css']) . "', specials_count = '" . (int)$data['specials_count'] . "', latest_count = '" . (int)$data['latest_count'] . "', popular_count = '" . (int)$data['popular_count'] . "'");
            $data['id'] = $this->db->getLastId();
            foreach ($data['body'] as $language_id => $body) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "ne_template_data SET `body` = '" . $this->db->escape($body) . "', `text_message` = '" . $this->db->escape(isset($data['text_message'][$language_id]) ? $data['text_message'][$language_id] : '') . "', `subject` = '" . $this->db->escape(isset($data['subject'][$language_id]) ? $data['subject'][$language_id] : '') . "', `defined` = '" . $this->db->escape(isset($data['defined_text'][$language_id]) ? $data['defined_text'][$language_id] : '') . "', `special` = '" . $this->db->escape(isset($data['special_text'][$language_id]) ? $data['special_text'][$language_id] : '') . "', `latest` = '" . $this->db->escape(isset($data['latest_text'][$language_id]) ? $data['latest_text'][$language_id] : '') . "', `popular` = '" . $this->db->escape(isset($data['popular_text'][$language_id]) ? $data['popular_text'][$language_id] : '') . "', `categories` = '" . $this->db->escape(isset($data['categories_text'][$language_id]) ? $data['categories_text'][$language_id] : '') . "', `savings` = '" . $this->db->escape(isset($data['savings_text'][$language_id]) ? $data['savings_text'][$language_id] : '') . "', `language_id` = '" . (int)$language_id . "', `template_id` = '" . (int)$data['id'] . "'");
            }
        }
        return $data['id'];
    }

    public function get($template_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_template WHERE template_id = '" . (int)$template_id . "'");
        $template_info = $query->row;

        if ($template_info) {
            $template_info['body'] = array();
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "ne_template_data WHERE template_id = '" . (int)$template_id . "'");
            foreach ($query->rows as $row) {
                $template_info['subject'][$row['language_id']] = $row['subject'];
                $template_info['body'][$row['language_id']] = $row['body'];
                $template_info['text_message'][$row['language_id']] = $row['text_message'];
                $template_info['defined_text'][$row['language_id']] = $row['defined'];
                $template_info['special_text'][$row['language_id']] = $row['special'];
                $template_info['latest_text'][$row['language_id']] = $row['latest'];
                $template_info['popular_text'][$row['language_id']] = $row['popular'];
                $template_info['categories_text'][$row['language_id']] = $row['categories'];
                $template_info['savings_text'][$row['language_id']] = $row['savings'];
            }
            return $template_info;
        } else {
            return false;
        }
    }

    public function copy($template_id) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "ne_template (`name`, `uri`, `product_image_width`, `product_image_height`, `product_show_prices`, `product_show_savings`, `product_description_length`, `product_cols`, `heading_color`, `heading_style`, `product_name_color`, `product_name_style`, `product_model_color`, `product_model_style`, `product_price_color`, `product_price_style`, `product_price_color_special`, `product_price_style_special`, `product_price_color_when_special`, `product_price_style_when_special`, `product_description_color`, `product_description_style`, `product_savings_color`, `product_savings_style`, `custom_css`, `specials_count`, `latest_count`, `popular_count`) SELECT `name`, `uri`, `product_image_width`, `product_image_height`, `product_show_prices`, `product_show_savings`, `product_description_length`, `product_cols`, `heading_color`, `heading_style`, `product_name_color`, `product_name_style`, `product_model_color`, `product_model_style`, `product_price_color`, `product_price_style`, `product_price_color_special`, `product_price_style_special`, `product_price_color_when_special`, `product_price_style_when_special`, `product_description_color`, `product_description_style`, `product_savings_color`, `product_savings_style`, `custom_css`, `specials_count`, `latest_count`, `popular_count` FROM " . DB_PREFIX . "ne_template WHERE `template_id` = '" . (int)$template_id . "'");
        $template_new_id = $this->db->getLastId();

        $query = $this->db->query("SELECT `template_data_id` FROM " . DB_PREFIX . "ne_template_data WHERE template_id = '" . (int)$template_id . "'");
        foreach ($query->rows as $row) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "ne_template_data (`body`, `text_message`, `subject`, `defined`, `special`, `latest`, `popular`, `categories`, `savings`, `language_id`, `template_id`) SELECT `body`, `text_message`, `subject`, `defined`, `special`, `latest`, `popular`, `categories`, `savings`, `language_id`, " . (int)$template_new_id ." FROM " . DB_PREFIX . "ne_template_data WHERE `template_data_id` = '" . (int)$row['template_data_id'] . "'");
        }

        return $template_new_id;
    }

}

?>