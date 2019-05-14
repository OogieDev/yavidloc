<?php

class ModelMnservicesServices extends Controller {

    public function getServices($filter) {
        if (isset($filter['page']) && isset($filter['offset']))
            $query = $this->db->query("SELECT * FROM mnservices  ORDER BY -service_id LIMIT " . ((int)$filter['page']) . "," . $filter['offset']);
        else
            $query = $this->db->query("SELECT * FROM mnservices LIMIT 5");

        return $query->rows;
    }

    public function getService($service_id) {
        $query = $this->db->query("SELECT * FROM mnservices WHERE service_id = " . $service_id . " LIMIT 1");

        return $query->row;
    }

    public function getTotalServices() {
        $count = $this->db->query("SELECT count(*) FROM mnservices");
        return (int)$count->row['count(*)'];
    }

    public function getSeoUrlByQuery($query) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX ."seo_url WHERE query = 'service_id=" . $query . "' LIMIT 1");

        return $query->row;
    }

}