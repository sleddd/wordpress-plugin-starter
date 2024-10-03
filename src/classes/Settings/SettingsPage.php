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
			function () {
				if ( array_key_exists( 'parent_slug', static::$SETTINGS_PAGE ) ) {
					add_submenu_page(
						static::$SETTINGS_PAGE['parent_slug'],
						esc_html__( static::$SETTINGS_PAGE['page_title'], 'wpstarterplugin' ),
						esc_html__( static::$SETTINGS_PAGE['menu_title'], 'wpstarterplugin' ),
						static::$SETTINGS_PAGE['capability'],
						static::$SETTINGS_PAGE['slug'],
						array( $this, 'render_page' ),
						null
					);
				} else {
					add_menu_page(
						esc_html__( static::$SETTINGS_PAGE['page_title'], 'wpstarterplugin' ),
						esc_html__( static::$SETTINGS_PAGE['menu_title'], 'wpstarterplugin' ),
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
		if ( ! current_user_can( static::$SETTINGS_PAGE['capability'] ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'wpstarterplugin' ) );
		}

		if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['admin_form_submission'] ) ) {
			$this->save_fields( $_POST );
		}

		$page       = esc_attr( static::$SETTINGS_PAGE['slug'] );
		$page_title = esc_html( static::$SETTINGS_PAGE['page_title'] );
		$tabs       = static::$SETTINGS_TABS;
		include WP_STARTER_PLUGIN_PATH . '/src/templates/settings/' . sanitize_file_name( static::$SETTINGS_PAGE['template'] ) . '.php';
	}

	/**
	 * Adds sections to page using $SETTINGS_PAGE, $SETTINGS_SECTIONS.
	 */
	public function add_sections() {
		$page_slug = static::$SETTINGS_PAGE['slug'];
		foreach ( static::$SETTINGS_SECTIONS as $section ) {
			add_settings_section(
				$section['id'],
				wp_kses_post( $section['title'] ),
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
				include WP_STARTER_PLUGIN_PATH . '/src/templates/settings/' . sanitize_file_name( $sub_section['template'] ) . '.php';
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
				wp_kses_post( $field['label'] ),
				array( $this, 'render_fields' ),
				$page_slug,
				$field['section'],
				$field
			);
			register_setting(
				$page_slug,
				$id,
				array(
					'sanitize_callback' => function ( $value ) {
						return $this->sanitize_field( $value, 'text' );
					},
				)
			);
		}
	}

	/**
	 * Saves fields for a settings page.
	 *
	 * @param array $fields Fields to be saved.
	 */
	public static function save_fields( $fields ) {
		if ( ! isset( $fields['admin_form_submission'] ) ||
			! wp_verify_nonce( $fields['admin_form_submission'], 'wpstarter_plugin_form_nonce' ) ||
			! current_user_can( static::$SETTINGS_PAGE['capability'] )
		) {
			wp_die( esc_html__( 'Sorry, you are not allowed to perform this action.', 'wpstarterplugin' ) );
		}

		foreach ( $fields as $field_name => $value ) {
			if ( array_key_exists( $field_name, static::$SETTINGS_FIELDS ) ) {
				$sanitized_value = self::sanitize_field( $value, static::$SETTINGS_FIELDS[ $field_name ]['type'] );
				update_option( $field_name, $sanitized_value );
			}
		}
	}

	/**
	 * Sanitizes a field based on its type.
	 *
	 * @param mixed  $value The value to sanitize.
	 * @param string $type The type of the field.
	 * @return mixed The sanitized value.
	 */
	public static function sanitize_field( $value, $type ) {
		switch ( $type ) {
			case 'text':
				return sanitize_text_field( $value );
			case 'textarea':
				return sanitize_textarea_field( $value );
			case 'checkbox':
				return is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : array();
			case 'radio':
			case 'select':
				return sanitize_text_field( $value );
			default:
				return $value;
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
				echo '<div><input type="text" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $option ) . '" />';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
				}
				echo '</div>';
				break;
			case 'textarea':
				echo '<div><textarea name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '">' . esc_textarea( $option ) . '</textarea>';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
				}
				echo '</div>';
				break;
			case 'checkbox':
				if ( array_key_exists( 'options', $field ) ) {
					echo '<div class="fieldset">';
					$options_count = 1;
					foreach ( $field['options'] as $field_option ) {
						$checked = is_array( $option ) && isset( $option[ $options_count ] ) && $option[ $options_count ] === $field_option['value'] ? 'checked="checked"' : '';
						echo '<label for="' . esc_attr( $field['id'] ) . '_' . $options_count . '">' . wp_kses_post( $field_option['label'] ) . '</label>';
						echo '<input type="checkbox" name="' . esc_attr( $field['id'] ) . '[' . (int) $options_count . ']" id="' . esc_attr( $field['id'] ) . '_' . $options_count . '" value="' . esc_attr( $field_option['value'] ) . '"' . $checked . '/>';
						++$options_count;
					}
					echo '<input type="hidden" name="' . esc_attr( $field['id'] ) . '[hidden]" value="0"/>';
					echo '</div>';
					if ( array_key_exists( 'description', $field ) ) {
						echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
					}
				}
				break;
			case 'radio':
				echo '<div class="fieldset">';
				foreach ( $field['options'] as $field_option ) {
					$checked = $option === $field_option['value'] ? 'checked="checked"' : '';
					echo '<label for="' . esc_attr( $field['id'] ) . '_' . sanitize_key( $field_option['value'] ) . '">' . wp_kses_post( $field_option['label'] ) . '</label>';
					echo '<input type="radio" name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '_' . sanitize_key( $field_option['value'] ) . '" value="' . esc_attr( $field_option['value'] ) . '"' . $checked . '/>';
				}
				echo '</div>';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
				}
				break;
			case 'select':
				echo '<br/><select name="' . esc_attr( $field['id'] ) . '" id="' . esc_attr( $field['id'] ) . '">';
				echo '<option value="" disabled>' . esc_html__( $field['label'], 'wpstarterplugin' ) . '</option>';
				foreach ( $field['options'] as $field_option ) {
					echo '<option', $option === $field_option['value'] ? ' selected="selected"' : '', ' value="' . esc_attr( $field_option['value'] ) . '">' . esc_html( $field_option['label'] ) . '</option>';
				}
				echo '</select>';
				if ( array_key_exists( 'description', $field ) ) {
					echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
				}
				break;
		}
	}
}
