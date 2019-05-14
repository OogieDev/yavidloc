<?php {

    class Mnpagination {
        public $total = 0;
        public $page = 1;
        public $perPage = 5;
        public $numLinks = 5;
        public $url = '';
        public $textPrev = '<li class="pagination__item"><a href="#"><i class="fa fa-angle-left"></i></a></li>';
        public $textNext = '<li class="pagination__item"><a href="#"><i class="fa fa-angle-right"></i></a></li>';

        public function render() {

            $page = $this->page;
            $total = $this->total;
            $perPage = $this->perPage;
            $numLinks = $this->numLinks;
            $url = $this->url;

            if ($total <= $perPage) {
                return '';
            }

            $pages = ceil($total / $perPage);

            if ($numLinks > $pages) {
                $numLinks = $pages;
            }
            $startItem = $page - 2;

            if ($page + 2 >= $pages) {
                $startItem = $pages - $numLinks;
                if ($startItem == 0) {
                    $startItem = 1;
                }
            }

            if ($page + 2 <= $numLinks) {
                $startItem = 1;
            }

            $output = '<div class="blog__pagination">' . PHP_EOL;
            $output .= '<ul class="pagination">' . PHP_EOL;

            if (($page - 1) > 1) {
                $output .= '<li class="pagination__item"><a href="'. str_replace("{page}", ($page - 1), $url) .'"><i class="fa fa-angle-left"></i></a></li>' . PHP_EOL;
            }

            for ($i = 0; $i < $numLinks; $i++) {
                if($startItem == $page) {
                    $output .= '<li class="pagination__item active">'. $startItem .'</li>';
                    $startItem++;
                    continue;
                }
                $output .= '<li class="pagination__item"><a href="'. str_replace("{page}",  $startItem, $url) .'">'. $startItem .'</a></li>';
                $startItem++;
            }

            if (($page + 4) <= $pages) {
                $output .= '<li class="pagination__item"><a href="'. str_replace("{page}",  $pages, $url) .'">'. $pages .'</a></li>';
            }

            if (($page + 1) < $pages) {
                $output .= '<li class="pagination__item"><a href="'. str_replace("{page}",  ($page + 1), $url) .'"><i class="fa fa-angle-right"></i></a></li>';
            }

            $output .= '</ul>';
            $output .= '</div>';

            return $output;

        }
    }

}