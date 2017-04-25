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
    		'add_new_item'          => __( 'Add New', $domain ) . ' ' . $singular,
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
    		'uploaded_to_this_item' => __( 'Uploaded to this', $domain ) . ' ' . strtolower( $singular ),
    		'items_list'            => $plural . ' ' . __( 'list', $domain ),
    		'items_list_navigation' => $plural . ' ' . __( 'list navigation', $domain ),
    		'filter_items_list'     => sprintf( esc_html__( 'Filter %s list', $domain ), $plural ),
        ];
    }

    public static function create_update_messages( $singular, $plural, $post_type, $domain = 'default' ) {
        $post_type_object = get_post_type_object( $post_type );
        $post = get_post();

        $messages = [
            0  => '', // Unused. Messages start at index 1.
    		1  => sprintf( __( '%s updated.', $domain ), $singular),
    		2  => __( 'Custom field updated.', $domain ),
    		3  => __( 'Custom field deleted.', $domain ),
    		4  => sprintf( __( '%s updated.', $domain ), $singular),
    		/* translators: %s: date and time of the revision */
    		5  => isset( $_GET['revision'] ) ? sprintf( __( '%s restored to revision from %s', $domain ), $singular, wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    		6  => sprintf( __( '%s published.', $domain ), $singular ),
    		7  => sprintf( __( '%s saved.', $domain ), $singular ),
    		8  => sprintf( __( '%s submitted.', $domain ), $singular ),
    		9  => sprintf(
    			__( '%s scheduled for: <strong>%1$s</strong>.', $domain ),
                $singular,
    			// translators: Publish box date format, see http://php.net/date
    			date_i18n( __( 'M j, Y @ G:i', $domain ), strtotime( $post->post_date ) )
    		),
    		10 => sprintf( __( '%s draft updated.', $domain ), $singular )
        ];

        if ( $post_type_object->publicly_queryable ) {
            $permalink = get_permalink( $post->ID );

    		$view_link = sprintf( ' <a href="%s">%s</a>', esc_url( $permalink ), __( 'View', $domain ) . ' ' . strtolower( $singular ) );
    		$messages[ $post_type ][1] .= $view_link;
    		$messages[ $post_type ][6] .= $view_link;
    		$messages[ $post_type ][9] .= $view_link;

    		$preview_permalink = add_query_arg( 'preview', 'true', $permalink );
    		$preview_link = sprintf( ' <a target="_blank" href="%s">%s</a>', esc_url( $preview_permalink ), __( 'Preview', $domain ) . ' ' . strtolower( $singular ) );
    		$messages[ $post_type ][8]  .= $preview_link;
    		$messages[ $post_type ][10] .= $preview_link;
    	}

        return $messages;
    }
}
