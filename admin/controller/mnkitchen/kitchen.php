<?php

class ControllerMnkitchenKitchen extends Controller
{

  private $error = array();

  public function index()
  {

    $this->load->language('mnkitchen/kitchen');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('mnkitchen/kitchen');
    $this->getList();

  }

  public function edit()
  {

    $this->load->language('mnkitchen/kitchen');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('mnkitchen/kitchen');
    $this->load->model('design/seo_url');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      $this->model_mnkitchen_kitchen->editKitchen($this->request->get['kitchen_id'], $this->request->post);

      $this->model_mnkitchen_kitchen->deleteKitchenCategory($this->request->get['kitchen_id']);

      if (isset($this->request->post['product_category']) && !empty($this->request->post['product_category']) && $this->request->post['product_category'] != '') {
        foreach ($this->request->post['product_category'] as $category) {
          $this->model_mnkitchen_kitchen->addKitchenCategory($this->request->get['kitchen_id'], $category);
        }
      }
      if (isset($this->request->post['categories']) && !empty($this->request->post['categories']) && $this->request->post['categories'] != '') {
        foreach ($this->request->post['categories'] as $category) {
          $this->model_mnkitchen_kitchen->addKitchenCategory($this->request->get['kitchen_id'], $category);
        }
      }


      if (isset($this->request->post['images']) && !empty($this->request->post['images']) && $this->request->post['images'] != '') {
        $this->model_mnkitchen_kitchen->deleteKitchenImage($this->request->get['kitchen_id']);

        foreach ($this->request->post['images'] as $image) {
          $this->model_mnkitchen_kitchen->addKitchenImage($this->request->get['kitchen_id'], $image);
        }
      } else if (!isset($this->request->post['images']) || empty($this->request->post['images']) || $this->request->post['images'] == '') {
        $this->model_mnkitchen_kitchen->deleteKitchenImage($this->request->get['kitchen_id']);
      }

      if (isset($this->request->post['colors']) && !empty($this->request->post['colors']) && $this->request->post['colors'] != '') {
        $this->model_mnkitchen_kitchen->deleteKitchenColor($this->request->get['kitchen_id']);

        foreach ($this->request->post['colors'] as $image) {
          $this->model_mnkitchen_kitchen->addKitchenColor($this->request->get['kitchen_id'], $image);
        }
      } else if (!isset($this->request->post['colors']) || empty($this->request->post['colors']) || $this->request->post['colors'] == '') {
        $this->model_mnkitchen_kitchen->deleteKitchenColor($this->request->get['kitchen_id']);
      }

      $seo_url = $this->model_design_seo_url->getSeoUrlByQuery('kitchen_id=' . $this->request->get['kitchen_id']);
      if (!empty($seo_url) && $this->request->post['seo_url'] != '') {
        $seo_data = array(
          'store_id' => 0,
          'language_id' => 1,
          'query' => 'kitchen_id=' . $this->request->get['kitchen_id'],
          'keyword' => $this->request->post['seo_url']
        );

        $this->model_design_seo_url->editSeoUrl($seo_url['seo_url_id'], $seo_data);
      } else if (!empty($seo_url) && $this->request->post['seo_url'] == '') {
        $this->model_design_seo_url->deleteSeoUrl($seo_url['seo_url_id']);
      } else if (empty($seo_url) && $this->request->post['seo_url'] != '') {
        $seo_data = array(
          'store_id' => 0,
          'language_id' => 1,
          'query' => 'kitchen_id=' . $this->request->get['kitchen_id'],
          'keyword' => $this->request->post['seo_url']
        );

        $this->model_design_seo_url->addSeoUrl($seo_data);
      }


      $this->response->redirect($this->url->link('mnkitchen/kitchen', 'user_token=' . $this->session->data['user_token'], true));

    }

