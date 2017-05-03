<?php

namespace BenchPress\Taxonomy;

/**
 * Extend this class to create your taxonomies. The taxonomy name can be accessed
 * statically for easy access.
 *
 * If your taxonomy class was called My_Taxonomy, you would
 * be able to access the taxonomy name like so: My_Taxonomy::taxonomy()
 *
 * @package BenchPress\Taxonomy
 */
abstract class Base_Taxonomy {

    protected static $taxonomies = [];

    public static function init() {
        if ( isset( self::$taxonomies[ static::class ] ) ) return;

        $self = new static();

        self::$taxonomies[ static::class ] = $self;

        add_action( 'init', [ $self, '_register_taxonomy' ] );
        add_filter( 'term_updated_messages', [ $self, '_term_updated_messages_handler' ] );
    }

    final public function _register_taxonomy() {
        register_taxonomy(
            $this->get_taxonomy(),
            $this->get_post_types(),
            array_merge(
                [
                    'labels' => Label_Maker::create_labels( $this->get_singular_name(), $this->get_plural_name(), $this->get_text_domain() )
                ],
                $this->get_args()
            )
        );
    }

    final public function _term_updated_messages_handler( $messages ) {
        return $this->get_updated_messages();
    }

    protected function get_updated_messages() {
        return Label_Maker::create_update_messages(
            $this->get_singular_name(),
            $this->get_plural_name(),
            $this->get_text_domain()
        );
    }
    /**
     * Return your taxonomy name
     */
    protected abstract function get_taxonomy();
    protected abstract function get_post_types();
    protected abstract function get_singular_name();
    protected abstract function get_plural_name();

    protected function get_text_domain() {
        return 'default';
    }

    protected function get_args() {
        return [];
    }

    /**
     * Returns the taxonomy name for the class
     * @return string
     */
    public static function taxonomy () {
        return self::$taxonomies[static::class]->get_taxonomy();
    }


}
