<?php

/**
 * Includes the Google Analytics tracking code in the footer of every page on the site
 * 
 * @param string $ua The UA account number
 */
function bp_google_analytics($ua) {
    add_action('wp_footer', function() use ($ua) {
        ?>
        
        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', '<?php echo $ua ?>', 'auto');
            ga('send', 'pageview');

        </script>

        <?php
    }, 1000);
}

