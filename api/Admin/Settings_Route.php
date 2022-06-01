<?php
namespace WPV\Api\Admin;
use WP_REST_Controller;

class Settings_Route extends WP_REST_Controller {

    protected $namespace;
    protected $rest_base;


    public function __construct() {
        $this->namespace = 'wpv/v1';
        $this->rest_base = 'settings';
    }


    /**
     * Register Routes
     */

     public function register_routes() {
         register_rest_route(
             $this->namespace,
             $this->rest_base,
             [
                 [
                     'methods' => \WP_REST_Server::READABLE,
                     'callback'=> [$this, 'get_items'],
                     'permission_callback' => [$this, 'get_route_access'],
                 ],
                 [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback'=> [$this, 'create_items'],
                    'permission_callback' => [$this, 'get_route_access'],
                 ],
             ]
                 );
     }

     /**
      * get route access callback
      */
     public function get_route_access($request) {
         return true;
     }


    /**
     * get items callback
     */
     public function get_items($request) {
         $response = [
             'first_name' => 'john',
             'second_name' => 'doe',
             'email' => 'doe@joe.com',
         ];

         return rest_ensure_response($response);
     }

     /**
      * Create Items callback
      */

      public function create_items($request) {
        $firstName = 'John';
        $lastName = 'Doe';
        $email = 'doe@john.com';


        update_option('wpv_settings_first_name', $firstName);
        update_option('wpv_settings_last_name', $lastName);
        update_option('wpv_settings_email', $email);

        $response = true;


        return rest_ensure_response($response);
      }
}