<?php
namespace TotalTheme\Customizer\Controls;
use \WP_Customize_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Customizer Background Patterns Control.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3.1
 */
class Bg_Patterns extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'wpex_bg_patterns';

	/**
	 * The control template.
	 *
	 * @since 4.0
	 */
	public function render_content() {

		$this_val = $this->value();

		$input_id = '_customize-input-' . $this->id;
		?>

		<?php if ( ! empty( $this->label ) ) : ?>
			<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php endif; ?>

		<?php if ( ! empty( $this->description ) ) : ?>
			<span id="<?php echo esc_attr( '_customize-description-' . $this->id ); ?>" class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
		<?php endif; ?>

		<select id="<?php echo esc_attr( $input_id ); ?>" <?php $this->link(); ?>>
			<option value="" <?php selected( $this_val, '', true ); ?>><?php esc_html_e( 'None', 'total' ); ?></option>
			<?php if ( $patterns = wpex_get_background_patterns() ) {
				foreach ( $patterns as $key => $val ) { ?>
					<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $this_val, $key, true ); ?>><?php echo esc_html( $val['label'] ); ?></option>
				<?php }
			} ?>
		</select>

	<?php }

}