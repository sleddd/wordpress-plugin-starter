<?php global $wp_settings_sections; ?>
<div class="wrap wp_starter_plugin_settings">
	<h1 class="wp-heading-inline"><?php echo esc_attr( $page_title ); ?></h1>
	<h2 class="nav-tab-wrapper"> 
		<?php
		// Render tabs.
		$active_tab = '';
		if ( ! empty( $tabs ) ) {
			$first_tab   = reset( $tabs );
			$active_tab  = isset( $_GET['tab'] ) ? $_GET['tab'] : $first_tab['slug'];
			$_GET['tab'] = $active_tab;
			foreach ( $tabs as $slug => $tab ) {
				?>
				<a href="?page=<?php echo $page; ?>&tab=<?php echo esc_attr( $tab['slug'] ); ?>" class="nav-tab <?php echo $tab['slug'] === $active_tab ? 'nav-tab-active' : ''; ?>">
					<?php echo esc_attr( $tab['title'] ); ?>
				</a>
			<?php } ?>
		<?php } ?>
	</h2>
	<?php settings_errors(); ?>

	<form method="POST" action="<?php echo htmlspecialchars( admin_url( basename( $_SERVER['PHP_SELF'] ) ) ) . '?page=' . $page . '&tab=' . $active_tab; ?>">
		<?php
		foreach ( $wp_settings_sections[ $page ] as $section ) {
			$active_id = $section['id'];
			if ( $active_tab ) {
				$section['id'] === $active_tab && call_user_func( $section['callback'], $section );
			} else {
				call_user_func( $section['callback'], $section );
			}
		}
		wp_nonce_field( 'wpstarter_plugin_form_nonce', 'admin_form_submission' );
		submit_button();
		?>
	</form>
</div>
