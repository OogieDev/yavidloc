<?php

class ModelMnblogBlog extends Model {

    public function getArticles($data) {
        if (isset($data['page']) && isset($data['offset']))
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mnblog_article ORDER BY -article_id LIMIT " . ((int)$data['page']) . "," . $data['offset']);
        else
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mnblog_article LIMIT 5");

        return $query->rows;
    }

    public function getArticle($article_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mnblog_article WHERE article_id='" . (int)$article_id . "' LIMIT 1");

        return $query->row;
    }

    public function getTotalArticles() {
        $count = $this->db->query("SELECT count(*) FROM " . DB_PREFIX . "mnblog_article");
        return (int)$count->row['count(*)'];
    }

    public function getSeoUrlByQuery($query) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = 'article_id=" . $query . "' LIMIT 1");

        return $query->row;
    }

}