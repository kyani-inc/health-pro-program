<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

/**
 * Adds support for Term colors.
 *
 * @package TotalThemeCore
 * @version 1.3.1
 */
final class Term_Colors {

	/**
	 * Our single Term_Colors instance.
	 */
	private static $instance;

	/**
	 * The meta_id used to store the term color.
	 */
	protected static $meta_id = 'wpex_color';

	/**
	 * Create or retrieve the instance of Term_Colors.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {

		if ( is_admin() ) {
			add_filter( 'wpex_term_meta_options', __CLASS__ . '::add_term_option' );
		}

		add_filter( 'wpex_head_css', __CLASS__ . '::head_css', 40 ); // set high priority.

	}

	/**
	 * Returns an array of supported taxonomies for the color option.
	 */
	public static function supported_taxonomies() {
		$taxonomies = array( 'category' );

		/**
		 * Filters the supported taxonomies for the term color meta option (wpex_color).
		 *
		 * @param array $taxonomies
		 */
		$taxonomies = (array) apply_filters( 'wpex_term_colors_supported_taxonomies', $taxonomies );

		return $taxonomies;
	}

	/**
	 * Adds a new term option for defining the term color.
	 */
	public static function add_term_option( $options ) {
		$options[self::$meta_id] = array(
			'label'          => esc_html__( 'Color', 'total-theme-core' ),
			'type'           => 'color',
			'has_admin_col'  => true,
			'show_on_create' => true,
			'taxonomies'     => self::supported_taxonomies(),
			'args'           => array(
				'type'              => 'color',
				'single'            => true,
				'sanitize_callback' => 'sanitize_hex_color',
			),
		);
		return $options;
	}

	/**
	 * Returns the color for a given term.
	 */
	public static function get_term_color( $term ) {
		$term = get_term( $term );
		if ( $term && ! is_wp_error( $term ) ) {
			return get_term_meta( $term->term_id, self::$meta_id, true );
		}
	}

	/**
	 * Generates CSS for term colors: "has-term-{term_id}-color" and "has-term-{term_id}-background-color".
	 */
	public static function get_terms_colors_css() {

		$taxonomies = self::supported_taxonomies();

		if ( ! is_array( $taxonomies ) || 0 === count( $taxonomies ) ) {
			return;
		}

		$terms_colors = array();

		foreach( $taxonomies as $taxonomy ) {

			$terms = get_terms( array(
				'taxonomy' => $taxonomy,
				'hide_empty' => true,
			) );

			if ( $terms && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					$term_color = self::get_term_color( $term );
					if ( $term_color ) {
						$terms_colors[$term->term_id] = $term_color;
					}
				}
			}

		}

		if ( ! $terms_colors ) {
			return;
		}

		$css = '';

		// Loop through colors to generate the term colors CSS.
		foreach( $terms_colors as $term_id => $term_color ) {
			$css .= '.has-term-' . absint( $term_id ) . '-color{color:' . sanitize_hex_color( $term_color ) . '!important;}';
			$css .= '.has-term-' . absint( $term_id ) . '-background-color{background-color:' . sanitize_hex_color( $term_color ) . '!important;}';
		}

		return $css;

	}

	/**
	 * Adds terms color CSS to the site head tag by hooking into wpex_head_css.
	 */
	public static function head_css( $css ) {
		if ( $term_colors_css = self::get_terms_colors_css() ) {
			$css .= $term_colors_css;
		}
		return $css;
	}

}