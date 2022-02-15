<?php
defined( 'ABSPATH' ) || exit;

/**
 * Contact Form Shortcode.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
if ( ! class_exists( 'VCEX_Contact_Form_Shortcode' ) ) {

	class VCEX_Contact_Form_Shortcode {

		/**
		 * Main constructor.
		 */
		public function __construct() {

			add_shortcode( 'vcex_contact_form', __CLASS__ . '::output' );

			if ( function_exists( 'vc_lean_map' ) ) {
				TotalThemeCore\WPBakery\Map\Vcex_Contact_Form::instance();
			}

			// Register element scripts.
			add_action( 'wp_enqueue_scripts', __CLASS__ . '::register_scripts' );

			// Ajax form submission.
			add_action( 'wp_ajax_vcex_contact_form_action', __CLASS__ . '::form_submission' );
			add_action( 'wp_ajax_nopriv_vcex_contact_form_action', __CLASS__ . '::form_submission' );

		}

		/**
		 * Register form scripts.
		 */
		public static function register_scripts() {

			wp_register_script(
				'vcex-contact-form',
				vcex_asset_url( 'js/shortcodes/vcex-contact-form.min.js' ),
				array(),
				'1.0',
				true
			);

			if ( function_exists( 'wpex_get_recaptcha_keys' ) ) {
				$site_key = wpex_get_recaptcha_keys( 'site' );
				if ( $site_key ) {
					wp_register_script(
						'recaptcha',
						esc_url( 'https://www.google.com/recaptcha/api.js?render=' . esc_attr( trim( $site_key ) ) )
					);
				}
			}

		}

		/**
		 * Shortcode output => Get template file and display shortcode.
		 */
		public static function output( $atts, $content = null ) {
			$atts = vcex_shortcode_atts( 'vcex_contact_form', $atts, get_class() );
			self::enqueue_scripts( $atts );
			return self::get_template( $atts );
		}

		/**
		 * Get shortcode template.
		 */
		public static function get_template( $atts ) {
			ob_start();
			do_action( 'vcex_shortcode_before', 'vcex_contact_form', $atts );
			include( vcex_get_shortcode_template( 'vcex_contact_form' ) );
			do_action( 'vcex_shortcode_after', 'vcex_contact_form', $atts );
			return ob_get_clean();
		}

		/**
		 * Enqueue form scripts.
		 */
		public static function enqueue_scripts( $atts ) {

			wp_enqueue_script( 'vcex-contact-form' );

			// Load captcha scripts (enabled by default).
			if ( empty( $atts['enable_recaptcha'] ) || 'true' === $atts['enable_recaptcha'] ) {
				wp_enqueue_script( 'recaptcha' );
			}

		}

		/**
		 * Array of shortcode parameters.
		 */
		public static function get_params() {

			$params = array(
				// General
				array(
					'type' => 'vcex_notice',
					'param_name' => 'editor_notice',
					'text' => esc_html__( 'Forms will be sent to the "Administration Email Address" as defined in WordPress under Settings > General.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Style', 'total' ),
					'param_name' => 'style',
					'value' => vcex_get_form_styles(),
					'description' => esc_html__( 'Select a preset form style or go to Appearance > Customize > General Theme Options > Forms where you can customize the design of all forms.', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Bottom Margin', 'total-theme-core' ),
					'param_name' => 'bottom_margin',
					'value' => vcex_margin_choices(),
					'admin_label' => true,
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Placeholders', 'total' ),
					'param_name' => 'enable_placeholders',
					'std' => 'false',
					'description' => esc_html__( 'Enable to display placeholders instead of labels.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Stack Fields', 'total' ),
					'param_name' => 'stack_fields',
					'std' => 'false',
					'description' => esc_html__( 'By default the name and email fields display side by side, enable this option to stack them vertically.', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Full Button', 'total' ),
					'param_name' => 'button_fullwidth',
					'std' => 'true',
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Privacy Checkbox', 'total' ),
					'param_name' => 'enable_privacy_check',
					'std' => 'true',
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'reCAPTCHA', 'total-theme-core' ),
					'param_name' => 'enable_recaptcha',
					'std' => 'true',
					'description' => sprintf( esc_html__( 'Enable Google reCAPTCHA to help prevent spam submissions. You will need to generate your site and secret keys %shere%s then enter these keys in the %sTheme Panel.%s', 'total-theme-core' ), '<a href="https://g.co/recaptcha/v3" target="_blank" rel="nofollow noopener noreferrer">', '</a>', '<a href="' . esc_url( admin_url( '?page=wpex-panel' ) ) . '" target="_blank" rel="nofollow noopener noreferrer">', '</a>' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Hide reCAPTCHA Badge', 'total-theme-core' ),
					'param_name' => 'enable_recaptcha_notice',
					'std' => 'false',
					'description' => sprintf( esc_html__( 'According to the %sreCAPTCHA guidelines%s, if you wish to hide the default reCAPTCHA badge you must include the reCAPTCHA branding visibly in the user flow. Enable this setting to display the reCAPTCHA branding after your form and hide the default badge.', 'total-theme-core' ), '<a href="https://developers.google.com/recaptcha/docs/faq#id-like-to-hide-the-recaptcha-badge.-what-is-allowed" target="_blank" rel="nofollow noopener noreferrer">', '</a>' ),
					'dependency' => array( 'element' => 'enable_recaptcha', 'value' => 'true' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Subject', 'total-theme-core' ),
					'param_name' => 'email_subject',
					'description' => esc_html__( 'Override the default form subject.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Message Rows', 'total-theme-core' ),
					'param_name' => 'message_rows',
					'description' => esc_html__( 'Number of rows for the message textarea.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Message Minlength', 'total-theme-core' ),
					'param_name' => 'message_minlength',
					'description' => esc_html__( 'Minimum length in characters for the message field.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Message Maxlength', 'total-theme-core' ),
					'param_name' => 'message_maxlength',
					'description' => esc_html__( 'Maximum length in characters for the message field.', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Extra class name', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'el_class' ),
					'param_name' => 'el_class',
				),
				array(
					'type' => 'vcex_responsive_sizes',
					'target' => 'font-size',
					'heading' => esc_html__( 'Font Size', 'total-theme-core' ),
					'description' => vcex_shortcode_param_description( 'font_size' ),
					'param_name' => 'font_size',
				),
				vcex_vc_map_add_css_animation(),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Duration', 'total'),
					'param_name' => 'animation_duration',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Animation Delay', 'total'),
					'param_name' => 'animation_delay',
					'description' => esc_html__( 'Enter your custom time in seconds (decimals allowed).', 'total' ),
				),
				// Labels
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Name Label', 'total-theme-core' ),
					'param_name' => 'label_name',
					'group' => esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Email Label', 'total-theme-core' ),
					'param_name' => 'label_email',
					'group' => esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Message Label', 'total-theme-core' ),
					'param_name' => 'label_message',
					'group' => esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Privacy Policy Label', 'total-theme-core' ),
					'param_name' => 'label_privacy',
					'group' => esc_html__( 'Labels', 'total-theme-core' ),
					'dependency' => array( 'element' => 'enable_privacy_check', 'value' => 'true' ),
					'description' => esc_html__( 'To create a link to your privacy page simply add double curly brackets around the text you want to link, example: {{Privacy Page}} and the element will automatically link to your privacy page as defined in the WordPress Settings > Privacy tab.', 'total' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Button Text', 'total-theme-core' ),
					'param_name' => 'button_text',
					'group' => esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Required Symbol?', 'total' ),
					'param_name' => 'enable_required_label',
					'std' => 'true',
					'group' => esc_html__( 'Labels', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Required Label', 'total' ),
					'param_name' => 'label_required',
					'dependency' => array( 'element' => 'enable_required_label', 'value' => 'true' ),
					'description' => esc_html__( 'Display a custom text instead of the default asterisk.', 'total-theme-core' ),
					'group' => esc_html__( 'Labels', 'total-theme-core' ),
				),
				// Notices
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Success Notice', 'total-theme-core' ),
					'param_name' => 'notice_success',
					'group' => esc_html__( 'Notices', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Error Notice', 'total-theme-core' ),
					'param_name' => 'notice_error',
					'group' => esc_html__( 'Notices', 'total-theme-core' ),
				),
				// CSS
				array(
					'type' => 'css_editor',
					'heading' => esc_html__( 'CSS box', 'total-theme-core' ),
					'param_name' => 'css',
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Width', 'total-theme-core' ),
					'param_name' => 'width',
					'description' => vcex_shortcode_param_description( 'width' ),
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Shadow', 'total' ),
					'param_name' => 'shadow',
					'value' => vcex_shadow_choices(),
					'group' => esc_html__( 'CSS', 'total-theme-core' ),
				),
			);

			return apply_filters( 'vcex_shortcode_params', $params, 'vcex_contact_form' );

		}

		/**
		 * Submit form.
		 */
		public static function form_submission() {

			check_ajax_referer( 'vcex-contact-form-nonce', 'nonce' ); // security check.

			$error = '';
			$status = 'error';
			$captcha_pass = false;

			// reCAPTCHA check
			if ( ! empty( $_POST['recaptcha'] ) && function_exists( 'wpex_get_recaptcha_keys' ) ) {

				$keys = wpex_get_recaptcha_keys();

				if ( empty( $keys['site'] ) || empty( $keys['secret'] ) ) {
					$captcha_pass = true; // no keys saved.
				} else {
					$recaptcha = wp_remote_get( 'https://www.google.com/recaptcha/api/siteverify?secret=' . esc_attr( trim( $keys['secret'] ) ) .'&response=' . esc_attr( $_POST['recaptcha'] ) );

					if ( empty( $recaptcha['body'] ) ) {
						$error = 'reCAPTCHA keys are most likely incorrect.';
					} else {

						$recaptcha = json_decode( $recaptcha['body'], false );

						// This is a human.
						if ( true == $recaptcha->success
							&& 0.5 <= $recaptcha->score
							&& 'vcex_contact_form' === $recaptcha->action
						) {
							$captcha_pass = true;
						}

						// Score less than 0.5 indicates suspicious activity. Return an error.
						else {
							$error = 'We don\'t take kindly to Bots around here.';
						}

					}

				}

			} else {
				$captcha_pass = true; // captcha is disabled.
			}

			if ( $captcha_pass ) {

				// If all required fields exist try and send the email.
				if ( empty( $_POST['name'] ) || empty( $_POST['email'] ) || empty( $_POST['message'] ) ) {
					$error = 'empty_fields';
				} else {
					$send_email = self::send_email( $_POST );
					if ( true === $send_email ) {
						$status = 'success';
					} else {
						$error = 'wp_mail error';
					}
				}

			}

			$response = $error ?: $status;

			header( "Content-Type: application/json" );
			echo json_encode( $response );
			wp_die();

		}

		/**
		 * Send the email.
		 */
		protected static function send_email( $data ) {

			if ( true === apply_filters( 'vcex_contact_form_demo_mode', false ) ) {
				return true;
			}

			if ( empty( $data ) || ! is_array( $data ) ) {
				return false;
			}

			$mail_to = sanitize_email( self::email_to_address() );

			if ( ! is_email( $mail_to ) ) {
				return false;
			}

			$mail_subject = ! empty( $data['subject'] ) ? wp_strip_all_tags( do_shortcode( $data['subject'] ) ) : sprintf( esc_html__( 'New contact form submission from %s', 'total' ), get_bloginfo( 'name' ) );

			$mail_body = '';

			if ( ! empty( $data['name'] ) ) {
				$label_name = ! empty( $data['label_name'] ) ? wp_strip_all_tags( $data['label_name'] ) : esc_html__( 'Your Name', 'total-theme-core' );
				$mail_body .= '<strong>' . esc_html( str_replace( ':', '', $label_name ) ) . '</strong>: ' . esc_html( $data['name'] ) . '<br>';
			}

			if ( ! empty( $data['email'] ) ) {
				$label_email = ! empty( $data['label_email'] ) ? wp_strip_all_tags( $data['label_email'] ) : esc_html__( 'Your Email', 'total-theme-core' );
				$mail_body .= '<strong>' . esc_html( str_replace( ':', '', $label_email ) ) . '</strong>: ' . esc_html( $data['email'] ) . '<br>';
			}

			if ( ! empty( $data['message'] ) ) {
				$label_message = ! empty( $data['label_message'] ) ? wp_strip_all_tags( $data['label_message'] ) : esc_html__( 'Message', 'total-theme-core' );
				$mail_body .= '<strong>' . esc_html( str_replace( ':', '', $label_message ) ) . '</strong>:<br />' . wpautop( wp_kses_post( stripslashes( $data['message'] ) ) );
			}

			$mail_body = apply_filters( 'vcex_contact_form_mail_body', $mail_body, $data );

			if ( $mail_to && $mail_body ) {
				$mail_headers = array( 'Content-Type: text/html; charset=UTF-8' );
				return wp_mail( $mail_to, $mail_subject, $mail_body, $mail_headers );
			}

		}

		/**
		 * Get the to email for the contact form.
		 */
		protected static function email_to_address() {
			return apply_filters( 'vcex_contact_form_mail_to_address', get_bloginfo( 'admin_email' ) );
		}

	}

}
new VCEX_Contact_Form_Shortcode;

if ( class_exists( 'WPBakeryShortCode' ) && ! class_exists( 'WPBakeryShortCode_Vcex_Contact_Form' ) ) {
	class WPBakeryShortCode_Vcex_Contact_Form extends WPBakeryShortCode {}
}