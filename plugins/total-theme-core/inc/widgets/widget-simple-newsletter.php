<?php
namespace TotalThemeCore\Widgets;
use TotalThemeCore\WidgetBuilder as Widget_Builder;

defined( 'ABSPATH' ) || exit;

/**
 * Minimal Newsletter widget.
 *
 * @package Total Theme Core
 * @subpackage Widgets
 * @version 1.2.8
 */
class Widget_Simple_Newsletter extends Widget_Builder {
	private $args;

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		$this->args = array(
			'id_base' => 'wpex_newsletter',
			'name'    => $this->branding() . esc_html__( 'Newsletter Form v2', 'total-theme-core' ),
			'options' => array(
				'customize_selective_refresh' => true,
				'description' => esc_html__( 'Single line newsletter form.', 'total-theme-core' ),
			),
			'fields'  => array(
				array(
					'id'    => 'title',
					'label' => esc_html__( 'Title', 'total-theme-core' ),
					'type'  => 'text',
				),
				array(
					'id'    => 'description',
					'label' => esc_html__( 'Description', 'total-theme-core' ),
					'type'  => 'textarea',
				),
				array(
					'id'          => 'form_action',
					'label'       => esc_html__( 'Form Action URL', 'total-theme-core' ),
					'type'        => 'text',
					'description' => '<a href="https://wpexplorer-themes.com/total/docs/mailchimp-form-action-url/" target="_blank">' . esc_html__( 'Learn more', 'total-theme-core' ) . '&rarr;</a>',
				),
				array(
					'id'      => 'placeholder_text',
					'label'   => esc_html__( 'Email Placeholder Text', 'total-theme-core' ),
					'type'    => 'text',
					'default' => esc_html__( 'Your email address', 'total-theme-core' ),
				),
				array(
					'id'          => 'input_name',
					'label'       => esc_html__( 'Email Input Attribute', 'total-theme-core' ),
					'type'        => 'text',
					'default'     =>'EMAIL',
					'description' => esc_html__( 'Used for the input name attribute value.', 'total-theme-core' ),
				),
				array(
					'id'      => 'button_text',
					'label'   => esc_html__( 'Button Text', 'total-theme-core' ),
					'type'    => 'text',
					'default' => esc_html__( 'Sign Up', 'total-theme-core' ),
				),
				array(
					'id'      => 'space_between',
					'label'   => esc_html__( 'Spacing Between Input & Button', 'total-theme-core' ),
					'type'    => 'select',
					'choices' => function_exists( 'wpex_utl_margins' ) ? wpex_utl_margins() : array(),
				),
			),
		);

		$this->create_widget( $this->args );

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 * @since 1.0
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		// Parse and extract widget settings.
		extract( $this->parse_instance( $instance ) );

		// Before widget hook.
		echo wp_kses_post( $args['before_widget'] );

		// Display widget title.
		$this->widget_title( $args, $instance );

		// Define html var.
		$html = '';

		// Display the description.
		if ( ! empty( $description ) ) {

			$html .= '<div class="wpex-newsletter-widget-description wpex-text-sm wpex-mb-15 wpex-last-mb-0">';

				$html .= wp_kses_post( trim( $description ) );

			$html .= '</div>';

		}

		// Sanitize args.
		$input_name = ! empty( $input_name ) ? $input_name : 'EMAIL';

		// Begin output.
		$html .= '<form action="'. esc_attr( $form_action ) .'" method="post" class="wpex-simple-newsletter wpex-flex wpex-w-100 wpex-justify-center validate">';

			$label_class = 'wpex-flex-grow';

			if ( ! empty( $space_between ) ) {
				$label_class .= ' wpex-mr-' . sanitize_html_class( absint( $space_between ) );
			}

			$html .= '<label class="' . esc_attr( $label_class ) . '">';

				$html .= '<span class="screen-reader-text">' . esc_html( $placeholder_text ) . '</span>';

				$html .= '<input type="email" name="' . esc_attr( $input_name ) . '" placeholder="' . esc_attr( $placeholder_text ) . '" autocomplete="off" class="wpex-p-10 wpex-w-100 wpex-bg-white wpex-p-10">';

			$html .= '</label>';

			// Extra fields.
			$html .= apply_filters( 'wpex_newsletter_widget_form_extras', null );

			// Submit button.
			if ( empty( $button_text ) ) {
				$button_text = esc_html__( 'Sign Up', 'total-theme-core' );
			}

			$html .= '<button type="submit" value="" name="subscribe" class="wpex-uppercase wpex-semibold wpex-text-center wpex-p-10 wpex-text-xs wpex-truncate">' . do_shortcode( wp_strip_all_tags( $button_text ) ) . '</button>';

		$html .= '</form>';

		// Echo output.
		echo $html;

		// After widget hook.
		echo wp_kses_post( $args['after_widget'] );

	}

}
register_widget( 'TotalThemeCore\Widgets\Widget_Simple_Newsletter' );