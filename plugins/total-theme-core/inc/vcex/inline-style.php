<?php
namespace TotalThemeCore\Vcex;

defined( 'ABSPATH' ) || exit;

/**
 * Parses inline styles.
 *
 * @package Total WordPress Theme
 * @subpackage Framework
 * @version 1.3.1
 */
final class Inline_Style {
	private $style;
	private $add_style;

	/**
	 * Class Constructor.
	 */
	public function __construct( $atts, $add_style ) {

		$this->style = array();
		$this->add_style = $add_style;

		// Loop through shortcode atts and run class methods.
		foreach ( $atts as $key => $value ) {
			if ( ! empty( $value ) ) {
				$method = 'parse_' . $key;
				if ( method_exists( $this, $method ) ) {
					$this->$method( $value );
				}
			}
		}

	}

	/**
	 * Display.
	 */
	private function parse_display( $value ) {
		$this->style[] = 'display:' . esc_attr( $value ) . ';';
	}

	/**
	 * Gap.
	 */
	private function parse_gap( $value ) {
		if ( is_numeric( $value ) ) {
			$value = $value . 'px';
		}
		$this->style[] = 'gap:' . esc_attr( $value ) . ';';
	}

	/**
	 * Flex Basis.
	 */
	private function parse_flex_basis( $value ) {
		$this->style[] = 'flex-basis:' . esc_attr( $value ) . ';';
	}

	/**
	 * Float.
	 */
	private function parse_float( $value ) {
		if ( 'center' === $value ) {
			$this->style[] = 'margin-right:auto;margin-left:auto;float:none;';
		} else {
			$this->style[] = 'float:' . esc_attr( $value ) . ';';
		}
	}

	/**
	 * Width.
	 */
	private function parse_width( $value ) {
		$width = $this->sanitize_width( $value );
		if ( $width ) {
			$this->style[] = 'width:' . esc_attr( $width ) . ';';
		}
	}

	/**
	 * Max-Width.
	 */
	private function parse_max_width( $value ) {
		$width = $this->sanitize_width( $value );
		if ( $width ) {
			$this->style[] = 'max-width:' . esc_attr( $width )  . ';';
		}
	}

	/**
	 * Min-Width.
	 */
	private function parse_min_width( $value ) {
		$width = $this->sanitize_width( $value );
		if ( $width ) {
			$this->style[] = 'min-width:' . esc_attr( $width )  . ';';
		}
	}

	/**
	 * Background.
	 */
	private function parse_background( $value ) {
		$color = $this->sanitize_color( $value );
		if ( $color ) {
			$this->style[] = 'background:' . esc_attr( $color ) . ';';
		}
	}

	/**
	 * Background Image.
	 */
	private function parse_background_image( $value ) {
		$this->style[] = 'background-image:url(' . esc_attr( esc_url( $value ) ) . ');';
	}

	/**
	 * Background Position.
	 */
	private function parse_background_position( $value ) {
		$this->style[] = 'background-position:' . esc_attr( $value ) . ';';
	}

	/**
	 * Background Color.
	 */
	private function parse_background_color( $value ) {
		$color = $this->sanitize_color( $value );
		if ( $color ) {
			$this->style[] = 'background-color:' . esc_attr( $color ) . ';';
		}
	}

	/**
	 * Border.
	 */
	private function parse_border( $value ) {
		$value = 'none' === $value ? '0' : $value;
		$this->style[] = 'border:' . esc_attr( $value ) . ';';
	}

	/**
	 * Border: Color.
	 */
	private function parse_border_color( $value ) {
		$color = $this->sanitize_color( $value );
		if ( $color ) {
			$this->style[] = 'border-color:' . esc_attr( $color ) . ';';
		}
	}

	/**
	 * Border: Bottom Color.
	 */
	private function parse_border_bottom_color( $value ) {
		$color = $this->sanitize_color( $value );
		if ( $color ) {
			$this->style[] = 'border-bottom-color:' . esc_attr( $color ) . ';';
		}
	}

