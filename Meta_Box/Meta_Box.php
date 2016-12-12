<?php

namespace BP\Meta_Box;

/**
 * A base class to make creating Meta Boxes easier.
 *
 * @package BP\Meta_Box
 */
abstract class Meta_Box {

    /**
     * The id of the meta box.
     *
     * @var string
     */
    public $id;

    public static function init() {
        $self = new static();

        $self->register_hooks();

        return $self;
    }

    public function __construct() {
        $this->id = $this->get_id();
    }

    public function register_hooks() {
        \add_action(
            'add_meta_boxes_' . $this->get_post_type(),
            [ $this, 'add_meta_boxes_handler' ]
        );

        \add_action(
            'save_post_' . $this->get_post_type(),
            [ $this, 'save_post_handler' ]
        );

        \add_filter(
            'postbox_classes_' . $this->get_post_type() . '_' . $this->id,
            [ $this, 'add_classes' ]
        );
    }

    /**
     * @return string The Post Type slug for which to add the meta box
     */
    protected abstract function get_post_type();

    final public function add_meta_boxes_handler( $post ) {

        \add_meta_box(
            $this->id,
            $this->get_title( $post ),
            [ $this, 'get_content' ],
            $this->get_screen( $post ),
            $this->get_context( $post ),
            $this->get_priority( $post ),
            $this->get_callback_args( $post )
        );
    }

    final public function save_post_handler( $post ) {
        // allow sub class to prevent saving post meta
        if ( ! $this->should_save_meta_data( $post ) ) return;

        // return early if an autosave is happening
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

        $this->save_post_meta( $post );
    }

    final public function add_classes( $classes ) {
        return array_merge( $classes, $this->get_classes( $classes ) );
    }

    /**
     * You can override this method and add additional classes to the metabox.
     *
     * @param array $classes The array of classes that will be added to the metabox
     *
     * @return array
     */
    protected function get_classes( $classes ) {
        return $classes;
    }

    protected function should_save_meta_data( $post ) {
        return true;
    }

    protected abstract function save_post_meta( $post_id );

    protected function get_id() {
        $qualified_class_name = strtolower( get_class( $this ) );

        return basename( str_replace( '\\', '/', $qualified_class_name ) );
    }

    protected abstract function get_title();

    /**
     * This method should be overridden to return the contents of the metabox.
     *
     * @return string The HTML contents of the metabox.
     */
    public function get_content() {
        return '<h1>You need to return your own meta box content</h1>';
    }

    protected function get_screen( $post ) {
        return null;
    }

    protected function get_context( $post ) {
        return Meta_Box_Context::ADVANCED;
    }

    protected function get_priority( $post ) {
        return Meta_Box_Priority::NORMAL;
    }

    protected function get_callback_args( $post ) {
        return null;
    }
}