<?php

class ModelMnservicesServices extends Model {

    public function addService($data) {
        $this->db->query("INSERT INTO mnservices SET title = '" . $this->db->escape($data['title']) . "', short_description = '" . $this->db->escape($data['short_description']) . "', content = '". $data['content'] ."', image = '" . $data['image'] . "', meta_title = '" . $this->db->escape($data['meta_title']) . "', meta_description = '" . $this->db->escape($data['meta_description']) . "', meta_keywords = '" . $this->db->escape($data['meta_keywords']) . "'");

        $service_id = $this->db->getLastId();

        return $service_id;
    }

    public function editService($service_id, $data) {
        $this->db->query("UPDATE mnservices SET title = '" . $this->db->escape($data['title']) . "', short_description = '" . $data['short_description'] . "', content = '". $data['content'] ."', image = '" . $data['image'] . "', meta_title = '" . $data['meta_title'] . "', meta_description = '" . $data['meta_description'] . "', meta_keywords = '" . $data['meta_keywords'] . "' WHERE service_id = " . $service_id);
    }

    public function deleteService($service_id) {
        $this->db->query("DELETE FROM mnservices WHERE service_id = " . $service_id);
    }

    public function getServices() {
        $query = $this->db->query("SELECT * FROM mnservices ORDER BY -service_id");

        return $query->rows;
    }

    public function getServiceById($service_id) {
        $query = $this->db->query("SELECT * FROM mnservices WHERE service_id = " . $service_id . " LIMIT 1");

        return $query->row;
    }

}