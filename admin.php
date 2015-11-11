<?php

/*
 * Static helper class for admin functions
 */
class BP_Admin_Helper {

    /**
     * Removes the supplied menu page slugs from the admin menu
     * @param  array $pages An array of page slugs to remove from the admin menu
     */
    static function bp_remove_admin_menu_pages($pages) {

        add_action('admin_menu', function () use ($pages) {
            foreach ($pages as $page) {
                remove_menu_page($page);
            }
        });
    }


    /*
     * Removes all admin update notifications
     * @param boolean $plugins include plugin notification messages to be hidden. default true
     */
    static function hide_updates($plugins = true) {
        add_action( 'after_setup_theme', function()
        {

            if (!current_user_can('update_core')) {
                remove_action( 'admin_notices', 'update_nag', 3 );
            }

            add_action('init', create_function('$a',"remove_action( 'init', 'wp_version_check' );"),2);
            add_filter('pre_option_update_core','__return_null');
            add_filter('pre_site_transient_update_core','__return_null');

            //disable plugin update notifications
            if ($plugins) {
                remove_action('load-update-core.php','wp_update_plugins');
                add_filter('pre_site_transient_update_plugins','__return_null');
            }

        }, 1);
    }
}