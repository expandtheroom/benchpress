<?php

namespace BenchPress\Hooks;

abstract class Base_Hook {

    protected static $instances = [];

    /**
     * Initializes the hook
     */
    public static function init() {
        $called_class = static::class;

        if ( isset( static::$instances[ $called_class ] ) ) return;

        static::$instances[ $called_class ] = new static();

        $instance = static::$instances[ $called_class ];

        $instance->add_hook();
    }

    abstract protected function add_hook();

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
     * This function is used as the hook callback so we can determine if the actual
     * developer supplied callback should be invoked (depending on the return value of $this->should_run()).
     */
    final public function __callback( ...$args ) {
        // check if the user supplied callback should be invoked
        if ( ! method_exists( $this, 'should_run' ) || $this->should_run( ...$args ) ) {
            /**
             * Return a call to callback which is necessary for filters. There is no harm in returning
             * the value for actions.
             */
            if ( ! method_exists( $this, 'callback' ) )
                throw new \Exception( 'Required method "callback" is not defined.' );

            return $this->callback( ...$args );
        } else {
            /**
             * If we should not invoke callback, return the first function arg which is necessary for filters.
             */
            return count( $args ) ? $args[0] : '';
        }
    }
}
