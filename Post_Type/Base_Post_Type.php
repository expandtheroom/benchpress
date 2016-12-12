<?php

namespace BP\Post_Type;

/**
 * Extend this class to create your post types. The post type slug can be accessed
 * statically for easy access.
 *
 * If your post type class was called My_Post_Type, you would
 * be able to access the post type slug like so: My_Post_Type::post_type()
 *
 * @package BP\Post_Type
 */
abstract class Base_Post_Type {

    /**
     * Hold reference to all post types that are created.
     * @var array
     */
    protected static $post_types = [];

    public static function init() {
        if ( isset( self::$post_types[ static::class ] ) ) return;

        $self = new static();
        /**
         * store reference to the instance so we can provide easy access to
         * the post type slug for the class
         */
        self::$post_types[ static::class ] = $self;

        add_action( 'init', [ $self, '_register_post_type' ] );
    }

    /**
     * Returns the post type slug for the class
     * @return string
     */
    public static function post_type() {
        return self::$post_types[static::class]->get_post_type();
    }

    /**
     * Return your post type slug
     */
    protected abstract function get_post_type();


    final public function _register_post_type() {
        $this->register_post_type( $this->get_post_type() );
    }

    /**
     * You should register your post type in this method.
     *
     * @param $post_type The post type slug returned by get_post_type()
     */
    protected abstract function register_post_type( $post_type );
}