<?php

class ControllerMnstockStock extends Controller {

    public function index() {

        $this->load->model('mnstock/stock');

        if (isset($this->request->get['stock_id'])) {
            $this->getSingleStockPage();
        } else {
            $this->getStockPage();
        }

    }

    public function getSingleStockPage() {

        $stock = $this->model_mnstock_stock->getStock($this->request->get['stock_id']);

        if (empty($stock)) {
            $this->response->redirect('error/not_found');
        }

        $this->document->setTitle($stock['meta_title']);
        $this->document->setDescription($stock['meta_description']);
        $this->document->setKeywords($stock['meta_keywords']);

        $this->document->addMeta('og:title', 'property', $stock['title']);
        $this->document->addMeta('og:image', 'property', HTTP_SERVER . 'image/' . $stock['image']);

        $data['stock'] = array(
            'id' => $stock['stock_id'],
            'post_date' => $stock['post_date'],
            'content' => html_entity_decode($stock['content']),
            'title' => $stock['title'],
            'url' => HTTP_SERVER . substr($_SERVER['REQUEST_URI'], 1),
            'img' => HTTP_SERVER . 'image/' . $stock['image']
        );

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Акции',
            'href' => $this->url->link('mnstock/stock')
        );

        $data['breadcrumbs'][] = array(
            'text' => $stock['title'],
            'href' => ''
        );

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('mnstock/stock', $data));

    }

    public function getStockPage() {

        $this->document->setTitle('Акции');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Акции',
            'href' => ''
        );


        $pagination = new Mnpagination();
        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }
        $pagination->page = $page;
        $filterData = array(
            'page' => $page * $pagination->perPage - $pagination->perPage,
            'offset' => $pagination->perPage
        );
        $stock = $this->model_mnstock_stock->getStockList($filterData);
        if (empty($stock)) {
            $this->response->redirect('error/not_found');
        }

        $data['stock'] = array();
        foreach ($stock as $item) {
            $url = $this->model_mnstock_stock->getSeoUrlByQuery($item['stock_id']);
            if (!empty($url)) {
                $url = $url['keyword'];
            } else {
                $url = false;
            }
            $data['stock'][] = array(
                'stock_id' => $item['stock_id'],
                'title' => $item['title'],
                'short_description' => $item['short_description'],
                'post_date' => $item['post_date'],
                'image' => '/image/' . $item['image'],
                'url' => $url
            );
        }

        $pagination->total = $this->model_mnstock_stock->getTotalStock();
        $pagination->url = '/akcii?page={page}';

        $data['pagination'] = $pagination->render();
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mnstock/stock_list', $data));

    }

}