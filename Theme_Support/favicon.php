<?php

add_filter( 'wp_head', function() {
    $favicon_url = get_theme_support( BenchPress\Theme_Support\Theme_Support::FAVICON );

    echo '<link rel="icon" type="image/png" href="' . $favicon_url . '">';
});