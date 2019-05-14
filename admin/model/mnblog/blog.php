<?php

class ModelMnblogBlog extends Model {

    public function addArticle($data) {

        $this->db->query("INSERT INTO " . DB_PREFIX . "mnblog_article SET title = '" . $this->db->escape($data['title']) . "', short_description = '" . $this->db->escape($data['short_description']) . "', image = '" . $data['image'] . "', meta_title = '" . $data['meta_title'] . "', meta_description = '" . $data['meta_description'] . "', meta_keywords = '" . $data['meta_keywords'] . "', content = '" . $data['content'] . "', post_date = '" . date("Y-m-d H:i:s") . "'");
        //        $this->db->query("INSERT INTO " . DB_PREFIX . "mnblog_article SET");

        $article_id = $this->db->getLastId();

        return $article_id;

    }

    public function editArticle($article_id, $data) {

        $this->db->query("UPDATE " . DB_PREFIX . "mnblog_article SET title = '" . $this->db->escape($data['title']) . "', short_description = '" . $this->db->escape($data['short_description']) . "', image = '" . $data['image'] . "', meta_title = '" . $data['meta_title'] . "', meta_description = '" . $data['meta_description'] . "', meta_keywords = '" . $data['meta_keywords'] . "', content = '" . $data['content'] . "', post_date = '" . date("Y-m-d H:i:s") . "' WHERE article_id = " . (int)$article_id);

    }

    public function deleteArticle($article_id) {
        $this->db->query("DELETE FROM " . DB_PREFIX . "mnblog_article where article_id = " . $article_id);
    }

    public function getArticle($article_id) {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mnblog_article WHERE article_id = " . $article_id);

        return $query->row;

    }

    public function getArticles() {

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "mnblog_article ORDER BY -article_id");

        return $query->rows;

    }

}