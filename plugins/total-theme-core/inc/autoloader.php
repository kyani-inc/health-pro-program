<?php
namespace TotalThemeCore;

defined( 'ABSPATH' ) || exit;

final class Autoloader {

	/**
	 * Register our autoloader.
	 */
	public static function run() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Function registered as an autoloader which loads class files.
	 */
	private static function autoload( $class ) {

		// WPEX Factory Classes.
		if ( 'WPEX_Meta_Factory' === $class ) {
			require_once TTC_PLUGIN_DIR_PATH . 'inc/lib/wpex-meta-factory/class-wpex-meta-factory.php';
			return;
		}

		// Check to make sure the class is part of our namespace.
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		// Get the absolute path to a class file.
		$path = self::get_class_path( $class );

		// Include class file if it's readable.
		if ( $path && file_exists( $path ) ) {
			require $path;
		}

	}

	/**
	 * Get the absolute path to a class file.
	 */
	private static function get_class_path( $class ) {

		// Remove namespace.
		$class = str_replace( __NAMESPACE__ . '\\', '', $class );

		// Lowercase.
		$class = strtolower( $class );

		// Convert underscores to dashes.
		$class = str_replace( '_', '-', $class );

		// Fix classnames with incorrect naming convention.
		$class = self::parse_class_filename( $class );

		// Return early if parsing returns null.
		if ( ! $class ) {
			return;
		}

		// Convert backslash to correct directory separator.
		$class = str_replace( '\\', DIRECTORY_SEPARATOR, $class ) . '.php';

		// Return final class path.
		return trailingslashit( TTC_PLUGIN_DIR_PATH )  . 'inc/' . $class;

	}

	/**
	 * Parses the class filename to fix classnames with incorrect naming convention.
	 */
	private static function parse_class_filename( $class ) {

		if ( 'widgetbuilder' === $class ) {
			$class = 'widget-builder';
		}

		return $class;
	}

}