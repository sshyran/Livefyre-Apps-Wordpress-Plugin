<?php

//Disallow direct access to this file
if (!defined('LFAPPS__PLUGIN_PATH'))
    die('Bye');

if (!class_exists('LFAPPS_Sidenotes_Admin')) {

    class LFAPPS_Sidenotes_Admin {

        private static $initiated = false;

        public static function init() {
            if (!self::$initiated) {
                self::$initiated = true;
                self::init_hooks();
            }
        }

        /**
         * Initialise WP hooks
         */
        private static function init_hooks() {
            add_action('admin_menu', array('LFAPPS_Sidenotes_Admin', 'init_admin_menu'));
            add_action('admin_enqueue_scripts', array('LFAPPS_Sidenotes_Admin', 'load_resources'));
        }

        /**
         * Initialise admin menu items
         */
        public static function init_admin_menu() {
            add_submenu_page('livefyre_apps', 'Sidenotes', 'Sidenotes', "manage_options", 'livefyre_apps_sidenotes', array('LFAPPS_Sidenotes_Admin', 'menu_sidenotes'));
        }

        /**
         * Add assets required by Livefyre Apps Admin section
         */
        public static function load_resources() {
            ##wp_register_style('lfapps_comments.css', LFAPPS__PLUGIN_URL . 'apps/comments/assets/css/lfapps_comments.css', array(), LFAPPS__VERSION);
            ##wp_enqueue_style('lfapps_comments.css');
        }

        /**
         * Run Livefyre Comments page
         */
        public static function menu_sidenotes() {
            
            
            //process settings form
            if (isset($_POST['livefyre_sidenotes_general']) && check_admin_referer('form-livefyre_sidenotes_general')) {
                $excludes = array('_builtin' => false);
                $post_types = get_post_types($args = $excludes);
                $post_types = array_merge(array('post' => 'post', 'page' => 'page'), $post_types);
                foreach ($post_types as $post_type) {
                    $post_type_name = 'livefyre_sidenotes_display_' . $post_type;
                    if (isset($_POST[$post_type_name])) {
                        Livefyre_Apps::update_option($post_type_name, true);
                    } else {
                        Livefyre_Apps::update_option($post_type_name, false);
                    }
                }
                if(isset($_POST['livefyre_sidenotes_selectors'])) {
                    Livefyre_Apps::update_option('livefyre_sidenotes_selectors', sanitize_text_field($_POST['livefyre_sidenotes_selectors']));
                }
                Livefyre_Apps::$form_saved = true;
            }
            LFAPPS_View::render('general', array(), 'sidenotes');
        }
    }

}