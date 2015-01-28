<?php

/**
 * Adds a favicon to the site
 * @param  string $favicon_url The path to the favicon png file
 */
function bp_favicon($favicon_url) {

    add_filter('wp_head', function() use ($favicon_url) {
        echo '<link rel="icon" type="image/png" href="' . $favicon_url . '">';
    });
}