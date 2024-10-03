<?php namespace WpStarterPlugin\PostTypes;

/**
 * Get formatted set of arguments for a post type name.
 *
 * @param   string $name   Name of post type.
 * @param   array  $args   Optional. Custom post type arguments.
 * @param   array  $labels Optional. Custom post type labels.
 * @return  array  Formatted post type arguments merged with defaults.
 */
function get_post_type_args( $name = '', $args = array(), $labels = array() ) {
	if ( empty( $name ) ) {
		return;
	}
	$name   = ucwords( str_replace( '_', ' ', $name ) );
	$plural = $name . 's';

	$labels = array_merge(
		array(
			'name'               => __( $plural, 'wpstarterplugin' ),
			'singular_name'      => __( $name, 'wpstarterplugin' ),
			'add_new'            => __( 'Add New ' . ucwords( strtolower( $name ) ), 'wpstarterplugin' ),
			'add_new_item'       => __( 'Add New ' . $name, 'wpstarterplugin' ),
			'edit_item'          => __( 'Edit ' . $name, 'wpstarterplugin'  ),
			'new_item'           => __( 'New ' . $name, 'wpstarterplugin'  ),
			'all_items'          => __( 'All ' . $plural, 'wpstarterplugin'  ),
			'view_item'          => __( 'View ' . $name, 'wpstarterplugin' ),
			'search_items'       => __( 'Search ' . $plural, 'wpstarterplugin'  ),
			'not_found'          => __( 'No ' . strtolower( $plural ) . ' found', 'wpstarterplugin'  ),
			'not_found_in_trash' => __( 'No ' . strtolower( $plural ) . ' found in Trash', 'wpstarterplugin'  ),
			'parent_item_colon'  => '',
			'menu_name'          => __( $plural, 'wpstarterplugin' )
		),
		$labels
	);

	$args = array_merge(
		array(
			'label'             	=> $plural,
			'labels'            	=> $labels,
			'public'				=> true,
			'publicly_queryable'	=> true,
			'supports' 				=> array(
				'title',
				'editor',
				'excerpt',
				'thumbnail'
			),
			'show_ui'				=> true,
			'show_in_rest'			=> true,
			'show_in_menu'			=> true,
			'show_in_nav_menus'     => true,
			'has_archive'			=> true
		),
		$args
	);

	return $args;

}


/**
 * Get formatted set of arguments for a taxonomy.
 *
 * @param   string $name   Name of taxonomy.
 * @param   array  $args   Optional. Custom taxonomy arguments.
 * @param   array  $labels Optional. Custom taxonomy labels.
 * @return  array  Formatted taxonomy arguments merged with defaults.
 */
function get_taxonomy_args( $name = '', $args = array(), $labels = array() ) {
	if ( empty( $name ) ) {
		return;
	}
	$name   = ucwords( str_replace( '_', ' ', $name ) );
	$plural = $name . 's';

	$labels = array_merge(
		array(
			'name'              => _x( $plural, ' wpstarterplugin' ),
			'singular_name'     => _x( $name, ' wpstarterplugin' ),
			'search_items'      => __( 'Search ' . $plural,  ' wpstarterplugin' ),
			'all_items'         => __( 'All ' . $plural, 'wpstarterplugin' ),
			'parent_item'       => __( 'Parent ' . $name, 'wpstarterplugin' ),
			'parent_item_colon' => __( 'Parent ' . $name . ':', 'wpstarterplugin' ),
			'edit_item'         => __( 'Edit ' . $name, 'wpstarterplugin' ),
			'update_item'       => __( 'Update ' . $name, 'wpstarterplugin' ),
			'add_new_item'      => __( 'Add New ' . $name, 'wpstarterplugin' ),
			'new_item_name'     => __( 'New ' . $name . ' Name', 'wpstarterplugin' ),
			'menu_name'         => __( $name, 'wpstarterplugin' )
		),
		$labels
	);

	$args = array_merge(
		array(
			'hierarchical'      	=> false,
			'public'				=> true,
			'labels'            	=> $labels,
			'show_ui'           	=> true,
			'show_admin_column' 	=> true,
			'show_in_rest'      	=> true,
			'show_tagcloud'			=> true,		
		),
		$args
	);

	return $args;

}


/**
 * Registers a post type.
 *
 * @param   string $name   Name of post type.
 * @param   array  $args   Optional. Custom post type arguments.
 * @param   array  $labels Optional. Custom post type labels.
 * @return  void
 */
