<?php

class ModelMnkitchenKitchen extends Model
{
  public function getKitchens()
  {
    $query = $this->db->query("SELECT * FROM mnkitchen ORDER BY -kitchen_id");

    return $query->rows;
  }

  public function addKitchen($data)
  {
    $this->db->query("INSERT INTO mnkitchen SET title = '" . $this->db->escape($data['title']) . "', description = '" . $data['description'] . "', main_image = '" . $data['main_image'] . "', facade = '" . $this->db->escape($data['facade']) . "', skeleton = '" . $this->db->escape($data['skeleton']) . "', fittings = '" . $this->db->escape($data['fittings']) . "', style = '" . $this->db->escape($data['style']) . "', term = '" . $this->db->escape($data['term']) . "', guarantee = '" . $this->db->escape($data['guarantee']) . "', price = " . $data['price'] . ", meta_title = '" . $data['meta_title'] . "', meta_description = '" . $data['meta_description'] . "', meta_keywords = '" . $data['meta_keywords'] . "'");

    return $this->db->getLastId();
  }

  public function editKitchen($kitchen_id, $data)
  {
    $this->db->query("UPDATE mnkitchen SET title = '{$data['title']}', description = '{$data['description']}', main_image = '{$data['main_image']}', facade = '{$this->db->escape($data['facade'])}', skeleton = '{$this->db->escape($data['skeleton'])}', fittings = '{$this->db->escape($data['fittings'])}', style = '{$this->db->escape($data['style'])}', term = '{$this->db->escape($data['term'])}', guarantee = '{$this->db->escape($data['guarantee'])}', price = {$data['price']}, meta_title = '{$this->db->escape($data['meta_title'])}', meta_description = '{$this->db->escape($data['meta_description'])}', meta_keywords = '{$this->db->escape($data['meta_keywords'])}' WHERE kitchen_id = {$kitchen_id}");
  }

  public function addKitchenCategory($kitchen_id, $category_id)
  {
    $this->db->query("INSERT INTO kitchen_category SET category_id = '{$category_id}', kitchen_id = '{$kitchen_id}'");
  }

  public function getKitchenCategories($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM kitchen_category WHERE kitchen_id = " . $kitchen_id);

    return $query->rows;
  }

  public function deleteKitchenCategory($kitchen_id)
  {
    $this->db->query("DELETE FROM kitchen_category WHERE kitchen_id = {$kitchen_id}");
  }

  public function editKitchenCategory($kitchen_id, $category_id)
  {
    $this->db->query("UPDATE kitchen_category SET kitchen_id = '{$kitchen_id}', category_id = '{$category_id}' WHERE kitchen_id = {$kitchen_id} AND category_id = {$category_id}");
  }

  public function deleteKitchen($kitchen_id)
  {
    $this->db->query("DELETE FROM mnkitchen WHERE kitchen_id=" . $kitchen_id);
  }

  public function getKitchen($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM mnkitchen WHERE kitchen_id = {$kitchen_id} LIMIT 1");

    return $query->row;
  }

  public function getKitchenImages($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM kitchen_images WHERE kitchen_id = " . $kitchen_id);

    return $query->rows;
  }

  public function addKitchenImage($kitchen_id, $image)
  {
    $this->db->query("INSERT INTO kitchen_images SET kitchen_id = '" . $kitchen_id . "', image = '" . $image . "'");
  }

  public function deleteKitchenImage($kitchen_id)
  {
    $this->db->query("DELETE FROM kitchen_images WHERE kitchen_id = {$kitchen_id}");
  }

  public function addKitchenColor($kitchen_id, $color)
  {
    $this->db->query("INSERT INTO kitchen_colors SET kitchen_id = '" . $kitchen_id . "', color = '" . $color . "'");
  }

  public function getKitchenColors($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM kitchen_colors WHERE kitchen_id = " . $kitchen_id);

    return $query->rows;
  }

  public function deleteKitchenColor($kitchen_id)
  {
    $this->db->query("DELETE FROM kitchen_colors WHERE kitchen_id = {$kitchen_id}");
  }

  public function getTotalKitchens()
  {
    $count = $this->db->query("SELECT count(*) FROM mnkitchen");
    return (int)$count->row['count(*)'];
  }

  public function addRelativeKitchenCategory($original_id, $category_id)
  {
    $this->db->query("INSERT INTO mn_relative_kitchen_category SET original_id = {$original_id}, category_id = {$category_id}");
  }

  public function deleteRelativeKitchenCategory($original_id)
  {
    $this->db->query("DELETE FROM mn_relative_kitchen_category WHERE original_id = {$original_id}");
  }

}