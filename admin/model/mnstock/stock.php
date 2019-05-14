<?php

class ModelMnstockStock extends Model {

    public function addStock($data) {

        $this->db->query("INSERT INTO mnstock SET title = '" . $this->db->escape($data['title']) . "', short_description = '" . $this->db->escape($data['short_description']) . "', image = '" . $data['image'] . "', meta_title = '" . $data['meta_title'] . "', meta_description = '" . $data['meta_description'] . "', meta_keywords = '" . $data['meta_keywords'] . "', content = '" . $data['content'] . "', post_date = '" . date("Y-m-d H:i:s") . "'");

        $stock_id = $this->db->getLastId();

        return $stock_id;

    }

    public function editStock($stock_id, $data) {

        $this->db->query("UPDATE mnstock SET title = '" . $this->db->escape($data['title']) . "', short_description = '" . $this->db->escape($data['short_description']) . "', image = '" . $data['image'] . "', meta_title = '" . $data['meta_title'] . "', meta_description = '" . $data['meta_description'] . "', meta_keywords = '" . $data['meta_keywords'] . "', content = '" . $data['content'] . "', post_date = '" . date("Y-m-d H:i:s") . "' WHERE stock_id = " . (int)$stock_id);

    }

    public function deleteStock($stock_id) {
        $this->db->query("DELETE FROM mnstock where stock_id = " . $stock_id);
    }

    public function getStockById($stock_id) {

        $query = $this->db->query("SELECT * FROM mnstock WHERE stock_id = " . $stock_id);

        return $query->row;

    }

    public function getStock() {

        $query = $this->db->query("SELECT * FROM mnstock");

        return $query->rows;

    }

}