	/**
	 * Border Width.
	 */
	private function parse_border_width( $value ) {
		$value_escaped = $this->sanitize_border_width( $value );
		if ( $value_escaped ) {
			$this->style[] = 'border-width:' . $value_escaped . ';';
		}
	}

	/**
	 * Border Style.
	 */
	private function parse_border_style( $value ) {
		$this->style[] = 'border-style:' . esc_attr( $value ) . ';';
	}

	/**
	 * Border: Top Width.
	 */
	private function parse_border_top_width( $value ) {
		$value_escaped = $this->sanitize_border_width( $value );
		if ( $value_escaped ) {
			$this->style[] = 'border-top-width:' . $value_escaped . ';';
		}
	}

	/**
	 * Border: Bottom Width.
	 */
	private function parse_border_bottom_width( $value ) {
		$value_escaped = $this->sanitize_border_width( $value );
		if ( $value_escaped ) {
			$this->style[] = 'border-bottom-width:' . $value_escaped . ';';
		}
	}

	/**
	 * Margin.
	 */
	private function parse_margin( $value ) {

		if ( $this->parse_trbl_property( $value, 'margin' ) ) {
			return;
		}

		$this->style[]  = 'margin:' . $this->sanitize_margin( $value ) . ';';

	}

	/**
	 * Margin: Right.
	 */
	private function parse_margin_right( $value ) {
		$this->style[] = 'margin-right:' . $this->sanitize_margin( $value ) . ';';
	}

	/**
	 * Margin: Left.
	 */
	private function parse_margin_left( $value ) {
		$this->style[] = 'margin-left:' . $this->sanitize_margin( $value ) . ';';
	}

	/**
	 * Margin: Top.
	 */
	private function parse_margin_top( $value ) {
		$this->style[] = 'margin-top:' . $this->sanitize_margin( $value ) . ';';
	}

	/**
	 * Margin: Bottom.
	 */
	private function parse_margin_bottom( $value ) {
		$this->style[] = 'margin-bottom:' . $this->sanitize_margin( $value ) . ';';
	}

	/**
	 * Padding.
	 */
	private function parse_padding( $value ) {

		if ( $this->parse_trbl_property( $value, 'padding' ) ) {
			return;
		}

		$this->style[] = 'padding:' . $this->sanitize_padding( $value ) . ';';

	}

	/**
	 * Padding: Top.
	 */
	private function parse_padding_top( $value ) {
		$this->style[] = 'padding-top:' . $this->sanitize_padding( $value ) . ';';
	}

	/**
	 * Padding: Bottom.
	 */
	private function parse_padding_bottom( $value ) {
		$this->style[] = 'padding-bottom:' . $this->sanitize_padding( $value ) . ';';
	}

	/**
	 * Padding: Left.
	 */
	private function parse_padding_left( $value ) {
		$this->style[] = 'padding-left:' . $this->sanitize_padding( $value ) . ';';
	}

	/**
	 * Padding: Right.
	 */
	private function parse_padding_right( $value ) {
		$this->style[] = 'padding-right:' . $this->sanitize_padding( $value ) . ';';
	}

	/**
	 * Font-Size.
	 */
	private function parse_font_size( $value ) {
		if ( $value && strpos( $value, '|' ) === false ) {
			$font_size = $this->sanitize_font_size( $value );
			if ( $font_size ) {
				$this->style[] = 'font-size:' . esc_attr( $font_size )  . ';';
			}
		}
	}

	/**
	 * Font Weight.
	 */
	private function parse_font_weight( $value ) {
		switch ( $value ) {
			case 'normal':
				$value = '400';
				break;
			case 'medium' :
				$value = '500';
				break;
			case 'semibold':
				$value = '600';
				break;
			case 'bold':
				$value = '700';
				break;
			case 'bolder':
				$value = '900';
				break;
		}
		$this->style[] = 'font-weight:' . esc_attr( $value )  . ';';
	}

	/**
	 * Font Family (exclusive to Total theme)
	 */
	private function parse_font_family( $value ) {

		// Make sure font is loaded before parsing - important!!
		vcex_enqueue_font( $value );

		// Sanitize font family
		if ( function_exists( 'wpex_sanitize_font_family' ) ) {
			$value = wpex_sanitize_font_family( $value );
			if ( ! empty( $value ) ) {
				$value = str_replace( '"', "'", $value );
				$this->style[] = 'font-family:' . esc_attr( $value ) . ';';
			}
		}

	}

