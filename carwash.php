<?php

/**
 * Plugin Name:       Carwash
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       A simple plugin for car service
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.4
 * Author:            JoomShaper
 * Author URI:        https://www.joomshaper.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       carwash
 * Domain Path:       /languages
 */

require_once('vendor/autoload.php');

use Carwash\CarwashHelper;

define('CARWASH_ASSETS_DIR', plugin_dir_url(__FILE__) . 'assets/');

/**
 * Carwash class
 * 
 * @since 1.0.0
 */
class Carwash
{
    private $version;
    /**
     * Construct function
     */
    public function __construct()
    {
        $this->version = time();
        // Initialize with loading textdomain
        add_action('plugins_loaded', array($this, 'load_textdomain'));

        // Create Admin menu items
        add_action('admin_menu', array($this, 'create_admin_submenu'));

        // Add Custom taxonomy Car Model
        add_action('init', array($this, 'create_taxonomy_car_model'));

        // Load assets on Admin
        add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));

        // Load Admin dashboard widget
        add_action('wp_dashboard_setup', array($this, 'dashboard_widget'));

        // Check Log In role
        add_action('admin_init', array($this, 'check_login_role'));

        // Custom post Car
        add_action('init', array($this, 'register_car'));
        add_filter('manage_car_posts_columns', array($this, 'car_custom_column'));
        add_action('manage_car_posts_custom_column', array($this, 'car_column_populate'), 10, 2);

        // Custom post Service
        add_action('init', array($this, 'register_service'));
        add_action('add_meta_boxes', array($this, 'add_service_metabox'));
        add_action('save_post', array($this, 'save_service_metadata'));
        add_filter('manage_service_posts_columns', array($this, 'service_custom_column'));
        add_action('manage_service_posts_custom_column', array($this, 'service_column_populate'), 10, 2);

        // Custom post Package
        add_action('init', array($this, 'register_package'));
        add_action('add_meta_boxes', array($this, 'add_package_metabox'));
        add_action('save_post', array($this, 'save_package_metadata'));
        add_filter('manage_package_posts_columns', array($this, 'package_custom_column'));
        add_action('manage_package_posts_custom_column', array($this, 'package_column_populate'), 10, 2);

        // Custom post Appointment
        add_action('init', array($this, 'register_appointment'));
        add_action('add_meta_boxes', array($this, 'add_appointment_metabox'));
        add_action('save_post', array($this, 'save_appointment_metadata'));
        add_filter('manage_appointment_posts_columns', array($this, 'appointment_custom_column'));
        add_action('manage_appointment_posts_custom_column', array($this, 'appointment_column_populate'), 10, 2);

        // Load assets on Frontend
        add_action('wp_enqueue_scripts', array($this, 'load_front_assets'));

        // Short code for Frontend Appointment module
        add_shortcode('carwash_appointment', array($this, 'front_appointment'));

        // Ajax action for Appointment
        add_action('wp_ajax_carwash_add_appointment', array($this, 'carwash_add_appointment'));
        add_action('wp_ajax_nopriv_carwash_add_appointment', array($this, 'carwash_add_appointment'));
        add_action('send_customer_email', array($this, 'send_customer_email'));

        // Ajax action for Frontend Login
        add_action('wp_ajax_nopriv_carwash_front_login', array($this, 'carwash_front_login'));

        // Ajax action for Frontend Registration
        add_action('wp_ajax_nopriv_carwash_front_registration', array($this, 'carwash_front_registration'));
    }

    /**
     * Function to load Textdomain
     *
     * @return void
     */
    public function load_textdomain()
    {
        load_plugin_textdomain('carwash', false, dirname(__FILE__) . "/languages");
    }

    /**
     * Function to create Admin Sub-Menu
     *
     * @return void
     */
    public function create_admin_submenu()
    {
        add_menu_page(__('Carwash', 'carwash'), __('Carwash', 'carwash'), 'manage_options', 'carwash-menu', '', 'dashicons-car', 25);
        add_submenu_page('carwash-menu', __('Cars', 'carwash'), __('Cars', 'carwash'), 'manage_options', 'edit.php?post_type=car', '', 1);
        add_submenu_page('carwash-menu', __('Car Models', 'carwash'), __('Car Models', 'carwash'), 'manage_options', 'edit-tags.php?taxonomy=car_model&post_type=car', '', 2);
        add_submenu_page('carwash-menu', __('Services', 'carwash'), __('Services', 'carwash'), 'manage_options', 'edit.php?post_type=service', '', 3);
        add_submenu_page('carwash-menu', __('Packages', 'carwash'), __('Packages', 'carwash'), 'manage_options', 'edit.php?post_type=package', '', 4);
        add_submenu_page('carwash-menu', __('Appointments', 'carwash'), __('Appointments', 'carwash'), 'manage_options', 'edit.php?post_type=appointment', '', 5);

        remove_submenu_page('carwash-menu', 'carwash-menu');
    }

    /**
     * Function to create custom taxonomy Car Model
     *
     * @return void
     */
    public function create_taxonomy_car_model()
    {
        // Add new taxonomy car_model, NOT hierarchical (like tags)
        $labels = array(
            'name'                       => _x('Car Models', 'taxonomy general name', 'carwash'),
            'singular_name'              => _x('Car Model', 'taxonomy singular name', 'carwash'),
            'search_items'               => __('Search Car Models', 'carwash'),
            'popular_items'              => __('Popular Car Models', 'carwash'),
            'all_items'                  => __('All Car Models', 'carwash'),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'edit_item'                  => __('Edit Car Model', 'carwash'),
            'update_item'                => __('Update Car Model', 'carwash'),
            'add_new_item'               => __('Add New Car Model', 'carwash'),
            'new_item_name'              => __('New Car Model Name', 'carwash'),
            'separate_items_with_commas' => __('Separate Car Models with commas', 'carwash'),
            'add_or_remove_items'        => __('Add or remove Car Models', 'carwash'),
            'choose_from_most_used'      => __('Choose from the most used Car Models', 'carwash'),
            'not_found'                  => __('No Car Models found.', 'carwash'),
            'menu_name'                  => __('Car Models', 'carwash'),
        );

        $args = array(
            'hierarchical'          => false,
            'labels'                => $labels,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var'             => true,
            'rewrite'               => array('slug' => 'car-model'),
        );

        register_taxonomy('car_model', 'car', $args);
    }

    /**
     * Function to load necessary assets in Admin
     *
     * @return void
     */
    public function load_admin_assets()
    {
        wp_enqueue_style('carwash-main-css', CARWASH_ASSETS_DIR . 'admin/css/style.css', null, $this->version);
        wp_enqueue_script('carwash-main-js', CARWASH_ASSETS_DIR . 'admin/js/main.js', array('jquery'), $this->version, true);
        
        // Pass data to js file
        $data = array(
            'confirm_text'  => __('Are you sure?', 'carwash'),
            'nonce'         => wp_create_nonce('wp_rest')
        );
        wp_localize_script('carwash-main-js', 'carwash_info', $data);
    }

    /**
     * Function for dashboard Widget
     *
     * @return void
     */
    public function dashboard_widget()
    {
        wp_add_dashboard_widget('carwash_dashboard', __('Carwash Widget', 'carwash'), array($this, 'dashboard_widget_output'), null, null, 'normal', 'high');
    }

    /**
     * Function to output the widget
     *
     * @return void
     */
    public function dashboard_widget_output()
    {
        $data['total_cars'] = wp_count_posts('car')->publish;
        $data['total_services'] = wp_count_posts('service')->publish;
        $data['total_packages'] = wp_count_posts('package')->publish;
        $data['total_appointments'] = wp_count_posts('appointment')->publish;

        $args = array(
            'post_type'     => 'appointment',
            'post_status'   => 'publish',
            'order'         => 'DESC',
            'numberposts'   => 5,
        );
        $appointments = get_posts($args);

        foreach ($appointments as $key => $appointment) {
            $package_id = get_post_meta($appointment->ID, 'carwash_package_id', true);
            $appointments[$key]->package_name = get_post_field('post_title', $package_id);

            $appointments[$key]->customer_name = get_post_meta($appointment->ID, 'carwash_customer_name', true);

            $appointments[$key]->email = get_post_meta($appointment->ID, 'carwash_email', true);

            $apt_date = get_post_meta($appointment->ID, 'carwash_apt_date', true);
            $apt_time = get_post_meta($appointment->ID, 'carwash_apt_time', true);
            $appointments[$key]->apt_date_time = date('d/m/Y', strtotime($apt_date)) . '<br>' . date('h:i A', strtotime($apt_time));

            $time = get_post_meta($appointment->ID, 'carwash_time', true);
            $appointments[$key]->time = is_numeric($time) ? $time . ' mins' : '00 mins';

        }

        $data['appointments'] = $appointments;

        CarwashHelper::View('widget/carwash_widget', $data);
        // exit;
    }

    /**
     * Function to check User role and redirect
     *
     * @return void
     */
    public function check_login_role()
    {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            $current_roles = $user->roles;
            if (in_array('customer', $current_roles) && wp_doing_ajax() == false) {
                wp_safe_redirect(site_url());
                exit;
            }
        }
    }

    /**
     * Function to load necessary assets in Frontend
     *
     * @return void
     */
    public function load_front_assets()
    {
        // Loading Bootstrap 5 css
        wp_enqueue_style('carwash-bootstrap-css', CARWASH_ASSETS_DIR . 'public/css/bootstrap.min.css', null, $this->version);
        // Loading Bootstrap 5 js
        wp_enqueue_script('carwash-bootstrap-js', CARWASH_ASSETS_DIR . 'public/js/bootstrap.min.js', array('jquery'), $this->version, true);

        wp_enqueue_style('carwash-front-main-css', CARWASH_ASSETS_DIR . 'public/css/style.css', null, $this->version);
        wp_enqueue_script('carwash-front-main-js', CARWASH_ASSETS_DIR . 'public/js/main.js', array('jquery'), $this->version, true);

        // Pass data to js file
        $data = array(
            'ajax_url'              => admin_url('admin-ajax.php'),
            'confirm_text'          => __('Are you sure?', 'carwash'),
            'processing_text'       => __('Processing...', 'carwash'),
            'submit_text'           => __('Submit', 'carwash'),
            'login_success_text'    => __('Successfully Logged In', 'carwash'),
            'register_success_text' => __('Successfully Registered', 'carwash'),
        );
        wp_localize_script('carwash-front-main-js', 'carwash_info', $data);
    }

    /**
     * Function to check security after POST
     *
     * @param string $nonce_field
     * @param string $action
     * @param int $post_id
     * @return boolean
     */
    private function is_secured($nonce_field, $action, $post_id = null)
    {
        $nonce = CarwashHelper::Input($nonce_field);

        if ($nonce == '') {
            return false;
        }
        if (!wp_verify_nonce($nonce, $action)) {
            return false;
        }
        if (!empty($post_id)) {
            if (!current_user_can('edit_post', $post_id)) {
                return false;
            }
            if (wp_is_post_autosave($post_id)) {
                return false;
            }
            if (wp_is_post_revision($post_id)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Function to register Car as a custom Post type
     *
     * @return void
     */
    public function register_car()
    {
        $labels = [
            "name"                  => __("Cars", "carwash"),
            "singular_name"         => __("Car", "carwash"),
            "all_items"             => __("All Cars", "carwash"),
            "add_new"               => __("Add New Car", "carwash"),
            "add_new_item"          => __("Add New Car", "carwash"),
            "edit_item"             => __("Edit Car", "carwash"),
            "new_item"              => __("New Car", "carwash"),
            "view_item"             => __("View Car", "carwash"),
            "view_items"            => __("View Cars", "carwash"),
            "search_items"          => __("Search Car", "carwash"),
            "featured_image"        => __("Car Image", "carwash"),
            "set_featured_image"    => __("Set Car Image", "carwash"),
            "remove_featured_image" => __("Remove Car Image", "carwash"),
            "use_featured_image"    => __("Use Car Image", "carwash"),
        ];

        $args = [
            "label"                 => __("Cars", "carwash"),
            "labels"                => $labels,
            "description"           => "",
            "public"                => true,
            "publicly_queryable"    => true,
            "show_ui"               => true,
            "show_in_rest"          => true,
            "rest_base"             => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive"           => true,
            "show_in_menu"          => false,
            "show_in_nav_menus"     => true,
            "delete_with_user"      => false,
            "exclude_from_search"   => false,
            "capability_type"       => "post",
            "map_meta_cap"          => true,
            "hierarchical"          => false,
            "rewrite"               => array("slug" => "car", "with_front" => true),
            "query_var"             => true,
            "menu_icon"             => "dashicons-car",
            "supports"              => array("title", "author", "thumbnail"),
            "show_in_graphql"       => false,
        ];

        register_post_type("car", $args);
    }

    /**
     * Function to set Car columns
     *
     * @param array $columns
     * @return array
     */
    public function car_custom_column($columns)
    {
        unset($columns['taxonomy-car_model']);
        unset($columns['title']);
        unset($columns['author']);
        unset($columns['date']);

        $columns['image'] = __('Image', 'carwash');
        $columns['title'] = __('Title', 'carwash');
        $columns['taxonomy-car_model'] = __('Model', 'carwash');
        $columns['author'] = __('Author', 'carwash');
        $columns['date'] = __('Date', 'carwash');

        return $columns;
    }

    /**
     * Function to populate Car columns
     *
     * @param array $column
     * @param int $post_id
     * @return void
     */
    public function car_column_populate($column, $post_id)
    {
        switch ($column) {
            case 'image':
                echo "<img class='wp-image' src='" . get_the_post_thumbnail_url($post_id) . "' alt='" . get_post_field('post_title', $post_id) . "'>";
                break;
        }
    }

    /**
     * Function to register Service as a custom Post type
     *
     * @return void
     */
    public function register_service()
    {
        $labels = [
            "name"                  => __("Services", "carwash"),
            "singular_name"         => __("Service", "carwash"),
            "all_items"             => __("All Services", "carwash"),
            "add_new"               => __("Add New Service", "carwash"),
            "add_new_item"          => __("Add New Service", "carwash"),
            "edit_item"             => __("Edit Service", "carwash"),
            "new_item"              => __("New Service", "carwash"),
            "view_item"             => __("View Service", "carwash"),
            "view_items"            => __("View Services", "carwash"),
            "search_items"          => __("Search Service", "carwash"),
            "featured_image"        => __("Service Image", "carwash"),
            "set_featured_image"    => __("Set Service Image", "carwash"),
            "remove_featured_image" => __("Remove Service Image", "carwash"),
            "use_featured_image"    => __("Use Service Image", "carwash"),
        ];

        $args = [
            "label"                 => __("Services", "carwash"),
            "labels"                => $labels,
            "description"           => "",
            "public"                => true,
            "publicly_queryable"    => true,
            "show_ui"               => true,
            "show_in_rest"          => true,
            "rest_base"             => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive"           => true,
            "show_in_menu"          => false,
            "show_in_nav_menus"     => true,
            "delete_with_user"      => false,
            "exclude_from_search"   => false,
            "capability_type"       => "post",
            "map_meta_cap"          => true,
            "hierarchical"          => false,
            "rewrite"               => array("slug" => "service", "with_front" => true),
            "query_var"             => true,
            "menu_icon"             => "dashicons-clipboard",
            "supports"              => array("title", "author"),
            "show_in_graphql"       => false,
        ];

        register_post_type("service", $args);
    }

    /**
     * Function to add Metabox for Service
     *
     * @return void
     */
    public function add_service_metabox()
    {
        add_meta_box(
            'carwash_post_service',
            __('Basic Information', 'carwash'),
            array($this, 'carwash_display_post_service'),
            array('service')
        );
    }

    /**
     * Function to display Service Metabox
     *
     * @param object $post
     * @return void
     */
    public function carwash_display_post_service($post)
    {
        $data['saved_car_id'] = get_post_meta($post->ID, 'carwash_car_id', true);
        $data['saved_price'] = get_post_meta($post->ID, 'carwash_price', true);
        $data['saved_time'] = get_post_meta($post->ID, 'carwash_time', true);
        $data['label_car'] = __('Car', 'carwash');
        $data['label_price'] = __('Price (USD)', 'carwash');
        $data['label_time'] = __('Required Time (mins)', 'carwash');
        wp_nonce_field('carwash_service', 'carwash_service_token');

        $args = array('posts_per_page' => -1, 'post_type' => 'car');
        $data['cars'] = get_posts($args);

        CarwashHelper::View('metabox/service', $data);
    }

    /**
     * Function to save Service Metadata
     *
     * @param int $post_id
     * @return void
     */
    public function save_service_metadata($post_id)
    {

        if (!$this->is_secured('carwash_service_token', 'carwash_service', $post_id)) {
            return $post_id;
        }

        $car_id = CarwashHelper::Input('carwash_car_id');
        $price = CarwashHelper::Input('carwash_price');
        $time = CarwashHelper::Input('carwash_time');

        if ($car_id == 0 || $price == 0 || $time == 0) {
            return $post_id;
        }

        update_post_meta($post_id, 'carwash_car_id', $car_id);
        update_post_meta($post_id, 'carwash_price', $price);
        update_post_meta($post_id, 'carwash_time', $time);
    }

    /**
     * Function to set Service columns
     *
     * @param array $columns
     * @return array
     */
    public function service_custom_column($columns)
    {
        unset($columns['author']);
        unset($columns['date']);

        $columns['car'] = __('Car', 'carwash');
        $columns['price'] = __('Price', 'carwash');
        $columns['time'] = __('Required Time', 'carwash');
        $columns['author'] = __('Author', 'carwash');
        $columns['date'] = __('Date', 'carwash');

        return $columns;
    }

    /**
     * Function to populate Service columns
     *
     * @param array $column
     * @param int $post_id
     * @return void
     */
    public function service_column_populate($column, $post_id)
    {
        switch ($column) {
            case 'car':
                $car_id = get_post_meta($post_id, 'carwash_car_id', true);
                echo get_post_field('post_title', $car_id);
                break;

            case 'price':
                $price = get_post_meta($post_id, 'carwash_price', true);
                if (is_numeric($price)) {
                    echo '$' . number_format(get_post_meta($post_id, 'carwash_price', true), 2);
                } else {
                    echo '$' . number_format(0, 2);
                }
                break;

            case 'time':
                echo get_post_meta($post_id, 'carwash_time', true) . ' mins';
                break;
        }
    }

    /**
     * Function to register Package as a custom Post type
     *
     * @return void
     */
    public function register_package()
    {
        $labels = [
            "name"                  => __("Packages", "carwash"),
            "singular_name"         => __("Package", "carwash"),
            "all_items"             => __("All Packages", "carwash"),
            "add_new"               => __("Add New Package", "carwash"),
            "add_new_item"          => __("Add New Package", "carwash"),
            "edit_item"             => __("Edit Package", "carwash"),
            "new_item"              => __("New Package", "carwash"),
            "view_item"             => __("View Package", "carwash"),
            "view_items"            => __("View Packages", "carwash"),
            "search_items"          => __("Search Package", "carwash"),
            "featured_image"        => __("Package Image", "carwash"),
            "set_featured_image"    => __("Set Package Image", "carwash"),
            "remove_featured_image" => __("Remove Package Image", "carwash"),
            "use_featured_image"    => __("Use Package Image", "carwash"),
        ];

        $args = [
            "label"                 => __("Packages", "carwash"),
            "labels"                => $labels,
            "description"           => "",
            "public"                => true,
            "publicly_queryable"    => true,
            "show_ui"               => true,
            "show_in_rest"          => true,
            "rest_base"             => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive"           => true,
            "show_in_menu"          => false,
            "show_in_nav_menus"     => true,
            "delete_with_user"      => false,
            "exclude_from_search"   => false,
            "capability_type"       => "post",
            "map_meta_cap"          => true,
            "hierarchical"          => false,
            "rewrite"               => array("slug" => "package", "with_front" => true),
            "query_var"             => true,
            "menu_icon"             => "dashicons-portfolio",
            "supports"              => array("title", "author"),
            "show_in_graphql"       => false,
        ];

        register_post_type("package", $args);
    }

    /**
     * Function to add Metabox for Package
     *
     * @return void
     */
    public function add_package_metabox()
    {
        add_meta_box(
            'carwash_post_package',
            __('Select Service(s)', 'carwash'),
            array($this, 'carwash_display_post_package'),
            array('package')
        );
    }

    /**
     * Function to display Package Metabox
     *
     * @param object $post
     * @return void
     */
    public function carwash_display_post_package($post)
    {
        $data['saved_service_ids'] = get_post_meta($post->ID, 'carwash_service_ids', true);
        $data['label_service'] = __('Service', 'carwash');
        wp_nonce_field('carwash_package', 'carwash_package_token');

        $args = array('posts_per_page' => -1, 'post_type' => 'service');
        $data['services'] = get_posts($args);

        CarwashHelper::View('metabox/package', $data);
    }

    /**
     * Function to save Package Metadata
     *
     * @param int $post_id
     * @return void
     */
    public function save_package_metadata($post_id)
    {

        if (!$this->is_secured('carwash_package_token', 'carwash_package', $post_id)) {
            return $post_id;
        }
        if (!is_array(CarwashHelper::Input('carwash_service_ids'))) {
            return $post_id;
        }
        $service_ids = CarwashHelper::Input('carwash_service_ids');

        if (empty($service_ids)) {
            return $post_id;
        }

        update_post_meta($post_id, 'carwash_service_ids', $service_ids);
    }

    /**
     * Function to set Package columns
     *
     * @param array $columns
     * @return array
     */
    public function package_custom_column($columns)
    {
        unset($columns['author']);
        unset($columns['date']);

        $columns['services'] = __('Services', 'carwash');
        $columns['author'] = __('Author', 'carwash');
        $columns['date'] = __('Date', 'carwash');

        return $columns;
    }

    /**
     * Function to populate Package columns
     *
     * @param array $column
     * @param int $post_id
     * @return void
     */
    public function package_column_populate($column, $post_id)
    {
        switch ($column) {
            case 'services':
                $service_ids = get_post_meta($post_id, 'carwash_service_ids', true);
                $services = array();
                foreach ($service_ids as $service_id) {
                    $services[] = get_post_field('post_title', $service_id);
                }
                echo implode(", ", $services);
                break;
        }
    }

    /**
     * Function to register Appointment as a custom Post type
     *
     * @return void
     */
    public function register_appointment()
    {
        $labels = [
            "name"                  => __("Appointments", "carwash"),
            "singular_name"         => __("Appointment", "carwash"),
            "all_items"             => __("All Appointments", "carwash"),
            "add_new"               => __("Add New Appointment", "carwash"),
            "add_new_item"          => __("Add New Appointment", "carwash"),
            "edit_item"             => __("Edit Appointment", "carwash"),
            "new_item"              => __("New Appointment", "carwash"),
            "view_item"             => __("View Appointment", "carwash"),
            "view_items"            => __("View Appointments", "carwash"),
            "search_items"          => __("Search Appointment", "carwash"),
        ];

        $args = [
            "label"                 => __("Appointments", "carwash"),
            "labels"                => $labels,
            "description"           => "",
            "public"                => true,
            "publicly_queryable"    => true,
            "show_ui"               => true,
            "show_in_rest"          => true,
            "rest_base"             => "",
            "rest_controller_class" => "WP_REST_Posts_Controller",
            "has_archive"           => true,
            "show_in_menu"          => false,
            "show_in_nav_menus"     => true,
            "delete_with_user"      => false,
            "exclude_from_search"   => false,
            "capability_type"       => "post",
            'capabilities'          => array('create_posts' => false), // Removes support for the "Add New"
            "map_meta_cap"          => true,
            "hierarchical"          => false,
            "rewrite"               => array("slug" => "appointment", "with_front" => true),
            "query_var"             => true,
            "menu_icon"             => "dashicons-list-view",
            "supports"              => array(""),
            "show_in_graphql"       => false,
        ];

        register_post_type("appointment", $args);
    }

    /**
     * Function to set Appointment columns
     *
     * @param array $columns
     * @return array
     */
    public function appointment_custom_column($columns)
    {
        unset($columns['title']);
        unset($columns['author']);
        unset($columns['date']);

        $columns['title'] = __('Apt. No', 'carwash');
        $columns['package_name'] = __('Package Name', 'carwash');
        $columns['customer_name'] = __('Customer Name', 'carwash');
        // $columns['email'] = __('Email', 'carwash');
        $columns['apt_date_time'] = __('Apt. Datetime', 'carwash');
        // $columns['price'] = __('Total Price', 'carwash');
        // $columns['time'] = __('Total Time', 'carwash');
        $columns['status'] = __('Status', 'carwash');
        $columns['date'] = __('Date', 'carwash');

        return $columns;
    }

    /**
     * Function to populate Appointment columns
     *
     * @param array $column
     * @param int $post_id
     * @return void
     */
    public function appointment_column_populate($column, $post_id)
    {
        switch ($column) {
            case 'package_name':
                $package_id = get_post_meta($post_id, 'carwash_package_id', true);
                echo get_post_field('post_title', $package_id);
                break;
            case 'customer_name':
                echo get_post_meta($post_id, 'carwash_customer_name', true);
                break;
            case 'email':
                echo get_post_meta($post_id, 'carwash_email', true);
                break;
            case 'apt_date_time':
                $apt_date = get_post_meta($post_id, 'carwash_apt_date', true);
                $apt_time = get_post_meta($post_id, 'carwash_apt_time', true);
                echo date('d/m/Y', strtotime($apt_date)) . '<br>' . date('h:i A', strtotime($apt_time));
                break;
            case 'price':
                $price = get_post_meta($post_id, 'carwash_price', true);
                echo is_numeric($price) ? '$' . number_format($price, 2) : '$0.00';
                break;
            case 'time':
                $time = get_post_meta($post_id, 'carwash_time', true);
                echo is_numeric($time) ? $time . ' mins' : '00 mins';
                break;
            case 'status':
                $status = get_post_meta($post_id, 'carwash_status', true);
                $class_name = '';
                if ($status == 'pending') {
                    $class_name = 'text-danger';
                } elseif ($status == 'processing') {
                    $class_name = 'text-primary';
                } elseif ($status == 'completed') {
                    $class_name = 'text-success';
                }
                echo '<span class="' . $class_name . '">' . ucfirst($status) . '</span>';
                break;
        }
    }

    /**
     * Function to add Metabox for Appointment
     *
     * @return void
     */
    public function add_appointment_metabox()
    {
        add_meta_box(
            'carwash_post_appointment',
            __('Appointment Details', 'carwash'),
            array($this, 'carwash_display_post_appointment'),
            array('appointment')
        );
    }

    /**
     * Function to display Appointment Metabox
     *
     * @param object $post
     * @return void
     */
    public function carwash_display_post_appointment($post)
    {
        $data['apt_no'] = get_post_field('post_title', $post->ID);
        $data['label_apt_no'] = __('Apt. No', 'carwash');

        $package_id = get_post_meta($post->ID, 'carwash_package_id', true);
        $data['package_name'] = get_post_field('post_title', $package_id);
        $data['label_package_name'] = __('Package Name', 'carwash');

        $data['customer_name'] = get_post_meta($post->ID, 'carwash_customer_name', true);
        $data['label_customer_name'] = __('Customer Name', 'carwash');

        $data['email'] = get_post_meta($post->ID, 'carwash_email', true);
        $data['label_email'] = __('Email', 'carwash');

        $data['apt_date'] = date('d/m/Y', strtotime(get_post_meta($post->ID, 'carwash_apt_date', true)));
        $data['apt_time'] = date('h:i A', strtotime(get_post_meta($post->ID, 'carwash_apt_time', true)));
        $data['label_apt_datetime'] = __('Appointment Datetime', 'carwash');

        $price = get_post_meta($post->ID, 'carwash_price', true);
        $data['price'] = is_numeric($price) ? '$' . number_format($price, 2) : '$0.00';
        $data['label_price'] = __('Total Price', 'carwash');

        $time = get_post_meta($post->ID, 'carwash_time', true);
        $data['time'] = is_numeric($time) ? $time . ' mins' : '00 mins';
        $data['label_time'] = __('Total Time', 'carwash');

        $data['status'] = get_post_meta($post->ID, 'carwash_status', true);
        $data['label_status'] = __('status', 'carwash');

        wp_nonce_field('carwash_appointment', 'carwash_appointment_token');

        $data['status_fields'] = CarwashHelper::GetAppointmentStatusFields();

        CarwashHelper::View('metabox/appointment', $data);
    }

    /**
     * Function to save Appointment Metadata
     *
     * @param int $post_id
     * @return void
     */
    public function save_appointment_metadata($post_id)
    {

        if (!$this->is_secured('carwash_appointment_token', 'carwash_appointment', $post_id)) {
            return $post_id;
        }

        $status = CarwashHelper::Input('carwash_status');

        if (empty($status)) {
            return $post_id;
        }

        update_post_meta($post_id, 'carwash_status', $status);
    }

    /**
     * Short code Function for frontend Appointment page
     * Short code => [carwash_appointment /]
     * 
     * @param array $attributes
     * @return void
     */
    public function front_appointment($attributes)
    {
        $args = array('posts_per_page' => -1, 'post_type' => 'package');
        $data['packages'] = get_posts($args);

        $i = 0;
        foreach ($data['packages'] as $package) {
            $carwash_service_ids = get_post_meta($package->ID, 'carwash_service_ids', true);
            $services = array();

            $j = 0;
            foreach ($carwash_service_ids as $carwash_service_id) {
                $services[] = get_post($carwash_service_id);
                $car_id = get_post_meta($carwash_service_id, 'carwash_car_id', true);
                $price = get_post_meta($carwash_service_id, 'carwash_price', true);
                $time = get_post_meta($carwash_service_id, 'carwash_time', true);
                $car = get_post($car_id);
                $services[$j]->car_name = $car->post_title;
                $services[$j]->price = $price;
                $services[$j]->time = $time;
                $j++;
            }

            $data['packages'][$i]->services = $services;
            $i++;
        }

        ob_start();
        if (is_user_logged_in()) {
            $data['page_info'] = __('Make Appointment from the following Packages', 'carwash');
            CarwashHelper::View('front/appointment/index', $data);
        } else {
            $data['page_info'] = __('Please Log In to make an Appointment', 'carwash');
            CarwashHelper::View('front/auth/index', $data);
        }
        return ob_get_clean();
    }

    /**
     * Function to add Appointment from Frontend
     *
     * @return json
     */
    public function carwash_add_appointment()
    {
        if (!$this->is_secured('carwash_appointment_token', 'carwash_front_appointment')) {
            $response = array(
                'success'   => false,
                'message'   => __('Token verification failed!', 'carwash')
            );
            echo json_encode($response);
        } else {
            $package_id = CarwashHelper::Input('package_id');
            $customer_name = CarwashHelper::Input('customer_name');
            $email = CarwashHelper::Input('email');
            $apt_date = CarwashHelper::Input('apt_date');
            $apt_time = CarwashHelper::Input('apt_time');
            $price = CarwashHelper::Input('price');
            $time = CarwashHelper::Input('time');
            $status = 'pending';

            // Create post object with the form values
            $args = array(
                'post_title'    => '#' . strtoupper(uniqid()),
                'post_status'   => 'publish',
                'post_type'     => 'appointment'
            );
            $appointment_id = wp_insert_post($args);

            update_post_meta($appointment_id, 'carwash_package_id', $package_id);
            update_post_meta($appointment_id, 'carwash_customer_name', $customer_name);
            update_post_meta($appointment_id, 'carwash_email', $email);
            update_post_meta($appointment_id, 'carwash_apt_date', $apt_date);
            update_post_meta($appointment_id, 'carwash_apt_time', $apt_time);
            update_post_meta($appointment_id, 'carwash_price', $price);
            update_post_meta($appointment_id, 'carwash_time', $time);
            update_post_meta($appointment_id, 'carwash_status', $status);

            $args = array(
                'to'        => $email,
                'name'      => $customer_name,
                'apt_id'    => $appointment_id,
                'apt_date'  => $apt_date,
                'apt_time'  => $apt_time,
            );

            // Action for sending email to the customer about the new Appointment
            $is_email_send = do_action('send_customer_email', $args);

            $message = __('Successfully submitted! Email sent to ' . $email, 'carwash');

            if (!$is_email_send) {
                __('Successfully submitted! <span class="text-danger">Failed to send Email to </span>' . $email, 'carwash');
            }

            $response = array(
                'success'   => true,
                'message'   => $message,
                'data'      => null
            );

            echo json_encode($response);
        }

        exit;
    }

    /**
     * Function to send email
     *
     * @param array $data
     * @return bool
     */
    public function send_customer_email($data)
    {
        $to = $data['to'];
        $subject = 'New Appointment ' . $data['apt_id'];
        $message = "Dear " . $data['name'] . ",\n";
        $message .= "Thank you for your Appointment. Your Appointment details are,\n";
        $message .= "Appointment ID: " . $data['apt_id'] . "\n";
        $message .= "Appointment Date: " . date('d/m/Y', strtotime($data['apt_date'])) . "\n";
        $message .= "Appointment Time: " . date('h:i A', strtotime($data['apt_time'])) . "\n";

        $email_status = wp_mail($to, $subject, $message);

        return $email_status;
    }

    /**
     * Log In function for Frontend
     *
     * @return json
     */
    public function carwash_front_login()
    {
        if (!$this->is_secured('carwash_login_token', 'carwash_front_login')) {
            $response = array(
                'success'   => false,
                'message'   => __('Token verification failed!', 'carwash')
            );
            echo json_encode($response);
        } else {
            $username = CarwashHelper::Input('username');
            $password = CarwashHelper::Input('password');

            $user = wp_signon(array(
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => false,
            ));
            if (is_wp_error($user)) {
                $response = array(
                    'success'   => false,
                    'message'   => __('Log In failed!', 'carwash')
                );
            } else {
                wp_set_current_user($user->ID);

                $response = array(
                    'success'   => true,
                    'message'   => __('Successfully Logged In', 'carwash'),
                    'data'      => $user->ID
                );
            }

            echo json_encode($response);
        }
        exit;
    }

    /**
     * Registration function for Frontend
     *
     * @return json
     */
    public function carwash_front_registration()
    {
        if (!$this->is_secured('carwash_register_token', 'carwash_front_register')) {
            $response = array(
                'success'   => false,
                'message'   => __('Token verification failed!', 'carwash')
            );
            echo json_encode($response);
        } else {
            $email = CarwashHelper::Input('email');
            $username = CarwashHelper::Input('username');
            $password = CarwashHelper::Input('password');

            $user_id = wp_create_user($username, $password, $email);
            if (!$user_id) {
                $response = array(
                    'success'   => false,
                    'message'   => __('Registration failed!', 'carwash')
                );
            } else {
                $user = new WP_User($user_id);
                $current_roles = $user->roles;
                foreach ($current_roles as $role) {
                    $user->remove_role($role);
                }
                $user->add_role('customer');

                wp_set_current_user($user_id);
                wp_set_auth_cookie($user_id);

                $response = array(
                    'success'   => true,
                    'message'   => __('Successfully Registered', 'carwash'),
                    'data'      => $user_id
                );
            }

            echo json_encode($response);
        }

        exit;
    }
}

new Carwash();

/**
 * Function to add Custom Role in Plugin activation
 *
 * @return void
 */
function carwash_plugin_activate()
{
    // Create custom Role
    $caps = array(
        'read' => true
    );
    add_role('customer', __('Customer', 'carwash'), $caps);
}

register_activation_hook(__FILE__, 'carwash_plugin_activate');
