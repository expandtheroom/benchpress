<?php

namespace BenchPress\Hooks;

abstract class Base_Hook {

    protected static $instances = [];

    /**
     * Initializes the hook
     */
    public static function init() {
        $called_class = get_called_class();

        if ( isset( static::$instances[ $called_class ] ) ) return;

        static::$instances[ $called_class ] = new static();

        $instance = static::$instances[ $called_class ];

        $instance->add_hook( $instance );
    }

    protected abstract function add_hook( Base_Hook $instance );

    /**
     * @return int The number of arguments the hook callback accepts. Defaults to 1.
     */
    protected function get_arg_count() {
        return 1;
    }

    /**
     * @return int The priority of the hook. Defaults to 10.
     */
    protected function get_priority() {
        return 10;
    }

    /**
     * Decides if the hook callback should be invoked. Generally checks if a certain
     * condition is met. i.e. `return is_post_type_archive()` if you only want the
     * callback to execute if a post type archive is being viewed.
     *
     * @return bool Whether the hook callback should be invoked.
     */
    protected function should_run() {
        return true;
    }

    /**
     * The callback for the registered hook. The arguments vary depending on the particular hook.
     * @throws \Error
     */
    protected function callback() {
        throw new \Error( 'You must override callback.' );
    }

    /**
     * This function is used as the hook callback so we can determine if the actual
     * developer supplied callback should be invoked (depending on the return value of $this->should_run()).
     */
    final public function __callback() {
        // check if the user supplied callback should be invoked
        if ( call_user_func_array( [ $this, 'should_run' ], func_get_args() ) ) {
            /**
             * Return a call to callback which is necessary for filters. There is no harm in returning
             * the value for actions.
             */
            return call_user_func_array( [ $this, 'callback' ], func_get_args() );
        } else {
            /**
             * If we should not invoke callback, return the first function arg which is necessary for filters.
             */
            return func_get_arg(0);
        }
    }
}
