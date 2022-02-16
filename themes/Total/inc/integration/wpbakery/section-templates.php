<?php
namespace TotalTheme\Integration\WPBakery;

defined( 'ABSPATH' ) || exit;

final class Section_Templates {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Template Type Name.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $template_type = 'wpex_section_templates';

	/**
	 * Create or retrieve the instance of Register_Section_Templates.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
		}
		return static::$instance;
	}

	/**
	 * Add hooks.
	 */
	public function __construct() {

		// Register templates.
		add_action( 'vc_get_all_templates', __CLASS__ . '::register_templates' );
		add_action( 'vc_templates_render_category', __CLASS__ . '::render_template_tab' );

		/**
		 * Render templates.
		 *
		 * @see js_composer/includes/classes/core/shared-templates/class-vc-shared-templates.
		 */
		add_action( 'vc_templates_render_backend_template', __CLASS__ . '::render_backend_template', 10, 2 );
		add_action( 'vc_templates_render_frontend_template', __CLASS__ . '::render_frontend_template', 10, 2 );

		// Enqueue filter scripts.
		add_action( 'vc_frontend_editor_enqueue_js_css', __CLASS__ . '::filter_js' );
		add_action( 'vc_backend_editor_enqueue_js_css', __CLASS__ . '::filter_js' );

	}

	/**
	 * Register templates.
	 */
	public static function register_templates( $data ) {

		$templates = self::get_templates();

		if ( ! $templates ) {
			return $data;
		}

		$total_cat = array(
			'category'             => self::$template_type,
			'category_name'        => esc_html__( 'Section Templates', 'total' ),
			'category_description' => esc_html__( 'Append a section template to the current layout.', 'total' ),
			'category_weight'      => apply_filters( 'wpex_vc_section_templates_category_weight', 11 ),
		);

		$total_cat_templates = array();

		$count = 0;
		foreach ( $templates as $template_id => $template_data ) {
			$count ++;
			$total_cat_templates[] = array(
				'unique_id' => $template_id,
				'name'      => $template_data['name'],
				'type'      => self::$template_type,
				'content'   => $template_data['content'],
				'weight'    => $count,
			);
		}

		$total_cat['templates'] = $total_cat_templates;

		$data[] = $total_cat;

		return $data;

	}

	/**
	 * Get templates.
	 */
	public static function get_templates() {

		// Define template arrays.
		$templates = array();

		// Placeholder images.
		$ph_location  = TRAILINGSLASHIT( WPEX_THEME_URI ) . '/inc/integration/wpbakery/templates/placeholders/';
		$ph_landscape = $ph_location . 'landscape.png';
		$ph_portrait  = $ph_location . 'portrait.png';
		$ph_square    = $ph_location . 'square.png';

		// Loop through categories to get templates.
		$categories = self::get_categories();

		if ( $categories ) {
			foreach( $categories as $key => $val ) {
				$file = WPEX_INC_DIR . 'integration/wpbakery/templates/sections/' . $key . '.php';
				if ( file_exists( $file ) ) {
					require $file;
				}
			}
		}

		/**
		 * Filters the Total theme wpbakery section templates.
		 *
		 * @param array $templates.
		 */
		$templates = (array) apply_filters( 'wpex_wpbakery_section_templates', $templates );

		return $templates;

	}

	/**
	 * Render the template tab.
	 */
	public static function render_template_tab( $category ) {
		if ( self::$template_type === $category['category'] ) {
			$category['output'] = '';
			$category['output'] .= '<div class="vc_column vc_col-sm-12">';
			if ( isset( $category['category_name'] ) ) {
				$category['output'] .= '<h3>' . esc_html( $category['category_name'] ) . '</h3>';
			}
			if ( isset( $category['category_description'] ) ) {
				$category['output'] .= '<p class="vc_description">' . esc_html( $category['category_description'] ) . '</p>';
			}
			$category['output'] .= '</div>';
			$category['output'] .= '<div class="vc_column vc_col-sm-12">';
				$category['output'] .= self::get_category_filter();
			$category['output'] .= '</div>';
			$category['output'] .= '<div class="vc_column vc_col-sm-12">';
			$category['output'] .= '<div class="wpex-vc-template-list">';
				if ( ! empty( $category['templates'] ) ) {
					foreach ( $category['templates'] as $template ) {
						$category['output'] .= self::render_template_tab_item( $template );
					}
				}
			$category['output'] .= '</div></div>';
		}
		return $category;
	}

	/**
	 * Get categories.
	 */
	public static function get_categories() {

		$categories = array(
			'hero'           => esc_html__( 'Hero', 'total' ),
			'features'       => esc_html__( 'Features', 'total' ),
			'statistics'     => esc_html__( 'Statistics', 'total' ),
			'team'           => esc_html__( 'Team', 'total' ),
			'call-to-action' => esc_html__( 'Call to Action', 'total' ),
			'faq'            => esc_html__( 'FAQ', 'total' ),
			'subscribe'      => esc_html__( 'Subscribe', 'total' ),
			'pricing'        => esc_html__( 'Pricing', 'total' ),
			'contact'        => esc_html__( 'Contact', 'total' ),
		);

		/**
		 * Filters the Total theme wpbakery section template categories.
		 *
		 * @param array $templates.
		 */
		$categories = (array) apply_filters( 'wpex_wpbakery_section_templates_categories', $categories );

		return $categories;

	}