function register_post_type( $post_type_name = '', $args = array(), $labels = array() ) {
	if ( empty( $post_type_name ) ) {
		return;
	}
	$args = get_post_type_args( $post_type_name, $args, $labels );

	add_action(
		'init',
		function() use ( $post_type_name, $args ) {
			\register_post_type( $post_type_name, $args );
			// Flush rewrites to ensure it will pull the right Gutenberg template.
			flush_rewrite_rules();
		}
	);
}


/**
 * Adds a taxonomy to a post type.
 *
 * @param   string $taxonomy_name    Name of taxonomy.
 * @param   string $post_type_name   Name of post type.
 * @param   array  $args   Optional. Custom taxonomy arguments.
 * @param   array  $labels Optional. Custom taxonomy labels.
 * @return  void
 */
function add_taxonomy( $taxonomy_name = '', $post_type_name = '', $args = array(), $labels = array() ) {

	if ( empty( $post_type_name ) || empty( $taxonomy_name ) ) {
		return;
	}

	$post_type_name = strtolower( str_replace( '_', ' ', $post_type_name ) );

	$args = get_taxonomy_args( $taxonomy_name, $args, $labels );

	if ( ! taxonomy_exists( $taxonomy_name ) ) {
		// Add the taxonomy to the post type.
		add_action(
			'init',
			function() use ( $taxonomy_name, $post_type_name, $args ) {
				 \register_taxonomy( strtolower( $taxonomy_name ), $post_type_name, $args );
			}
		);
	} else {
		// The taxonomy already exists, attach existing taxonomy to post type.
		add_action(
			'init',
			function() use ( $taxonomy_name, $post_type_name ) {
				\register_taxonomy_for_object_type( strtolower( $taxonomy_name ), $post_type_name );
			}
		);
	}
}


/**
 * Adds a meta box to a post type.
 *
 * @param   string $title     		Title for meta box.
 * @param   string $meta_prefix		Optional. Prefix for the meta box ids. ( i.e. example_book_cpt_title, example_book_cpt_author)
 * @param   array  $fields    		Optional. Metabox fields properties.
 * @param   string $context   		Optional. Where the metabox is displayed ( normal, side, advanced ).
 * @param   string $priority  		Optional. Where in the context the metabox shows (high, core, default, low).
 * @param   string $post_type_name  Name of post type.
 * @return  void
 */
