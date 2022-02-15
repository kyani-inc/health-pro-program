<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Accent Colors.
 *
 * @package TotalTheme
 * @subpackage Integration\WPBakery
 * @version 5.1.2
 */
class Accent_Colors {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Accent_Colors.
	 *
	 * @return Accent_Colors
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
		add_filter( 'wpex_accent_texts', __CLASS__ . '::accent_texts' );
		add_filter( 'wpex_accent_borders', __CLASS__ . '::accent_borders' );
	}

	/**
	 * Adds text accents.
	 */
	public static function accent_texts( $texts ) {
		return array_merge( array(
			'.vc_toggle_total .vc_toggle_title',
			//'.wpb-js-composer .vc_tta.vc_general.vc_tta-style-total .vc_tta-panel-title>a',
			//'.wpb-js-composer .vc_tta.vc_general.vc_tta-style-total .vc_tta-tab>a',
		), $texts );
	}

	/**
	 * Adds border accents.
	 */
	public static function accent_borders( $borders ) {
		return array_merge( array(
			'.wpb_tabs.tab-style-alternative-two .wpb_tabs_nav li.ui-tabs-active a' => array( 'bottom' ),
		), $borders );
	}

}