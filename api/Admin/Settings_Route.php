<?php
namespace WPV\Api\Admin;
use WP_REST_Controller;
use WP_REST_Request;
use Datetime;


class Settings_Route extends WP_REST_Controller {

    protected $namespace;
    protected $rest_base;
    protected $bookingsTable;   


    public function __construct() {
        global $wpdb;
        $this->bookingsTable = $wpdb->prefix . 'bookings_calendar';
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
                     'callback'=> [$this, 'get_bookings'],
                     'permission_callback' => [$this, 'get_route_access'],
                 ],
                 [
                    'methods' => \WP_REST_Server::CREATABLE,
                    'callback'=> [$this, 'create_booking'],
                    'permission_callback' => [$this, 'get_route_access'],
                 ],
                 [
                    'methods' => \WP_REST_Server::EDITABLE,
                    'callback'=> [$this, 'updateSingleBooking'],
                    'permission_callback' => [$this, 'get_route_access'],
                 ],
             ]
                 );


            /**
             * Register Route for updating new column in db
             */
            register_rest_route(
            $this->namespace,
            'booking/seen/(?P<id>\d+)',
            array(
            'methods' => 'POST',
            'callback' => [$this, 'setBookingAsSeen'],
            )
            );

              /**
             * Register Route for calendarBookings
             */
            register_rest_route(
                $this->namespace,
                'bookings/(?P<id>\d+)/approve',
                array(
                'methods' => 'POST',
                'callback' => [$this, 'approveBooking'],
                )
                );

            /**
             * Register Route for calendarBookings
             */
            register_rest_route(
                $this->namespace,
                'bookings/calendar',
                array(
                'methods' => 'GET',
                'callback' => [$this, 'bookingsCalendar'],
                )
                );

     }

     /**
      * get route access callback
      */
     public function get_route_access($request) {
         return true;
     }

     public function setBookingAsSeen(WP_REST_Request $request){
        $bookingId = $request['id'];
        $data = ['new' => 0];
        $where = ['id' => $bookingId];
        $valueTypes = ['%d'];
        $this->updateBooking($this->bookingsTable, $data, $where, $valueTypes);

        return $bookingId;
     }

     public function bookingsCalendar(WP_REST_Request $request) {
        $outputType = 'ARRAY_A';
        $query = "SELECT * FROM $this->bookingsTable";
        $bookingsAll = $this->retrieveBookings($query, $outputType);

        return $bookingsAll;

     }

     public function approveBooking(WP_REST_Request $request) 
     {
        $bookingId = $request['id'];
        $data = ['accepted' => 1];
        $where = ['id' => $bookingId];
        $valueTypes = ['%d'];
        $this->updateBooking($this->bookingsTable, $data, $where, $valueTypes);

        return $this->retrieveNewBooking($bookingId);
     }

    /**
     * get items callback
     */
     public function get_bookings(WP_REST_Request $request) {
         /**
          * get the query for all or new bookings
          */
        $bookingTypeQuery = $request->get_param('new');
        /**
         * check for per_page in request 
         */
        if (!isset($request['per_page']) ) {  
            $per_page = 10;  
        } else {  
            $per_page = $request['per_page'];  
        }
        /**
         * Check for page in request
         */
        if (!isset($request['page']) ) {  
            $page = 1;  
        } else {  
            $page = $request['page'];  
        }  
        /**
         * get the first result of page formula
         */
        $page_first_result = ($page-1) * $per_page;  

        /**
         * vars
         */
        $dateStart = $request['date_start'];
        $dateEnd = $request['date_end'];
        $email = $request['email'];
        $name = $request['name'];
        $contactNumber = $request['contact_number'];

        /**
         * Get the total bookings count
         */
        $outputType = 'ARRAY_A';
          if($bookingTypeQuery === "true" && $dateStart && $dateEnd ){
            $query = "SELECT * FROM $this->bookingsTable WHERE new = $bookingTypeQuery AND DATE(booking_start_date) >= '$dateStart' AND DATE(booking_end_date) <= '$dateEnd' AND email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%'";
        }else if($dateStart && $dateEnd){
            $query = "SELECT * FROM $this->bookingsTable WHERE DATE(booking_start_date) >= '$dateStart' AND DATE(booking_end_date) <= '$dateEnd' AND email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%'";
        }else if($bookingTypeQuery === "true"){
            $query = "SELECT * FROM $this->bookingsTable WHERE new = $bookingTypeQuery AND email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%'";

        }else{
            $query = "SELECT * FROM $this->bookingsTable WHERE email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%'";
        }
        $bookingsAll = $this->retrieveBookings($query, $outputType);
        $bookingsCount = COUNT($bookingsAll);
        /**
         * Find the number of pages based on results
         */
        $number_of_pages = ceil ($bookingsCount / $per_page);  
        /**
         * get the bookings from db and return 
         */
        $query = $this->buildQuery($request, $page_first_result, $per_page);

        // if($bookingTypeQuery === "true"){
        //     $query = "SELECT * FROM $this->bookingsTable WHERE new = $bookingTypeQuery LIMIT $page_first_result , $per_page";
        // }else{
        //     $query = "SELECT * FROM $this->bookingsTable LIMIT $page_first_result, $per_page";
        // }
        $bookings = $this->retrieveBookings($query, $outputType);
        $newBookings = array_filter($bookingsAll, function($booking) {
            return $booking['new'] === "1";
        });
         return array(
             'number_of_pages' => $number_of_pages,
             'bookings' => $bookings,
             'newBookings' => COUNT($newBookings)
         );
     }

     public function buildQuery(WP_REST_Request $request, $first_result, $per_page):String {
        $dateStart = $request['date_start'];
        $dateEnd = $request['date_end'];

        $email = $request['email'];
        $name = $request['name'];
        $contactNumber = $request['contact_number'];
        $new = $request['new'];
        $query = '';

        if($new === "true" && $dateStart && $dateEnd ){
            $query = "SELECT * FROM $this->bookingsTable WHERE new = $new AND DATE(booking_start_date) >= '$dateStart' AND DATE(booking_end_date) <= '$dateEnd' AND email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%' LIMIT $first_result, $per_page";
        }else if($dateStart && $dateEnd){
            $query = "SELECT * FROM $this->bookingsTable WHERE DATE(booking_start_date) >= '$dateStart' AND DATE(booking_end_date) <= '$dateEnd' AND email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%' LIMIT $first_result, $per_page";
        }else if($new === "true"){
            $query = "SELECT * FROM $this->bookingsTable WHERE new = $new AND email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%' LIMIT $first_result, $per_page";

        }else{
            $query = "SELECT * FROM $this->bookingsTable WHERE email LIKE '%$email%' AND booking_name LIKE '%$name%' AND contact_number LIKE '%$contactNumber%' LIMIT $first_result, $per_page";
        }

        return $query;
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


        return $this->retrieveNewBooking($wpdb->insert_id);
      }

      public function retrieveNewBooking($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'bookings_calendar';
        $newBooking = $wpdb->get_row( "SELECT * FROM $table WHERE id = $id", ARRAY_A );

        return $newBooking;
      }

      public function retrieveBookings($query, $outputType = null) {
          global $wpdb;
          return $wpdb->get_results($query, $outputType);
      }

      public function updateSingleBooking(WP_REST_Request $request) {
        $bookingName = sanitize_text_field($request->get_param('booking_name'));
        $email = sanitize_text_field($request->get_param('email'));
        $contactNumber = sanitize_text_field($request->get_param('contact_number'));
        $notes = sanitize_text_field($request->get_param('notes'));
        $bookingStart = sanitize_text_field($request->get_param('booking_start_date'));
        $bookingEnd = sanitize_text_field($request->get_param('booking_end_date'));
          $data = [
            'booking_name' => $bookingName,
            'email' => $email,
            'contact_number' => $contactNumber,
            'booking_start_date' => $bookingStart,
            'booking_end_date' => $bookingEnd,
          ];
          $where = [
              'id' => $request['id']
          ];
          $dataTypes = [
            '%s',
            '%s',
            '%d',
            '%s',
            '%s'
          ];
          $this->updateBooking($this->bookingsTable, $data, $where, $dataTypes);

          return $this->retrieveNewBooking($request['id']);
      }

      /**
       * @param string $table
       */
      public function updateBooking($table, $data, $where, $dataTypes) {
        global $wpdb;

        $wpdb->update($table, $data, $where, $dataTypes);

      }
}