<?php

class ControllerMnstockStock extends Controller {

    private $error = array();

    public function index() {

        $this->load->language('mnstock/stock');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mnstock/stock');

        $this->getList();

    }

    public function edit() {

        $this->load->language('mnstock/stock');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mnstock/stock');

        $this->load->model('design/seo_url');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() && isset($this->request->get['stock_id'])) {

            $this->model_mnstock_stock->editStock($this->request->get['stock_id'], $this->request->post);


            $seo_data = array(
                'store_id' => 0,
                'language_id' => 1,
                'query' => 'stock_id=' . $this->request->get['stock_id'],
                'keyword' => $this->request->post['seo_url']
            );

            $seo_url = $this->model_design_seo_url->getSeoUrlByQuery($seo_data['query']);
            if (!empty($seo_url) && $this->request->post['seo_url'] == '') {
                $this->model_design_seo_url->deleteSeoUrl($seo_url['seo_url_id']);
            }
            else if (empty($seo_url) && $this->request->post['seo_url'] != '') {
                $this->model_design_seo_url->addSeoUrl($seo_data);
            } else if (!empty($seo_url) && $this->request->post['seo_url'] != '') {
                $this->model_design_seo_url->editSeoUrl($seo_url['seo_url_id'], $seo_data);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('mnstock/stock', 'user_token=' . $this->session->data['user_token'], true));

        }

