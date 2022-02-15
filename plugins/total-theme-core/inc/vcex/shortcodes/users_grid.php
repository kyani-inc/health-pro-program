<?php
/**
 * Users Grid Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Users_Grid_Shortcode' ) ) {

	class VCEX_Users_Grid_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_users_grid', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Users_Grid::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_users_grid', $atts );
			include( vcex_get_shortcode_template( 'vcex_users_grid' ) );
			do_action( 'vcex_shortcode_after', 'vcex_users_grid', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Header', 'total-theme-core' ),
					'param_name' => 'header',
					'admin_label' => true,
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Header Style', 'total-theme-core' ),
					'param_name' => 'header_style',
					'value' => vcex_get_theme_heading_styles(),
					'description' => sprintf( esc_html__( 'Select your custom heading style. You can select your global style in %sthe Customizer%s.', 'total-theme-core' ), '<a href="' . esc_url( admin_url( '/customize.php?autofocus[section]=wpex_theme_heading' ) ) . '" target="_blank" rel="noopener noreferrer">', '</a>' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Grid Style', 'total-theme-core' ),
					'param_name' => 'grid_style',
					'std' => 'fit_columns',
					'value' => array(
						esc_html__( 'Fit Columns', 'total-theme-core' ) => 'fit_columns',
						esc_html__( 'Masonry', 'total-theme-core' ) => 'masonry',
					),
					'edit_field_class' => 'vc_col-sm-3 vc_column clear',
				),
				array(
					'type' => 'vcex_grid_columns',
					'heading' => esc_html__( 'Columns', 'total-theme-core' ),
					'param_name' => 'columns',
					'std' => '5',
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'vcex_column_gaps',
					'heading' => esc_html__( 'Gap', 'total-theme-core' ),
					'param_name' => 'columns_gap',
					'edit_field_class' => 'vc_col-sm-3 vc_column',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Responsive', 'total-theme-core' ),
					'param_name' => 'columns_responsive',
					'value' => array(
						esc_html__( 'Yes', 'total-theme-core' ) => 'true',
						esc_html__( 'No', 'total-theme-core' ) => 'false'
					),
					'edit_field_class' => 'vc_col-sm-3 vc_column',
					'dependency' => array( 'element' => 'columns', 'value' => array( '2', '3', '4', '5', '6', '7', '8', '9', '10' ) ),
				),
				array(
					'type' => 'vcex_grid_columns_responsive',
					'heading' => esc_html__( 'Responsive Settings', 'total-theme-core' ),
					'param_name' => 'columns_responsive_settings',
					'dependency' => array( 'element' => 'columns_responsive', 'value' => 'true' ),
				),
				array(
					'type' => 'dropdown',
					'std' => 'author_page',
					'heading' => esc_html__( 'On click action', 'total-theme-core' ),
					'param_name' => 'onclick',
					'value' => array(
						esc_html__( 'Open author page', 'total-theme-core' ) => 'author_page',
						esc_html__( 'Open user website', 'total-theme-core' ) => 'user_website',
						esc_html__( 'Disable', 'total-theme-core' ) => 'disable',
					),
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
					'admin_label' => true,
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
				// Style
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Content Style', 'total-theme-core' ),
					'param_name' => 'content_style',
					'choices' => vcex_entry_content_styles(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Content Alignment', 'total-theme-core' ),
					'param_name' => 'content_alignment',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Content Padding', 'total-theme-core' ),
					'param_name' => 'content_padding_all',
					'value' => vcex_padding_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Content Background', 'total-theme-core' ),
					'param_name' => 'content_background_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Content Border Style', 'total-theme-core' ),
					'param_name' => 'content_border_style',
					'value' => vcex_border_style_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Content Border Width', 'total' ),
					'param_name' => 'content_border_width',
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total-theme-core' ),
					'param_name' => 'content_border_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Query
				array(
					'type' => 'autocomplete',
					'heading' => esc_html__( 'User Roles', 'total-theme-core' ),
					'param_name' => 'role__in',
					'admin_label' => true,
					'std' => '',
					'settings' => array(
						'multiple' => true,
						'min_length' => 1,
						'groups' => false,
						'unique_values' => true,
						'display_inline' => true,
						'delay' => 0,
						'auto_focus' => true,
					),
					'admin_label' => true,
					'group' => esc_html__( 'Query', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order', 'total-theme-core' ),
					'param_name' => 'order',
					'group' => esc_html__( 'Query', 'total-theme-core' ),
					'value' => array(
						esc_html__( 'ASC', 'total-theme-core' ) => 'ASC',
						esc_html__( 'DESC', 'total-theme-core' ) => 'DESC',
					),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Order By', 'total-theme-core' ),
					'param_name' => 'orderby',
					'value' => array(
						esc_html__( 'Display Name', 'total-theme-core' ) => 'display_name',
						esc_html__( 'Nicename', 'total-theme-core' ) => 'nicename',
						esc_html__( 'Login', 'total-theme-core' ) => 'login',
						esc_html__( 'Registered', 'total-theme-core' ) => 'registered',
						'ID' => 'ID',
						esc_html__( 'Email', 'total-theme-core' ) => 'email',
					),
					'group' => esc_html__( 'Query', 'total-theme-core' ),
				),
				// Image
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Enable', 'total-theme-core' ),
					'param_name' => 'avatar',
					'group' => esc_html__( 'Avatar', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Size', 'total-theme-core' ),
					'param_name' => 'avatar_size',
					'std' => '150',
					'group' => esc_html__( 'Avatar', 'total-theme-core' ),
					'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
					'description' => esc_html__( 'Size of Gravatar to return (max is 512 for standard Gravatars)', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Meta Field', 'total-theme-core' ),
					'param_name' => 'avatar_meta_field',
					'std' => '',
					'group' => esc_html__( 'Avatar', 'total-theme-core' ),
					'dependency' => array( 'element' => 'avatar', 'value' => 'true' ),
					'description' => esc_html__( 'Enter the "ID" of a custom user meta field to pull the avatar from there instead of searching for the user\'s Gravatar', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Image Border Radius', 'total' ),
					'param_name' => 'avatar_border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Avatar', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_image_hovers',
					'heading' => esc_html__( 'CSS3 Image Hover', 'total-theme-core' ),
					'param_name' => 'avatar_hover_style',
					'group' => esc_html__( 'Avatar', 'total-theme-core' ),
				),
				// Name
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Enable', 'total-theme-core' ),
					'param_name' => 'name',
					'group' => esc_html__( 'Name', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Tag', 'total-theme-core' ),
					'param_name' => 'name_heading_tag',
					'choices' => 'html_tag',
					'std' => 'div',
					'group' => esc_html__( 'Name', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'name_color',
					'group' => esc_html__( 'Name', 'total-theme-core' ),
					'std' => '',
					'dependency' => array( 'element' => 'name', 'value' => 'true' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'name_font_family',
					'group' => esc_html__( 'Name', 'total-theme-core' ),
					'dependency' => array( 'element' => 'name', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'name_font_weight',
					'group' => esc_html__( 'Name', 'total-theme-core' ),
					'dependency' => array( 'element' => 'name', 'value' => 'true' ),
				),
				array(
					'type'  => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'name_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Name', 'total-theme-core' ),
					'dependency' => array( 'element' => 'name', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_text_transforms',
					'heading' => esc_html__( 'Text Transform', 'total-theme-core' ),
					'param_name' => 'name_text_transform',
					'group' => esc_html__( 'Name', 'total-theme-core' ),
					'dependency' => array( 'element' => 'name', 'value' => 'true' ),
				),
				array(
					'type'  => 'textfield',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'name_margin_bottom',
					'description' => vcex_shortcode_param_description( 'margin' ),
					'group' => esc_html__( 'Name', 'total-theme-core' ),
					'dependency' => array( 'element' => 'name', 'value' => 'true' ),
				),
				// Description
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Enable', 'total-theme-core' ),
					'param_name' => 'description',
					'group' => esc_html__( 'Description', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'description_color',
					'group' => esc_html__( 'Description', 'total-theme-core' ),
					'std' => '',
					'dependency' => array( 'element' => 'description', 'value' => 'true' ),
				),
				array(
					'type'  => 'vcex_font_family_select',
					'heading' => esc_html__( 'Font Family', 'total-theme-core' ),
					'param_name' => 'description_font_family',
					'group' => esc_html__( 'Description', 'total-theme-core' ),
					'dependency' => array( 'element' => 'description', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_font_weight',
					'heading' => esc_html__( 'Font Weight', 'total-theme-core' ),
					'param_name' => 'description_font_weight',
					'group' => esc_html__( 'Description', 'total-theme-core' ),
					'dependency' => array( 'element' => 'description', 'value' => 'true' ),
				),
				array(
					'type'  => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'description_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Description', 'total-theme-core' ),
					'dependency' => array( 'element' => 'description', 'value' => 'true' ),
				),
				// Social
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Enable', 'total-theme-core' ),
					'param_name' => 'social_links',
					'group' => esc_html__( 'Social', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_social_button_styles',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'social_links_style',
					'std' => get_theme_mod( 'staff_social_default_style', 'minimal-round' ),
					'group' => esc_html__( 'Social', 'total-theme-core' ),
					'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'social_links_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Social', 'total-theme-core' ),
					'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
				),
				array(
					'type' => 'vcex_trbl',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'social_links_padding',
					'description' => vcex_shortcode_param_description( 'padding' ),
					'group' => esc_html__( 'Social', 'total-theme-core' ),
					'dependency' => array( 'element' => 'social_links', 'value' => 'true' ),
				),
				// Design Options
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'entry_css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				// Deprecated
				array( 'type' => 'hidden', 'param_name' => 'link_to_author_page' ),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_users_grid' );

		}

	}

}
new VCEX_Users_Grid_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Users_Grid' ) ) {
	class WPBakeryShortCode_Vcex_Users_Grid extends WPBakeryShortCode {}
}