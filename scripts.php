<?php 
/**
 * Creates a local fallback for JavaScript files that are loaded from a remote source or CDN
 * 
 * @param  string  $handle          The handle of the script that the fallback will support
 * @param  string  $local_path      The path to the local version of the script
 * @param  string  $global_var_name The global variable to search on the window object to determine if the remote script was loaded
 * @param  boolean $footer          Should the fallback script be created in the footer (true) or header (false)
 */
function bp_cdn_script_fallback($handle, $local_path, $global_var_name, $footer = true) {
    if (!wp_script_is($handle, 'enqueued')) return;
    
    $script_tag = "<script>window.{$global_var_name} || document.write('<script src=\"{$local_path}\"><\/script>')</script>";
    
    $location = $footer ? 'wp_footer' : 'wp_head';
    
    add_action($location, function() use ($script_tag) {
        echo $script_tag;
    });
}

/**
 * Creates a script tag to require a browserify page module on the page matching the module name.
 * 
 * @param  string $script_module The name of the page module to require
 * @param  function $page_predicate An optional predicate function that will be used to determine wheter or not to output the require call
 */
function bp_init_browserify_page_script($script_module, $page_predicate = null) {
    // if predicate is not defined, see if script_module matches post type
    if (empty($page_predicate) && (
            get_post_type() != $script_module &&
            !is_page_template($script_module . '.php') &&
            !is_page_template('templates/' . $script_module . '.php')
        )
    || !empty($page_predicate) && !$page_predicate()) return;

    add_action('wp_footer', function() use ($script_module) { ?>
        <script>
            require('<?php echo $script_module ?>').init();
        </script>
    <?php }, 200);
}