	/**
	 * Color.
	 */
	private function parse_color( $value ) {
		$color = $this->sanitize_color( $value );
		if ( $color ) {
			$this->style[] = 'color:' . esc_attr( $color )  . ';';
		}
	}

	/**
	 * Opacity.
	 */
	private function parse_opacity( $value ) {
		$value = str_replace( '%', '', $value ); // % is the only non numeric character allowed.
		if ( ! is_numeric( $value ) ) {
			return;
		}
		if ( $value > 1 ) {
			$value = $value / 100;
		}
		if ( $value <= 1 ) {
			$this->style[] = 'opacity:' . esc_attr( $value ) . ';';
		}
	}

	/**
	 * Text Align.
	 *
	 * @todo use new wpex-text-{position} classes with RTL support.
	 */
	private function parse_text_align( $value ) {
		switch ( $value ) {
			case 'textcenter':
				$value = 'center';
				break;
			case 'textleft':
				$value = 'left';
				break;
			case 'textright':
				$value = 'right';
				break;
		}
		if ( $value ) {
			$this->style[] = 'text-align:' . esc_attr( $value ) . ';';
		}
	}

	/**
	 * Text Transform.
	 */
	private function parse_text_transform( $value ) {

		$allowed_values = array(
			'none',
			'capitalize',
			'uppercase',
			'lowercase',
			'initial',
			'inherit'
		);

		if ( ! in_array( $value, $allowed_values ) ) {
			return;
		}

		$this->style[] = 'text-transform:' .  esc_attr( $value ) . ';';

	}

	/**
	 * Letter Spacing.
	 */
	private function parse_letter_spacing( $value ) {

		if ( '0px' === $value || '0em' === $value || '0rem' === $value ) {
			return $value;
		}

		$unit = $this->get_unit( $value );

		$allowed_units = array( 'px', 'em', 'rem', 'vmin', 'vmax', 'vh', 'vw' );

		if ( ! in_array( $unit, $allowed_units ) ) {
			$value = floatval( $value ) . 'px';
		}

		$this->style[] = 'letter-spacing:' . esc_attr( $value ) . ';';

	}

	/**
	 * Line-Height.
	 */
	private function parse_line_height( $value ) {
		$this->style[] = 'line-height:' . esc_attr( $value ) . ';';
	}

	/**
	 * Line-Height with px sanitize.
	 */
	private function parse_line_height_px( $value ) {
		$line_height = $this->sanitize_px( $value );
		if ( $line_height ) {
			$this->style[] = 'line-height:' . esc_attr( $line_height )  . ';';
		}
	}

	/**
	 * Height.
	 */
	private function parse_height( $value ) {
		$height = $this->sanitize_height( $value );
		if ( $height ) {
			$this->style[] = 'height:' . esc_attr( $height ) . ';';
		}
	}

	/**
	 * Height with px sanitize.
	 */
	private function parse_height_px( $value ) {
		$this->style[] = 'height:' . $this->sanitize_px( $value )  . ';';
	}

	/**
	 * Min-Height.
	 */
	private function parse_min_height( $value ) {
		$this->style[] = 'min-height:' . $this->sanitize_px_pct( $value ) . ';';
	}

	/**
	 * Border Radius.
	 */
	private function parse_border_radius( $value ) {
		$border_radius = $this->sanitize_border_radius( $value );
		if ( $border_radius !== NULL ) {
			$this->style[] = 'border-radius:' . esc_attr( $border_radius )  . ';';
		}
	}

	/**
	 * Position: Top.
	 */
	private function parse_top( $value ) {
		$this->style[] = 'top:' . $this->sanitize_px_pct( $value ) . ';';
	}

	/**
	 * Position: Bottom.
	 */
	private function parse_bottom( $value ) {
		$this->style[] = 'bottom:' . $this->sanitize_px_pct( $value ) . ';';
	}

