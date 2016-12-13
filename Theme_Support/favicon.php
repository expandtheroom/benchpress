<?php

add_filter( 'wp_head', function() {
    $args = get_theme_support( BenchPress\Theme_Support\Theme_Support::FAVICON );
    $favicon_url = $args[0];

    echo '<link rel="icon" type="image/png" href="' . $favicon_url . '">';
});
