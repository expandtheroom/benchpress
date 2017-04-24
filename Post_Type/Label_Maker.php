<?php

namespace BenchPress\Post_Type;

class Label_Maker {
    public static function create_labels( $singular, $plural, $post_type, $domain = 'default' ) {
        return [
            'name'                  => $plural,
    		'singular_name'         => $singular,
    		'menu_name'             => $plural,
    		'name_admin_bar'        => $singular,
    		'archives'              => $singular . ' ' . __( 'Archives', $domain ),
    		'attributes'            => $singular . ' ' . __( 'Attributes', $domain ),
    		'parent_item_colon'     => __( 'Parent', $domain ) . ' ' . $singular,
    		'all_items'             => __( 'All', $domain ) . ' ' . $plural,
    		'add_new_item'          => __( 'Add New', $domain ) . $singular,
    		'add_new'               => _x( 'Add New', $post_type, $domain ),
    		'new_item'              => __( 'New', $domain ) . ' ' . $singular,
    		'edit_item'             => __( 'Edit', $domain ) . ' ' . $singular,
    		'update_item'           => __( 'Update', $domain ) . ' ' . $singular,
    		'view_item'             => __( 'View', $domain ) . ' ' . $singular,
    		'view_items'            => __( 'View', $domain ) . ' ' . $plurarl,
    		'search_items'          => __( 'Search', $domain ) . ' ' . $plural,
    		'not_found'             => __( 'Not found', $domain ),
    		'not_found_in_trash'    => __( 'Not found in Trash', $domain ),
    		'featured_image'        => __( 'Featured Image', $domain ),
    		'set_featured_image'    => __( 'Set featured image', $domain ),
    		'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
    		'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
    		'insert_into_item'      => __( 'Insert into', $domain ) . ' ' . strtolower( $singular ),
    		'uploaded_to_this_item' => __( 'Uploaded to this', $domain ) . ' ' strtolower( $singular ),
    		'items_list'            => $plural . ' ' . __( 'list', $domain ),
    		'items_list_navigation' => $plural . ' ' . __( 'list navigation', $domain ),
    		'filter_items_list'     => printf( esc_html__( 'Filter %s list', $domain ) ), $plural,
        ]
    }
}
