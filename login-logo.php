<?php

/**
 * Replaces the Wordpress logo with a custom logo on the admin login screen and makes it link back to the site homepage.
 * 
 * @param  string $logo_path The absolute path to the logo image
 * @param  array $dims An associative array containing the 'width' and 'height' of the image. These values will be used directly as the css property values for width and height so units are required.
 */
function bp_login_logo($logo_path, $dims) {

    add_action('login_enqueue_scripts', function () use ($logo_path, $dims) { ?>
        <style type="text/css">
            body.login div#login h1 a {
                background-image: url(<?php echo $logo_path; ?>);
                -webkit-background-size: 100%;
                background-size: 100%;
                width: <?php echo $dims['width']; ?>;
                height: <?php echo $dims['height']; ?>;
                padding-bottom: 30px;
            }
        </style>
    <?php });

    add_filter('login_headerurl', function () {
        return home_url();
    });
}