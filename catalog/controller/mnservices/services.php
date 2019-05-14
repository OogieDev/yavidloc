<?php

class ControllerMnservicesServices extends Controller {

    public function index() {

        $this->load->model('mnservices/services');

        if (isset($this->request->get['service_id'])) {
            $this->getSingleServicePage();
        } else {
            $this->getServicesPage();
        }

    }

    public function getSingleServicePage() {

        $service = $this->model_mnservices_services->getService($this->request->get['service_id']);

        if (empty($service)) {
            $this->response->redirect('error/not_found');
        }

        $this->document->setTitle($service['meta_title']);
        $this->document->setDescription($service['meta_description']);
        $this->document->setKeywords($service['meta_keywords']);

        $this->document->addMeta('og:title', 'property', $service['title']);
        $this->document->addMeta('og:image', 'property', HTTP_SERVER . 'image/' . $service['image']);

        $data['og_title'] = 'Og hello world';

        $data['service'] = array(
            'id' => $service['service_id'],
            'content' => html_entity_decode($service['content']),
            'title' => $service['title'],
            'url' => HTTP_SERVER . substr($_SERVER['REQUEST_URI'], 1),
            'img' => HTTP_SERVER . 'image/' . $service['image']
        );

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Услуги',
            'href' => $this->url->link('mnservices/services')
        );

        $data['breadcrumbs'][] = array(
            'text' => $service['title'],
            'href' => ''
        );

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('mnservices/service', $data));

    }

    public function getServicesPage() {

        $this->document->setTitle('Услуги');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => 'Услуги',
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

        $services = $this->model_mnservices_services->getServices($filterData);

        if (empty($services)) {
            $this->response->redirect('error/not_found');
        }

        $data['services'] = array();
        foreach ($services as $service) {
            $url = $this->model_mnservices_services->getSeoUrlByQuery($service['service_id']);
            if (!empty($url)) {
                $url = '/' . $url['keyword'];
            } else {
                $url = $this->url->link('mnservices/services', 'service_id=' . $service['service_id']);
            }
            $data['services'][] = array(
                'service_id' => $service['service_id'],
                'title' => html_entity_decode($service['title']),
                'short_description' => $service['short_description'],
                'image' => '/image/' . $service['image'],
                'url' => $url
            );
        }

        $pagination->total = $this->model_mnservices_services->getTotalServices();
        $pagination->url = $this->url->link('mnservices/services', 'page={page}');

        $data['pagination'] = $pagination->render();
        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('mnservices/services', $data));

    }

    public function getServicePage() {

    }

}