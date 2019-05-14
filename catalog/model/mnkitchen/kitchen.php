<?php

class ModelMnkitchenKitchen extends Model
{

  public function getKitchen($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM mnkitchen WHERE kitchen_id = {$kitchen_id}");

    return $query->row;
  }

  public function getKitchens($category_id)
  {
    $kitchensCategoryQuery = $this->db->query("SELECT * FROM kitchen_category WHERE category_id = " . $category_id);

    if (empty($kitchensCategoryQuery)) {
      return array();
    }

    $kitchens = array();
    foreach ($kitchensCategoryQuery->rows as $kitchen) {
      $query = $this->db->query("SELECT * FROM mnkitchen WHERE kitchen_id = {$kitchen['kitchen_id']}");
      if (!empty($query)) {
        $kitchens[] = $query->row;
      }
    }

    return $kitchens;
  }

  public function getRelatedKitchens($category_id)
  {
    $query = $this->db->query("SELECT * FROM mn_relative_kitchen_category WHERE category_id = {$category_id}");

    return $query->rows;
  }

  public function getCategory($kithen_id) {
    $query = $this->db->query("SELECT * FROM kitchen_category WHERE kitchen_id = {$kithen_id} LIMIT 1");

    return $query->row;
  }

  public function getDiningGroups() {
    $query = $this->db->query("SELECT * FROM kitchen_category WHERE category_id = 68 ORDER BY -kitchen_id LIMIT 3");

    return $query->rows;
  }

  public function getKitchenSeoUrl($query)
  {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "seo_url WHERE query = '" . $query . "' LIMIT 1");

    return $query->row;
  }

  public function getKitchenColors($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM kitchen_colors WHERE kitchen_id = {$kitchen_id}");

    return $query->rows;
  }

  public function getKitchenImages($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM kitchen_images WHERE kitchen_id = {$kitchen_id}");

    return $query->rows;
  }

  public function getRelatedKitchenCategory($kitchen_id)
  {
    $query = $this->db->query("SELECT * FROM mn_relative_kitchen_category WHERE kitchen_id = {$kitchen_id} LIMIT 1");

    return $query->row;
  }

}