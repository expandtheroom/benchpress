<?php
/**
 * Adds Google Analytics tracking script to the website.
 *
 * add_theme_support( BenchPress\Theme_Support\Theme_Support::GOOGLE_ANALYTICS, 'UA-XXXXXXX' )
 *
 * @var string - Your Google Analytics UA id.
 */
add_action( 'wp_footer', function() {
    $args = get_theme_support( BenchPress\Theme_Support\Theme_Support::GOOGLE_ANALYTICS );
    $ua = $args[0];

    ?>
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

        ga('create', '<?php echo $ua ?>', 'auto');
        ga('send', 'pageview');
    </script>
    <?php
}, 1000);
