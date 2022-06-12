<?php
namespace WPV\Api\Admin;
use WP_REST_Controller;
use WP_REST_Request;

class Settings_Route extends WP_REST_Controller {

    protected $namespace;
    protected $rest_base;


    public function __construct() {
        $this->namespace = 'wpv/v1';
        $this->rest_base = 'bookings';
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
                    'callback'=> [$this, 'create_booking'],
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

      public function create_booking(WP_REST_Request $request) {
        $bookingName = sanitize_text_field($request->get_param('name'));
        $email = sanitize_text_field($request->get_param('email'));
        $contactNumber = sanitize_text_field($request->get_param('contact_number'));
        $notes = sanitize_text_field($request->get_param('notes'));
        $bookingStart = sanitize_text_field($request->get_param('booking_start'));
        $bookingEnd = sanitize_text_field($request->get_param('booking_end'));
          global $wpdb;
          $table = $wpdb->prefix . 'bookings_calendar';
          $data = [
          'booking_start_date' => $bookingStart, 
          'booking_end_date' => $bookingEnd,
          'booking_name' => $bookingName,
          'email' => $email,
          'contact_number' => $contactNumber,
          'notes' => $notes,
          ];
          $format = ['%s', '%s', '%s', '%s', '%s', '%s', '%s'];
          $wpdb->insert($table,$data,$format);

        // $wpdb->query(
        //     $wpdb->prepare(
        //     "INSERT INTO $table
        //     (id, new, booking_start_date, booking_end_date, booking_name, email, contact_number, notes)
        //     VALUES ( %d, %d, %s,%d )",
        //     array(
        //         $bookingStart,
        //         $bookingEnd,
        //         $bookingName,
        //         $email,
        //         $contactNumber,
        //         $notes,
        //     )
        //     )
        //     );


        return $wpdb->insert_id;
      }
}