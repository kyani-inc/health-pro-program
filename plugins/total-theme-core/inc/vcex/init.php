<?php
namespace TotalThemeCore\Vcex;

defined( 'ABSPATH' ) || exit;

/**
 * VCEX Shortcodes.
 *
 * The original Visual Composer Extension Plugin by WPExplorer rebuilt for Total.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Init {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Init.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->global_classes();
			static::$instance->include_functions();
			static::$instance->register_shortcodes();
		//	static::$instance->gutenberg_support(); // @WIP - move to it's own Gutenberg folder.
		}

		return static::$instance;
	}

	/**
	 * Run global classes.
	 */
	public function global_classes() {
		Scripts::instance();
	}

	/**
	 * Include helper functions.
	 */
	public function include_functions() {
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/deprecated.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/shortcodes-list.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/core.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/field-description.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/shortcode-atts.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/arrays.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/sanitize.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/grid-filter.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/loadmore.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/entry-classes.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/font-icons.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/onclick.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/scripts.php';
		require_once TTC_PLUGIN_DIR_PATH . 'inc/vcex/functions/parsers.php';
	}

	/**
	 * Register shortcodes.
	 */
	public function register_shortcodes() {
		$modules = vcex_shortcodes_list();
		$path = TTC_PLUGIN_DIR_PATH . 'inc/vcex/shortcodes/';

		if ( ! empty( $modules ) ) {

			foreach ( $modules as $key => $val ) {

				$file = '';

				if ( is_array( $val ) ) {

					$condition = $val['condition'] ?? true;

					if ( $condition ) {

						if ( isset( $val['file'] ) ) {
							$file = $val['file'];
						} else {
							$file = $path . wp_strip_all_tags( $key ) . '.php';
						}

					}

				} else {

					$file = $path . wp_strip_all_tags( $val ) . '.php';

				}

				if ( $file && file_exists( $file ) ) {
					require_once $file;
				}

			}

		}
	}

	/**
	 * Gutenberg support for vcex elements.
	 */
	public function gutenberg_support() {

		// Add new Gutenberg category.
		add_filter( 'block_categories', array( $this, 'add_block_category' ) );

		// Register blocks.
		Blocks\Alert\Block::instance();

	}

	/**
	 * Gutenberg support for vcex elements.
	 */
	public function add_block_category( $categories ) {
		$category_slugs = wp_list_pluck( $categories, 'slug' );

		if ( ! in_array( 'total', $category_slugs, true ) ) {
		    $categories = array_merge(
		        $categories,
		        array(
		            array(
		                'title' => esc_html__( 'Total', 'total-theme-core' ),
		                'slug'  => 'total',
		                'icon'  => '<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><g clip-rule="evenodd" fill="currentColor" fill-rule="evenodd"><path d="m68.3 21.5 33.7-19.5 42.5 24.5 42.4 24.5v39z"/><path d="m17.2 120.7v-20.7-49l60.3 34.9z"/><path d="m186.9 149-42.4 24.5-42.5 24.5-42.4-24.5-15.8-9.2 84.8-49z"/></g></svg>',
		            ),
		        )
		    );
		}

		return $categories;
	}

}