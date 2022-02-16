<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Inline_CSS {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Inline_CSS.
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
		add_filter( 'wpex_head_css', __CLASS__ . '::add_css' );
	}

	/**
	 * Hook into wpex_head_css to add inline CSS for WPBakery.
	 */
	public static function add_css( $css ) {
		$wpbakery_css = self::generate_css();
		if ( $wpbakery_css ) {
			$css .= $wpbakery_css;
		}
		return $css;
	}

	/**
	 * Get template ID's that we need to load CSS for.
	 */
	public static function get_template_ids() {

		// Define ID's as array.
		$ids = array();

		// Get current post ID.
		$post_id = wpex_get_current_post_id();

		// Toggle Bar.
		if ( wpex_has_togglebar() && $togglebar_id = wpex_togglebar_content_id() ) {
			$ids[] = $togglebar_id;
		}

		// Topbar.
		if ( wpex_has_topbar() ) {

			// Top Bar Content.
			if ( $topbar_content = wpex_topbar_content( $post_id ) ) {
				if ( $topbar_content && strpos( $topbar_content, 'vc_row' ) ) {
					$ids[] = wpex_get_translated_theme_mod( 'top_bar_content' );
				}
			}

			// Top Bar Social Alt.
			if ( $topbar_social_alt = wpex_topbar_social_alt_content( $post_id ) ) {
				if ( $topbar_social_alt && strpos( $topbar_social_alt, 'vc_row' ) ) {
					$ids[] = wpex_get_translated_theme_mod( 'top_bar_social_alt' );
				}
			}
		}

		// Header Aside.
		if ( wpex_header_supports_aside() ) {
			$header_aside_content = wpex_header_aside_content( $post_id );
			if ( $header_aside_content && strpos( $header_aside_content, 'vc_row' ) ) {
				$ids[] = wpex_get_translated_theme_mod( 'header_aside' );
			}
		}

		// Callout.
		if ( wpex_has_callout() && $callout_content = wpex_callout_content( $post_id ) ) {
			if ( $callout_content && strpos( $callout_content, 'vc_row' ) ) {
				$ids[] = wpex_get_translated_theme_mod( 'callout_text' );
			}
		}

		// Singular template.
		if ( is_singular() ) {
			$dynamic_template = wpex_get_singular_template_id();
			if ( $dynamic_template ) {
				$ids[] = $dynamic_template;
			}
		}

		// Overlay Header template.
		$overlay_header_template = apply_filters( 'wpex_overlay_header_template', get_theme_mod( 'overlay_header_template' ) );
		if ( $overlay_header_template && wpex_has_overlay_header() ) {
			$ids[] = $overlay_header_template;
		}

		// WooCommerce.
		if ( wpex_is_woo_shop() ) {
			$ids[] = wpex_parse_obj_id( wc_get_page_id( 'shop' ) );
		}

		// Apply filters to ID's.
		$ids = (array) apply_filters( 'wpex_vc_css_ids', $ids );

		// Sanitize ID's.
		if ( $ids ) {
			return array_unique( array_filter( $ids ) );
		}

	}

	/**
	 * Generate CSS.
	 */
	public static function generate_css() {

		$css = '';

		$template_ids = self::get_template_ids();

		// Generate CSS.
		if ( $template_ids ) {

			// Loop through id's.
			foreach ( $template_ids as $id ) {

				$id = intval( $id );

				// If not valid ID continue - @todo should we also check using get_post_status?
				if ( ! $id ) {
					continue;
				}

				// Conditional checks, some CSS isn't necessarily needed globally.
				if ( function_exists( 'is_shop' ) && is_shop() ) {
					$condition = true; // Always return true for the shop
				} elseif ( is_404() && $id == wpex_get_current_post_id() ) {
					$condition = true;
				} else {
					$condition = ( $id == wpex_get_current_post_id() ) ? false : true;
				}

				// Add CSS.
				if ( $condition && $vc_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true ) ) {
					$css .= '/*VC META CSS*/' . $vc_css;
				}

			}

		}

		return $css;
	}

}