<?php
/**
 * Navbar Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Navbar_Shortcode' ) ) {

	class VCEX_Navbar_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_navbar', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Navbar::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {

			$this->enqueue_scripts( $atts );

			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_navbar', $atts );
			include( vcex_get_shortcode_template( 'vcex_navbar' ) );
			do_action( 'vcex_shortcode_after', 'vcex_navbar', $atts );
			return ob_get_clean();

		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Show Select Field on Mobile?', 'total-theme-core' ),
					'param_name' => 'mobile_select',
					'description' => esc_html__( 'When enabled the menu buttons will be converted into a singular select dropdown on mobile devices.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Mobile Select Empty Option Text', 'total-theme-core' ),
					'param_name' => 'mobile_select_browse_txt',
					'description' => esc_html__( 'This option is used as the first option in the mobile select field. For example if you have a standard link based menu you may use "Browse" as the empty option text. If you have setup a filter style menu you may want to keep this empty and add an "All" link at the start of your menu which is done by adding a link with a # symbol as the url value.', 'total-theme-core' ),
					'dependency' => array( 'element' => 'mobile_select', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Element ID', 'total-theme-core' ),
					'param_name' => 'unique_id',
					'admin_label' => true,
					'description' => vcex_shortcode_param_description( 'unique_id' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
					'param_name' => 'classes',
				),
				vcex_vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Duration', 'total'),
					'param_name' => 'animation_duration',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Delay', 'total'),
					'param_name' => 'animation_delay',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total'),
				),
				// Menu
				array(
					'type' => 'vcex_menus_select',
					'admin_label' => true,
					'heading' => esc_html__( 'Menu', 'total-theme-core' ),
					'param_name' => 'menu',
					'save_always' => true,
					'group' => esc_html__( 'Menu', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Post Filter Grid ID', 'total-theme-core' ),
					'param_name' => 'filter_menu',
					'description' => esc_html__( 'Enter the "Element ID" of the post grid module you wish to filter. This will only work on the theme specific grids. Make sure the filter on the grid module is disabled to prevent conflicts. View theme docs for more info.', 'total-theme-core' ),
					'group' => esc_html__( 'Menu', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Local Scroll menu', 'total-theme-core'),
					'param_name' => 'local_scroll',
					'group' => esc_html__( 'Menu', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Sticky', 'total-theme-core'),
					'param_name' => 'sticky',
					'group' => esc_html__( 'Menu', 'total-theme-core' ),
					'description' => esc_html__( 'Note: Sticky is disabled in the front-end editor.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Offset Navbar Height', 'total-theme-core'),
					'param_name' => 'sticky_offset_nav_height',
					'group' => esc_html__( 'Menu', 'total-theme-core' ),
					'description' => esc_html__( 'Whether the navigation menu height should be included in the offset when calculating local scroll position. Generally you would enable for horizontal menus and disable for vertical menus (for example if the menu is placed in a sidebar).', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Sticky Endpoint', 'total-theme-core'),
					'param_name' => 'sticky_endpoint',
					'group' => esc_html__( 'Menu', 'total-theme-core' ),
					'description' => esc_html__( 'Enter the ID or classname of an element that when reached will disable the stickiness. Example: #footer', 'total-theme-core' ),
					'dependency' => array( 'element' => 'sticky', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'false',
					'heading' => esc_html__( 'Full-Screen Center', 'total-theme-core'),
					'param_name' => 'full_screen_center',
					'description' => esc_html__( 'Center the navigation when used inside a stretched row or full-screen page layout.', 'total-theme-core' ),
					'group' => esc_html__( 'Menu', 'total-theme-core' ),
				),
				// Filter
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Layout Mode', 'total-theme-core' ),
					'param_name' => 'filter_layout_mode',
					'std' => 'masonry',
					'choices' => 'filter_layout_mode',
					'group' => esc_html__( 'Filter', 'total-theme-core' ),
					'dependency' => array( 'element' => 'filter_menu', 'not_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Filter Speed', 'total-theme-core' ),
					'param_name' => 'filter_transition_duration',
					'description' => esc_html__( 'Default is "0.4" seconds. Enter "0.0" to disable.', 'total-theme-core' ),
					'group' => esc_html__( 'Filter', 'total-theme-core' ),
					'dependency' => array( 'element' => 'filter_menu', 'not_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Active Item ID', 'total-theme-core' ),
					'param_name' => 'filter_active_item',
					'description' => esc_html__( 'Enter the ID for the term you wish to have active by default. You must edit your portfolio grid or save and view live site to preview these changes.', 'total-theme-core' ),
					'group' => esc_html__( 'Filter', 'total-theme-core' ),
					'dependency' => array( 'element' => 'filter_menu', 'not_empty' => true ),
				),
				// Design
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Preset', 'total-theme-core' ),
					'param_name' => 'preset_design',
					'std' => 'none',
					'value' => array(
						esc_html__( 'None', 'total-theme-core' ) => 'none',
						esc_html__( 'Dark', 'total-theme-core' ) => 'dark',
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Alignment', 'total-theme-core' ),
					'param_name' => 'align',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_hover_animations',
					'heading' => esc_html__( 'Hover Animation', 'total-theme-core'),
					'param_name' => 'hover_animation',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_button_styles',
					'heading' => esc_html__( 'Button Style', 'total-theme-core' ),
					'param_name' => 'button_style',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'std' => 'minimal-border',
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total-theme-core' ),
					'param_name' => 'border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'vcex_button_colors',
					'heading' => esc_html__( 'Button Color', 'total-theme-core' ),
					'param_name' => 'button_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Layout', 'total-theme-core' ),
					'param_name' => 'button_layout',
					'std' => '',
					'choices' => array(
						'' => esc_html__( 'Default', 'total-theme-core' ),
						'spaced_out' => esc_html__( 'Spaced Out', 'total-theme-core' ),
						'list' => esc_html__( 'List', 'total-theme-core' ),
						'expanded' => esc_html__( 'Expanded', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Expand Links', 'total-theme-core' ),
					'param_name' => 'expand_links',
					'std' => 'false',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'button_layout', 'value' => 'spaced_out' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Link Side Margin', 'total-theme-core' ),
					'param_name' => 'link_margin_side',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Link Bottom Margin', 'total-theme-core' ),
					'param_name' => 'link_margin_bottom',
					'value' => vcex_margin_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Link Padding', 'total-theme-core' ),
					'param_name' => 'link_padding',
					'value' => vcex_padding_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background', 'total-theme-core' ),
					'param_name' => 'background',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color: Hover', 'total-theme-core' ),
					'param_name' => 'hover_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background: Hover', 'total-theme-core' ),
					'param_name' => 'hover_bg',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				// Typography
				array(
					'type' => 'vcex_responsive_sizes',
					'target' => 'font-size',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'font_family',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'font_weight',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Line Height', 'total-theme-core' ),
					'param_name' => 'line_height',
					'description' => vcex_shortcode_param_description( 'line_height' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Letter Spacing', 'total-theme-core' ),
					'param_name' => 'letter_spacing',
					'description' => vcex_shortcode_param_description( 'letter_spacing' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'Link CSS', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'Link CSS', 'total-theme-core' ),
					'dependency' => array( 'element' => 'preset_design', 'value' => 'none' ),
				),
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'Wrap CSS', 'total-theme-core' ),
					'param_name' => 'wrap_css',
					'group' => esc_html__( 'Wrap CSS', 'total-theme-core' ),
				),
				// Deprecated params
				array( 'type' => 'hidden', 'param_name' => 'style' ),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_navbar' );

		}

		/**
		 * Parses deprecated attributes.
		 */
		public static function parse_deprecated_attributes( $atts = '' ) {

			if ( empty( $atts ) || ! is_array( $atts ) ) {
				return $atts;
			}

			if ( isset( $atts['style'] ) && 'simple' === $atts['style'] ) {
				$atts['button_style'] = 'plain-text';
				unset( $atts['style'] );
			}

			return $atts;

		}

		/**
		 * Enqueue scripts.
		 */
		public function enqueue_scripts( $atts ) {

			if ( ! empty( $atts['filter_menu'] ) ) {

				vcex_enqueue_isotope_scripts();

				wp_enqueue_script(
					'vcex-navbar_filter-links',
					vcex_asset_url( 'js/shortcodes/vcex-navbar_filter-links.min.js' ),
					array( 'jquery', 'imagesloaded', 'isotope' ),
					TTC_VERSION,
					true
				);

			}

			if ( isset( $atts['sticky'] ) && vcex_validate_boolean( $atts['sticky'] ) ) {

				wp_enqueue_script(
					'vcex-navbar_sticky',
					vcex_asset_url( 'js/shortcodes/vcex-navbar_sticky.min.js' ),
					array( 'jquery' ),
					TTC_VERSION,
					true
				);

			}

			if ( isset( $atts['mobile_select'] ) && vcex_validate_boolean( $atts['mobile_select'] ) ) {

				wp_enqueue_script(
					'vcex-navbar_mobile-select',
					vcex_asset_url( 'js/shortcodes/vcex-navbar_mobile-select.min.js' ),
					array(),
					TTC_VERSION,
					true
				);

			}

		}

	}

}
new VCEX_Navbar_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Navbar' ) ) {
	class WPBakeryShortCode_Vcex_Navbar extends WPBakeryShortCode {}
}