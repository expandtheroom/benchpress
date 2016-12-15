<?php

/**
 * Clean up some of WP's default output. This includes output that may compromise security such
 * as the generator tag and version number.
 *
 * add_theme_support( BenchPress\Theme_Support\Theme_Support::CLEAN_UP )
 *
 */
add_action( 'init', function() {
    // Originally from http://wpengineer.com/1438/wordpress-header/
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10 );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head', 10 );
});


/**
 * Remove the WordPress version from RSS feeds
 */
add_filter( 'the_generator', '__return_false' );


/**
 * Remove unnecessary body classes
 */
add_filter( 'body_class', function( $classes ) {
    // Remove unnecessary classes
    $home_id_class = 'page-id-' . get_option( 'page_on_front' );

    $remove_classes = [
        'page-template-default',
        $home_id_class,
        'postid-' . get_the_ID(),
        'page-id-' . get_the_ID(),
        'page-template-templates',
        'page-template',
    ];

    if ( is_page_template() ) {
        $template_name = basename( get_page_template_slug( get_the_ID() ), '.php' );
        $remove_classes[] = 'page-template-templates' . $template_name  . '-php';
    }

    $classes = array_diff( $classes, $remove_classes );

    return $classes;
});
