<?php
/**
 * Login Form Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'VCEX_Login_Form' ) ) {

	class VCEX_Login_Form {

		/**
		 * Main constructor.
		 */
		public function __construct() {
			add_shortcode( 'vcex_login_form', array( $this, 'output' ) );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Login_Form::instance();
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public function output( $atts, $content = null ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_login_form', $atts );
			include( vcex_get_shortcode_template( 'vcex_login_form' ) );
			do_action( 'vcex_shortcode_after', 'vcex_login_form', $atts );
			return ob_get_clean();
		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// Logged In Content
				array(
					'type' => 'textarea_html',
					'heading' => esc_html__( 'Logged in Content', 'total-theme-core' ),
					'param_name' => 'content',
					'value' => esc_html__( 'You are currently logged in.', 'total-theme-core' ) .' ' . '<a href="' . esc_url( wp_logout_url( home_url() ) ) . '">' . esc_html__( 'Logout?', 'total-theme-core' ) . '</a>',
					'description' => esc_html__( 'The content to displayed for logged in users.','total-theme-core'),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Redirect', 'total-theme-core' ),
					'param_name' => 'redirect',
					'description' => esc_html__( 'Enter a URL to redirect the user after they successfully log in. Leave blank to redirect to the current page.','total-theme-core'),
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
				// Style
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Module Style', 'total-theme-core' ),
					'param_name' => 'style',
					'value' => array(
						esc_html__( 'Default', 'total-theme-core' ) => '',
						esc_html__( 'Plain', 'total-theme-core' ) => 'plain',
						esc_html__( 'Bordered', 'total-theme-core' ) => 'bordered',
						esc_html__( 'Boxed', 'total-theme-core' ) => 'boxed',
					),
					'group' =>  esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Form Style', 'total-theme-core' ),
					'param_name' => 'form_style',
					'std' => '',
					'value' => vcex_get_form_styles(),
					'group' =>  esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
					'group' =>  esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'float',
					'std' => 'center',
					'exclude_choices' => array( '', 'default' ),
					'dependency' => array( 'element' => 'width', 'not_empty' => true ),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Background Color', 'total' ),
					'param_name' => 'background_color',
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Padding', 'total-theme-core' ),
					'param_name' => 'padding_all',
					'value' => vcex_padding_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Radius', 'total' ),
					'param_name' => 'border_radius',
					'value' => vcex_border_radius_choices(),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Style', 'total-theme-core' ),
					'param_name' => 'border_style',
					'value' => vcex_border_style_choices(),
					'group' => esc_html__( 'Style', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Border Width', 'total' ),
					'param_name' => 'border_width',
					'value' => vcex_border_width_choices(),
					'group' => esc_html__( 'Style', 'total' ),
				),
				array(
					'type' => 'vcex_colorpicker',
					'heading' => esc_html__( 'Border Color', 'total' ),
					'param_name' => 'border_color',
					'group' => esc_html__( 'Style', 'total' ),
				),
				// Fields
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Remember Me', 'total-theme-core' ),
					'param_name' => 'remember',
					'group' =>  esc_html__( 'Fields', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Lost Password', 'total-theme-core' ),
					'param_name' => 'lost_password',
					'group' =>  esc_html__( 'Fields', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'std' => 'true',
					'heading' => esc_html__( 'Register', 'total-theme-core' ),
					'param_name' => 'register',
					'group' =>  esc_html__( 'Fields', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Register URL', 'total-theme-core' ),
					'param_name' => 'register_url',
					'dependency' => array( 'element' => 'register', 'value' => 'true' ),
					'group' =>  esc_html__( 'Fields', 'total-theme-core' ),
				),
				// Labels
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Username Label', 'total-theme-core' ),
					'param_name' => 'label_username',
					'group' =>  esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Password Label', 'total-theme-core' ),
					'param_name' => 'label_password',
					'group' =>  esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Remember Me Label', 'total-theme-core' ),
					'param_name' => 'label_remember',
					'group' =>  esc_html__( 'Labels', 'total-theme-core' ),
					'dependency' => array( 'element' => 'remember', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Lost Password Label', 'total-theme-core' ),
					'param_name' => 'lost_password_label',
					'dependency' => array( 'element' => 'lost_password', 'value' => 'true' ),
					'group' =>  esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Register Label', 'total-theme-core' ),
					'param_name' => 'register_label',
					'dependency' => array( 'element' => 'register', 'value' => 'true' ),
					'group' =>  esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Button Label', 'total-theme-core' ),
					'param_name' => 'label_log_in',
					'group' =>  esc_html__( 'Labels', 'total-theme-core' ),
				),
				// Typography
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'param_name' => 'text_font_size',
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'group' => esc_html__( 'Typography', 'total-theme-core' ),
				),
				array(
					'type' => 'colorpicker',
					'heading' => esc_html__( 'Color', 'total-theme-core' ),
					'param_name' => 'text_color',
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

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_login_form' );

		}

	}

}
new VCEX_Login_Form;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Login_Form' ) ) {
	class WPBakeryShortCode_Vcex_Login_Form extends WPBakeryShortCode {}
}