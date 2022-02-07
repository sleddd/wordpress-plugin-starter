<?php
/*
* Settings - Section template.
*/

global $plugin_page;
$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : '';?>
<div class="wp_starter_plugin_settings__section">
	<?php
	if ( ! empty( $section['title'] ) ) {
		echo '<h2>' . $section['title'] . '</h2>';
	}
	if ( ! empty( $section['description'] ) ) {
		echo '<p>' . $section['description'] . '</p>';
	}
	?>
	<?php
	if ( $active_tab ) {
		do_settings_fields( $plugin_page, $active_tab );
	} else {
		do_settings_fields( $plugin_page, $section['id'] );
	}
	?>
</div>
