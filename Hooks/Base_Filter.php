<?php

namespace BenchPress\Hooks;

/**
 * Class Base_Filter
 *
 * Base class for implementing WordPress filters. Sub-classes must implement
 * a callback method which will be called when the filter is triggered.
 *
 * @package BenchPress\Hooks
 */
abstract class Base_Filter extends Base_Hook {

    final protected function add_hook( Base_Hook $instance ) {
        add_filter(
            $instance->get_filter(),
            [ $instance, '__callback' ],
            $instance->get_priority(),
            $instance->get_arg_count()
        );
    }

    final public static function remove() {
        $class = get_called_class();

        if ( ! isset( static::$instances[ $class ] ) ) return;

        $instance = static::$instances[ $class ];

        remove_filter( $instance->get_filter(), [ $instance , '__callback' ], $instance->get_priority() );
    }

    /**
     * @return string The name of the WordPress filter to add
     */
    abstract protected function get_filter();
}
