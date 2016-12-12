<?php

/**
 * Replaces the login logo by overriding the default CSS.
 */
add_action( 'login_head', function() {
    $args = get_theme_support( BP\Theme_Support\Theme_Support::LOGIN_LOGO );

    $login_logo_url = $args[0]['path'];
    $width = $args[0]['width'];
    $height = $args[0]['height'];
    ?>

    <style type="text/css">
        .login h1 a {
            background-image: url(<?php echo $login_logo_url; ?>);
            background-size: 100%;
            background-position: center;
            max-height: 320px;
            max-width: 100%;
            width: <?php echo $width; ?>px;
            height: <?php echo $height; ?>px;
        }
    </style>

    <?php
});

/**
 * Link the logo to the site home url.
 */
add_filter( 'login_headerurl', function () {
    return home_url();
});
