<?php
namespace TotalTheme\Integration;

defined( 'ABSPATH' ) || exit;

/**
 * Yoast SEO Plugin Integration.
 *
 * @package TotalTheme
 * @subpackage Integration
 * @version 5.3
 */
final class Yoast_SEO {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Yoast_SEO.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function __construct() {

		// Add theme support.
		add_action( 'after_setup_theme', __CLASS__ . '::add_theme_support' );

		// Add support for Yoast SEO breadcrumb settings in the WP Customizer.
		add_action( 'customize_register' , __CLASS__ . '::customizer_settings' );

		// Customize breadcrumbs.
		if ( true === wp_validate_boolean( get_theme_mod( 'enable_yoast_breadcrumbs', true ) ) ) {

			// Filter the ancestors of the yoast seo breadcrumbs.
			if ( apply_filters( 'wpex_filter_wpseo_breadcrumb_links', true ) ) {
				add_filter( 'wpseo_breadcrumb_links', __CLASS__ . '::wpseo_breadcrumb_links' );
			}

			// Trim the title.
			add_filter( 'wpseo_breadcrumb_single_link_info', __CLASS__ . '::trim_title', 10, 3 );

		} // End customize breadcrumbs.

		// Make sure there is a description.
		if ( apply_filters( 'wpex_filter_wpseo_metadesc', true ) ) {
			add_filter( 'wpseo_metadesc', __CLASS__ . '::metadesc' );
		}

	}

	/**
	 * Register Yoast SEO theme support.
	 */
	public static function add_theme_support() {
		add_theme_support( 'yoast-seo-breadcrumbs' );
	}

	/**
	 * Customizer Settings.
	 *
	 * @version 4.9.5
	 */
	public static function customizer_settings( $wp_customize ) {

		$wp_customize->add_setting( 'enable_yoast_breadcrumbs' , array(
			'default'           => true,
			'transport'         => 'refresh',
			'sanitize_callback' => 'wp_validate_boolean',
		) );

		$wp_customize->add_control( 'enable_yoast_breadcrumbs', array(
			'label'    => __( 'Override Theme Breadcrumbs', 'total' ),
			'section'  => 'wpseo_breadcrumbs_customizer_section',
			'settings' => 'enable_yoast_breadcrumbs',
			'type'     => 'checkbox',
			'priority' => -1,
		) );

	}

	/**
	 * Filter the ancestors of the yoast seo breadcrumbs
	 * Adds the portfolio, staff, testimonials and blog links
	 *
	 * @version 3.3.0
	 */
	public static function wpseo_breadcrumb_links( $links ) {

		if ( ! class_exists( '\WPSEO_Options' ) ) {
			return $links;
		}

		$new_breadcrumb = array();

		// Loop through theme post types to add parent item.
		if ( is_singular( array( 'portfolio', 'staff', 'testimonials', 'post' ) ) ) {
			$type = get_post_type();
			if ( 'post' === $type ) {
				$type = 'blog';
			}
			$page_id = wpex_parse_obj_id( get_theme_mod( $type . '_page' ), 'page' );
			if ( $page_id ) {
				$page_title     = get_the_title( $page_id );
				$page_permalink = get_permalink( $page_id );
				if ( $page_permalink && $page_title ) {
					$new_breadcrumb[] = array(
						'url'  => $page_permalink,
						'text' => $page_title,
					);
				}
			}
		}

		// Combine new crumb.
		if ( $new_breadcrumb ) {
			if ( '' !== \WPSEO_Options::get( 'breadcrumbs-home' ) ) {
				array_splice( $links, 1, -2, $new_breadcrumb );
			} else {
				array_splice( $links, 0, -3, $new_breadcrumb );
			}
		}

		// Return links.
		return $links;

	}

	/**
	 * Trim the Yoast SEO title
	 *
	 * @version 3.3.2
	 */
	public static function trim_title( $link_info, $index, $crumbs ) {
		$trim = absint( get_theme_mod( 'breadcrumbs_title_trim' ) );
		if ( $trim && is_array( $crumbs ) && ( absint( $index ) + 1 == count( $crumbs ) ) ) {
			if ( isset( $link_info['text'] ) ) {
				$link_info['text'] = wp_trim_words( $link_info['text'], $trim );
			}
		}
		return $link_info;
	}

	/**
	 * Auto Generate meta description if empty using Total excerpt function.
	 *
	 * @version 3.3.2
	 */
	public static function metadesc( $metadesc ) {
		if ( ! $metadesc && is_singular() ) {
			$metadesc = wpex_get_excerpt( array(
				'length'    => apply_filters( 'wpex_yoast_metadesc_length', 160 ),
				'trim_type' => 'characters',
				'more'      => null,
			) );
		}
		return trim( wp_strip_all_tags( $metadesc ) );
	}

}