<?php
//-----------------------------------------------------
// Newsletter Enhancements for Opencart
// Created by @DmitryNek (Dmitry Shkoliar)
// exmail.Nek@gmail.com
//-----------------------------------------------------

class ModelNeBlacklist extends Model {

    public function getTotal($data = array()) {
        $sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "ne_blacklist";

        $implode = array();

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
            $implode[] = "DATE(datetime) = DATE('" . $this->db->escape($data['filter_date']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        } else {
            $sql .= " WHERE 1";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }

    public function getList($data = array()) {

        $sql = "SELECT * FROM " . DB_PREFIX . "ne_blacklist";

        $implode = array();

        if (isset($data['filter_email']) && !is_null($data['filter_email'])) {
            $implode[] = "email LIKE '%" . $this->db->escape($data['filter_email']) . "%'";
        }

        if (isset($data['filter_date']) && !is_null($data['filter_date'])) {
            $implode[] = "DATE(datetime) = DATE('" . $this->db->escape($data['filter_date']) . "')";
        }

        if ($implode) {
            $sql .= " WHERE " . implode(" AND ", $implode);
        } else {
            $sql .= " WHERE 1";
        }

        $sort_data = array(
            'email',
            'datetime'
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY datetime";
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

    public function add($data) {
        $emails = $data['emails'];

        $i = 0;
        if ($emails) {
            $emails = preg_replace("/\n|\r/", ',', $emails);
            $emails = explode(',', $emails);

            $emails = array_filter($emails, array($this, 'filter_email'));

            foreach ($emails as $email) {
                $email = trim(preg_replace("/\s+/", ' ', $email));

                $this->db->query("INSERT IGNORE INTO " . DB_PREFIX . "ne_blacklist SET email = '" . $this->db->escape($email) . "'");
                if ($this->db->countAffected() > 0) {
                    $i++;
                }
            }
        }

        return $i;
    }

    public function delete($blacklist_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "ne_blacklist WHERE blacklist_id = '" . (int)$blacklist_id . "'");
    }

    private function filter_email($email) {
        $temp = explode('|', $email);
        if (count($temp)) {
            return $temp[count($temp) - 1] && filter_var(htmlspecialchars(trim($temp[count($temp) - 1])), FILTER_VALIDATE_EMAIL);
        } else {
            return false;
        }
    }

}

?>