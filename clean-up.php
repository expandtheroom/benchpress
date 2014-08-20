<?php

/**
 * Clean up some of WP's default output. This includes output that may compromise security such
 * as the generator tag and version number.
 */
add_action('init', function () {
    // Originally from http://wpengineer.com/1438/wordpress-header/
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
});


/**
 * Remove the WordPress version from RSS feeds
 */
add_filter('the_generator', '__return_false');


/**
 * Add and remove body_class() classes
 */
add_filter('body_class', function ($classes) {
    // Originally from the soil plugin: 
    // Add post/page slug
    if (is_single() || is_page() && !is_front_page()) {
        $classes[] = basename(get_permalink());
    }

    // Remove unnecessary classes
    $home_id_class = 'page-id-' . get_option('page_on_front');
    $remove_classes = [
        'page-template-default',
        $home_id_class
    ];
    
    $classes = array_diff($classes, $remove_classes);

    return $classes;
});
