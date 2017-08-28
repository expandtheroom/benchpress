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
        $class = get_called_class();

        if ( isset( self::$taxonomies[ $class ] ) ) return;

        $self = new static();

        self::$taxonomies[ $class ] = $self;

        add_action( 'init', [ $self, '_register_taxonomy' ] );
        add_filter( 'term_updated_messages', [ $self, '_term_updated_messages_handler' ] );
    }

    final public function _register_taxonomy() {
        register_taxonomy(
            $this->get_taxonomy(),
            $this->get_post_types(),
            array_replace_recursive(
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
                $this->get_text_domain()
            )
        ];
    }

    final public function _term_updated_messages_handler( $messages ) {
        $messages[ $this->get_taxonomy() ] = $this->get_updated_messages();

        return $messages;
    }

    /**
     * Returns an array of update message strings to use when the taxonomy is updated.
     */
    protected function get_updated_messages() {
        return Label_Maker::create_update_messages(
            $this->get_singular_name(),
            $this->get_plural_name(),
            $this->get_text_domain()
        );
    }
    /**
     * Returns the taxonomy name.
     */
    protected abstract function get_taxonomy();

    /**
     * Returns the post types that taxonomy should be applied to.
     */
    protected abstract function get_post_types();

    /**
     * Returns the singular name of the taxonomy. The name should be initial caps.
     */
    protected abstract function get_singular_name();

    /**
     * Returns the plural name of the taxonomy. The name should be initial caps.
     */
    protected abstract function get_plural_name();

    /**
     * Returns the text domain to use for translations for this taxonomy.
     */
    protected function get_text_domain() {
        return 'default';
    }

    /**
     * Returns the taxonomy arguments array.
     */
    protected function get_args() {
        return [];
    }

    /**
     * Returns the taxonomy name for the class
     * @return string
     */
    public static function taxonomy () {
        $class = get_called_class();

        return self::$taxonomies[ $class ]->get_taxonomy();
    }
}
