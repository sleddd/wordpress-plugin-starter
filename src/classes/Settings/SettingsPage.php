<?php namespace WpStarterPlugin\Settings;

/**
 * Manages registration of a custom settings page.
 *
 * Extend this class with custom static members to easily
 * generate a custom settings page in the admin menu.
 */
class SettingsPage {

	/**
	 * Defines admin menu page properties.
	 *
	 * @see https://codex.wordpress.org/Administration_Menus
	 * @var array static $SETTINGS_PAGE
	 */
	public static $SETTINGS_PAGE = array();


	/**
	 * Defines settings page tab properties.
	 *
	 * @var array static $SETTINGS_TABS {
	 *     Used to generate tabs for a custom settings page.
	 *
	 *     @type string  $title  Displays as tab label/title.
	 *     @type string  $slug   Page slug or query var for tab (?tab=slug).
	 * }
	 */
	public static $SETTINGS_TABS = array();


	/**
	 * Defines settings page sections.
	 *
	 * @see https://codex.wordpress.org/Administration_Menus
	 * @var array static $SETTINGS_SECTIONS {
	 *     {
	 *          @type string  $title    Displays before section fields.
	 *          @type string  $id       Identifier for section.
	 *          @type string  $description  Displays after title.
	 *          @type string  $template     Template file name. (Templates are in src/templates/settings).
	 *    }
	 * }
	 */
	public static $SETTINGS_SECTIONS = array();

	/**
	 * Defines settings fields for a section.
	 *
	 * @var array static $SETTINGS_FIELDS {
	 *   {
	 *          @type string  $type         text, textarea, checkbox, radio, or select field types.
	 *          @type string  $label        Optional. Field label.
	 *          @type string  $description  Optional. Field description.
	 *          @type array   $options  Optional. Field options for checkbox, radio, or select field type.
	 *                   {
	 *                       'field_option_name' => {
	 *                           @type string $label Field option label.
	 *                           @type string $value Field option value.
	 *                   }
	 *        @type string  $section Section identifier/slug.
	 *   }
	 * }
	 */
	public static $SETTINGS_FIELDS = array();


	/**
	 * Adds admin menu page using $SETTINGS_PAGE.
	 */
	public function add_page() {
		add_action(
			'admin_menu',
			function() {
				if ( array_key_exists( 'parent_slug', static::$SETTINGS_PAGE ) ) {
					add_submenu_page(
						static::$SETTINGS_PAGE['parent_slug'],
						__( static::$SETTINGS_PAGE['page_title'], 'wpstarterplugin' ),
						__( static::$SETTINGS_PAGE['menu_title'], 'wpstarterplugin' ),
						static::$SETTINGS_PAGE['capability'],
						static::$SETTINGS_PAGE['slug'],
						array( $this, 'render_page' ),
						null
					);
				} else {
					add_menu_page(
						__( static::$SETTINGS_PAGE['page_title'], 'wpstarterplugin' ),
						__( static::$SETTINGS_PAGE['menu_title'], 'wpstarterplugin' ),
						static::$SETTINGS_PAGE['capability'],
						static::$SETTINGS_PAGE['slug'],
						array( $this, 'render_page' ),
						null
					);
				}
			}
		);
	}


	/**
	 * Renders admin menu page using $SETTINGS_PAGE.
	 *
	 * If the page is saving fields, it will save fields from $_POST array.
	 */
	public function render_page() {
		if ( $_POST ) {
			static::save_fields( $_POST );
		}
		$page       = $this::$SETTINGS_PAGE['slug'];
		$page_title = $this::$SETTINGS_PAGE['page_title'];
		$tabs       = $this::$SETTINGS_TABS;
		include WP_STARTER_PLUGIN_PATH . '/src/templates/settings/' . static::$SETTINGS_PAGE['template'] . '.php';
	}


	/**
	 * Adds sections to page using $SETTINGS_PAGE, $SETTINGS_SECTIONS.
	 */
	public function add_sections() {
		$page_slug = static::$SETTINGS_PAGE['slug'];
		foreach ( static::$SETTINGS_SECTIONS as $section ) {
			add_settings_section(
				$section['id'],
				$section['title'],
				array( $this, 'render_sections' ),
				$page_slug
			);
		}
	}


