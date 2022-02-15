<?php
/**
 * Contact Form shortcode template.
 *
 * @package Total WordPress Theme
 * @subpackage Total Theme Core
 * @version 1.3.1
 */

defined( 'ABSPATH' ) || exit;

$unique_id = uniqid( 'vcex-contact-form__' );
$use_placeholders = ( 'true' === $atts['enable_placeholders'] ) ? true : false;
$hidden_label_class = $use_placeholders ? ' screen-reader-text' : '';

$output = '';

// Shortcode classes.
$shortcode_class = array(
	'vcex-contact-form',
);

if ( ! empty( $atts['style'] ) ) {

	if ( 'white' === $atts['style'] ) {
		$shortcode_class[] = 'light-form';
	} else {
		$shortcode_class[] = 'wpex-form-' . sanitize_html_class( $atts['style'] );
	}

}

if ( ! empty( $atts['width'] ) ) {
	$shortcode_class[] = 'wpex-mx-auto';
}

if ( $shadow_class = vcex_parse_shadow_class( $atts['shadow'] ) ) {
	$shortcode_class[] = $shadow_class;
}

$extra_classes = vcex_get_shortcode_extra_classes( $atts, 'vcex_contact_form' );

if ( $extra_classes ) {
	$shortcode_class = array_merge( $shortcode_class, $extra_classes );
}

// Responsive styles.
$unique_classname = vcex_element_unique_classname();

$el_responsive_styles = array(
	'font_size' => $atts['font_size'],
);

$responsive_css = vcex_element_responsive_css( $el_responsive_styles, $unique_classname );

if ( $responsive_css ) {
	$shortcode_class[] = $unique_classname;
	$output .= '<style>' . $responsive_css . '</style>';
}

// Parse shortcode classes.
$shortcode_class = vcex_parse_shortcode_classes( $shortcode_class, 'vcex_contact_form', $atts );

// Inline styles.
$shortcode_style = vcex_inline_style( array(
	'font_size'          => $atts['font_size'],
	'animation_delay'    => $atts['animation_delay'],
	'animation_duration' => $atts['animation_duration'],
	'max_width'          => $atts['width'],
) );

// Shortcode data.
$shortcode_data = 'data-ajaxurl="' . esc_attr( set_url_scheme( admin_url( 'admin-ajax.php' ) ) ) . '"';

// Notices
$notice_success = ! empty( $atts['notice_success'] ) ? wp_strip_all_tags( $atts['notice_success'] ) : esc_html__( 'Thank you for the message. We will respond as soon as possible.', 'total' );
$shortcode_data .= ' data-notice-success="' . esc_attr( do_shortcode( $notice_success ) ) . '"';

$notice_error = ! empty( $atts['notice_error'] ) ? wp_strip_all_tags( $atts['notice_error'] ) : esc_html__( 'Some errors occurred.', 'total' );
$shortcode_data .= ' data-notice-error="' . esc_attr( do_shortcode( $notice_error ) ) . '"';

// Custom to subject.
if ( ! empty( $atts['email_subject'] ) ) {
	$shortcode_data .= ' data-subject="' . esc_attr( do_shortcode( $atts['email_subject'] ) ) . '"';
}

// Check if reCAPTCHA is enabled.
if ( 'true' === $atts['enable_recaptcha'] && function_exists( 'wpex_get_recaptcha_keys' ) ) {
	$site_key = wpex_get_recaptcha_keys( 'site' );
	$shortcode_data .= ' data-recaptcha="' . esc_attr( $site_key ) . '"';
}

// Security Nonce.
$shortcode_data .= ' data-nonce="' . esc_attr( wp_create_nonce( 'vcex-contact-form-nonce' ) ) . '"';

// Required labels.
$show_required_label = ( 'true' === $atts['enable_required_label'] );
$placeholder_required = ! empty( $atts['label_required'] ) ? ' ' . wp_strip_all_tags( trim( $atts['label_required'] ) ) : '*';

