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

    private static $post_types = [];

    public static function init() {
        if ( isset( self::$post_types[ static::class ] ) ) return;

        $self = new static();

        self::$post_types[ static::class ] = $self;

        add_action( 'init', [ $self, '_register_post_type' ] );
        add_filter( 'post_updated_messages', [ $self, '_post_updated_messages_handler' ] );
    }

    final public function _register_post_type() {
        register_post_type(
            $this->get_post_type(),
            array_merge(
                $this->get_default_args(),
                $this->get_args()
            )
        );
    }

    private function get_default_args() {
        return [
            'labels' => Label_Maker::create_labels(
                $this->get_singular_name(),
                $this->get_plural_name(),
                $this->get_post_type(),
                $this->get_text_domain()
            ),
            'public' => true,
            'taxonomies' => $this->get_taxonomies()
        ];
    }

    final public function _post_updated_messages_handler( $messages ) {
        $messages[ $this->get_post_type() ] = $this->get_updated_messages();

        return $messages;
    }
    
    /**
     * Returns an array of message strings to use when the post type is updated.
     */
    protected function get_updated_messages() {
        return Label_Maker::create_update_messages(
            $this->get_singular_name(),
            $this->get_plural_name(),
            $this->get_post_type(),
            $this->get_text_domain()
        );
    }

    /**
     * Returns the post type slug.
     */
    protected abstract function get_post_type();

    /**
     * Returns the singular name for the post type. The name should be initial caps.
     */
    protected abstract function get_singular_name();

    /**
     * Returns the plural name for the post type. The name should be initial caps.
     */
    protected abstract function get_plural_name();

    /**
     * Returns the post type arguments array.
     */
    protected function get_args() {
        return [];
    }

    /**
     * Returns an array of registered taxonomies for this post type.
     */
    protected function get_taxonomies() {
        return [];
    }

    /**
     * Returns the text domain to use for translations for this post type.
     */
    protected function get_text_domain() {
        return 'default';
    }

    /**
     * Returns the post type slug for the class.
     */
    public static function post_type() {
        return self::$post_types[static::class]->get_post_type();
    }
}