	/**
	 * Position: Right.
	 */
	private function parse_right( $value ) {
		$this->style[] = 'right:' . $this->sanitize_px_pct( $value ) . ';';
	}

	/**
	 * Position: Left.
	 */
	private function parse_left( $value ) {
		$this->style[] = 'left:' . $this->sanitize_px_pct( $value ) . ';';
	}

	/**
	 * Style.
	 */
	private function parse_font_style( $value ) {
		$this->style[] = 'font-style:' . esc_attr( $value )  . ';';
	}

	/**
	 * Text Decoration.
	 */
	private function parse_text_decoration( $value ) {
		$this->style[] = 'text-decoration:' . esc_attr( $value )  . ';';
	}

	/**
	 * Italic.
	 */
	private function parse_italic( $value ) {
		if ( 'true' ===  $value || 'yes' === $value || true === $value ) {
			$this->style[] = 'font-style:italic;';
		}
	}

	/**
	 * Animation duration.
	 */
	private function parse_animation_duration( $value ) {
		$this->style[] = 'animation-duration:' . esc_attr( floatval( $value ) ) . 's;';
	}

	/**
	 * Animation delay.
	 */
	private function parse_animation_delay( $value ) {
		$this->style[] = 'animation-delay:' . esc_attr( floatval( $value ) ) . 's;';
	}

	/**
	 * Transition Speed.
	 */
	private function parse_transition_speed( $value ) {
		$this->style[] = 'transition-duration:' . esc_attr( floatval( $value ) ) . 's;';
	}

	/**
	 * Parse top/right/bottom/left fields.
	 */
	private function parse_trbl_property( $value, $property ) {

		if ( ! function_exists( 'vcex_parse_multi_attribute' ) ) {
			return;
		}

		if ( false !== strpos( $value, ':' ) && $values = vcex_parse_multi_attribute( $value ) ) {

			// All values are the same
			if ( isset( $values['top'] )
				&& count( $values ) == 4
				&& count( array_unique( $values ) ) <= 1
			) {
				$value = $values['top'];
				$value = ( 'none' === $value ) ? '0' : $value;
				$value = is_numeric( $value ) ? $value  . 'px' : $value;
				$this->style[] = esc_attr( trim( $property ) ) . ':' . esc_attr( $value ) . ';';
				return true;
			}

			// Values are different.
			foreach ( $values as $k => $v ) {

				if ( 0 == $v ) {
					$v = '0px'; // 0px fix
				}

				if ( ! empty( $v ) ) {

					$method = 'parse_' . $property . '_' . $k;
					if ( method_exists( $this, $method ) ) {
						$this->$method( $v );
					}

				}

			}

			return true;

		}

	}

	/**
	 * Sanitize border_radius input.
	 */
	private function sanitize_border_radius( $input ) {

		if ( 'none' === $input || '0px' === $input || 0 === $input || '0' === $input ) {
			return '0';
		}

		if ( 'full' === $input ) {
			return '9999px';
		}

		if ( is_numeric( $input ) ) {
			return $input . 'px';
		}

		if ( is_string( $input ) && false !== strpos( trim( $input ), ' ' ) ) {
			return esc_attr( $input ); // shorthand format.
		}

		$unit = $this->get_unit( $input );

		$allowed_units = array( 'px', 'rem', '%' );

		if ( in_array( $unit, $allowed_units ) ) {
			return esc_attr( $input );
		}

		$input = floatval( $input );

		if ( $input ) {
			return $input . 'px';
		}

	}

	/**
	 * Sanitize height input.
	 */
	private function sanitize_height( $input ) {

		if ( is_numeric( $input ) ) {
			return $input . 'px';
		}

		$unit = $this->get_unit( $input );

		$allowed_units = array( 'px', 'em', 'rem', 'vh', 'vmin', 'vmax', '%' );

		if ( in_array( $unit, $allowed_units ) ) {
			return esc_attr( $input );
		}

		$input = floatval( $input );

		if ( $input ) {
			return $input . 'px';
		}

	}

