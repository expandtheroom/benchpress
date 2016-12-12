<?php

add_action( 'admin_menu', function() {
    $pages = get_theme_support( BP\Theme_Support\BP\Theme_Support\Theme_Support::REMOVE_ADMIN_MENUS );

    foreach ( $pages as $page ) {
        remove_menu_page( $page );
    }
});
