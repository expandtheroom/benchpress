<?php

namespace BenchPress\Taxonomy;

class Label_Maker {

    public static function create_labels( $singular, $plural, $domain = 'default' ) {

    	return [
    		'name'                       => $plural,
    		'singular_name'              => $singular,
    		'menu_name'                  => $plural,
    		'all_items'                  => sprintf( __( 'All %s', $domain ), $plural ),
    		'parent_item'                => sprintf( __( 'Parent %s', $domain ), $plural ),
    		'parent_item_colon'          => sprintf( __( 'Parent %s:', $domain ), $singular ),
    		'new_item_name'              => sprintf( __( 'New %s Name', $domain ), $singular ),
    		'add_new_item'               => sprintf( __( 'Add New %s', $domain ), $singular ),
    		'edit_item'                  => sprintf( __( 'Edit %s', $domain ), $singular ),
    		'update_item'                => sprintf( __( 'Update %s', $domain ), $singular ),
    		'view_item'                  => sprintf( __( 'View %s', $domain ), $singular ),
    		'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', $domain ), strtolower( $plural ) ),
    		'add_or_remove_items'        => sprintf( __( 'Add or remove %s', $domain ), strtolower( $plural ) ),
    		'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', $domain ), strtolower( $plural ) ),
    		'popular_items'              => sprintf( __( 'Popular %s', $domain ), $plural ),
    		'search_items'               => sprintf( __( 'Search %s', $domain ), $plural ),
    		'not_found'                  => sprintf( __( 'No %s found.', $domain ), strtolower( $plural ) ),
    		'no_terms'                   => sprintf( __( 'No %s', $domain ), strtolower( $plural ) ),
    		'items_list'                 => sprintf( __( '%s list', $domain ), $plural ),
    		'items_list_navigation'      => sprintf( __( '%s list navigation', $domain ), $plural )
    	];
    }

    public static function create_update_messages( $singular, $plural, $domain = 'default' ) {

        return [
            0 => '',
            1 => sprintf( __( '%s added.', $domain ), $singular ),
            2 => sprintf( __( '%s deleted.', $domain ), $singular ),
            3 => sprintf( __( '%s updated.', $domain ), $singular ),
            4 => sprintf( __( '%s not added.', $domain ), $singular ),
            5 => sprintf( __( '%s not updated.', $domain ), $singular ),
            6 => sprintf( __( '%s deleted.', $domain ), $plural )
        ];
    }
}
