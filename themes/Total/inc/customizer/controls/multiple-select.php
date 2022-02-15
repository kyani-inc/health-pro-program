<?php
namespace TotalTheme\Customizer\Controls;
use \WP_Customize_Control;

defined( 'ABSPATH' ) || exit;

/**
 * Customizer Multi-Check Control.
 *
 * @package TotalTheme
 * @subpackage Customizer
 * @version 5.3
 *
 * @todo move script to JS file.
 */
class Multiple_Select extends WP_Customize_Control {

	public $description = '';
	public $subtitle = '';
	private static $firstLoad = true;

	/**
	 * The control type.
	 *
	 * @access public
	 * @var string
	 */
	public $type = 'multiple-select';

	/**
	 * The control template.
	 *
	 * @since 3.6.0
	 */
	public function render_content() {

		// The saved value is an array, convert to a comma separated value
		if ( is_array( $this->value() ) ) {
			$value_csv = implode( ',', $this->value() );
			$values = $this->value();
		} else {
			$value_csv = $this->value();
			$values = explode( ',', $this->value() );
		}

		if ( self::$firstLoad ) {
			self::$firstLoad = false;

		?>
			<script>
				jQuery(document).ready( function($) {
					"use strict";
					$( 'input.tf-multicheck' ).change( function(event) {
						event.preventDefault();
						var csv = '';
						$( this ).parents( 'li:eq(0)' ).find( 'input[type=checkbox]' ).each( function() {
							if ($( this ).is( ':checked' )) {
								csv += $( this ).attr( 'value' ) + ',';
							}
						} );
						csv = csv.replace(/,+$/, "");
						$( this ).parents( 'li:eq(0)' ).find( 'input[type=hidden]' ).val(csv)
						// we need to trigger the field afterwards to enable the save button
						.trigger( 'change' );
						return true;
					} );
				} );
			</script>

			<?php
		}

		?>

		<label class='tf-multicheck-container'>

			<?php if ( ! empty( $this->label ) ) : ?>
				<label><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span></label>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php
					echo wp_strip_all_tags( $this->description );
				?></span>
			<?php endif; ?>


			<?php if ( ! empty( $this->subtitle ) ) : ?>
				<div class="customizer-subtitle"><?php echo esc_html( $this->subtitle ); ?></div>
			<?php endif; ?>

			<?php foreach ( $this->choices as $value => $label ) {
				printf( '<label for="%s"><input class="tf-multicheck" id="%s" type="checkbox" value="%s" %s> %s</label><br>',
					$this->id . $value,
					$this->id . $value,
					esc_attr( $value ),
					checked( in_array( $value, $values ), true, false ),
					$label
				);
			} ?>
			<input type="hidden" value="<?php echo esc_attr( $value_csv ); ?>" <?php $this->link(); ?>>
		</label>

		<?php
	}
}