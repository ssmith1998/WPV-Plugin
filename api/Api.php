<?php 

namespace WPV\Api;

use WP_REST_Controller;
use WPV\Api\Admin\Settings_Route;

class Api extends WP_REST_Controller {
    public function __construct() {
        add_action('rest_api_init', [$this, 'register_routes']);
    }

    /**
     * Register Api Routes
     */

     public function register_routes() {
        (new Settings_Route())->register_routes();
     }
}