        $this->getForm();

    }


    public function add() {

        $this->load->language('mnstock/stock');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mnstock/stock');

        $this->load->model('design/seo_url');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $stock_id = $this->model_mnstock_stock->addStock($this->request->post);

            $seo_data = array(
                'store_id' => 0,
                'language_id' => 1,
                'query' => 'stock_id=' . $stock_id,
                'keyword' => $this->request->post['seo_url']
            );

            if ($this->request->post['seo_url'] != '') {
                $this->model_design_seo_url->addSeoUrl($seo_data);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('mnstock/stock', 'user_token=' . $this->session->data['user_token'], true));

        }

        $this->getForm();

    }

    private function getForm() {
        $data['text_form'] = !isset($this->request->get['stock_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

        if (isset($this->error['seo_url'])) {
            $data['error_seo_url'] = $this->error['seo_url'];
        } else {
            $data['error_seo_url'] = '';
        }

        if (isset($this->error['seo_url_contains'])) {
            $data['error_seo_url_contains'] = $this->error['seo_url_contains'];
        } else {
            $data['error_seo_url_contains'] = '';
        }

        if (isset($this->error['seo_url_length'])) {
            $data['error_seo_url_length'] = $this->error['seo_url_length'];
        } else {
            $data['error_seo_url_length'] = '';
        }

        if (isset($this->error['short_description'])) {
            $data['error_short_description'] = $this->error['short_description'];
        } else {
            $data['error_short_description'] = '';
        }

        if (isset($this->error['image'])) {
            $data['error_image'] = $this->error['image'];
        } else {
            $data['error_image'] = '';
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

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->language->get('data_success');
        } else {
            $data['success'] = '';
        }

        if (!isset($this->request->get['stock_id'])) {
            $data['action'] = $this->url->link('mnstock/stock/add', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('mnstock/stock/edit', 'user_token=' . $this->session->data['user_token'] . '&stock_id=' . $this->request->get['stock_id'], true);
        }

        $data['cancel'] = $this->url->link('mnstock/stock', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['stock_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $stock_info = $this->model_mnstock_stock->getStockById($this->request->get['stock_id']);
            $seo_url = $this->model_design_seo_url->getSeoUrlByQuery('stock_id=' . $this->request->get['stock_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->post['title'])) {
            $data['title'] = $this->request->post['title'];
        } elseif (!empty($stock_info)) {
            $data['title'] = $stock_info['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->request->post['short_description'])) {
            $data['short_description'] = $this->request->post['short_description'];
        } elseif (!empty($stock_info)) {
            $data['short_description'] = $stock_info['short_description'];
        } else {
            $data['short_description'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($stock_info)) {
            $data['image'] = $stock_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($stock_info) && is_file(DIR_IMAGE . $stock_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($stock_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['seo_url'])) {
            $data['seo_url'] = $this->request->post['seo_url'];
        } elseif (!empty($stock_info) && !empty($seo_url)) {
            $data['seo_url'] = $seo_url['keyword'];
        } else {
            $data['seo_url'] = '';
        }

        if (isset($this->request->post['meta_title'])) {
            $data['meta_title'] = $this->request->post['meta_title'];
        } elseif (!empty($stock_info)) {
            $data['meta_title'] = $stock_info['meta_title'];
        } else {
            $data['meta_title'] = '';
        }

        if (isset($this->request->post['meta_description'])) {
            $data['meta_description'] = $this->request->post['meta_description'];
        } elseif (!empty($stock_info)) {
            $data['meta_description'] = $stock_info['meta_description'];
        } else {
            $data['meta_description'] = '';
        }

        if (isset($this->request->post['meta_keywords'])) {
            $data['meta_keywords'] = $this->request->post['meta_keywords'];
        } elseif (!empty($stock_info)) {
            $data['meta_keywords'] = $stock_info['meta_keywords'];
        } else {
            $data['meta_keywords'] = '';
        }

        if (isset($this->request->post['content'])) {
            $data['content'] = $this->request->post['content'];
        } elseif (!empty($stock_info)) {
            $data['content'] = $stock_info['content'];
        } else {
            $data['content'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mnstock/stock_form', $data));

    }

    private function validateForm() {

        if (!$this->user->hasPermission('modify', 'mnstock/stock')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (utf8_strlen($this->request->post['title']) < 3 || (utf8_strlen($this->request->post['title']) > 255)) {
            $this->error['title'] = $this->language->get('error_title');
        }

        if (utf8_strlen($this->request->post['short_description']) > 300) {
            $this->error['short_description'] = $this->language->get('error_short_description');
        }

        if (!$this->request->post['image'] || empty($this->request->post['image'])) {
            $this->error['image'] = $this->language->get('error_image');
        }

        if (utf8_strlen($this->request->post['meta_description']) > 2000) {
            $this->error['meta_description'] = $this->language->get('error_meta_description');
        }

        if (utf8_strlen($this->request->post['meta_keywords']) > 2000) {
            $this->error['meta_keywords'] = $this->language->get('error_meta_keyword');
        }

        if (utf8_strlen($this->request->post['meta_title']) < 3 || utf8_strlen($this->request->post['meta_title']) > 300) {
            $this->error['meta_title'] = $this->language->get('error_meta_title');
        }

        if (utf8_strlen($this->request->post['seo_url']) > 300) {
            $this->error['seo_url_length'] = $this->language->get('error_seo_url_length');
        }

        $seo_stock = $this->model_design_seo_url->getSeoUrlsByKeyword($this->request->post['seo_url']);

        if (!empty($seo_stock) && $this->request->post['seo_url'] != '' && !preg_match('#edit#', $this->request->get['route'])) {
            $this->error['seo_url_contains'] = $this->language->get('error_seo_url_contains');
        }

        return !$this->error;

    }

    public function delete() {

        $this->load->language('mnstock/stock');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mnstock/stock');

        $this->load->model('design/seo_url');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $stock_id) {
                $this->model_mnstock_stock->deleteStock($stock_id);
                $seo_url = $this->model_design_seo_url->getSeoUrlByQuery('stock_id=' . $stock_id);
                if (!empty($seo_url)) {
                    $this->model_design_seo_url->deleteSeoUrl($seo_url['seo_url_id']);
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('mnstock/stock', 'user_token=' . $this->session->data['user_token'], true));

        }

        $this->getList();

    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'mnstock/stock')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    private function getList() {

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('mnstock/stock', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['add'] = $this->url->link('mnstock/stock/add', 'user_token=' . $this->session->data['user_token'], true);
        $data['delete'] = $this->url->link('mnstock/stock/delete', 'user_token=' . $this->session->data['user_token'], true);

        $data['stock'] = array();

        $results = $this->model_mnstock_stock->getStock();

        foreach ($results as $result) {
            $data['stock'][] = array(
                'stock_id' => $result['stock_id'],
                'title' => $result['title'],
                'post_date' => $result['post_date'],
                'edit' => $this->url->link('mnstock/stock/edit', 'user_token=' . $this->session->data['user_token'] . '&stock_id=' . $result['stock_id'], true)
            );
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['success'])) {
            $data['success'] = $this->error['success'];
        } else {
            $data['success'] = '';
        }

        if (isset($this->request->post['selected'])) {
            $data['selected'] = (array)$this->request->post['selected'];
        } else {
            $data['selected'] = array();
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mnstock/stock_list', $data));

    }

}