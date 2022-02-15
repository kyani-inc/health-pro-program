<?php
/**
 * Post Meta Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Post_Meta_Shortcode' ) ) {

	class VCEX_Post_Meta_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_post_meta', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Post_Meta::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_post_meta', $atts );
			include( vcex_get_shortcode_template( 'vcex_post_meta' ) );
			do_action( 'vcex_shortcode_after', 'vcex_post_meta', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// Sections
				array(
					'type' => 'param_group',
					'param_name' => 'sections',
					'value' => urlencode( json_encode( array(
						array(
							'type' => 'date',
							'icon' => 'ticon ticon-clock-o',
						),
						array(
							'type' => 'author',
							'icon' => 'ticon ticon-user-o',
						),
						array(
							'type' => 'comments',
							'icon' => 'ticon ticon-comment-o',
						),
						array(
							'type' => 'post_terms',
							'taxonomy' => 'category',
							'fist_only' => 'false',
							'icon' => 'ticon ticon-folder-o',
						),
					) ) ),
					'params' => array(
						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Section', 'total-theme-core' ),
							'param_name' => 'type',
							'admin_label' => true,
							'value' => apply_filters( 'vcex_post_meta_sections', array(
								esc_html__( 'Date', 'total-theme-core' ) => 'date',
								esc_html__( 'Author', 'total-theme-core' ) => 'author',
								esc_html__( 'Author Avatar + Name', 'total-theme-core' ) => 'author_w_avatar',
								esc_html__( 'Comments', 'total-theme-core' ) => 'comments',
								esc_html__( 'Post Terms', 'total-theme-core' ) => 'post_terms',
								esc_html__( 'Last Updated', 'total-theme-core' ) => 'modified_date',
								esc_html__( 'Callback Function', 'total-theme-core' ) => 'callback',
							) ),
						),
						// Label
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Label', 'total-theme-core' ),
							'param_name' => 'label',
						),
						// Taxonomy Select
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Taxonony Name', 'total-theme-core' ),
							'param_name' => 'taxonomy',
							'dependency' => array( 'element' => 'type', 'value' => 'post_terms' )
						),
						// Date Format
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Date Format', 'total-theme-core' ),
							'param_name' => 'date_format',
							'dependency' => array( 'element' => 'type', 'value' => array( 'date', 'last_modified' ) ),
							'description' => sprintf( esc_html__( 'Enter your preferred date format according to the %sWordPress manual%s.', 'total-theme-core' ), '<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank" rel="noopener noreferrer">', '</a>' ),
						),
						// Callback Function
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Callback Function', 'total-theme-core' ),
							'param_name' => 'callback_function',
							'dependency' => array( 'element' => 'type', 'value' => 'callback' )
						),
						// Avatar size
						array(
							'type' => 'textfield',
							'heading' => esc_html__( 'Avatar Size', 'total-theme-core' ),
							'param_name' => 'avatar_size',
							'dependency' => array( 'element' => 'type', 'value' => 'author_w_avatar' ),
							'description' => esc_html__( 'Default', 'total-theme-core' ) . ': 25',
						),
						// Has link section option
						array(
							'type' => 'vcex_ofswitch',
							'std' => 'false',
							'heading' => esc_html__( 'Enable Link', 'total-theme-core' ),
							'param_name' => 'has_link',
							'dependency' => array( 'element' => 'type', 'value' => 'comments' ),
						),
						// Icon select
						array(
							'type' => 'dropdown',
							'heading' => esc_html__( 'Icon library', 'total-theme-core' ),
							'param_name' => 'icon_type',
							'description' => esc_html__( 'Select icon library.', 'total-theme-core' ),
							'dependency' => array( 'element' => 'type', 'value_not_equal_to' => 'author_w_avatar' ),
							'value' => array(
								esc_html__( 'Theme Icons', 'total-theme-core' )  => '',
								esc_html__( 'Font Awesome', 'total-theme-core' ) => 'fontawesome',
								esc_html__( 'Typicons', 'total-theme-core' )     => 'typicons',
							),
						),
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'total-theme-core' ),
							'param_name' => 'icon',
							'settings' => array( 'emptyIcon' => true, 'type' => 'ticons', 'iconsPerPage' => 100 ),
							'dependency' => array( 'element' => 'icon_type', 'is_empty' => true ),
						),
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'total-theme-core' ),
							'param_name' => 'icon_fontawesome',
							'settings' => array( 'emptyIcon' => true, 'iconsPerPage' => 100 ),
							'dependency' => array( 'element' => 'icon_type', 'value' => 'fontawesome' ),
						),
						array(
							'type' => 'iconpicker',
							'heading' => esc_html__( 'Icon', 'total-theme-core' ),
							'param_name' => 'icon_typicons',
							'settings' => array( 'emptyIcon' => true, 'type' => 'typicons', 'iconsPerPage' => 100 ),
							'dependency' => array( 'element' => 'icon_type', 'value' => 'typicons' ),
						),
					),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Label Colon', 'total-theme-core' ),
					'param_name' => 'label_colon',
					'description' => esc_html__( 'Add a colon automatically after the custom labels.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total-theme-core' ),
					'param_name' => 'visibility',
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'param_name' => 'el_class',
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'total-theme-core' ),
				),
				vcex_vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Duration', 'total' ),
					'param_name' => 'animation_duration',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Delay', 'total' ),
					'param_name' => 'animation_delay',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
				),
				// Style
				array(
					'type' => 'vcex_select_buttons',
					'heading' => esc_html__( 'Style', 'total-theme-core' ),
					'param_name' => 'style',
					'choices' => array(
						'' => esc_html__( 'Default', 'total-theme-core' ),
						'vertical' => esc_html__( 'Vertical', 'total-theme-core' ),
					),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Separator', 'total-theme-core' ),
					'param_name' => 'separator',
					'value' => array(
						esc_html__( 'Empty Space', 'total-theme-core' ) => 'empty_space',
						esc_html__( 'Dot', 'total-theme-core' ) => 'dot',
						esc_html__( 'Dash', 'total-theme-core' ) => 'dash',
						esc_html__( 'Forward Slash', 'total-theme-core' ) => 'forward_slash',
						esc_html__( 'Backslash', 'total-theme-core' ) => 'backslash',
						esc_html__( 'Pipe', 'total-theme-core' ) => 'pipe',
					),
					'dependency' => array( 'element' => 'style', 'value_not_equal_to' => 'vertical' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin', // can't name it margin_bottom due to WPBakery parsing issue
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Max Width', 'total-theme-core' ),
					'param_name' => 'max_width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'float', // can't use "align" because it was already taken for the text align.
					'dependency' => array( 'element' => 'max_width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				// Typography
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Text Align', 'total-theme-core' ),
					'param_name' => 'align',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'font_size',
					'description' => vcex_shortcode_param_description( 'line_height' ),
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
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'color',
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_post_meta' );

		}

	}

}
new VCEX_Post_Meta_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Post_Meta' ) ) {
	class WPBakeryShortCode_Vcex_Post_Meta extends WPBakeryShortCode {}
}