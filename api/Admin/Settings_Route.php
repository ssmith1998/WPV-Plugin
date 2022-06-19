<?php
namespace WPV\Api\Admin;
use WP_REST_Controller;
use WP_REST_Request;

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
     }

    /**
     * get items callback
     */
     public function get_bookings(WP_REST_Request $request) {
        $bookingTypeQuery = $request->get_param('new');
        if($bookingTypeQuery === "true"){
            $query = "SELECT * FROM $this->bookingsTable WHERE new = $bookingTypeQuery;";
        }else{
            $query = "SELECT * FROM $this->bookingsTable";
        }
        $outputType = 'ARRAY_A';
        $bookings = $this->retrieveBookings($query, $outputType);
         return $bookings;
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

      /**
       * @param string $table
       */
      public function updateBooking($table, $data, $where, $dataTypes) {
        global $wpdb;

        $wpdb->update($table, $data, $where, $dataTypes);

      }
}