<?php
namespace TotalTheme\Customizer\Controls;
use \WP_Customize_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Customizer Heading Control.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3.1
 */

class Heading extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'wpex-heading';

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 *
	 * @since 3.6.0
	 */
	public function render_content() {}

	/**
	 * The control template.
	 *
	 * @since 3.6.0
	 */
	public function content_template() {

		$admin_color = get_user_option( 'admin_color' );
		$admin_color = $admin_color ? ' wpex-customizer-heading--' . $admin_color : '';
		?>

		<# if ( data.label ) { #>
			<span class="wpex-customizer-heading<?php echo esc_attr( $admin_color ); ?>">{{ data.label }}</span>
		<# } #>

	<?php }

}