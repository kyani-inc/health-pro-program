<?php
namespace TotalTheme\Customizer\Controls;
use \WP_Customize_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Customizer HR Control.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3.1
 */
class Hr extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'hr';

	/**
	 * The control template.
	 *
	 * @since 3.6.0
	 */
	public function content_template() { ?>
		<hr>
	<?php }

}