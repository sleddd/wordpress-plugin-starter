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

		$post_type_name	= strtolower( str_replace( '_', ' ', $post_type_name ) );
		$box_id       	= empty( $meta_prefix ) ? strtolower( str_replace( ' ', '_', $title ) ) : $meta_prefix;
		$box_title   	= ucwords( str_replace( '_', ' ', $title ) );
		$box_context  	= $context;
		$box_priority 	= $priority;

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
						wp_nonce_field( plugin_basename( __FILE__ ), 'custom_post_type' );

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
								$field_id_name = esc_attr( strtolower( str_replace( ' ', '_', $data['id'] ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) ) );
								switch ( $field['type'] ) {
									case 'text':										
										$value = is_array( $meta ) && array_key_exists( $field_id_name, $meta ) ? $meta[ $field_id_name ][0] : '';
										echo '<label for="' . $field_id_name . '">' . $label . '</label><input type="text" name="' . $field_id_name . '" id="' . $field_id_name . '" value="' . wp_kses_post( $value ) . '" />';
										if ( array_key_exists( 'desc', $field ) ) {
											echo '<span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
										}
										break;
									case 'textarea':
										$value = is_array( $meta ) && array_key_exists( $field_id_name, $meta ) ? $meta[ $field_id_name ][0] : '';
										echo '<label for="' . $field_id_name . '">' . $label . '</label><textarea name="' . $field_id_name . '" id="' . $field_id_name . '">' . wp_kses_post( $value ) . '</textarea>';
										if ( array_key_exists( 'desc', $field ) ) {
											echo '<span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
										}
										break;
									case 'checkbox':
										echo '<label for="' . $field_id_name . '">' . $label . '</label>';
										if ( array_key_exists( 'options', $field ) ) {
											$options_count = 0;
											$saved_options = is_array( $meta ) && array_key_exists( $field_id_name, $meta ) ? $meta[ $field_id_name ][0] : [];
											$saved_options = maybe_unserialize( $saved_options );
											echo '<div class="fieldset">';
											foreach ( $field['options'] as $label => $value ) {
												$label = wp_kses_post( $label );
												$checked = ! empty( $saved_options ) && isset( $saved_options[ $options_count ] ) && $saved_options[ $options_count ] === $value ? 'checked="checked"' : '';
												echo '<label for="' . $field_id_name . '">' . $label . '</label><input type="checkbox" name="' . $field_id_name . '[' . $options_count . ']" id="' . $field_id_name . '" value="' . esc_attr( $value ) . '"' . $checked . '/>';
												$options_count++;
											}
											echo '</div>';
										}
										if ( array_key_exists( 'desc', $field ) ) {
											echo '<span class="description">' . $field['desc'] . '</span>';
										}
										break;
									case 'radio':
										echo '<label for="' . $field_id_name . '">' . $label . '</label>';
										echo '<div class="fieldset">';
										foreach ( $field['options'] as $label => $value ) {
											$label = wp_kses_post( $label );
											$checked =  is_array( $meta ) && array_key_exists( $field_id_name, $meta ) && $meta[ $field_id_name ][0] === $value ? 'checked="checked"' : '';
											echo '<label for="' . $field_id_name . '">' . $label . '</label><input type="radio" name="' . $field_id_name . '" id="' . $field_id_name . '" value="' . esc_attr( $value ) . '"' . $checked . '/>';
										}
										echo '</div>';
										if ( array_key_exists( 'desc', $field ) ) {
											echo '<span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
										}
										break;
									case 'select':
										echo '<label for="' . $field_id_name . '">' . $label . '</label>';
										echo '<select name="' . $field_id_name . '" id="' . $field_id_name . '">';
										foreach ( $field['options'] as $label => $value ) {
											$label = wp_kses_post( $label );
											$selected =  is_array( $meta ) && array_key_exists( $field_id_name, $meta ) && $meta[ $field_id_name ][0] == $value ? 'selected="selected"' : '';
											echo '<option value="' . wp_kses_post( $value ) . '"' . $selected . '>' . $label . '</option>';
										}
										echo '</select>';
										if ( array_key_exists( 'desc', $field ) ) {
											echo '<span class="description">' . wp_kses_post( $field['desc'] ) . '</span>';
										}
										break;
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
			function() use ( $post_type_name ) {
				 // Deny the WordPress autosave function
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
					return;
				}

				if ( ! isset( $_POST['custom_post_type'] ) || ! wp_verify_nonce( $_POST['custom_post_type'], plugin_basename( __FILE__ ) ) ) {
					return;
				}

				global $post;

				if ( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == $post_type_name ) {
					global $custom_fields;

					// Loop through each meta box
					foreach ( $custom_fields as $title => $fields ) {

						// Loop through all fields
						foreach ( $fields as $label => $type ) {
							$field_id_name = strtolower( str_replace( ' ', '_', $title ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );
							$old           = get_post_meta( $post->ID, $field_id_name, true );
							$new           = is_array( $_POST[ $field_id_name ] ) ? $_POST[ $field_id_name ] : esc_html( $_POST[ $field_id_name ] );
							if ( gettype( $new ) == 'string' && strlen( $new ) <= 0 ) {
								delete_post_meta( $post->ID, $field_id_name );
							} else {
								update_post_meta( $post->ID, $field_id_name, $new );
							}
						}
					}
				}
			}
		);

	}

}
