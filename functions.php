<?php

namespace BP;

/**
 * This file contains various helper functions for use in your theme.
 */

if ( ! function_exists( __NAMESPACE__ . '\get_partial' ) ) {
    /**
     * Returns a partial
     *
     * @param string $partial The name of the partial
     * @param array|null $params
     *
     * @return string
     */
    function get_partial($partial, $params = [])
    {
        // if it's not absolutely pathed, add on /partials/
        if ( substr( $partial, 0, 1 ) !== '/') {
            $partial = '/partials/' . $partial;
        }

        // if it doesn't end in .php, add .php on
        if ( substr( $partial, -4, 4 ) !== '.php') {
            $partial .= '.php';
        }

        // extract any vars passed to function so partial has access to them
        extract( $params );

        /**
         * We use locate template to find the partial so child themes can override
         * the partials when necessary.
         */
        $template = locate_template( $partial );

        // if we don't find a template, return an empty string
        if ( empty( $template ) ) return '';

        // start buffer to capture partial output
        ob_start();

        // include the template
        include( $template );

        // capture the output of the included partial above
        $html = ob_get_contents();

        ob_end_clean();

        return $html;
    }
}

if ( ! function_exists( __NAMESPACE__ . '\the_partial' ) ) {
    /**
     * Outputs a partial
     *
     * @param string $partial
     * @param array|null $params
     */
    function the_partial( $partial, $params = [] ) {
        echo get_partial( $partial, $params );
    }
}

if ( ! function_exists( __NAMESPACE__ . '\get_user_role' ) ) {
    /**
     * Retrieve a role for the given user. If a user isn't provided, the function
     * will try to use the current logged in user.
     *
     * @param \WP_User|null $user The user whose role we want to retrieve.
     *
     * @return string|bool Returns the user role if found, false otherwise.
     */
    function get_user_role( \WP_User $user = null ) {
        $user = $user ? new \WP_User( $user ) : \wp_get_current_user();

        return $user->roles ? $user->roles[0] : false;
    }
}