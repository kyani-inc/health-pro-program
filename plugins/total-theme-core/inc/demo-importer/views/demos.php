<?php
defined( 'ABSPATH' ) || exit;

?>

<div class="wpex-demo-import-page wrap">

	<h1><?php esc_html_e( 'Demo Importer', 'total-theme-core' ); ?></h1>

	<?php
	$max_execute = ini_get( 'max_execution_time' );
	if ( $max_execute > 0 && $max_execute < 300 ) { ?>
		<div class="notice notice-error">
			<p style="font-size:1.1em;"><?php echo wp_kses_post( sprintf( __( '<strong>Important:</strong> Your server\'s max_execution_time is set to %d but some demos may require more time to import, especially on shared hosting plans. We highly recommend increasing your server\'s max_execution_time value to at least 300. This can be done via your cPanel or by contacting your hosting company.', 'total-theme-core' ), $max_execute ) ); ?></p>
		</div>
	<?php } ?>

	<?php if ( ! empty( $this->categories ) && is_array( $this->categories ) ) : ?>
		<div class="wpex-demo-import-filter">
			<div class="wpex-demo-import-filter__categories">
				<select>
					<?php
					// Loop through categories.
					echo '<option value="all">' . esc_html__( 'Filter by Category', 'total-theme-core' ) . '</option>';

					// Add the 'other' category at the end of the array.
					if ( isset( $this->categories[ 'other' ] ) ) {
						$value = $this->categories[ 'other' ];
						unset( $this->categories[ 'other' ] );
						$this->categories[ 'other' ] = $value;
					}

					// Loop through categories and display them at the top
					foreach ( $this->categories as $category_key => $category_value ) {
						echo '<option value="' . esc_attr( $category_key ) . '">' . esc_html( $category_value ) . '</option>';
					} ?>
				</select>
			</div>
			<input class="wpex-demo-import-filter__search" type="text" placeholder="<?php esc_attr_e( 'Search demos...', 'total-theme-core' ); ?>"></input>
		</div>
	<?php endif; ?>

	<div class="wpex-demo-import-grid theme-browser">

		<?php
		if ( ! empty( $this->demos ) && is_array( $this->demos ) ) {

			foreach ( $this->demos as $demo_key => $demo_data ) {

				$categories = '';

				if ( array_key_exists( 'categories', $demo_data ) && is_array( $demo_data['categories'] ) ) {
					$categories = implode( ',', array_keys( $demo_data['categories'] ) );
				}

				?>

				<div class="wpex-demo-import-grid__item theme" data-demo="<?php echo esc_attr( $demo_data['demo_slug'] ); ?>" data-categories="<?php echo esc_attr( $categories ); ?>">

					<div class="theme-screenshot">
						<img src="<?php echo esc_url( $demo_data['screenshot'] ); ?>" alt="<?php _e( 'Screenshot', 'total-theme-core' ); ?>" loading="lazy">
						<span class="spinner wpex-demo-spinner"></span>
					</div>

					<h3 class="theme-name">
						<span class="wpex-demo-name"><?php echo esc_html( $demo_data['name'] ); ?></span>
						<div class="theme-actions">
							<?php
							// Get preview URL
							if ( ! empty( $demo_data['demo_url'] ) ) {
								$demo_preview = $demo_data['demo_url'];
							} else {
								$demo_preview = ! empty( $demo_data['demo_slug'] ) ? 'https://total.wpexplorer.com/' . $demo_data['demo_slug'] . '/' : '';
							} ?>
							<a href="<?php echo esc_url( $demo_preview ); ?>" class="button button-primary" target="_blank"><?php esc_html_e( 'Live Preview', 'total-theme-core' ); ?></a>
						</div>
					</h3>

				</div>

			<?php } ?>

		<?php } ?>

	</div>

	<div class="wpex-demo-import-popup">
		<a href="#" class="wpex-demo-import-popup__close"><span class="screen-reader-text"><?php echo esc_html__( 'Close selected demo', 'total-theme-core' ); ?></span><span class="dashicons dashicons-no-alt"></span></a>
		<div class="wpex-demo-import-popup__inner">
			<div class="wpex-demo-import-popup__content"></div>
		</div>
	</div>

</div>