	/**
	 * Renders settings sections using $SETTINGS_SECTIONS.
	 */
	public function render_sections( $section ) {
		foreach ( $this::$SETTINGS_SECTIONS as $sub_section ) {
			if ( $section['id'] == $sub_section['id'] ) {
				include WP_STARTER_PLUGIN_PATH . '/src/templates/settings/' . $sub_section['template'] . '.php';
			}
		}
	}


	/**
	 * Adds and registers settings fields using $SETTINGS_PAGE, $SETTINGS_FIELDS.
	 */
	public function add_fields() {
		$page_slug = static::$SETTINGS_PAGE['slug'];
		foreach ( static::$SETTINGS_FIELDS as $id => $field ) {
			$field['id'] = $id;
			add_settings_field(
				$id,
				$field['label'],
				array( $this, 'render_fields' ),
				$page_slug,
				$field['section'],
				$field,
			);
			register_setting(
				$page_slug,
				$id
			);
		}
	}


	/**
	 * Saves fields for a settings page.
	 *
	 * @param array $fields Fields to be saved.
	 */
	public static function save_fields( $fields ) {
		if ( check_admin_referer( 'wpstarter_plugin_form_nonce', 'admin_form_submission' ) ) {
			if ( current_user_can( static::$SETTINGS_PAGE['capability'] ) ) {
				foreach ( $fields as  $field_name => $value ) {
					if ( array_key_exists( $field_name, static::$SETTINGS_FIELDS ) ) {
						$setting = static::$SETTINGS_FIELDS[ $field_name ];
						switch ( $setting['type'] ) {
							case 'text':
								$value = sanitize_text_field( $value );
								break;
							case 'textarea':
								$value = sanitize_textarea_field( $value );
								break;
						}
						update_option( $field_name, $value );
					}
				}
			}
		}
	}


	/**
	 * Renders a settings field by type.
	 *
	 * @param array $field Fields to be rendered.
	 */
	public function render_fields( $field ) {
		$option = get_option( $field['id'] );
		switch ( $field['type'] ) {
			case 'text':
				echo '<div><input type="text" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . esc_attr( $option ) . '" />';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . $field['description'] . '</span>';
				}
				echo '</div>';
				break;
			case 'textarea':
				echo '<div><textarea name="' . $field['id'] . '" id="' . $field['id'] . '">' . esc_attr( $option ) . '</textarea>';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . $field['description'] . '</span>';
				}
				echo '</div>';
				break;
			case 'checkbox':
				if ( array_key_exists( 'options', $field ) ) {
					echo '<div class="fieldset">';
					$options_count = 1;
					foreach ( $field['options'] as $field_option ) {
						$checked = is_array( $option ) && $option[ $options_count ] === $field_option['value'] ? 'checked="checked"' : '';
						echo '<label for="' . $field['id'] . '">' . $field_option['label'] . '</label><input type="checkbox" name="' . $field['id'] . '[' . $options_count . ']" id="' . $field['id'] . '[' . $options_count . ']" value="' . $field_option['value'] . '"' . $checked . '/>';
						$options_count++;
					}
					echo '<input type="hidden" name="' . $field['id'] . '[hidden]" value="0"/>';
					echo '</div>';
					if ( array_key_exists( 'description', $field ) ) {
						echo '<span class="description">' . $field['description'] . '</span>';
					}
				}
				if ( array_key_exists( 'desc', $field ) ) {
					echo '<span class="description">' . $field['desc'] . '</span>';
				}
				break;
			case 'radio':
				echo '<div class="fieldset">';
				foreach ( $field['options'] as $field_option ) {
					$checked = $option === $field_option['value'] ? 'checked="checked"' : '';
					echo '<label for="' . $field['id'] . '">' . $field_option['label'] . '</label><input type="radio" name="' . $field['id'] . '" id="' . $field['id'] . '" value="' . $field_option['value'] . '"' . $checked . '/>';
				}
				echo '</div>';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . $field['description'] . '</span>';
				}
				break;
			case 'select':
				echo '<br/><select name="' . $field['id'] . '" id="' . $field['id'] . '">';
				echo '<option class="disabled">' . __( $field['label'], 'wpstarterplugin' ) . '</option>';
				foreach ( $field['options'] as $field_option ) {
					echo '<option', $option === $field_option['value'] ? ' selected="selected"' : '', ' value="' . $field_option['value'] . '">' . $field_option['label'] . '</option>';
				}
				echo '</select>';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . $field['description'] . '</span>';
				}
				break;
		}
	}

}
