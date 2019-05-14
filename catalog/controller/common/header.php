<?php
class ControllerCommonHeader extends Controller {
	public function index() {
		// Analytics
		$this->load->model('setting/extension');
		$this->load->model('catalog/category');

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
		    if ($category['top']) {
		        $children_data = array();

		        $children = $this->model_catalog_category->getCategories($category['category_id']);

                foreach ($children as $child) {
                    $children_data[] = array(
                        'name' => $child['name'],
                        'href' => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
                    );
                }

                $data['categories'][] = array(
                    'name' => $category['name'],
                    'href' => $this->url->link('product/category', 'path=' . $category['category_id']),
                    'children' => $children_data
                );
            }
        }

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}

		$data['title'] = $this->document->getTitle();

		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();
		$data['keywords'] = $this->document->getKeywords();
		$data['links'] = $this->document->getLinks();
		$data['styles'] = $this->document->getStyles();
		$data['scripts'] = $this->document->getScripts('header');
        $data['metas'] = $this->document->getMetas();
		$data['lang'] = $this->language->get('code');
		$data['direction'] = $this->language->get('direction');

		$data['name'] = $this->config->get('config_name');

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {
			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$data['logo'] = '';
		}

		$this->load->language('common/header');



		return $this->load->view('common/header', $data);
	}
}
