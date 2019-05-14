<?php

class ControllerProductCategory extends Controller
{
  public function index()
  {
    $this->load->language('product/category');
    $this->load->model('catalog/category');
    $this->load->model('mnkitchen/kitchen');

    $data['breadcrumbs'] = array();

    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home')
    );

    if (isset($this->request->get['path'])) {
      $path = '';

      $parts = explode('_', (string)$this->request->get['path']);

      $category_id = (int)array_pop($parts);

      foreach ($parts as $path_id) {
        if (!$path) {
          $path = (int)$path_id;
        } else {
          $path .= '_' . (int)$path_id;
        }

        $category_info = $this->model_catalog_category->getCategory($path_id);

        if ($category_info) {
          $data['breadcrumbs'][] = array(
            'text' => $category_info['name'],
            'href' => $this->url->link('product/category', 'path=' . $path)
          );
        }
      }
    } else {
      $category_id = 0;
    }



    $category_info = $this->model_catalog_category->getCategory($category_id);

    if ($category_info) {
      $this->document->setTitle($category_info['meta_title']);
      $this->document->setDescription($category_info['meta_description']);
      $this->document->setKeywords($category_info['meta_keyword']);

      $data['heading_title'] = $category_info['name'];

      // Set the last category breadcrumb
      $data['breadcrumbs'][] = array(
        'text' => $category_info['name'],
        'href' => $this->url->link('product/category', 'path=' . $this->request->get['path'])
      );
      $data['category_name'] = $category_info['name'];
      $data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');

      $data['products'] = array();
      $results = $this->model_mnkitchen_kitchen->getKitchens($category_id);

      if (!empty($results)) {
        foreach ($results as $result) {
          $data['kitchens'][] = array(
            'product_id' => $result['kitchen_id'],
            'href' => $this->url->link('mnkitchen/kitchen', 'path=' . $this->request->get['path'] . '&kitchen_id=' . $result['kitchen_id']),
            'image' => 'image/' . $result['main_image'],
            'title' => $result['title']
          );
        }
      } else {
        $categories = $this->model_catalog_category->getCategories($category_id);

        if (!empty($categories)) {
          $data['categories'] = array();

          foreach ($categories as $category) {
            $data['categories'][] = array(
              'name' => $category['name'],
              'href' => $this->url->link('catalog/category', 'path=' . $category['category_id'])
            );
          }
        }
      }


      $data['footer'] = $this->load->controller('common/footer');
      $data['header'] = $this->load->controller('common/header');

      $this->response->setOutput($this->load->view('product/category', $data));
    } else {

      $data['breadcrumbs'][] = array(
        'text' => $this->language->get('text_error'),
        'href' => $this->url->link('product/category')
      );

      $this->document->setTitle($this->language->get('text_error'));

      $data['continue'] = $this->url->link('common/home');

      $this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . ' 404 Not Found');

      $data['column_left'] = $this->load->controller('common/column_left');
      $data['column_right'] = $this->load->controller('common/column_right');
      $data['content_top'] = $this->load->controller('common/content_top');
      $data['content_bottom'] = $this->load->controller('common/content_bottom');
      $data['footer'] = $this->load->controller('common/footer');
      $data['header'] = $this->load->controller('common/header');

      $this->response->setOutput($this->load->view('error/not_found', $data));
    }
  }
}
