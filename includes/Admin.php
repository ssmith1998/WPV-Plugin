<?php 

namespace WPV\Includes;

class Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'register_scripts_styles']);
        $this->createDBTables();
    }

    public function register_scripts_styles() {
        $this->loadStyles();
        $this->loadScripts();
    }

    public function loadScripts() {
        wp_register_script('wpv-manifest', WPV_PLUGIN_URL . 'assets/js/manifest.js', [], rand(), true);
        wp_register_script('wpv-vendor', WPV_PLUGIN_URL . 'assets/js/vendor.js', ['wpv-manifest'], rand(), true);
        wp_register_script('wpv-admin', WPV_PLUGIN_URL . 'assets/js/admin.js', [], rand(), true);
        wp_register_script('font-awesome', 'https://kit.fontawesome.com/f2e27c939b.js', [], rand(), true);
        wp_register_script('sweet-alert', WPV_PLUGIN_URL . 'node_modules/sweetalert2/dist/sweetalert2.all.min.js', [], rand(), true);

        wp_enqueue_script('wpv-manifest');
        wp_enqueue_script('wpv-vendor');
        wp_enqueue_script('wpv-admin');
        wp_enqueue_script('font-awesome');
        wp_enqueue_script('sweet-alert');

        wp_localize_script('wpv-admin', 'wpvadminlocalizer', [
            'adminUrl' => admin_url('/'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'apiUrl' => home_url('/wp-json')
        ]);
    }

    public function loadStyles() {
        wp_register_style('wpv-admin', WPV_PLUGIN_URL . 'assets/css/admin.css');
        wp_register_style('wpv-admin-BS', WPV_PLUGIN_URL . 'node_modules/bootstrap/dist/css/bootstrap.min.css');


        wp_enqueue_style('wpv-admin');
        wp_enqueue_style('wpv-admin-BS');
    }

    public function createDBTables() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'bookings_calendar';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            new tinyint(1) default true NOT NULL,
            booking_start_date datetime NOT NULL,
            booking_end_date datetime NOT NULL,
            accepted tinyint(1) default false NOT NULL,
            booking_name varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            contact_number varchar(255) NOT NULL,
            notes varchar(255) NOT NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
    }


    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';
        $slug = 'wp-vue';

        add_menu_page(
            _('WP Vue', 'textdomain'),
            _('WP Vue', 'textdomain'),
            $capability,
            $slug,
            [$this, 'menu_page_template'],
            ''
        );

        if(current_user_can($capability)) {
            $submenu[$slug][] = [_('Calendar', 'textdomain'), $capability, 'admin.php?page=' . $slug . '#/'];
            $submenu[$slug][] = [_('Settings', 'textdomain'), $capability, 'admin.php?page=' . $slug . '#/settings'];
        }

    }

    public function menu_page_template() {
        echo '<div class="wrap"><div id="wpv-admin-app"></div></div>';
    }
}