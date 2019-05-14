<?php

class ControllerInformationCalc extends Controller {

    public function index() {

//        if ($this->request->server['REQUEST_METHOD'] == 'POST' && $this->validate()) {
//            $this->sendMail();
//        }

        $this->load->language('information/calc');

        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => '/'
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => ''
        );

        $data['header'] = $this->load->controller('common/header');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('information/calc', $data));

    }

    private function sendMail() {

    }

    private function validate() {

    }

}