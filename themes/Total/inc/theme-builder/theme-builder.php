<?php
namespace TotalTheme;
use TotalTheme\Theme_Builder\Render_Template as Render_Template;
use TotalTheme\Theme_Builder\Location_Template as Location_Template;

defined( 'ABSPATH' ) || exit;

/**
 * Theme Builder.
 *
 * @package TotalTheme
 * @subpackage ThemeBuilder
 * @version 5.1
 */
final class Theme_Builder {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Main Theme_Builder Instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Theme_Builder ) ) {
			self::$instance = new Theme_Builder;
		}

		return self::$instance;
	}

	/**
	 * Do location.
	 */
	public function do_location( $location ) {

		// Check for elementor templates first.
		if ( function_exists( 'elementor_theme_do_location' ) ) {
			$elementor_doc = elementor_theme_do_location( $location );
			if ( $elementor_doc ) {
				return true;
			}
		}

		// Check for theme templates.
		$location_template = new Location_Template( $location );

		if ( ! empty( $location_template->template ) ) {

			$render_template = new Render_Template( $location_template->template, $location );

			return $render_template->render();

		}

	}

}