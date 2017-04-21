<?php

namespace BenchPress\Taxonomy;

abstract class Base_Taxonomy {
    /**
     * Hold reference to all post types that are created so that we
     * can statically access the post type slug for any registered post type.
     *
     * @var array
     */
    protected static $taxonomies = [];

    public static function init() {
        if ( isset( self::$taxonomies[ static::class ] ) ) return;

        $self = new static();
        /**
         * store reference to the instance so we can provide easy access to
         * the post type slug for the class
         */
        self::$taxonomies[ static::class ] = $self;

        add_action( 'init', [ $self, '_register_taxonomy' ] );
    }

    final public function _register_taxonomy() {
        $this->register( $this->get_taxonomy() );
    }

    /**
     * Returns the post type slug for the class
     * @return string
     */
    public static function post_type() {
        return self::$taxonomies[static::class]->get_taxonomy();
    }

    /**
     * Return your post type slug
     */
    protected abstract function get_taxonomy();

    /**
     * You should register your post type with WordPress in this method.
     *
     * @param $post_type The post type slug returned by get_post_type()
     */
    protected abstract function register( $taxonomy );
}
