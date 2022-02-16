<?php
namespace TotalTheme\Customizer\Controls;
use \WP_Customize_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Customizer Card Style Select
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3.1
 */
class Card_Select extends WP_Customize_Control {

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'wpex-card-select';

	/**
	 * Render the content
	 *
	 * @access public
	 */
	public function render_content() {

		$value = $this->value();

		$input_id = '_customize-input-' . $this->id;

		?>

		<?php if ( ! empty( $this->label ) ) : ?>
			<label for="<?php echo esc_attr( $input_id ); ?>" class="customize-control-title"><?php echo esc_html( $this->label ); ?></label>
		<?php endif; ?>

		<span id="<?php echo esc_attr( '_customize-description-' . $this->id ); ?>" class="description customize-control-description"><?php echo esc_html__( 'Select a card style to override the default entry design using a preset theme card.', 'total' ) . ' ' . sprintf( esc_html__( '%sPreview card styles%s', 'total-theme-core' ), '<a href="https://total.wpexplorer.com/features/cards/" target="_blank" rel="noopener noreferrer">', '</a>' ); ?></span>

		<div class="wpex-customizer-chosen-select"><?php

			$select = wpex_card_select( array(
				'id'       => $input_id,
				'name'     => $input_id,
				'selected' => $this->value(),
				'echo'     => 0,
			) );

			// Hackily add in the data link parameter.
			echo str_replace( '<select', '<select ' . $this->get_link(), $select );

		?></div>

	<?php }
}