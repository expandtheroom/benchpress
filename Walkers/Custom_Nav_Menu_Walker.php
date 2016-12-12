<?php

namespace BP\Walkers;

/**
 * A Customer Nav Walker that can be used to provide more control over the
 * output when calling wp_nav_menu.
 */
class Custom_Nav_Menu_Walker extends \Walker_Nav_Menu {

    protected $args = [
        /**
         * Whether or not to included the default WordPress menu classes
         */
        'include_wp_classes' => true,
        /**
         * The element to use for each level of the nav menu
         */
        'level_el' => 'ul',
        /**
         * The classes to add to each level element
         */
        'level_classes' => ['sub-menu'],
        /**
         * The element to use for the container of each nav item
         */
        'el_container' => 'li',
        /**
         * The classes to add to each container element
         */
        'el_container_classes' => [],
        /**
         * The classes to add to the anchor for each nav item
         */
        'link_classes' => []
    ];

    public function __construct( $args ) {
        $this->args = wp_parse_args( $args, $this->args );
    }

    public function start_lvl( &$output, $depth = 0, $args = [] ) {
        $args = wp_parse_args( $args, $this->args );

        $indent = str_repeat( "\t", $depth );
        $classes = implode( ' ', $args['level_classes'] );

        if ( empty( $args['level_el'] ) ) {
            return;
        }

        $output .= "\n$indent<{$args['level_el']} class=\"#{$classes}\">\n";
    }

    public function end_lvl( &$output, $depth = 0, $args = [] ) {
        $args = wp_parse_args( $args, $this->args );

        if ( empty( $args['level_el'] ) ) {
            return;
        }

        $indent = str_repeat( "\t", $depth );
        $output .= "$indent</{$args['level_el']}>\n";
    }

    public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
        $args = wp_parse_args( $args, $this->args );

        $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

        if ( $args['include_wp_classes'] ) {
            $classes = empty( $item->classes ) ? array() : (array) $item->classes;
            $classes[] = 'menu-item-' . $item->ID;
        } else {
            $classes = [];
        }

        $classes = array_merge( $classes, $args['el_container_classes'] );

        /**
         * Filters the arguments for a single nav menu item.
         *
         * @since 4.4.0
         *
         * @param array  $args  An array of arguments.
         * @param object $item  Menu item data object.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

        /**
         * Filters the CSS class(es) applied to a menu item's list item element.
         *
         * @since 3.0.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of wp_nav_menu() arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
        $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

        /**
         * Filters the ID applied to a menu item's list item element.
         *
         * @since 3.0.1
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
         * @param object $item    The current menu item.
         * @param array  $args    An array of wp_nav_menu() arguments.
         * @param int    $depth   Depth of menu item. Used for padding.
         */
        $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
        $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

        if ( ! $args['include_wp_classes'] ) {
            $id = '';
        }

        if ( ! empty( $args['el_container'] ) ) {
            $output .= $indent . '<' . $args['el_container'] . ' '  . $id . $class_names .'>';
        }

        $atts = array();
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target )     ? $item->target     : '';
        $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
        $atts['href']   = ! empty( $item->url )        ? $item->url        : '';

        if ( ! empty( $args['link_classes'] ) ) {
            $atts['class']  = implode(' ', $args['link_classes'] );
        }

        /**
         * Filters the HTML attributes applied to a menu item's anchor element.
         *
         * @since 3.6.0
         * @since 4.1.0 The `$depth` parameter was added.
         *
         * @param array $atts {
         *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
         *
         *     @type string $title  Title attribute.
         *     @type string $target Target attribute.
         *     @type string $rel    The rel attribute.
         *     @type string $href   The href attribute.
         * }
         * @param object $item  The current menu item.
         * @param array  $args  An array of wp_nav_menu() arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

        $attributes = '';
        foreach ( $atts as $attr => $value ) {
            if ( ! empty( $value ) ) {
                $value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters( 'the_title', $item->title, $item->ID );

        /**
         * Filters a menu item's title.
         *
         * @since 4.4.0
         *
         * @param string $title The menu item's title.
         * @param object $item  The current menu item.
         * @param array  $args  An array of wp_nav_menu() arguments.
         * @param int    $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before . $title . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        /**
         * Filters a menu item's starting output.
         *
         * The menu item's starting output only includes `$args->before`, the opening `<a>`,
         * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
         * no filter for modifying the opening and closing `<li>` for a menu item.
         *
         * @since 3.0.0
         *
         * @param string $item_output The menu item's starting HTML output.
         * @param object $item        Menu item data object.
         * @param int    $depth       Depth of menu item. Used for padding.
         * @param array  $args        An array of wp_nav_menu() arguments.
         */
        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    public function end_el( &$output, $item, $depth = 0, $args = [] ) {
        $args = wp_parse_args( $args, $this->args );

        if ( ! empty( $args['el_container'] ) ) {
            $output .= "</{$args['el_container']}>\n";
        }
    }
}
