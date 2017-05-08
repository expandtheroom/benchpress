<?php

namespace BenchPress\Tools;

use BenchPress\Hooks\Base_Filter;

class Map_Remote_Uploads extends Base_Filter {

    private $uploads_url;

    public function __construct( $args ) {
        $this->uploads_url = $args['uploads_url'];
    }

    protected function get_filter() {
        return 'upload_dir';
    }

    protected function callback( $uploads_info ) {
        $uploads_info['baseurl'] = $this->uploads_url;

        return $uploads_info;
    }
}
