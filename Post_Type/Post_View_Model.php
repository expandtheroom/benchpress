<?php

namespace BenchPress\Post_Type;

/**
 * Class Post_View_Model
 *
 * Base class for Post View Models.
 *
 * @package BenchPress
 */
abstract class Post_View_Model {

    /**
     * The post object that the view model will use.
     *
     * @var \WP_Post
     */
    protected $post;

    /**
     * Post_View_Model constructor.
     *
     * @param \WP_Post $post
     */
    public function __construct( \WP_Post $post ) {
        $this->post = $post;
    }

    /**
     * Get the post for the view model.
     *
     * @return \WP_Post
     */
    public function get_post() {
        return $this->post;
    }

    /**
     * Convenience method for the_content
     */
    public function the_content( $more_link_text = null, $strip_teaser = false ) {
        the_content( $more_link_text, $strip_teaser );
    }

    /**
     * Convenience method for get_the_content
     */
    public function get_the_content( $more_link_text = null, $strip_teaser = false ) {
        return get_the_content( $more_link_text, $strip_teaser );
    }

    /**
     * Convenience method for the_title
     */
    public function the_title( $before = '', $after = '', $echo = true ) {
        the_title( $before, $after, $echo );
    }

    /**
     * Convenience method for get_the_title
     */
    public function get_the_title() {
        return get_the_title( $this->post );
    }

    /**
     * Convenience method for the_permalink
     */
    public function the_permalink() {
        the_permalink( $this->post );
    }

    /**
     * Convenience method for get_the_permalink
     */
    public function get_the_permalink($leavename = false) {
        return get_the_permalink( $this->post, $leavename );
    }
}