	/**
	 * Sanitize border_width input.
	 */
	private function sanitize_border_width( $input ) {

		if ( is_numeric( $input ) ) {
			return $input . 'px';
		}

		if ( 'thin' === $input || 'medium' === $input || 'thick' === $input ) {
			return $input;
		}

		if ( in_array( $input, vcex_border_width_choices( $input ) ) ) {
			return absint( $input ) . 'px';
		}

		$unit = $this->get_unit( $input );

		$allowed_units = array( 'px', 'em', 'rem' );

		if ( in_array( $unit, $allowed_units ) ) {
			return esc_attr( $input );
		}

		$input = floatval( $input );

		if ( $input ) {
			return $input . 'px';
		}

	}

	/**
	 * Sanitize width input.
	 */
	private function sanitize_width( $input ) {

		if ( 'auto' === $input ) {
			return $input;
		}

		if ( is_numeric( $input ) ) {
			return $input . 'px';
		}

		$unit = $this->get_unit( $input );

		$allowed_units = array( 'px', 'em', 'rem', 'vw', 'vmin', 'vmax', '%' );

		if ( in_array( $unit, $allowed_units ) ) {
			return esc_attr( $input );
		}

		$input = floatval( $input );

		if ( $input ) {
			return $input . 'px';
		}

	}

	/**
	 * Parse color input.
	 */
	private function sanitize_color( $input ) {

		switch ( $input ) {

			case 'none':
				$input = 'transparent';
				break;

			default:
				$input = vcex_parse_color( $input );
				break;
		}

		if ( false !== strpos( $input, 'palette-' ) ) {
			$input = null; // deleted color palette item.
		}

		return $input;
	}

	/**
	 * Sanitize padding input.
	 */
	private function sanitize_padding( $input ) {

		if ( 'none' === $input ) {
			return '0';
		}

		if ( is_numeric( $input ) ) {
			return $input  . 'px';
		}

		return esc_attr( $input );

	}

	/**
	 * Sanitize margin input.
	 */
	private function sanitize_margin( $input ) {

		if ( 'none' === $input ) {
			return '0';
		}

		if ( is_numeric( $input ) ) {
			return $input  . 'px';
		}

		return esc_attr( $input );

	}

	/**
	 * Sanitize px-pct input.
	 */
	private function sanitize_px_pct( $input ) {

		if ( 'none' === $input || '0px' === $input ) {
			return '0';
		}

		if ( is_numeric( $input ) ) {
			return $input . 'px';
		}

		$unit = $this->get_unit( $input );

		if ( 'px' === $unit || '%' == $unit ) {
			return esc_attr( $input );
		}

		$input = floatval( $input );

		if ( $input ) {
			return $input . 'px';
		}
	}

	/**
	 * Sanitize font-size input.
	 */
	private function sanitize_font_size( $input ) {

		if ( '0px' === $input || '0em' === $input || '0rem' === $input ) {
			return;
		}

		if ( is_numeric( $input ) ) {
			return absint( $input ) . 'px';
		}

		$unit = $this->get_unit( $input );

		$allowed_units = array( 'px', 'em', 'rem', 'vw', 'vmin', 'vmax', 'vh' );

		if ( in_array( $unit, $allowed_units ) ) {
			$input = esc_attr( $input );
		} else {
			$input = abs( floatval( $input ) ) . 'px'; // always return pixel value - important!
		}

		if ( '0px' !== $input ) {
			return $input;
		}

	}

	/**
	 * Sanitize px input.
	 */
	private function sanitize_px( $input ) {
		return vcex_validate_px( $input );
	}

	/**
	 * Return css unit (aka text) from input.
	 */
	private function get_unit( $input ) {
		if ( $input && ! is_numeric( $input ) ) {
			$non_numeric_string = preg_replace( '/[^0-9.]/', '', $input );
			$unit = str_replace( $non_numeric_string, '', $input );
			return trim( $unit );
		}
	}

	/**
	 * Returns the styles.
	 */
	public function return_style() {
		if ( ! empty( $this->style ) ) {
			$this->style = implode( false, $this->style );
			if ( $this->add_style ) {
				return ' style="' . esc_attr( $this->style )  . '"';
			} else {
				return esc_attr( $this->style );
			}
		} else {
			return null;
		}
	}

}