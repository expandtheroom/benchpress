<?php

/**
 * Remove menus from the WordPress admin sidebar.
 *
 * add_theme_support( BenchPress\Theme_Support\Theme_Support::REMOVE_ADMIN_MENUS, [
 *     'edit-comments.php',
 * ])
 */
add_action( 'admin_menu', function() {
    $args = get_theme_support( BenchPress\Theme_Support\Theme_Support::REMOVE_ADMIN_MENUS );
    $pages = $args[0];

    foreach ( $pages as $page ) {
        remove_menu_page( $page );
    }
});
