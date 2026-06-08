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

    final protected function add_hook() {
        add_filter(
            $this->get_filter(),
            [ $this, '__callback' ],
            $this->get_priority(),
            $this->get_arg_count()
        );
    }

    final public static function remove() {
        $class = static::class;

        if ( ! isset( static::$instances[ $class ] ) ) return;

        $instance = static::$instances[ $class ];

        remove_filter( $instance->get_filter(), [ $instance , '__callback' ], $instance->get_priority() );
    }

    /**
     * @return string The name of the WordPress filter to add
     */
    abstract protected function get_filter();
}
