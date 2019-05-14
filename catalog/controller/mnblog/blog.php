<?php

class ControllerMnblogBlog extends Controller {

    public function index() {

        $this->load->model('mnblog/blog');

        if (isset($this->request->get['article_id'])) {
            $this->getArticlePage();
        } else {
            $this->getBlogPage();
        }

    }

    private function getBlogPage() {

        $this->document->setTitle('Блог');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => '/'
        );
        $data['breadcrumbs'][] = array(
            'text' => 'Блог',
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

        $articles = $this->model_mnblog_blog->getArticles($filterData);
        if (empty($articles)) {
            $this->response->redirect('error/not_found');
        }
        $data['articles'] = array();
        foreach ($articles as $article) {
            $url = $this->model_mnblog_blog->getSeoUrlByQuery($article['article_id']);
            if (!empty($url)) {
                $url = $url['keyword'];
            } else {
                $url = false;
            }
            $data['articles'][] = array(
                'article_id' => $article['article_id'],
                'title' => $article['title'],
                'short_description' => $article['short_description'],
                'post_date' => $article['post_date'],
                'image' => '/image/' . $article['image'],
                'url' => $url
            );
        }

        $pagination->total = $this->model_mnblog_blog->getTotalArticles();
        $pagination->url = '/blog?page={page}';

        $data['pagination'] = $pagination->render();
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mnblog/blog', $data));
    }

    private function getArticlePage() {

        $article = $this->model_mnblog_blog->getArticle($this->request->get['article_id']);

        if (empty($article)) {
            $this->response->redirect('error/not_found');
        }

        $this->document->setTitle($article['meta_title']);
        $this->document->setDescription($article['meta_description']);
        $this->document->setKeywords($article['meta_keywords']);

        $this->document->addMeta('og:title', 'property', $article['title']);
        $this->document->addMeta('og:image', 'property', HTTP_SERVER . 'image/' . $article['image']);

        $data['og_title'] = 'Og hello world';

        $data['article'] = array(
            'id' => $article['article_id'],
            'post_date' => $article['post_date'],
            'content' => html_entity_decode($article['content']),
            'title' => $article['title'],
            'url' => HTTP_SERVER . substr($_SERVER['REQUEST_URI'], 1),
            'img' => HTTP_SERVER . 'image/' . $article['image']
        );

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Блог',
            'href' => $this->url->link('mnblog/blog')
        );

        $data['breadcrumbs'][] = array(
            'text' => $article['title'],
            'href' => ''
        );

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('mnblog/article', $data));
    }

}