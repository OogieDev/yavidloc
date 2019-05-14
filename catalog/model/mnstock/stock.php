<?php

class ModelMnstockStock extends Model {

    public function getStockList($data) {
        if (isset($data['page']) && isset($data['offset']))
            $query = $this->db->query("SELECT * FROM mnstock ORDER BY -stock_id LIMIT " . ((int)$data['page']) . "," . $data['offset']);
        else
            $query = $this->db->query("SELECT * FROM mnstock LIMIT 5");

        return $query->rows;
    }

    public function getStock($stock_id) {
        $query = $this->db->query("SELECT * FROM mnstock WHERE stock_id='" . (int)$stock_id . "' LIMIT 1");

        return $query->row;
    }

    public function getTotalStock() {
        $count = $this->db->query("SELECT count(*) FROM mnstock");
        return (int)$count->row['count(*)'];
    }

    public function getSeoUrlByQuery($query) {
        $query = $this->db->query("SELECT * FROM ". DB_PREFIX ."seo_url WHERE query = 'stock_id=" . $query . "' LIMIT 1");

        return $query->row;
    }

}