    $this->getForm();

  }

  public function add()
  {

    $this->load->language('mnkitchen/kitchen');
    $this->document->setTitle($this->language->get('heading_title'));
    $this->load->model('mnkitchen/kitchen');
    $this->load->model('design/seo_url');

    if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

      $item_id = $this->model_mnkitchen_kitchen->addKitchen($this->request->post);

      if ($this->request->post['seo_url'] != '') {
        $seo_data = array(
          'store_id' => 0,
          'language_id' => 1,
          'query' => 'kitchen_id=' . $item_id,
          'keyword' => $this->request->post['seo_url']
        );

        $this->model_design_seo_url->addSeoUrl($seo_data);
      }

      if (isset($this->request->post['images'])) {
        foreach ($this->request->post['images'] as $image) {
          $this->model_mnkitchen_kitchen->addKitchenImage($item_id, $image);
        }
      }

      if (isset($this->request->post['colors'])) {
        foreach ($this->request->post['colors'] as $color) {
          $this->model_mnkitchen_kitchen->addKitchenColor($item_id, $color);
        }
      }

      if (isset($this->request->post['product_category'])) {
        foreach ($this->request->post['product_category'] as $category) {
          $this->model_mnkitchen_kitchen->addKitchenCategory($item_id, (int)$category);
          $this->model_mnkitchen_kitchen->addRelativeKitchenCategory($item_id, $category);
        }
      }

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('mnkitchen/kitchen', 'user_token=' . $this->session->data['user_token'], true));

    }

    $this->getForm();

  }

  public function getImages()
  {

    return;

  }

  private function vardump($arr)
  {
    echo '<pre>';
    var_dump($arr);
    echo '</pre>';
  }

  public function getForm()
  {

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('mnkitchen/kitchen', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['text_form'] = !isset($this->request->get['kitchen_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

    if (!empty($this->error)) {
      $data['errors'] = $this->language->get('errors');
    } else {
      $data['errors'] = false;
    }

    if (isset($this->error['warning'])) {
      $data['error_warning'] = $this->error['warning'];
    } else {
      $data['error_warning'] = '';
    }

    if (isset($this->error['title'])) {
      $data['error_title'] = $this->error['title'];
    } else {
      $data['error_title'] = '';
    }

    if (isset($this->error['main_image'])) {
      $data['error_main_image'] = $this->error['main_image'];
    } else {
      $data['error_main_image'] = '';
    }

    if (isset($this->error['images'])) {
      $data['error_images'] = $this->error['images'];
    } else {
      $data['error_images'] = '';
    }

    if (isset($this->error['colors'])) {
      $data['error_colors'] = $this->error['colors'];
    } else {
      $data['error_colors'] = '';
    }

    if (isset($this->error['facade'])) {
      $data['error_facade'] = $this->error['facade'];
    } else {
      $data['error_facade'] = '';
    }

    if (isset($this->error['skeleton'])) {
      $data['error_skeleton'] = $this->error['skeleton'];
    } else {
      $data['error_skeleton'] = '';
    }

    if (isset($this->error['fittings'])) {
      $data['error_fittings'] = $this->error['fittings'];
    } else {
      $data['error_fittings'] = '';
    }

    if (isset($this->error['style'])) {
      $data['error_style'] = $this->error['style'];
    } else {
      $data['error_style'] = '';
    }

    if (isset($this->error['term'])) {
      $data['error_term'] = $this->error['term'];
    } else {
      $data['error_term'] = '';
    }

    if (isset($this->error['guarantee'])) {
      $data['error_guarantee'] = $this->error['guarantee'];
    } else {
      $data['error_guarantee'] = '';
    }

    if (isset($this->error['price'])) {
      $data['error_price'] = $this->error['price'];
    } else {
      $data['error_price'] = '';
    }

    if (isset($this->error['meta_title'])) {
      $data['error_meta_title'] = $this->error['meta_title'];
    } else {
      $data['error_meta_title'] = '';
    }

    if (isset($this->error['meta_description'])) {
      $data['error_meta_description'] = $this->error['meta_description'];
    } else {
      $data['error_meta_description'] = '';
    }

    if (isset($this->error['meta_keywords'])) {
      $data['error_meta_keywords'] = $this->error['meta_keywords'];
    } else {
      $data['error_meta_keywords'] = '';
    }

    if (isset($this->error['seo_url_length'])) {
      $data['error_seo_url_length'] = $this->error['seo_url_length'];
    } else {
      $data['error_seo_url_length'] = '';
    }

    if (isset($this->error['seo_url_contains'])) {
      $data['error_seo_url_contains'] = $this->error['seo_url_contains'];
    } else {
      $data['error_seo_url_contains'] = '';
    }

    if (isset($this->request->get['kitchen_id'])) {
      $data['action'] = $this->url->link('mnkitchen/kitchen/edit', 'user_token=' . $this->session->data['user_token'] . '&kitchen_id=' . $this->request->get['kitchen_id'], true);
    } else {
      $data['action'] = $this->url->link('mnkitchen/kitchen/add', 'user_token=' . $this->session->data['user_token'], true);
    }

    $data['cancel'] = $this->url->link('mnkitchen/kitchen', 'user_token=' . $this->session->data['user_token'], true);

    $data['user_token'] = $this->session->data['user_token'];

    if (isset($this->request->get['kitchen_id']) && $this->request->server['REQUEST_METHOD'] != 'POST') {
      $item_info = $this->model_mnkitchen_kitchen->getKitchen($this->request->get['kitchen_id']);
      if (!empty($item_info)) {
        $item_images = $this->model_mnkitchen_kitchen->getKitchenImages($item_info['kitchen_id']);
        $item_colors = $this->model_mnkitchen_kitchen->getKitchenColors($item_info['kitchen_id']);
        $item_categories = $this->model_mnkitchen_kitchen->getKitchenCategories($item_info['kitchen_id']);
      } else {
        $item_images = array();
        $item_colors = array();
        $item_categories = array();
      }
      $seo_url = $this->model_design_seo_url->getSeoUrlByQuery('kitchen_id=' . $this->request->get['kitchen_id']);
    }


    if (isset($this->request->post['title'])) {
      $data['title'] = $this->request->post['title'];
    } elseif (!empty($item_info)) {
      $data['title'] = $item_info['title'];
    } else {
      $data['title'] = '';
    }

    if (isset($this->request->post['description'])) {
      $data['description'] = $this->request->post['description'];
    } elseif (!empty($item_info)) {
      $data['description'] = $item_info['description'];
    } else {
      $data['description'] = '';
    }

    $this->load->model('tool/image');

    if (isset($this->request->post['main_image'])) {
      $data['main_image'] = $this->request->post['main_image'];
    } elseif (!empty($item_info)) {
      $data['main_image'] = $item_info['main_image'];
    } else {
      $data['main_image'] = '';
    }
    if ($data['main_image'] != '') {
      $data['main_image_thumb'] = $this->model_tool_image->resize($data['main_image'], 100, 100);
    } else {
      $data['main_image_thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
    }

    $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

    if (isset($this->request->post['images']) && !empty($this->request->post['images'])) {
      $data['images'] = array();
      foreach ($this->request->post['images'] as $image) {
        $data['images'][] = array(
          'thumb' => $this->model_tool_image->resize($image, 100, 100),
          'image' => $image
        );
      }
    } elseif (!empty($item_images)) {
      $data['images'] = array();
      foreach ($item_images as $image) {
        $data['images'][] = array(
          'thumb' => $this->model_tool_image->resize($image['image'], 100, 100),
          'image' => $image['image']
        );
      }
    } else {
      $data['images'] = '';
    }

    if (isset($this->request->post['colors']) && !empty($this->request->post['colors'])) {
      $data['colors'] = array();
      foreach ($this->request->post['colors'] as $color) {
        $data['colors'][] = array(
          'thumb' => $this->model_tool_image->resize($color, 100, 100),
          'color' => $color
        );
      }
    } elseif (!empty($item_info)) {
      $data['colors'] = array();
      foreach ($item_colors as $color) {
        $data['colors'][] = array(
          'thumb' => $this->model_tool_image->resize($color['color'], 100, 100),
          'color' => $color['color']
        );
      }
    } else {
      $data['colors'] = '';
    }

    $this->load->model('catalog/category');

    if (isset($this->request->post['categories']) && !empty($this->request->post['categories'])) {
      $data['categories'] = array();
      foreach ($this->request->post['categories'] as $category) {
        $category = $this->model_catalog_category->getCategory($category);
        $data['categories'][] = array(
          'name' => $category['name'],
          'category_id' => $category['category_id']
        );
      }
    } elseif (!empty($item_categories)) {
      $data['categories'] = array();
      foreach ($item_categories as $category) {
        $category = $this->model_catalog_category->getCategory($category['category_id']);
        $data['categories'][] = array(
          'name' => $category['name'],
          'category_id' => $category['category_id']
        );
      }
    } else {
      $data['categories'] = array();
    }

    if (isset($this->request->post['facade'])) {
      $data['facade'] = $this->request->post['facade'];
    } elseif (!empty($item_info)) {
      $data['facade'] = $item_info['facade'];
    } else {
      $data['facade'] = '';
    }

    if (isset($this->request->post['skeleton'])) {
      $data['skeleton'] = $this->request->post['skeleton'];
    } elseif (!empty($item_info)) {
      $data['skeleton'] = $item_info['skeleton'];
    } else {
      $data['skeleton'] = '';
    }

    if (isset($this->request->post['fittings'])) {
      $data['fittings'] = $this->request->post['fittings'];
    } elseif (!empty($item_info)) {
      $data['fittings'] = $item_info['fittings'];
    } else {
      $data['fittings'] = '';
    }

    if (isset($this->request->post['style'])) {
      $data['style'] = $this->request->post['style'];
    } elseif (!empty($item_info)) {
      $data['style'] = $item_info['style'];
    } else {
      $data['style'] = '';
    }

    if (isset($this->request->post['term'])) {
      $data['term'] = $this->request->post['term'];
    } elseif (!empty($item_info)) {
      $data['term'] = $item_info['term'];
    } else {
      $data['term'] = '';
    }

    if (isset($this->request->post['guarantee'])) {
      $data['guarantee'] = $this->request->post['guarantee'];
    } elseif (!empty($item_info)) {
      $data['guarantee'] = $item_info['guarantee'];
    } else {
      $data['guarantee'] = '';
    }

    if (isset($this->request->post['price'])) {
      $data['price'] = $this->request->post['price'];
    } elseif (!empty($item_info)) {
      $data['price'] = $item_info['price'];
    } else {
      $data['price'] = '';
    }

    if (isset($this->request->post['meta_description'])) {
      $data['meta_description'] = $this->request->post['meta_description'];
    } elseif (!empty($item_info)) {
      $data['meta_description'] = $item_info['meta_description'];
    } else {
      $data['meta_description'] = '';
    }

    if (isset($this->request->post['meta_keywords'])) {
      $data['meta_keywords'] = $this->request->post['meta_keywords'];
    } elseif (!empty($item_info)) {
      $data['meta_keywords'] = $item_info['meta_keywords'];
    } else {
      $data['meta_keywords'] = '';
    }

    if (isset($this->request->post['meta_title'])) {
      $data['meta_title'] = $this->request->post['meta_title'];
    } elseif (!empty($item_info)) {
      $data['meta_title'] = $item_info['meta_title'];
    } else {
      $data['meta_title'] = '';
    }
    if (isset($this->request->post['seo_url'])) {
      $data['seo_url'] = $this->request->post['seo_url'];
    } elseif (!empty($seo_url) && $seo_url != '') {
      $data['seo_url'] = $seo_url['keyword'];
    } else {
      $data['seo_url'] = '';
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('mnkitchen/kitchen', $data));

  }

  public function getList()
  {

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('heading_title'),
      'href' => $this->url->link('mnkitchen/kitchen', 'user_token=' . $this->session->data['user_token'], true)
    );

    $data['add'] = $this->url->link('mnkitchen/kitchen/add', 'user_token=' . $this->session->data['user_token'], true);
    $data['delete'] = $this->url->link('mnkitchen/kitchen/delete', 'user_token=' . $this->session->data['user_token'], true);

    $data['items'] = array();

    $results = $this->model_mnkitchen_kitchen->getKitchens();

    $this->load->model('tool/image');

    foreach ($results as $result) {
      if (isset($result['main_image']) && $result['main_image'] != '') {
        $image = $this->model_tool_image->resize($result['main_image'], 140, 140);
      } else {
        $image = $this->model_tool_image->resize('no_image.png', 140, 140);
      }
      $data['items'][] = array(
        'kitchen_id' => $result['kitchen_id'],
        'title' => $result['title'],
        'image' => $image,
        'price' => $result['price'],
        'edit' => $this->url->link('mnkitchen/kitchen/edit', 'user_token=' . $this->session->data['user_token'] . '&kitchen_id=' . $result['kitchen_id'], true)
      );
    }

    $data['header'] = $this->load->controller('common/header');
    $data['column_left'] = $this->load->controller('common/column_left');
    $data['footer'] = $this->load->controller('common/footer');

    $this->response->setOutput($this->load->view('mnkitchen/kitchens_list', $data));

  }

  public function delete()
  {

    $this->load->language('mnkitchen/kitchen');

    $this->document->setTitle($this->language->get('heading_title'));

    $this->load->model('mnkitchen/kitchen');

    $this->load->model('design/seo_url');

    if (isset($this->request->post['selected']) && $this->validateDelete()) {
      foreach ($this->request->post['selected'] as $item_id) {
        $this->model_mnkitchen_kitchen->deleteKitchen($item_id);
        $seo_url = $this->model_design_seo_url->getSeoUrlByQuery('kitchen_id=' . $item_id);
        if (!empty($seo_url)) {
          $this->model_design_seo_url->deleteSeoUrl($seo_url['seo_url_id']);
        }

        $this->model_mnkitchen_kitchen->deleteRelativeKitchenCategory($item_id);
        $this->model_mnkitchen_kitchen->deleteKitchenCategory($item_id);
        $this->model_mnkitchen_kitchen->deleteKitchenImage($item_id);
        $this->model_mnkitchen_kitchen->deleteKitchenColor($item_id);
      }

      $this->session->data['success'] = $this->language->get('text_success');

      $this->response->redirect($this->url->link('mnkitchen/kitchen', 'user_token=' . $this->session->data['user_token'], true));

    }

    $this->getList();

  }

  public function validateDelete()
  {
    if (!$this->user->hasPermission('modify', 'mnkitchen/kitchen')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    return !$this->error;
  }

  public function validateForm()
  {

    if (!$this->user->hasPermission('modify', 'mnkitchen/kitchen')) {
      $this->error['warning'] = $this->language->get('error_permission');
    }

    if (utf8_strlen($this->request->post['title']) < 3 || utf8_strlen($this->request->post['title']) > 255) {
      $this->error['title'] = $this->language->get('error_title');
    }

    if ($this->request->post['main_image'] == '') {
      $this->error['main_image'] = $this->language->get('error_main_image');
    }

    if (utf8_strlen($this->request->post['facade']) > 255) {
      $this->error['facade'] = $this->language->get('error_facade');
    }

    if (utf8_strlen($this->request->post['skeleton']) > 255) {
      $this->error['skeleton'] = $this->language->get('error_skeleton');
    }

    if (utf8_strlen($this->request->post['fittings']) > 255) {
      $this->error['fittings'] = $this->language->get('error_fittings');
    }

    if (utf8_strlen($this->request->post['style']) > 255) {
      $this->error['style'] = $this->language->get('error_style');
    }

    if (utf8_strlen($this->request->post['term']) > 255) {
      $this->error['term'] = $this->language->get('error_term');
    }

    if (utf8_strlen($this->request->post['guarantee']) > 255) {
      $this->error['guarantee'] = $this->language->get('error_guarantee');
    }

    if ($this->request->post['price'] == '') {
      $this->error['price'] = $this->language->get('error_price');
    }

    if (utf8_strlen($this->request->post['meta_title']) < 3 || utf8_strlen($this->request->post['meta_title']) > 255) {
      $this->error['meta_title'] = $this->language->get('error_meta_title');
    }

    if (utf8_strlen($this->request->post['meta_description']) > 255) {
      $this->error['meta_description'] = $this->language->get('error_meta_description');
      echo 'hello';
    }

    if (utf8_strlen($this->request->post['meta_keywords']) > 255) {
      $this->error['meta_keywords'] = $this->language->get('error_meta_keywords');
    }

    if (utf8_strlen($this->request->post['seo_url']) > 255) {
      $this->error['seo_url_length'] = $this->language->get('error_seo_url_length');
    }

    if ($this->request->post['seo_url'] != '') {
      $seo_url = $this->model_design_seo_url->getSeoUrlByKeyword($this->request->post['seo_url']);
      if (preg_match('#edit#', $this->request->get['route'])) {
        $seo_url_by_query = $this->model_design_seo_url->getSeoUrlByQuery('kitchen_id=' . $this->request->get['kitchen_id']);
        if ($seo_url != $seo_url_by_query) {
          $this->error['seo_url_contains'] = $this->language->get('error_seo_url_contains');
        }
      } else {
        if (!empty($seo_url)) {
          echo 'hello';
          $this->error['seo_url_contains'] = $this->language->get('error_seo_url_contains');
        }
      }
    }

    return !$this->error;

  }

}