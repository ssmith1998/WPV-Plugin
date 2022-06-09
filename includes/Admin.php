<?php 

namespace WPV\Includes;

class Admin {
    public function __construct() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'register_scripts_styles']);
    }

    public function register_scripts_styles() {
        $this->loadStyles();
        $this->loadScripts();
    }

    public function loadScripts() {
        wp_register_script('wpv-manifest', WPV_PLUGIN_URL . 'assets/js/manifest.js', [], rand(), true);
        wp_register_script('wpv-vendor', WPV_PLUGIN_URL . 'assets/js/vendor.js', ['wpv-manifest'], rand(), true);
        wp_register_script('wpv-admin', WPV_PLUGIN_URL . 'assets/js/admin.js', [], rand(), true);

        wp_enqueue_script('wpv-manifest');
        wp_enqueue_script('wpv-vendor');
        wp_enqueue_script('wpv-admin');

        wp_localize_script('wpv-admin', 'wpvadminlocalizer', [
            'adminUrl' => admin_url('/'),
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'apiUrl' => home_url('/wp-json')
        ]);
    }

    public function loadStyles() {
        wp_register_style('wpv-admin', WPV_PLGUIN_URL . 'assets/css/admin.css');
        wp_register_style('wpv-admin-BS', WPV_PLUGIN_URL . 'node_modules/bootstrap/dist/css/bootstrap.min.css');


        wp_enqueue_style('wpv-admin');
        wp_enqueue_style('wpv-admin-BS');
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