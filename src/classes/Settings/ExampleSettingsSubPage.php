<?php namespace WpStarterPlugin\Settings;

/**
 * Example for settings sub menu page.
 */
class ExampleSettingsSubPage extends SettingsPage {

	public static $instance = null;

	public static $SETTINGS_PAGE = array(
		'parent_slug' => 'theme_options',
		'menu_title'  => 'Sub Page',
		'page_title'  => 'Page Title',
		'slug'        => 'suboptions',
		'capability'  => 'administrator',
		'template'    => 'wrapper',
	);

	public static $SETTINGS_SECTIONS = array(
		array(
			'id'          => 'section_one',
			'title'       => 'Subsection One Example',
			'description' => 'Subsection one description.',
			'template'    => 'section',
		),
		array(
			'id'          => 'section_two',
			'title'       => 'Subsection Two Example',
			'description' => 'Subsection two description.',
			'template'    => 'section',
		),
	);

	public static $SETTINGS_TABS = array();

	public static $SETTINGS_FIELDS = array(
		'option_textfield_test_section_one' => array(
			'type'        => 'text',
			'label'       => 'Text Field Test Section One',
			'description' => 'Text field description.',
			'section'     => 'section_one',
		),
		'option_radio_test_section_one'     => array(
			'type'        => 'checkbox',
			'label'       => 'Checkbox Test Section One',
			'description' => 'Checkbox test description.',
			'options'     => array(
				'checkbox_option_1' => array(
					'label' => 'Option 1',
					'value' => '1',
				),
				'checkbox_option_2' => array(
					'label' => 'Option 2',
					'value' => '2',
				),
			),
			'section'     => 'section_one',
		),
		'option_textfield_test_section_two' => array(
			'type'        => 'text',
			'label'       => 'Text Field Test Section Two',
			'description' => 'Text field description.',
			'section'     => 'section_two',
		),
		'option_select_test_section_two'    => array(
			'type'        => 'select',
			'label'       => 'Select Test Section Two',
			'description' => 'Select test description.',
			'options'     => array(
				'select_option_1' => array(
					'label' => 'Option 1',
					'value' => '1',
				),
				'select_option_2' => array(
					'label' => 'Option 2',
					'value' => '2',
				),
			),
			'section'     => 'section_two',
		),
	);

	private function __construct() {}

	public static function get_instance() {
		if ( self::$instance === null ) {
			self::$instance = new ExampleSettingsSubPage();
		}
		return self::$instance;
	}

	public static function init() {
		$example_settings_sub_page = self::get_instance();
		$example_settings_sub_page->add_page( self::$SETTINGS_PAGE );
		add_action( 'admin_init', array( $example_settings_sub_page, 'add_sections' ) );
		add_action( 'admin_init', array( $example_settings_sub_page, 'add_fields' ) );
	}

}
