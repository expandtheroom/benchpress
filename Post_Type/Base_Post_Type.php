<?php

namespace BenchPress\Post_Type;

/**
 * Extend this class to create your post types. The post type slug can be accessed
 * statically for easy access.
 *
 * If your post type class was called My_Post_Type, you would
 * be able to access the post type slug like so: My_Post_Type::post_type()
 *
 * @package BenchPress\Post_Type
 */
abstract class Base_Post_Type {

    /**
     * Hold reference to all post types that are created so that we
     * can statically access the post type slug for any registered post type.
     *
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
        add_filter( 'post_updated_messages', [ $self, '_post_updated_messages_handler' ] );
    }

    final public function _register_post_type() {
        register_post_type(
            $this->get_post_type(),
            array_merge(
                [
                    'labels' => Label_Maker::create_labels( $this->get_singular_name(), $this->get_plural_name(), $this->get_post_type(), $this->get_text_domain() ),
                    'public' => true
                ],
                $this->get_args()
            )
        );
    }

    final public function _post_updated_messages_handler( $messages ) {
        $messages[ $this->get_post_type() ] = Label_Maker::create_update_messages( $this->get_singular_name(), $this->get_plural_name(), $this->get_post_type(), $this->get_text_domain() );
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

    protected abstract function get_singular_name();
    protected abstract function get_plural_name();

    protected function get_args() {
        return [];
    }

    protected function get_text_domain() {
        return 'default';
    }
}
