<?php

class ControllerMnkitchenKitchen extends Controller
{

  public function index()
  {

    $this->load->model('mnkitchen/kitchen');
    $this->load->model('catalog/category');

    $kitchen = $this->model_mnkitchen_kitchen->getKitchen($this->request->get['kitchen_id']);
    $kitchenColors = $this->model_mnkitchen_kitchen->getKitchenColors($kitchen['kitchen_id']);
    $kitchenImages = $this->model_mnkitchen_kitchen->getKitchenImages($kitchen['kitchen_id']);

    $this->document->setTitle($kitchen['meta_title']);
    $this->document->setDescription($kitchen['meta_description']);
    $this->document->getKeywords($kitchen['meta_keywords']);
    $this->document->addLink('/catalog/view/theme/yavid/stylesheet/libs/slick/slick.css', 'stylesheet');

    if (isset($this->request->get['path'])) {
      $category = $this->model_catalog_category->getCategory($this->request->get['path']);
    } else {
      $category = $this->model_mnkitchen_kitchen->getCategory($kitchen['kitchen_id']);
      $category = $this->model_catalog_category->getCategory($category['category_id']);
    }

    $data['breadcrumbs'] = array();
    $data['breadcrumbs'][] = array(
      'text' => $this->language->get('text_home'),
      'href' => $this->url->link('common/home')
    );
    $data['breadcrumbs'][] = array(
      'text' => $category['name'],
      'href' => $this->url->link('catalog/category', 'path=' . $category['category_id'])
    );
    $data['breadcrumbs'][] = array(
      'text' => $kitchen['title'],
      'href' => ''
    );

    $data['kitchen'] = array(
      'kitchen_id' => $kitchen['kitchen_id'],
      'title' => $kitchen['title'],
      'description' => html_entity_decode($kitchen['description']),
      'facade' => $kitchen['facade'],
      'skeleton' => $kitchen['skeleton'],
      'fittings' => $kitchen['fittings'],
      'style' => $kitchen['style'],
      'term' => $kitchen['term'],
      'guarantee' => $kitchen['guarantee'],
      'price' => $kitchen['price']
    );

    foreach ($kitchenColors as $kitchenColor) {
      $data['kitchen']['colors'][] = 'image/' . $kitchenColor['color'];
    }

    foreach ($kitchenImages as $kitchenImage) {
      $data['kitchen']['images'][] = 'image/' . $kitchenImage['image'];
    }

    if ($category['category_id'] != 68) {
      $diningGroupsIds = $this->model_mnkitchen_kitchen->getDiningGroups();
      $data['dining'] = array();
      if (!empty($diningGroupsIds)) {
        foreach ($diningGroupsIds as $diningGroupsId) {
          $dinigGroup = $this->model_mnkitchen_kitchen->getKitchen($diningGroupsId['kitchen_id']);
          $data['dining'][] = array(
            'title' => $dinigGroup['title'],
            'image' => '/image/' . $dinigGroup['main_image'],
            'href' => $this->url->link('mnkitchen/kitchen', 'kitchen_id=' . $diningGroupsId['kitchen_id'])
          );
        }
      }
    }

    $data['header'] = $this->load->controller('common/header');
    $data['footer'] = $this->load->controller('common/footer');
    $this->response->setOutput($this->load->view('mnkitchen/kitchen', $data));
  }

}