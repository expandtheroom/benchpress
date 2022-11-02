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
        $class = get_called_class();

        if ( isset( self::$post_types[ $class ] ) ) return;

        $self = new static();

        self::$post_types[ $class ] = $self;

        // Store a list of post types created by Benchpress
        $benchpress_post_types = get_option('benchpress_post_types', []);
        $benchpress_post_types[] = self::post_type();
        update_option('benchpress_post_types', array_unique($benchpress_post_types), false);

        add_action( 'init', [ $self, '_register_post_type' ] );
        add_filter( 'post_updated_messages', [ $self, '_post_updated_messages_handler' ] );
    }

    /**
     * Registers the post type for this class. 
     */
    final public function _register_post_type() {
        register_post_type(
            $this->get_post_type(),
            array_replace_recursive(
                $this->get_default_args(),
                $this->get_args()
            )
        );
    }

    /**
     * Returns the default args for this post type.
     */
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

    /**
     * Sets up the updated messages for this post type.
     */
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
        $class = get_called_class();

        return self::$post_types[ $class ]->get_post_type();
    }
}
