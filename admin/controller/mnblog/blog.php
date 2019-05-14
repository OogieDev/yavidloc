<?php

class ControllerMnblogBlog extends Controller {

    private $error = array();

	public function index() {
		
		$this->load->language('mnblog/blog');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('mnblog/blog');

		$this->getList();

	}

    public function edit() {

        $this->load->language('mnblog/blog');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mnblog/blog');

        $this->load->model('design/seo_url');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm() && isset($this->request->get['article_id'])) {

            $this->model_mnblog_blog->editArticle($this->request->get['article_id'], $this->request->post);


            $seo_data = array(
                'store_id' => 0,
                'language_id' => 1,
                'query' => 'article_id=' . $this->request->get['article_id'],
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

            $this->response->redirect($this->url->link('mnblog/blog', 'user_token=' . $this->session->data['user_token'], true));

        }

        $this->getForm();

    }


    public function add() {

        $this->load->language('mnblog/blog');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mnblog/blog');

        $this->load->model('design/seo_url');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            $article_id = $this->model_mnblog_blog->addArticle($this->request->post);

            if ($this->request->post['seo_url'] != '') {
                $seo_data = array(
                    'store_id' => 0,
                    'language_id' => 1,
                    'query' => 'article_id=' . $article_id,
                    'keyword' => $this->request->post['seo_url']
                );

                $this->model_design_seo_url->addSeoUrl($seo_data);
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('mnblog/blog', 'user_token=' . $this->session->data['user_token'], true));

        }

        $this->getForm();

    }

    private function getForm() {
        $data['text_form'] = !isset($this->request->get['article_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');

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

        if (!isset($this->request->get['article_id'])) {
            $data['action'] = $this->url->link('mnblog/blog/add', 'user_token=' . $this->session->data['user_token'], true);
        } else {
            $data['action'] = $this->url->link('mnblog/blog/edit', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $this->request->get['article_id'], true);
        }

        $data['cancel'] = $this->url->link('mnblog/blog', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['article_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $article_info = $this->model_mnblog_blog->getArticle($this->request->get['article_id']);
            $seo_url = $this->model_design_seo_url->getSeoUrlByQuery('article_id=' . $this->request->get['article_id']);
        }

        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->post['title'])) {
            $data['title'] = $this->request->post['title'];
        } elseif (!empty($article_info)) {
            $data['title'] = $article_info['title'];
        } else {
            $data['title'] = '';
        }

        if (isset($this->request->post['short_description'])) {
            $data['short_description'] = $this->request->post['short_description'];
        } elseif (!empty($article_info)) {
            $data['short_description'] = $article_info['short_description'];
        } else {
            $data['short_description'] = '';
        }

        if (isset($this->request->post['image'])) {
            $data['image'] = $this->request->post['image'];
        } elseif (!empty($article_info)) {
            $data['image'] = $article_info['image'];
        } else {
            $data['image'] = '';
        }

        $this->load->model('tool/image');

        if (isset($this->request->post['image']) && is_file(DIR_IMAGE . $this->request->post['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($this->request->post['image'], 100, 100);
        } elseif (!empty($article_info) && is_file(DIR_IMAGE . $article_info['image'])) {
            $data['thumb'] = $this->model_tool_image->resize($article_info['image'], 100, 100);
        } else {
            $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        }

        $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

        if (isset($this->request->post['seo_url'])) {
            $data['seo_url'] = $this->request->post['seo_url'];
        } elseif (!empty($article_info) && !empty($seo_url)) {
            $data['seo_url'] = $seo_url['keyword'];
        } else {
            $data['seo_url'] = '';
        }

        if (isset($this->request->post['meta_title'])) {
            $data['meta_title'] = $this->request->post['meta_title'];
        } elseif (!empty($article_info)) {
            $data['meta_title'] = $article_info['meta_title'];
        } else {
            $data['meta_title'] = '';
        }

        if (isset($this->request->post['meta_description'])) {
            $data['meta_description'] = $this->request->post['meta_description'];
        } elseif (!empty($article_info)) {
            $data['meta_description'] = $article_info['meta_description'];
        } else {
            $data['meta_description'] = '';
        }

        if (isset($this->request->post['meta_keywords'])) {
            $data['meta_keywords'] = $this->request->post['meta_keywords'];
        } elseif (!empty($article_info)) {
            $data['meta_keywords'] = $article_info['meta_keywords'];
        } else {
            $data['meta_keywords'] = '';
        }

        if (isset($this->request->post['content'])) {
            $data['content'] = $this->request->post['content'];
        } elseif (!empty($article_info)) {
            $data['content'] = $article_info['content'];
        } else {
            $data['content'] = '';
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mnblog/article_form', $data));

    }

    private function validateForm() {

        if (!$this->user->hasPermission('modify', 'mnblog/blog')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (utf8_strlen($this->request->post['title']) < 3 || (utf8_strlen($this->request->post['title']) > 255)) {
            $this->error['title'] = $this->language->get('error_title');
        }

        if (utf8_strlen($this->request->post['short_description']) > 365) {
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

        if ($this->request->post['seo_url'] != '') {
            $seo_article = $this->model_design_seo_url->getSeoUrlsByKeyword($this->request->post['seo_url']);
        } else {
            $seo_article = array();
        }

        if (!empty($seo_article) && !preg_match('#edit#', $this->request->get['route'])) {
            $this->error['seo_url_contains'] = $this->language->get('error_seo_url_contains');
        }

        return !$this->error;

    }

    public function delete() {

        $this->load->language('mnblog/blog');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('mnblog/blog');

        $this->load->model('design/seo_url');

        if (isset($this->request->post['selected']) && $this->validateDelete()) {
            foreach ($this->request->post['selected'] as $article_id) {
                $this->model_mnblog_blog->deleteArticle($article_id);
                $seo_url = $this->model_design_seo_url->getSeoUrlByQuery('article_id=' . $article_id);
                if (!empty($seo_url)) {
                    $this->model_design_seo_url->deleteSeoUrl($seo_url['seo_url_id']);
                }
            }

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('mnblog/blog', 'user_token=' . $this->session->data['user_token'], true));

        }

        $this->getList();

    }

    private function validateDelete() {
        if (!$this->user->hasPermission('modify', 'mnblog/blog')) {
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
			'href' => $this->url->link('mnblog/blog', 'user_token=' . $this->session->data['user_token'], true)
		);

        $data['add'] = $this->url->link('mnblog/blog/add', 'user_token=' . $this->session->data['user_token'], true);
        $data['delete'] = $this->url->link('mnblog/blog/delete', 'user_token=' . $this->session->data['user_token'], true);

        $data['articles'] = array();

        $results = $this->model_mnblog_blog->getArticles();

        foreach ($results as $result) {
            $data['articles'][] = array(
                'article_id' => $result['article_id'],
                'title' => $result['title'],
                'post_date' => $result['post_date'],
                'edit' => $this->url->link('mnblog/blog/edit', 'user_token=' . $this->session->data['user_token'] . '&article_id=' . $result['article_id'], true)
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

        $this->response->setOutput($this->load->view('mnblog/article_list', $data));

	}

}