function add_meta_box( $title = '', $meta_prefix = null, $fields = array(), $context = 'normal', $priority = 'default', $post_type_name = '' ) {
    if ( empty( $title ) || empty( $post_type_name ) ) {
        return;
    }

    if ( ! empty( $title ) ) {
        $post_type_name = sanitize_key( strtolower( str_replace( '_', ' ', $post_type_name ) ) );
        $box_id         = empty( $meta_prefix ) ? sanitize_key( strtolower( str_replace( ' ', '_', $title ) ) ) : sanitize_key( $meta_prefix );
        $box_title      = sanitize_text_field( ucwords( str_replace( '_', ' ', $title ) ) );
        $box_context    = sanitize_key( $context );
        $box_priority   = sanitize_key( $priority );

        global $custom_fields;
        $custom_fields[ $box_id ] = $fields;

        add_action(
            'admin_init',
            function() use ( $box_id, $box_title, $post_type_name, $box_context, $box_priority, $fields ) {
                \add_meta_box(
                    $box_id,
                    $box_title,
                    function ( $post, $data ) {
                        global $post;

                        // Nonce field for some validation
                        wp_nonce_field( plugin_basename( __FILE__ ), 'custom_post_type_nonce' );

                        // Get all inputs from $data
                        $custom_fields = $data['args'][0];

                        // Get the saved values
                        $meta = get_post_custom( $post->ID );

                        // Check the array and loop through it
                        if ( ! empty( $custom_fields ) ) {
                            echo '<div class="wp_starter_plugin_custom_meta">';
                            /* Loop through $custom_fields */
                            foreach ( $custom_fields as $label => $field ) {
                                $label = wp_kses_post( $label );
                                $field_id_name = esc_attr( sanitize_key( $data['id'] . '_' . $label ) );
                                switch ( $field['type'] ) {
                                    case 'text':                                        
                                        $value = isset( $meta[ $field_id_name ] ) ? esc_attr( $meta[ $field_id_name ][0] ) : '';
                                        echo '<label for="' . esc_attr( $field_id_name ) . '">' . esc_html( $label ) . '</label><input type="text" name="' . esc_attr( $field_id_name ) . '" id="' . esc_attr( $field_id_name ) . '" value="' . $value . '" />';
                                        break;
                                    case 'textarea':
                                        $value = isset( $meta[ $field_id_name ] ) ? esc_textarea( $meta[ $field_id_name ][0] ) : '';
                                        echo '<label for="' . esc_attr( $field_id_name ) . '">' . esc_html( $label ) . '</label><textarea name="' . esc_attr( $field_id_name ) . '" id="' . esc_attr( $field_id_name ) . '">' . $value . '</textarea>';
                                        break;
                                    case 'checkbox':
                                        echo '<label for="' . esc_attr( $field_id_name ) . '">' . esc_html( $label ) . '</label>';
                                        if ( isset( $field['options'] ) ) {
                                            $options_count = 0;
                                            $saved_options = isset( $meta[ $field_id_name ] ) ? maybe_unserialize( $meta[ $field_id_name ][0] ) : array();
                                            echo '<div class="fieldset">';
                                            foreach ( $field['options'] as $option_label => $value ) {
                                                $option_label = sanitize_text_field( $option_label );
                                                $checked = ! empty( $saved_options ) && isset( $saved_options[ $options_count ] ) && $saved_options[ $options_count ] === $value ? 'checked="checked"' : '';
                                                echo '<label for="' . esc_attr( $field_id_name . '_' . $options_count ) . '">' . esc_html( $option_label ) . '</label><input type="checkbox" name="' . esc_attr( $field_id_name . '[' . $options_count . ']' ) . '" id="' . esc_attr( $field_id_name . '_' . $options_count ) . '" value="' . esc_attr( $value ) . '"' . $checked . '/>';
                                                $options_count++;
                                            }
                                            echo '</div>';
                                        }
                                        break;
                                    case 'radio':
                                        echo '<label for="' . esc_attr( $field_id_name ) . '">' . esc_html( $label ) . '</label>';
                                        echo '<div class="fieldset">';
                                        foreach ( $field['options'] as $option_label => $value ) {
                                            $option_label = sanitize_text_field( $option_label );
                                            $checked = isset( $meta[ $field_id_name ] ) && $meta[ $field_id_name ][0] === $value ? 'checked="checked"' : '';
                                            echo '<label for="' . esc_attr( $field_id_name . '_' . sanitize_key( $value ) ) . '">' . esc_html( $option_label ) . '</label><input type="radio" name="' . esc_attr( $field_id_name ) . '" id="' . esc_attr( $field_id_name . '_' . sanitize_key( $value ) ) . '" value="' . esc_attr( $value ) . '"' . $checked . '/>';
                                        }
                                        echo '</div>';
                                        break;
                                    case 'select':
                                        echo '<label for="' . esc_attr( $field_id_name ) . '">' . esc_html( $label ) . '</label>';
                                        echo '<select name="' . esc_attr( $field_id_name ) . '" id="' . esc_attr( $field_id_name ) . '">';
                                        foreach ( $field['options'] as $option_label => $value ) {
                                            $option_label = sanitize_text_field( $option_label );
                                            $selected = isset( $meta[ $field_id_name ] ) && $meta[ $field_id_name ][0] == $value ? 'selected="selected"' : '';
                                            echo '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . esc_html( $option_label ) . '</option>';
                                        }
                                        echo '</select>';
                                        break;
                                }
                                if ( isset( $field['desc'] ) ) {
                                    echo '<span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
                                }
                            }
                            echo '</div>';
                        }
                    },
                    $post_type_name,
                    $box_context,
                    $box_priority,
                    array( $fields )
                );
            }
        );

        // Save post fields
        add_action(
            'save_post',
            function( $post_id ) use ( $post_type_name ) {
                // Deny the WordPress autosave function
                if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                    return;
                }

                // Verify nonce
                if ( ! isset( $_POST['custom_post_type_nonce'] ) || ! wp_verify_nonce( $_POST['custom_post_type_nonce'], plugin_basename( __FILE__ ) ) ) {
                    return;
                }

                // Check user permissions
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return;
                }

                if ( get_post_type( $post_id ) == $post_type_name ) {
                    global $custom_fields;

                    // Loop through each meta box
                    foreach ( $custom_fields as $title => $fields ) {
                        // Loop through all fields
                        foreach ( $fields as $label => $type ) {
                            $field_id_name = sanitize_key( $title . '_' . $label );
                            if ( isset( $_POST[ $field_id_name ] ) ) {
                                $new_value = $_POST[ $field_id_name ];
                                if ( is_array( $new_value ) ) {
                                    $new_value = array_map( 'sanitize_text_field', $new_value );
                                } else {
                                    $new_value = sanitize_text_field( $new_value );
                                }
                                if ( empty( $new_value ) ) {
                                    delete_post_meta( $post_id, $field_id_name );
                                } else {
                                    update_post_meta( $post_id, $field_id_name, $new_value );
                                }
                            }
                        }
                    }
                }
            }
        );
    }
}

