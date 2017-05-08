<?php

namespace BenchPress\Shortcode;

/**
 * A base class for creating shortcodes. This class must be extended for each
 * shortcode.
 *
 * @package BenchPress\Shortcode
 */
abstract class Shortcode {

    private $name;

    public static function init() {
        $self = new static();

        return $self;
    }

    public function __construct() {
        $this->name = $this->get_name();

        \add_shortcode( $this->name, [ $this, '__callback' ] );
    }

    final public function __callback( $atts, $content, $tag ) {
        return $this->get_content( shortcode_atts( $this->get_defaults(), $atts, $this->name ), $content, $tag );
    }

    /**
     * @return string The shortcode name.
     */
    protected abstract function get_name();

    /**
     * Return an array of defaults for the shortcode. These defaults will be merged with the
     * attributes provided to the shortcode when the shortcode is used.
     *
     * @return array The shortcode defaults as an array of key => value pairs. The
     */
    protected function get_defaults() {
        return [];
    }

    /**
     * Returns the content for the shortcode. Sub-classes should override this method.
     *
     * It will be passed the same arguments that the callback for `add_shortcode` be passed.
     * The $atts will contain the values provided when the shortcode is used combined with the
     * defaults returned by get_defaults().
     */
    protected abstract function get_content( $atts, $content, $tag );
}
