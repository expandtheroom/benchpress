<?php

namespace BenchPress\Taxonomy;

abstract class Base_Taxonomy {
    /**
     * Hold reference to all taxonomies that are created so that we
     * can statically access the taxonomy slug for any registered taxonomy.
     *
     * @var array
     */
    protected static $taxonomies = [];

    public static function init() {
        if ( isset( self::$taxonomies[ static::class ] ) ) return;

        $self = new static();
        /**
         * store reference to the instance so we can provide easy access to
         * the taxonomy slug for the class
         */
        self::$taxonomies[ static::class ] = $self;

        add_action( 'init', [ $self, '_register_taxonomy' ] );
    }

    final public function _register_taxonomy() {
        $this->register( $this->get_taxonomy() );
    }

    /**
     * Returns the taxonomy name for the class
     * @return string
     */
    public static function taxonomy () {
        return self::$taxonomies[static::class]->get_taxonomy();
    }

    /**
     * Return your taxonomy name
     */
    protected abstract function get_taxonomy();

    /**
     * You should register your taxonomy with WordPress in this method.
     *
     * @param $taxonomy The taxonomy name returned by get_taxonomy()
     */
    protected abstract function register( $taxonomy );
}
