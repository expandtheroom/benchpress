<?php

/**
 * Adds a favicon to the website.
 *
 * add_theme_support( BenchPress\Theme_Support\Theme_Support::FAVICON, 'path_to_favicon.png' )
 *
 * @var string - The path to the favicon .png file
 */
add_filter( 'wp_head', function() {
    $args = get_theme_support( BenchPress\Theme_Support\Theme_Support::FAVICON );
    $favicon_url = $args[0];

    echo '<link rel="icon" type="image/png" href="' . $favicon_url . '">';
});
