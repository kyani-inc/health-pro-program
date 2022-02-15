<?php
/**
 * Get template ID based on theme location.
 *
 * @package TotalTheme
 * @subpackage Theme_Builder
 * @version 5.1
 *
 * @todo move original dynamic template and header/footer builder functionality here.
 */

namespace TotalTheme\Theme_Builder;

defined( 'ABSPATH' ) || exit;

class Location_Template {

	/**
	 * Template to return for specific location.
	 */
	public $template = 0;

	/**
	 * Start things.
	 */
	public final function __construct( $location ) {

		// Parse location to get it's template ID.
		if ( method_exists( $this, $location ) ) {
			$this->$location();
		}

		/**
		 * Filters the location template ID.
		 *
		 * @param int $template_id
		 */
		$this->template = apply_filters( "wpex_{$location}_template_id", $this->template );
	}

	/*-------------------------------------------------------------------------------*/
	/* [ Main Locations ]
	/*-------------------------------------------------------------------------------*/

	/**
	 * Archive template.
	 */
	public function archive() {

		if ( is_search() ) {
			$this->search_archive_template(); // search must be first because it can return true for is_tax()

			if ( ! empty( $this->template ) ) {
				return; // fixes issues with wpex_is_blog_query when adding ?post= param to the search query.
			}

		}

		if ( is_tax() ) {
			$this->taxonomy_archive_template();
		}

		if ( is_post_type_archive() ) {
			$this->post_type_archive_template();
		}

		if ( is_author() ) {
			$this->author_archive_template();
			if ( ! empty( $this->template ) ) {
				return; // prevent the blog template to take over.
			}
		}

		if ( wpex_is_blog_query() ) {
			$this->blog_template();
		}

	}

	/*-------------------------------------------------------------------------------*/
	/* [ Sub Locations ]
	/*-------------------------------------------------------------------------------*/

	/**
	 * Taxonomy template.
	 */
	public function taxonomy_archive_template() {
		$template = '';

		$taxonomy = get_query_var( 'taxonomy' );

		if ( is_tax( array( 'staff_category', 'staff_tag' ) ) ) {
			$template = get_theme_mod( 'staff_archive_template_id' );
		}

		if ( is_tax( array( 'portfolio_category', 'portfolio_tag' ) ) ) {
			$template = get_theme_mod( 'portfolio_archive_template_id' );
		}

		if ( is_tax( array( 'testimonials_category', 'testimonials_tag' ) ) ) {
			$template = get_theme_mod( 'testimonials_archive_template_id' );
		}

		// Check theme mod defined template
		if ( empty( $template ) ) {
			$template = get_theme_mod( $taxonomy . '_template_id' );
		}

		// Check Post Types Unlimited Template (last)
		if ( WPEX_PTU_ACTIVE ) {
			$ptu_check = wpex_get_ptu_tax_mod( $taxonomy, 'template_id' );
			if ( $ptu_check ) {
				$template = $ptu_check;
			}
		}

		/**
		 * Filters the taxonomy archive template ID.
		 *
		 * @param int $template_id
		 */
		$this->template = apply_filters( 'wpex_taxonomy_template_id', $template, $taxonomy );
	}

	/**
	 * Author archive template.
	 */
	public function author_archive_template() {
		$template = get_theme_mod( 'author_archive_template_id' );

		/**
		 * Filters the author archive template ID.
		 *
		 * @param int $template_id
		 */
		$this->template = apply_filters( 'wpex_author_archive_template_id', $template );
	}

	/**
	 * Blog template.
	 */
	public function blog_template() {
		$template = get_theme_mod( 'blog_archive_template_id' );
		if ( empty( $template ) && is_tax( 'post_series' ) && get_theme_mod( 'post_series_enable', true ) ) {
			$template = get_theme_mod( 'post_series_template_id' );
		}

		/**
		 * Filters the blog archive template ID.
		 *
		 * @param int $template_id
		 */
		$this->template = apply_filters( 'wpex_blog_archive_template_id', $template );
	}

	/**
	 * Search template.
	 */
	public function search_archive_template() {
		$template = get_theme_mod( 'search_archive_template_id' );

		/**
		 * Filters the search archive template ID.
		 *
		 * @param int $template_id
		 */
		$this->template = apply_filters( 'wpex_search_archive_template_id', $template );
	}

	/**
	 * Post Type archive template.
	 */
	public function post_type_archive_template() {
		$post_type = get_query_var( 'post_type' );

		// Check theme mod defined template
		$template = get_theme_mod( $post_type . '_archive_template_id' );

		// Check Post Types Unlimited Template (last)
		if ( WPEX_PTU_ACTIVE ) {
			$ptu_check = wpex_get_ptu_type_mod( $post_type, 'archive_template_id' );
			if ( $ptu_check ) {
				$template = $ptu_check;
			}
		}

		/**
		 * Filters the post type archive template ID.
		 *
		 * @param int $template_id
		 */
		$this->template = apply_filters( 'wpex_post_type_archive_template_id', $template, $post_type );
	}

}