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
    /**
     * Construct function
     */
    public function __construct()
    {
        // Initialize with loading textdomain & assets
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('admin_enqueue_scripts', array($this, 'load_admin_assets'));

        // Custom post Car
        add_action('init', array($this, 'register_car'));
        add_filter('manage_car_posts_columns', array($this, 'car_custom_column'));
        add_action('manage_car_posts_custom_column' , array($this, 'car_column_populate'), 10, 2);

        // Custom post Service
        add_action('init', array($this, 'register_service'));
        add_action('add_meta_boxes', array($this, 'add_service_metabox'));
        add_action('save_post', array($this, 'save_service_metadata'));
        add_filter('manage_service_posts_columns', array($this, 'service_custom_column'));
        add_action('manage_service_posts_custom_column' , array($this, 'service_column_populate'), 10, 2);

        // Custom post Package
        add_action('init', array($this, 'register_package'));
        add_action('add_meta_boxes', array($this, 'add_package_metabox'));
        add_action('save_post', array($this, 'save_package_metadata'));
        add_filter('manage_package_posts_columns', array($this, 'package_custom_column'));
        add_action('manage_package_posts_custom_column' , array($this, 'package_column_populate'), 10, 2);

        // Short code for Frontend Appointment module
        add_shortcode('carwash_appointment', array($this, 'front_appointment'));
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
     * Function to load necessary assets in Admin
     *
     * @return void
     */
    function load_admin_assets()
    {
        $this_screen = get_current_screen();
        if ($this_screen->post_type == 'car' || $this_screen->post_type == 'service' || $this_screen->post_type == 'package') {
            wp_enqueue_style('carwash-main-css', CARWASH_ASSETS_DIR . 'admin/css/style.css', null, '1.0');
            wp_enqueue_script('carwash-main-js', CARWASH_ASSETS_DIR . 'admin/js/main.js', array('jquery'), '1.0', true);

            // Pass data to js file
            $data = array('confirm_text' => __('Are you sure?', 'carwash'));
            wp_localize_script('carwash-main-js', 'carwash_info', $data);
        }
    }

    /**
     * Function to check security after POST
     *
     * @param string $nonce_field
     * @param string $action
     * @param int $post_id
     * @return boolean
     */
    private function is_secured($nonce_field, $action, $post_id)
    {
        $nonce = CarwashHelper::Input($nonce_field);

        if ($nonce == '') {
            return false;
        }
        if (!wp_verify_nonce($nonce, $action)) {
            return false;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }
        if (wp_is_post_autosave($post_id)) {
            return false;
        }
        if (wp_is_post_revision($post_id)) {
            return false;
        }
        return true;
    }

    /**
     * Function to register Car as a custom Post type
     *
     * @return void
     */
    function register_car()
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
            "show_in_menu"          => true,
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
    function car_custom_column($columns)
    {
        unset($columns['title']);
        unset($columns['author']);
        unset($columns['date']);

        $columns['image'] = __('Image', 'carwash');
        $columns['title'] = __('Title', 'carwash');
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
    function car_column_populate($column, $post_id)
    {
        switch ($column) {
            case 'image':
                echo "<img class='wp-image' src='".get_the_post_thumbnail_url($post_id)."' alt='".get_post_field('post_title', $post_id)."'>";
                break;
        }
    }

    /**
     * Function to register Service as a custom Post type
     *
     * @return void
     */
    function register_service()
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
            "show_in_menu"          => true,
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
    function add_service_metabox()
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
    function carwash_display_post_service($post)
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

        CarwashHelper::View('metabox/service.php', $data);
    }

    /**
     * Function to save Service Metadata
     *
     * @param int $post_id
     * @return void
     */
    function save_service_metadata($post_id)
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
    function service_custom_column($columns)
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
    function service_column_populate($column, $post_id)
    {
        switch ($column) {
            case 'car':
                $car_id = get_post_meta($post_id, 'carwash_car_id', true);
                echo get_post_field('post_title', $car_id);
                break;
        
            case 'price':
                echo '$'.number_format(get_post_meta($post_id, 'carwash_price', true), 2);
                break;

            case 'time':
                echo get_post_meta($post_id, 'carwash_time', true).' mins';
                break;
        }
    }

    /**
     * Function to register Package as a custom Post type
     *
     * @return void
     */
    function register_package()
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
            "show_in_menu"          => true,
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
    function add_package_metabox()
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
    function carwash_display_post_package($post)
    {
        $data['saved_service_ids'] = get_post_meta($post->ID, 'carwash_service_ids', true);
        $data['label_service'] = __('Service', 'carwash');
        wp_nonce_field('carwash_package', 'carwash_package_token');

        $args = array('posts_per_page' => -1, 'post_type' => 'service');
        $data['services'] = get_posts($args);

        CarwashHelper::View('metabox/package.php', $data);
    }

    /**
     * Function to save Package Metadata
     *
     * @param int $post_id
     * @return void
     */
    function save_package_metadata($post_id)
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
    function package_custom_column($columns)
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
    function package_column_populate($column, $post_id)
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
     * Short code Function for frontend Appointment page
     * Short code => [carwash_appointment /]
     * 
     * @param array $attributes
     * @return void
     */
    function front_appointment($attributes)
    {
        $args = array('posts_per_page' => -1, 'post_type' => 'package');
        $data['packages'] = get_posts($args);

        CarwashHelper::View('front/appointment/index.php', $data);
    }

}

new Carwash();
