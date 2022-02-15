<?php
defined( 'ABSPATH' ) || exit;

?>

<?php if ( ! empty( $demo_data['screenshot'] ) ) { ?>
	<div class="wpex-demo-import-selected__screenshot">
		<img src="<?php echo esc_url( $demo_data['screenshot'] ); ?>">
	</div>
<?php } ?>

<div class="wpex-demo-import-selected wpex-selected-notice">

	<div class="wpex-demo-import-selected__heading"><?php esc_html_e( 'Demo Selected:', 'total-theme-core' ); ?> <span><?php echo esc_html( $demo_data['name'] ); ?></span></div>

	<div class="wpex-demo-import-selected__warning">
		<?php
		$plugin_link;
		if ( is_plugin_active( 'wordpress-database-reset/wp-reset.php' ) ) {
			$plugin_link = admin_url( 'tools.php?page=database-reset' );
		} else {
			$plugin_link = 'https://www.wpexplorer.com/reset-wordpress-website/';
		}
		echo wp_kses_post( sprintf( __( '<strong style="color:red;">Important:</strong> For your site to look exactly like this demo you should install the sample data on a clean (blank) installation of WordPress to prevent conflicts with any current content. You can use this plugin to reset your site if needed: <a href="%s" target="_blank">Wordpress Database Reset</a>. Otherwise, select only the options you require on the next screen.', 'total-theme-core' ), $plugin_link ) ); ?>
	</div>

	<?php

	// Get the data of all the plugins that might be required by the theme
	$plugins_data = $this->plugin_installer->get_plugins_data();

	// Contains the HTML output for the plugins that need to be installed or activated
	$plugins_output = '';

	// If the current demo requires some plugins
	if ( isset( $demo_data['plugins'] ) ) {

		// Iterate through the list of plugin data and display those plugins that are required
		foreach ( $plugins_data as $plugin_data ) {
			if ( in_array( $plugin_data['name'], $demo_data['plugins'] ) ) {
				$plugin_slug = $plugin_data['slug'];
				$user_action_url = '';
				$user_action_link = '';

				// If the plugin is not installed/activated provide the possibility to install/activate it
				if ( $this->plugin_installer->is_plugin_installed( $plugin_slug ) === false ) {

					$user_action_url = admin_url( 'update.php' ) . '?action=install-plugin&plugin=' . $plugin_slug . '&_wpnonce=' . wp_create_nonce( 'install-plugin_' . $plugin_slug );

					$user_action_link = '<a href="' . esc_url( $user_action_url ) . '" class="wpex-demo-import-plugin-install button button-secondary">' . esc_html__( 'Install', 'total' ) . '</a>';

				} else if ( $this->plugin_installer->is_plugin_activated( $plugin_slug ) === false ) {

					$user_action_url = admin_url( 'plugins.php' ) . '?action=activate&plugin=' . $plugin_data['file_path'] . '&_wpnonce=' . wp_create_nonce( 'activate-plugin_' . $plugin_data['file_path'] );

					$user_action_link = '<a href="' . esc_url( $user_action_url ) . '" class="button secondary-button wpex-demo-import-plugin-activate">' . esc_html__( 'Activate', 'total' ) . '</a>';

				}

				if ( $user_action_link !== '' ) {
					$plugins_output .= '<tr class="wpex-demo-import-required-plugins__item"><td>' . esc_html( $plugin_data['name'] ) . '</td><td class="wpex-plugin-action-result">' . $user_action_link . '</td></tr>';
				}

			}
		}

		if ( ! empty( trim( $plugins_output ) ) ) {
			echo '<div class="wpex-demo-import-required-plugins"><div class="wpex-demo-import-required-plugins__heading">' . esc_html__( 'Required Plugins', 'total-theme-core' ) . '</div><div class="wpex-demo-import-required-plugins__notice">' . esc_html__( 'This demo requires the plugins listed below. Please click on each plugin to install/activate it automatically.', 'total-theme-core' ) . '</div><table class="wpex-demo-import-required-plugins__list"><tbody>' . $plugins_output . '</tbody></table></div>';
		}
	}
	?>

	<div class="wpex-demo-import-buttons">

		<?php $disabled_class = $plugins_output !== '' ? ' disabled' : ''; ?>

		<a href="#" class="button-primary wpex-popup-selected-next<?php echo esc_attr( $disabled_class ); ?>"><?php esc_html_e( 'Next', 'total-theme-core' ); ?></a>

	</div>

