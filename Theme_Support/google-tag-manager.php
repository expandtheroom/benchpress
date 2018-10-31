<!-- Global site tag (gtag.js) - Google Analytics -->


<?php
/**
 * Adds Google Tag Manager tracking script to the website.
 *
 * add_theme_support( BenchPress\Theme_Support\Theme_Support::GOOGLE_ANALYTICS, 'UA-XXXXXXX' )
 *
 * @var string - Your Google Analytics UA id.
 */
add_action( 'wp_footer', function() {
    $args = get_theme_support( BenchPress\Theme_Support\Theme_Support::GOOGLE_TAG_MANAGER );
    $ua = $args[0];

    ?>

    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $ua; ?>"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', '<?php echo $ua ?>');
    </script>

    <?php
}, 1000);
