<?php
defined( 'ABSPATH' ) || exit;

?>

<div class="wrap wpex-demo-import-error-page">

	<h1><?php esc_html_e( 'Demo Importer', 'total-theme-core' ); ?></h1>

	<?php
	// Get errors
	$errors = $this->init_checks;

	if ( ! empty( $errors ) && is_array( $errors ) ) {

		// Loop through errors
		foreach ( $errors as $error ) {

			echo '<div class="notice notice-error"><p>' . esc_html( $error ) . '</p></div>';

		}

	} ?>

</div>