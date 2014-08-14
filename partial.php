<?php
/**
 * Renders a partial
 * @param string $partial
 * @param array|null $params
 * @return html
 */
function get_partial($partial, $params = array()) {
    
    // if it's not absolutely pathed, add on /partials/
    if(substr($partial, 0, 1) !== '/') {
        $partial = '/partials/' . $partial;
    }
    
    // if it doesn't end in .php, add .php on
    if(substr($partial, -4, 4) !== '.php') {
        $partial .= '.php';
    }
    
    extract($params);
    
    ob_start();
    /*
     * this is instead of using get_template_part; it means we get to use
     * variables, like $leader, without having to declare them as global
     */
    include(locate_template($partial));
    
    $html = ob_get_contents();
    ob_end_clean();
    
    return $html;
}

/**
 * Echoes out a rendered partial
 * @param string $partial
 * @param array|null $params
 */
function the_partial($partial, $params = array()) {
    echo get_partial($partial, $params);
}