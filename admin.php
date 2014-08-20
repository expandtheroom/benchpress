<?php

/**
 * Removes the supplied menu page slugs from the admin menu
 * @param  array $pages An array of page slugs to remove from the admin menu
 */
function bp_remove_admin_menu_pages($pages) {
    
    add_action('admin_menu', function () use ($pages) {
        foreach ($pages as $page) {
            remove_menu_page($page);
        }
    });
}