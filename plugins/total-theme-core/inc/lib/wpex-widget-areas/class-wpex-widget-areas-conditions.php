<?php
/**
 * WPEX Widget Areas Conditions.
 *
 * @package WPEX_Widget_Areas
 * @version 1.0
 * @copyright WPExplorer.com - All rights reserved.
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPEX_Widget_Areas_Conditions' ) ) {

	final class WPEX_Widget_Areas_Conditions {

		/**
		 * The post type used for custom widget areas.
		 */
		private static $post_type;

		/**
		 * Conditions index.
		 */
		private static $conditions_index;

		/**
		 * User selected conditions.
		 */
		private static $selected_conditions;

		/**
		 * Constructor
		 *
		 * @since 1.0
		 */
		public function __construct() {
			self::$post_type = WPEX_Widget_Areas::$post_type;
			if ( is_admin() ) {
				add_action( 'add_meta_boxes', __CLASS__ . '::add_meta_box' );
				add_action( 'save_post', __CLASS__ . '::save_meta_box' );
			}
		}

		/**
		 * Add metabox.
		 *
		 * @since 1.0
		 */
		public static function add_meta_box() {
			add_meta_box(
				'wpex-widget-areas-conditions',
				esc_html__( 'Conditions', 'total-theme-core' ),
				__CLASS__ . '::metabox_content',
				self::$post_type,
				'normal',
				'low'
			);
		}

		/**
		 * Save meta.
		 *
		 * @since 1.0
		 */
		public static function save_meta_box( $post_id ) {

			if ( self::$post_type !== get_post_type( $post_id ) ) {
				return;
			}

			// Check if our nonce is set.
			if ( ! isset( $_POST['wpex_widget_areas_conditions_nonce'] ) ) {
				return;
			}

			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['wpex_widget_areas_conditions_nonce'], 'wpex_widget_areas_conditions_save_meta' ) ) {
				return;
			}

			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			// Check the user's permissions.
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			/* OK, it's safe for us to save the data now. */
			if ( array_key_exists( 'wpex_wa_conditions', $_POST ) ) {
				if ( is_array( $_POST['wpex_wa_conditions'] ) && count( $_POST['wpex_wa_conditions'] ) > 0 ) {
					update_post_meta( $post_id, '_wpex_widget_area_conditions', $_POST['wpex_wa_conditions'], false );
				}
			} else {
				delete_post_meta( $post_id, '_wpex_widget_area_conditions' );
			}

		}

		/**
		 * The metabox content.
		 *
		 * @since 1.0
		 */
		public static function metabox_content( $post ) {

			self::metabox_scripts();

			wp_nonce_field( 'wpex_widget_areas_conditions_save_meta', 'wpex_widget_areas_conditions_nonce' );

			self::$selected_conditions = get_post_meta( $post->ID, '_wpex_widget_area_conditions', true );

			?>

			<div class="wpex-wa-conditions-panel">
				<?php self::render_metabox_tabs(); ?>
				<?php self::render_metabox_sections(); ?>
			</div>

			<div class="wpex-wa-conditions-footer">
				<a href="#" class="wpex-wa-conditions-clear button button-secondary"><?php esc_html_e( 'Clear All', 'total-theme-core' ); ?></a>
			</div>

		<?php }

		/**
		 * Enqueues metabox scripts.
		 *
		 * @since 1.0
		 */
		public static function metabox_scripts() {

			wp_enqueue_style(
				'wpex-widget-areas-conditions',
				plugin_dir_url( __FILE__ ) . 'assets/wpex-widget-areas-conditions.css',
				array(),
				'1.0'
			);

			wp_enqueue_script(
				'wpex-widget-areas-conditions',
				plugin_dir_url( __FILE__ ) . 'assets/wpex-widget-areas-conditions.js',
				array(),
				'1.0',
				true
			);

			wp_localize_script(
				'wpex-widget-areas-conditions',
				'wpexWidgetAreasConditionsL10n',
				array(
					'confirm' => esc_html__( 'Are you sure you want to clear all settings?', 'total-theme-core' ),
				)
			);

		}

		/**
		 * Metabox tabs.
		 *
		 * @since 1.0
		 */
		public static function render_metabox_tabs() { ?>

			<ul class="wpex-wa-conditions-panel__tabs">
				<li class="wpex-wa-conditions-panel__tab wpex-wa-conditions-panel__tab--active"><a class="wpex-wa-conditions-tab-link" href="#" aria-controls="wpex-wa-conditions-panel__section--pages" aria-selected="true" role="tab"><?php esc_html_e( 'Pages', 'total-theme-core' ); ?></a></li>
				<li class="wpex-wa-conditions-panel__tab"><a class="wpex-wa-conditions-tab-link" href="#" aria-controls="wpex-wa-conditions-panel__section--page-templates" aria-selected="false" role="tab"><?php esc_html_e( 'Page Templates', 'total-theme-core' ); ?></a></li>
				<li class="wpex-wa-conditions-panel__tab"><a class="wpex-wa-conditions-tab-link" href="#" aria-controls="wpex-wa-conditions-panel__section--post-types" aria-selected="false" role="tab"><?php esc_html_e( 'Post Types', 'total-theme-core' ); ?></a></li>
				<li class="wpex-wa-conditions-panel__tab"><a class="wpex-wa-conditions-tab-link" href="#" aria-controls="wpex-wa-conditions-panel__section--taxonomies" aria-selected="false" role="tab"><?php esc_html_e( 'Taxonomies', 'total-theme-core' ); ?></a></li>
				<li class="wpex-wa-conditions-panel__tab"><a class="wpex-wa-conditions-tab-link" href="#" aria-controls="wpex-wa-conditions-panel__section--terms" aria-selected="false" role="tab"><?php esc_html_e( 'Terms', 'total-theme-core' ); ?></a></li>
				<li class="wpex-wa-conditions-panel__tab"><a class="wpex-wa-conditions-tab-link" href="#" aria-controls="wpex-wa-conditions-panel__section--other" aria-selected="false" role="tab"><?php esc_html_e( 'Other', 'total-theme-core' ); ?></a></li>
			</ul>

		<?php }

		/**
		 * Metabox sections.
		 *
		 * @since 1.0
		 */
		public static function render_metabox_sections() { ?>
			<div class="wpex-wa-conditions-panel__sections">
				<?php self::render_pages_fields(); ?>
				<?php self::render_page_templates_fields(); ?>
				<?php self::render_post_types_fields(); ?>
				<?php self::render_taxonomies_fields(); ?>
				<?php self::render_terms_fields(); ?>
				<?php self::render_other_fields(); ?>
			</div>
		<?php }

		/**
		 * Page fields.
		 *
		 * @since 1.0
		 */
		public static function render_pages_fields() {

			$pages = get_pages();

			echo '<div id="wpex-wa-conditions-panel__section--pages" class="wpex-wa-conditions-panel__section wpex-wa-conditions-panel__section--active' . self::section_cols_class( $pages ) . '">';

				if ( ! is_wp_error( $pages ) && count( $pages ) > 0 ) {
					foreach ( $pages as $page ) {
						echo self::render_field( 'page-' . $page->ID, $page->post_title );
					}
				} else {
					echo '<p>' . esc_html__( 'No pages found.', 'total-theme-core' ) . '</p>';
				}

			echo '</div>';

		}

		/**
		 * Page template fields.
		 *
		 * @since 1.0
		 */
		public static function render_page_templates_fields() {

			$page_templates = get_page_templates();

			echo '<div id="wpex-wa-conditions-panel__section--page-templates" class="wpex-wa-conditions-panel__section' . self::section_cols_class( $page_templates ) . '">';

				if ( ! is_wp_error( $page_templates ) && count( $page_templates ) > 0 ) {
					foreach ( $page_templates as $k => $v ) {
						$value = str_replace( '.php', '', 'page-template-' . $v );
						echo self::render_field( $value, $k );
					}
				} else {
					echo '<p>' . esc_html__( 'No page templates found.', 'total-theme-core' ) . '</p>';
				}

			echo '</div>';

		}

		/**
		 * Post Types fields.
		 *
		 * @since 1.0
		 */
		public static function render_post_types_fields() {

			$post_types = get_post_types( array(
				'show_ui' => true,
				'public' => true,
				'publicly_queryable' => true,
			), 'object' );

			echo '<div id="wpex-wa-conditions-panel__section--post-types" class="wpex-wa-conditions-panel__section' . self::section_cols_class( $post_types ) . '">';

				if ( ! is_wp_error( $post_types ) && count( $post_types ) > 0 ) {
					foreach ( $post_types as $k => $v ) {
						echo self::render_field( 'post-type-' . $k, $v->name );
					}
				} else {
					echo '<p>' . esc_html__( 'No post types found.', 'total-theme-core' ) . '</p>';
				}

			echo '</div>';

		}

		/**
		 * Taxonomies fields.
		 *
		 * @since 1.0
		 */
		public static function render_taxonomies_fields() {

			$taxonomies = get_taxonomies( array(
				'public' => true
			), 'objects' );

			echo '<div id="wpex-wa-conditions-panel__section--taxonomies" class="wpex-wa-conditions-panel__section' . self::section_cols_class( $taxonomies ) . '">';

				if ( ! is_wp_error( $taxonomies ) && count( $taxonomies ) > 0 ) {
					foreach ( $taxonomies as $k => $v ) {
						$label = $v->labels->name . ' (' . $k . ')';
						echo self::render_field( 'tax-' . $k, $label );
					}
				} else {
					echo '<p>' . esc_html__( 'No taxonomies found.', 'total-theme-core' ) . '</p>';
				}

			echo '</div>';

		}

		/**
		 * Terms fields.
		 *
		 * @since 1.0
		 */
		public static function render_terms_fields() {

			$taxonomies = get_taxonomies( array(
				'public' => true
			), 'objects' );

			echo '<div id="wpex-wa-conditions-panel__section--terms" class="wpex-wa-conditions-panel__section">';

				if ( ! is_wp_error( $taxonomies ) && count( $taxonomies ) > 0 ) {

					foreach ( $taxonomies as $k => $v ) {
						$terms = get_terms( array( 'taxonomy' => $k, 'hide_empty' => false ) );
						if ( count( $terms ) < 1 ) {
							continue;
						}
						echo '<div class="wpex-wa-conditions-accordion">';
							echo '<h4 class="wpex-wa-conditions-accordion__title">';
								echo '<button class="wpex-wa-conditions-accordion__toggle" aria-expanded="false">';
									echo esc_html( $v->labels->name );
									echo '<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" role="img" aria-hidden="true" focusable="false"><path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"></path></svg></span>';
								echo '</button>';
							echo '</h4>';
							echo '<div class="wpex-wa-conditions-accordion__content">';
								foreach ( $terms as $term ) {
									echo self::render_field( 'term-' . $term->term_id, $term->name );
								}
							echo '</div>';
						echo '</div>';
					}

				} else {
					echo '<p>' . esc_html__( 'No taxonomies found.', 'total-theme-core' ) . '</p>';
				}

			echo '</div>';

		}

		/**
		 * Other fields.
		 *
		 * @since 1.0
		 */
		public static function render_other_fields() {

			echo '<div id="wpex-wa-conditions-panel__section--other" class="wpex-wa-conditions-panel__section">';
				echo self::render_field( 'home', esc_html__( 'Posts Page', 'total-theme-core' ) );
				echo self::render_field( 'search', esc_html__( 'Search Results', 'total-theme-core' ) );
				echo self::render_field( 'author', esc_html__( 'Author Archives', 'total-theme-core' ) );
			echo '</div>';

		}

		/**
		 * Get Section Columns Class.
		 *
		 * @since 1.0
		 */
		public static function section_cols_class( $items ) {
			if ( is_array( $items ) && count( $items ) > 21 ) {
				return ' wpex-wa-conditions-panel__section--columns';
			}
		}

		/**
		 * Render field.
		 *
		 * @since 1.0
		 */
		public static function render_field( $value, $label ) {
			$is_active = false;
			$selected_conditions = self::$selected_conditions;
			if ( is_array( $selected_conditions ) && count( $selected_conditions ) > 0 ) {
				$is_active = in_array( $value, $selected_conditions );
			}
			return '<div class="wpex-wa-conditions-item"><input type="checkbox" id="wpex-wac-field--' . esc_attr( $value ) . '" name="wpex_wa_conditions[]" value="' . esc_attr( $value ) . '" ' . checked( $is_active, true, false ) . '><label for="wpex-wac-field--' . esc_attr( $value ) . '">' . esc_html( $label ) . '</label></div>';
		}

		/**
		 * Displays the currently selected conditions.
		 *
		 * @since 1.0
		 */
		public static function selected_conditions_display( $conditions ) {

			if ( ! $conditions ) {
				$conditions = self::$selected_conditions;
			}

			if ( ! is_array( $conditions ) || ! count( $conditions ) ) {
				return;
			}

			$pages = array();
			$post_types = array();
			$page_templates = array();
			$taxonomies = array();
			$terms = array();

			foreach( $conditions as $condition ) {

				if ( 'search' === $condition ) {
					echo '<p>' . esc_html( 'Search Results', 'total-theme-core' ) . '</p>';
				} elseif ( 'home' === $condition ) {
					echo '<p>' . esc_html( 'Posts Page', 'total-theme-core' ) . '</p>';
				} elseif ( 'author' === $condition ) {
					echo '<p>' . esc_html( 'Author Archives', 'total-theme-core' ) . '</p>';
				} elseif ( 0 === strpos( $condition, 'page-template-' ) ) {
					$page_template = str_replace( 'page-template-', '', $condition );
					$page_template = $page_template . '.php';
					$page_template = array_search( $page_template, get_page_templates() );
					if ( $page_template ) {
						$page_templates[] = $page_template;
					}
				} elseif ( 0 === strpos( $condition, 'page-' ) ) {
					$page = get_post( str_replace( 'page-', '', $condition ) );
					if ( $page ) {
						$pages[] = $page->post_title;
					}
				} elseif ( 0 === strpos( $condition, 'post-type-' ) ) {
					$post_type_obj = get_post_type_object( str_replace( 'post-type-', '', $condition ) );
					if ( $post_type_obj ) {
						$post_types[] = $post_type_obj->labels->name;
					}
				} elseif ( 0 === strpos( $condition, 'tax-' ) ) {
					$tax = get_taxonomy( str_replace( 'tax-', '', $condition ) );
					if ( ! is_wp_error( $tax ) && is_object( $tax ) ) {
						$tax_labels = get_taxonomy_labels( $tax );
						if ( $tax_labels ) {
							$taxonomies[] = $tax_labels->name;
						}
					}
				} elseif ( 0 === strpos( $condition, 'term-' ) ) {
					$term = get_term( str_replace( 'term-', '', $condition ) );
					if ( ! is_wp_error( $term ) && is_object( $term ) ) {
						$terms[$term->taxonomy][] = $term->name;
					}
				}

			}

			if ( $pages ) {
				echo '<p><strong>' . esc_html( 'Pages', 'total-theme-core' ) . ':</strong> ' . implode( ', ', $pages ) . '</p>';
			}

			if ( $page_templates ) {
				echo '<p><strong>' . esc_html( 'Page Templates', 'total-theme-core' ) . ':</strong> ' . implode( ', ', $page_templates ) . '</p>';
			}

			if ( $post_types ) {
				echo '<p><strong>' . esc_html( 'Post Types', 'total-theme-core' ) . ':</strong> ' . implode( ', ', $post_types ) . '</p>';
			}

			if ( $taxonomies ) {
				echo '<p><strong>' . esc_html( 'Taxonomies', 'total-theme-core' ) . ':</strong> ' . implode( ', ', $taxonomies ) . '</p>';
			}

			if ( $terms ) {
				foreach( $terms as $taxonomy => $tax_terms ) {
					$tax = get_taxonomy( $taxonomy );
					if ( ! is_wp_error( $tax ) && is_object( $tax ) ) {
						$tax_labels = get_taxonomy_labels( $tax );
						if ( $tax_labels ) {
						echo '<p><strong>' . esc_html( $tax_labels->name ) . ':</strong> ' . implode( ', ', $tax_terms ) . '</p>';
						}
					}
				}
			}

		}

		/**
		 * Check if the conditions return true.
		 *
		 * @since 1.0
		 */
		public static function frontend_check( $conditions ) {

			if ( ! is_array( $conditions ) ) {
				return false;
			}

			// Loop through conditions, once a condition returns true we can bail.
			foreach( $conditions as $condition ) {

				// Search
				if ( 'search' === $condition ) {
					if ( is_search() ) {
						return true;
					}
				}

				// Home
				elseif ( 'home' === $condition ) {
					if ( is_home() ) {
						return true;
					}
				}

				// Author archives
				elseif ( 'author' === $condition ) {
					if ( is_author() ) {
						return true;
					}
				}
				// Template check
				elseif ( 0 === strpos( $condition, 'page-template-' ) ) {
					$page_template = str_replace( 'page-template-', '', $condition );
					if ( is_page_template( $page_template . '.php' ) ) {
						return true;
					}
				}

				// Page check
				elseif ( 0 === strpos( $condition, 'page-' ) ) {
					$page = str_replace( 'page-', '', $condition );
					if ( is_page( $page ) ) {
						return true;
					} elseif( in_array( $page, get_post_ancestors( get_queried_object_id() ) ) ) {
						return true;
					}
				}

				// Post type check.
				elseif ( 0 === strpos( $condition, 'post-type-' ) ) {
					$post_type = str_replace( 'post-type-', '', $condition );
					if ( is_singular( $post_type ) ) {
						return true;
					}
				}

				// Tax check.
				elseif ( 0 === strpos( $condition, 'tax-' ) ) {
					$tax = str_replace( 'tax-', '', $condition );
					if ( self::is_tax( $tax ) ) {
						return true;
					}
				}

				// Term check.
				elseif ( 0 === strpos( $condition, 'term-' ) ) {
					$term = str_replace( 'term-', '', $condition );
					$term_obj = get_term( $term );
					if ( ! is_wp_error( $term_obj ) && is_object( $term_obj ) ) {
						if ( self::is_tax( $term_obj->taxonomy ) ) {
							$current_term = get_queried_object_id();
							if ( $term == $current_term ) {
								return true;
							} else {
								$ancestor_terms = get_ancestors( $current_term, $term_obj->taxonomy, 'taxonomy' );
								if ( is_array( $ancestor_terms ) && in_array( $term, $ancestor_terms ) ) {
									return true;
								}
							}
						} elseif ( is_singular() ) {
							if ( has_term( $term, $term_obj->taxonomy, get_queried_object_id() ) ) {
								return true;
							} else {
								$term_childs = get_term_children( $term, $term_obj->taxonomy );
								if ( is_array( $term_childs ) ) {
									foreach( $term_childs as $child_term ) {
										if ( has_term( $child_term, $term_obj->taxonomy, get_queried_object_id() ) ) {
											return true;
										}
									}
								}
							}
						}
					}
				}

			}

		}

		/**
		 * Taxonomy check wrapper since categories and tags need their own checks.
		 *
		 * @since 1.0
		 */
		public static function is_tax( $taxonomy ) {
			if ( 'category' == $taxonomy ) {
				return is_category();
			} elseif ( 'post_tag' == $taxonomy ) {
				return is_tag();
			}
			return is_tax( $taxonomy );
		}

	}

}