	/**
	 * Category filter.
	 */
	public static function get_category_filter() {
		$categories = self::get_categories();
		$html = '<div class="wpex-vc-template-list__filter">';
			$html .= '<span>' . esc_html__( 'Filter by type', 'total' ) . ':</span>';
			$html .= '<a href="#" data-category="*" aria-pressed="true" role="button" class="wpex-vc-template-list__filter-button">' . esc_html__( 'All', 'total' ) . '</a>';
			foreach( $categories as $cat_id => $cat_name ) {
				$html .= '<a href="#" data-category="' . esc_attr( $cat_id ) . '" aria-pressed="false" role="button" class="wpex-vc-template-list__filter-button">' . esc_html( $cat_name ) . '</a>';
			}
		$html .= '</div>';
		return $html;
	}

	/**
	 * Renders the items for the section templates tab.
	 *
	 * @param $template
	 * @return string
	 */
	public static function render_template_tab_item( $template ) {
		$name = $template['name'];
		$template_id = $template['unique_id'];
		$template_id_hash = md5( $template_id );
		$template_name = $name;
		$template_name_lower = function_exists( 'vc_slugify' ) ? vc_slugify( $template_name ) : '';
		$template_type = self::$template_type;
		$template_category = trim( preg_replace( '/[^a-z]/', '', $template_id ) );
		if ( 'calltoaction' == $template_category ) {
			$template_category = 'call-to-action';
		}
		$preview_image = TRAILINGSLASHIT( WPEX_THEME_URI ) . '/inc/integration/wpbakery/templates/sections/thumbnails/' . $template_id . '.png';

		$output = '<div class="wpex-vc-template-list__item"
						data-template_id="' . esc_attr( $template_id ) . '"
						data-template_id_hash="' . esc_attr( $template_id_hash ) . '"
						data-category="' . esc_attr( $template_type ) . '"
						data-wpex-category="' . esc_attr( $template_category ) . '"
						data-template_unique_id="' . esc_attr( $template_id ) . '"
						data-template_name="' . esc_attr( $template_name_lower ) . '"
						data-template_type="' . esc_attr( $template_type ) . '">';

		if ( $preview_image ) {
			$output .= '<div class="wpex-vc-template-list__image"><img loading="lazy" src="' . esc_url( $preview_image ) . '"></div>';
		}

		$output .= '<div class="wpex-vc-template-list__overlay">';

		$output .= '<div class="wpex-vc-template-list__name">' . esc_html( $template_name ) . '</div>';

			$output .= '<div class="wpex-vc-template-list__actions">';

				$output .='<a href="https://total.wpexplorer.com/sections/wpbakery/' . esc_attr( $template_id ) . '" class="button button-primary" target="_blank" rel="nofollow noopener noreferrer">' . esc_html__( 'Preview', 'total' ) . '<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 6.5c3.79 0 7.17 2.13 8.82 5.5-1.65 3.37-5.02 5.5-8.82 5.5S4.83 15.37 3.18 12C4.83 8.63 8.21 6.5 12 6.5m0-2C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zm0 5c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5m0-2c-2.48 0-4.5 2.02-4.5 4.5s2.02 4.5 4.5 4.5 4.5-2.02 4.5-4.5-2.02-4.5-4.5-4.5z"/></svg></a>';

				$output .= '<button type="button" class="wpex-vc-template-list__insert button button-primary" data-template-handler="">' . esc_html__( 'Insert', 'total' ) . '<svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><g><rect fill="none" height="24" width="24"/></g><g><path d="M18,15v3H6v-3H4v3c0,1.1,0.9,2,2,2h12c1.1,0,2-0.9,2-2v-3H18z M17,11l-1.41-1.41L13,12.17V4h-2v8.17L8.41,9.59L7,11l5,5 L17,11z"/></g></svg></button></div>';

		$output .= '</div>';

		$output .= '</div>';

		return $output;
	}

	/**
	 * Render template for the backend editor.
	 */
	public static function render_backend_template( $template_id, $template_type ) {
		if ( self::$template_type === $template_type ) {
			$templates = self::get_templates();
			if ( isset( $templates[$template_id] ) ) {
				return trim( $templates[$template_id]['content'] );
			}
		}
		return $template_id;
	}

	/**
	 * Render template for the frontend editor.
	 */
	public static function render_frontend_template( $template_id, $template_type ) {
		if ( self::$template_type === $template_type ) {
			$templates = self::get_templates();
			if ( isset( $templates[$template_id] ) ) {
				vc_frontend_editor()->setTemplateContent( trim( $templates[$template_id]['content'] ) );
				vc_frontend_editor()->enqueueRequired();
				vc_include_template( 'editors/frontend_template.tpl.php', array(
					'editor' => vc_frontend_editor(),
				) );
				die();

			}
			wp_send_json_error( array(
				'code' => 'Wrong ID or no Template found #3',
			) );
		}
		return $template_id;
	}

	/**
	 * Enqueues template filter js.
	 *
	 */
	public static function filter_js() {

		wp_enqueue_script(
			'wpex-vc-template-filter',
			wpex_asset_url( 'js/dynamic/wpbakery/wpex-vc-template-filter.min.js' ),
			array( 'jquery' ),
			WPEX_THEME_VERSION,
			true
		);

	}

}