// Begin output.
$output .= '<div class="' . esc_attr( $shortcode_class ) . '"' . $shortcode_style . ' ' . $shortcode_data . '>';

	$output .= '<form class="vcex-contact-form__form">';

		// Fields.
		$fields_class = 'vcex-contact-form__fields wpex-mb-15';
		if ( 'true' === $atts['stack_fields'] ) {
			$fields_class .= ' wpex-flex wpex-flex-col wpex-gap-15';
		} else {
			$fields_class .= ' wpex-flex wpex-gap-20 wpex-flex-wrap';
		}
		$output .= '<div class="' . esc_attr( $fields_class ) . '">';

			// Name.
			$field_id = $unique_id . '-name';
			$label = ! empty( $atts['label_name'] ) ? $atts['label_name'] : esc_html__( 'Your Name', 'total-theme-core' );
			$placeholder = $show_required_label ? $label . $placeholder_required : $label;
			$placeholder = $use_placeholders ? ' placeholder="' . esc_attr( $placeholder ) . '"' : '';

			$output .= '<div class="vcex-contact-form__name wpex-flex-grow">';
				$output .= '<label class="vcex-contact-form__label wpex-block wpex-font-semibold wpex-mb-5' . $hidden_label_class . '" for="' . esc_attr( $field_id ) . '">' . esc_html( $label );
					if ( $show_required_label ) {
						if ( ! empty( $atts['label_required'] ) ) {
							$output .= ' <span class="vcex-contat-form__required">' . esc_html( trim( $atts['label_required'] ) ) . '</span>';
						} else {
							$output .= '<sup class="vcex-contat-form__required">*</sup>';
						}
					}
				$output .= '</label>';
				$output .= '<input class="vcex-contact-form__input" type="text" id="' . esc_attr( $field_id ) . '" name="vcex_cf_name" required' . $placeholder . '>';
			$output .= '</div>';

			// Email.
			$field_id = $unique_id . '-email';
			$label = ! empty( $atts['label_email'] ) ? $atts['label_email'] : esc_html__( 'Your Email', 'total-theme-core' );
			$placeholder = $show_required_label ? $label . $placeholder_required : $label;
			$placeholder = $use_placeholders ? ' placeholder="' . esc_attr( $placeholder ) . '"' : '';

			$output .= '<div class="vcex-contact-form__email wpex-flex-grow">';
				$output .= '<label class="vcex-contact-form__label wpex-block wpex-font-semibold wpex-mb-5' . $hidden_label_class . '" for="' . esc_attr( $field_id ) . '">' . esc_html( $label );
					if ( $show_required_label ) {
						if ( ! empty( $atts['label_required'] ) ) {
							$output .= ' <span class="vcex-contat-form__required">' . esc_html( trim( $atts['label_required'] ) ) . '</span>';
						} else {
							$output .= '<sup class="vcex-contat-form__required">*</sup>';
						}
					}
				$output .= '</label>';
				$output .= '<input class="vcex-contact-form__input" type="email" id="' . esc_attr( $field_id ) . '" name="vcex_cf_email" required' . $placeholder . '>';
			$output .= '</div>';

		$output .= '</div>';

		// Message.
		$field_id = $unique_id . '-message';
		$label = ! empty( $atts['label_message'] ) ? $atts['label_message'] : esc_html__( 'Message', 'total-theme-core' );
		$placeholder = $use_placeholders ? ' placeholder="' . esc_attr( $label ) . '"' : '';
		$rows = is_numeric( $atts['message_rows'] ) ? absint( $atts['message_rows'] ) : 8;
		$minlength = is_numeric( $atts['message_minlength'] ) ? ' minlength="' . esc_attr( absint( $atts['message_minlength'] ) ) . '"' : '';
		$maxlength = is_numeric( $atts['message_maxlength'] ) ? ' maxlength="' . esc_attr( absint( $atts['message_maxlength'] ) ) . '"' : '';

		$output .= '<div class="vcex-contact-form__message wpex-mb-15">';
			$output .= '<label class="vcex-contact-form__label wpex-block wpex-font-semibold wpex-mb-5' . $hidden_label_class . '" for="' . esc_attr( $field_id ) . '">' . esc_html( $label ) . '</label>';
			$output .= '<textarea rows="' . esc_attr( $rows ) . '" class="vcex-contact-form__textarea wpex-align-top" id="' . esc_attr( $field_id ) . '" name="vcex_cf_message" required' . $placeholder . $minlength . $maxlength . '></textarea>';
		$output .= '</div>';

		// Privacy policy.
		if ( 'true' === $atts['enable_privacy_check'] ) {
			$privacy_policy_page = get_option( 'wp_page_for_privacy_policy' );
			$privacy_policy_url = $privacy_policy_page ? get_permalink( $privacy_policy_page ) : '#';
			$field_id = $unique_id . '-privacy';
			if ( ! empty( $atts['label_privacy'] ) ) {
				$label = $atts['label_privacy'];
				$label = str_replace( '{{', '<a href="' . esc_url( $privacy_policy_url ) . '" target="_blank" rel="noopener noreferrer">', $label );
				$label = str_replace( '}}', '</a>', $label );
			} else {
				$label = sprintf( esc_html__( 'I agree with the %sPrivacy Policy%s.', 'total-theme-core' ), '<a href="' . esc_url( $privacy_policy_url ) . '" target="_blank" rel="noopener noreferrer">', '</a>' );
			}

			$output .= '<div class="vcex-contact-form__privacy wpex-flex wpex-items-center wpex-gap-5 wpex-mb-15"><input type="checkbox" class="vcex-contact-form__checkbox" id="' . esc_attr( $field_id ) . '" name="vcex_cf_privacy" required>';

			$label_class = 'vcex-contact-form__label wpex-block';
			if ( ! $use_placeholders ) {
				$label_class .= ' wpex-font-semibold';
			}
			$output .= '<label class="' . esc_attr( $label_class ) . '" for="' . esc_attr( $field_id ) . '">' . wp_kses_post( do_shortcode( $label ) ) . '</label></div>';
		}

		// Button.
		$button_text = ! empty( $atts['button_text'] ) ? $atts['button_text'] : esc_html__( 'Contact', 'total-theme-core' );
		$button_fullwidth = ( 'true' === $atts['button_fullwidth'] ) ? ' vcex-contact-form__submit--full' : '';
		$output .= '<button class="vcex-contact-form__submit' . esc_attr( $button_fullwidth ) . '">' . esc_html( $button_text ) . '</button>';

		// reCAPTCHA branding.
		if ( 'true' == $atts['enable_recaptcha']
			&& 'true' == $atts['enable_recaptcha_notice']
			&& function_exists( 'wpex_get_recaptcha_keys' )
		) {

			$recaptcha_keys = wpex_get_recaptcha_keys();

			if ( ! empty( $recaptcha_keys['site'] ) && ! empty( $recaptcha_keys['secret'] ) ) {

				$output .= '<style>.grecaptcha-badge { visibility: hidden; }</style>';

				$recaptcha_notice = 'This site is protected by reCAPTCHA and the Google <a href="https://policies.google.com/privacy">Privacy Policy</a> and <a href="https://policies.google.com/terms">Terms of Service</a> apply.';

				/**
				 * Filters the reCAPTCHA notice text.
				 *
				 * @link https://developers.google.com/recaptcha/docs/faq
				 * @param string $recaptcha_notice
				 */
				$recaptcha_notice = apply_filters( 'vcex_contact_form_recaptcha_notice', $recaptcha_notice );

				$output .= '<div class="vcex-contact-form__recaptcha wpex-mt-15 wpex-text-sm">' . wp_kses_post( $recaptcha_notice ) . '</div>';

			}
		}

		// Spinner.
		$output .= '<div class="vcex-contact-form__spinner wpex-mt-15 wpex-hidden">';
			if ( function_exists( 'wpex_get_svg' ) ) {
				$output .= wpex_get_svg( 'wp-spinner', 20 );
			} else {
				$output .= '<span class="ticon ticon-spinner ticon-spin" aria-hidden="true"></span>';
			}
		$output .= '</div>';

		// Notices.
		$output .= '<div class="vcex-contact-form__notice wpex-hidden wpex-alert wpex-mt-15 wpex-mb-0"></div>';

	$output .= '</form>';

$output .= '</div>';

echo $output; // @codingStandardsIgnoreLine