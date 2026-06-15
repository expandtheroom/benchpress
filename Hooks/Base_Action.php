<?php

namespace BenchPress\Hooks;

/**
 * Class Base_Action
 *
 * Base class for implementing WordPress actions. Sub-classes must implement
 * a callback method which will be called when the action is triggered.
 *
 * @package BenchPress\Hooks
 */
abstract class Base_Action extends Base_Hook {

    final protected function add_hook() {
        add_action(
            $this->get_action(),
            [ $this, '__callback' ],
            $this->get_priority(),
            $this->get_arg_count()
        );
    }

    final public static function remove() {
        $class = static::class;

        if ( ! isset( static::$instances[ $class ] ) ) return;

        $instance = static::$instances[ $class ];

        remove_action( $instance->get_action(), [ $instance , '__callback' ], $instance->get_priority() );
    }

    /**
     * @return string The name of the WordPress action to add
     */
    abstract protected function get_action();
}