</div>

<form method="post" class="wpex-demo-import-form">

	<input id="wpex_import_demo" type="hidden" name="wpex_import_demo" value="<?php echo esc_attr( $demo ); ?>">

	<div class="wpex-demo-import-form__types">

		<div class="wpex-demo-import-selected__heading"><?php esc_html_e( 'Please select what content you want to import:', 'total-theme-core' ); ?></div>

		<ul>
			<li>
				<label for="wpex_import_xml">
					<input id="wpex_import_xml" type="checkbox" name="wpex_import_xml" checked="checked">
					<strong><?php esc_html_e( 'Import XML Data', 'total-theme-core' ); ?></strong> (<?php esc_html_e( 'pages, posts, meta data, terms, menus, etc', 'total-theme-core' ); ?>)
				</label>
			</li>

			<li>
				<label for="wpex_import_xml_attachments">
					<input id="wpex_import_xml_attachments" type="checkbox" name="wpex_import_xml_attachments" checked="checked">
					<strong><?php esc_html_e( 'Import Images', 'total-theme-core' ); ?></strong>
				</label>
			</li>

			<li>
				<label for="wpex_import_mods">
					<input id="wpex_import_mods" type="checkbox" name="wpex_import_mods" checked="checked">
					<strong><?php esc_html_e( 'Import Customizer Settings', 'total-theme-core' ); ?></strong> (<?php esc_html_e( 'Will reset your current settings', 'total-theme-core' ); ?>)
				</label>
			</li>

			<li>
				<label for="wpex_import_widgets">
					<input id="wpex_import_widgets" type="checkbox" name="wpex_import_widgets" checked="checked">
					<strong><?php esc_html_e( 'Import Widgets', 'total-theme-core' ); ?></strong> (<?php esc_html_e( 'Imports new widgets, will not reset current widgets', 'total-theme-core' ); ?>)
				</label>
			</li>

			<?php
			// Sliders
			if ( in_array( 'Slider Revolution', $demo_data['plugins'] ) || in_array( 'Revolution Slider', $demo_data['plugins'] ) ) :

				// Make sure zips can be uploaded
				$mimes              = get_allowed_mime_types();
				$allows_zip_uploads = ( is_array( $mimes ) && array_key_exists( 'zip', $mimes ) ) ?  true : false; ?>

				<li>
					<label for="wpex_import_sliders">
						<input id="wpex_import_sliders" type="checkbox" name="wpex_import_sliders" <?php checked( $allows_zip_uploads, true ); ?> <?php if ( ! $allows_zip_uploads ) echo ' disabled="disabled"'; ?>>
						<strong><?php esc_html_e( 'Import Sliders', 'total-theme-core' ); ?></strong><?php if ( ! $allows_zip_uploads ) { echo ' - <span class="wpex-warning">' . esc_html__( 'You must first enable zip uploads for your WordPress install', 'total-theme-core' ) . '</span>'; } ?>
					</label>
				</li>

			<?php endif; ?>

		</ul>

	</div>

	<div class="wpex-demo-import-buttons">
		<?php wp_nonce_field( 'wpex_import_demo_nonce', 'wpex_import_demo_nonce' ); ?>
		<input type="submit" name="submit" class="button button-primary wpex-submit-form" value="<?php esc_html_e( 'Confirm Import', 'total-theme-core' ); ?>">
	</div>

</form>

<div class="wpex-demo-import-loading">
	<div class="wpex-demo-import-selected__heading"><?php esc_html_e( 'The import process could take some time, so please be patient.', 'total-theme-core' ); ?></div>
	<div class="wpex-demo-import-status"></div>
	<div class="wpex-demo-import-buttons wpex-hidden">
		<?php /*<a href="#" class="button button-primary wpex-popup-selected-close"><?php esc_html_e( 'Close', 'total-theme-core' ); ?></a> */ ?>
	</div>
</div>

<div class="wpex-import-complete">
	<div class="wpex-import-complete__message"><?php esc_html_e( 'Import completed', 'total-theme-core' ); ?> <span class="dashicons dashicons-yes-alt" aria-hidden="true"></span></div>
	<div class="wpex-demo-import-buttons">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" class="button-primary"><?php esc_html_e( 'View Site', 'total-theme-core' ); ?></a>
	</div>
</div>