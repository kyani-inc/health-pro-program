<?php
namespace TotalTheme\Customizer\Controls;
use \WP_Customize_Control;
use \WP_Query;

defined( 'ABSPATH' ) || exit;

/**
 * Customizer Pages Select Control.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3.1
 */
class Dropdown_Pages extends WP_Customize_Control {

	/**
	 * Define control type.
	 */
	public $type = 'wpex-dropdown-pages';

	/**
	 * Whether to include templates in the dropdown or not.
	 */
	public $include_templates = false;

	/**
	 * Render the content.
	 */
	public function render_content() {

		$input_id = '_customize-input-' . $this->id;

		$admin_color = get_user_option( 'admin_color' );
		$admin_color = $admin_color ? ' wpex-customizer-chosen-select--' . $admin_color : '';

		?>

		<?php if ( ! empty( $this->label ) ) : ?>
			<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php endif; ?>

		<?php if ( ! empty( $this->description ) ) : ?>
			<span id="<?php echo esc_attr( '_customize-description-' . $this->id ); ?>" class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>

		<div class="wpex-customizer-chosen-select<?php echo esc_attr( $admin_color ); ?>">

			<?php if ( $this->include_templates && post_type_exists( 'templatera' ) ) { ?>

				<select id="<?php echo esc_attr( $input_id ); ?>" <?php $this->link(); ?>>

					<option value="">&#8211; <?php esc_html_e( 'Select', 'total' ); ?> &#8211;</option>

					<?php
					// Add Templatera templates
					$templatera_templates = new WP_Query( array(
						'posts_per_page' => -1,
						'post_type' => 'templatera',
					) );

					if ( $templatera_templates->have_posts() ) { ?>

						<optgroup label="<?php esc_html_e( 'WPBakery Templates', 'total' ); ?>">

							<?php while ( $templatera_templates->have_posts() ) {

								$templatera_templates->the_post();

								echo '<option value="' . intval( get_the_ID() ) . '"' . selected( $this->value(), get_the_ID(), false ) . '>' . esc_html( get_the_title() ) . '</option>';

							}
							wp_reset_postdata(); ?>

						</optgroup>

					<?php } ?>

					<optgroup label="<?php esc_html_e( 'Pages', 'total' ); ?>">
						<?php
						$pages = get_pages( array(
							'exclude' => get_option( 'page_on_front' ),
						) );
						if ( $pages ) {
							foreach ( $pages as $page ) {
								echo '<option value="' . absint( $page->ID ) . '"' . selected( $this->value(), $page->ID, false ) . '>' . esc_html( $page->post_title ) . '</option>';
							}
						} ?>
					</optgroup>

				</select>

			<?php } else {

				$dropdown = wp_dropdown_pages( array(
					'id'                => $input_id,
					'name'              => $input_id,
					'echo'              => 0,
					'show_option_none'  => '&mdash; ' . esc_html__( 'Select', 'total' ) . ' &mdash;',
					'option_none_value' => '0',
					'selected'          => $this->value(),
				) );

				// Hackily add in the data link parameter.
				echo str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

			} ?>

		</div>

	<?php }
}