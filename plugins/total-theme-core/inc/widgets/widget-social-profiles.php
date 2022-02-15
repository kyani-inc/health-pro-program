<?php
namespace TotalThemeCore;
use \WP_Widget;

defined( 'ABSPATH' ) || exit;

/**
 * Social Profiles Widget
 *
 * @package Total Theme Core
 * @subpackage Widgets
 * @version 1.3.1
 */
class Widget_Social_Profiles extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		if ( function_exists( 'wpex_get_theme_branding' ) ) {
			$branding = wpex_get_theme_branding();
			$branding = $branding ? $branding . ' - ' : '';
		} else {
			$branding = 'Total - ';
		}

		parent::__construct(
			'wpex_fontawesome_social_widget',
			$branding . esc_html__( 'Social Links', 'total-theme-core' ),
			array(
				'description' => esc_html__( 'Displays your social profile links via retina ready font icons with many different styles to choose from (recommended). ', 'total-theme-core' ),
				'customize_selective_refresh' => true,
			)
		);

	}

	/**
	 * Returns social options.
	 *
	 * @since 1.0.0
	 */
	public function get_social_options() {
		return apply_filters( 'wpex_social_widget_profiles', array(
			'twitter' => array(
				'name' => 'Twitter',
				'url'  => '',
			),
			'facebook' => array(
				'name' => 'Facebook',
				'url'  => '',
			),
			'instagram' => array(
				'name' => 'Instagram',
				'url'  => '',
			),
			'linkedin' => array(
				'name' => 'LinkedIn',
				'url'  => '',
			),
			'etsy' => array(
				'name' => 'Etsy',
				'url'  => '',
			),
			'discord' => array(
				'name' => 'Discord',
				'url'  => '',
			),
			'pinterest' => array(
				'name' => 'Pinterest',
				'url'  => '',
			),
			'yelp' => array(
				'name' => 'Yelp',
				'url'  => '',
			),
			'tripadvisor' => array(
				'name' => 'Tripadvisor',
				'url'  => '',
			),
			'dribbble' => array(
				'name' => 'Dribbble',
				'url'  => '',
			),
			'flickr' => array(
				'name' => 'Flickr',
				'url'  => '',
			),
			'vk' => array(
				'name' => 'VK',
				'url'  => '',
			),
			'github' => array(
				'name' => 'GitHub',
				'url'  => '',
			),
			'tumblr' => array(
				'name' => 'Tumblr',
				'url'  => '',
			),
			'skype' => array(
				'name' => 'Skype',
				'url'  => '',
			),
			'whatsapp' => array(
				'name' => 'Whatsapp',
				'url' => '',
			),
			'trello' => array(
				'name' => 'Trello',
				'url'  => '',
			),
			'foursquare' => array(
				'name' => 'Foursquare',
				'url'  => '',
			),
			'renren' => array(
				'name' => 'RenRen',
				'url'  => '',
			),
			'xing' => array(
				'name' => 'Xing',
				'url'  => '',
			),
			'vimeo-square' => array(
				'name' => 'Vimeo',
				'url'  => '',
			),
			'youtube' => array(
				'name' => 'Youtube',
				'url'  => '',
			),
			'tiktok' => array(
				'name' => 'Tiktok',
				'url'  => '',
			),
			'twitch' => array(
				'name' => 'Twitch',
				'url'  => '',
			),
			'houzz' => array(
				'name' => 'Houzz',
				'url' => '',
			),
			'spotify' => array(
				'name' => 'Spotify',
				'url' => '',
			),
			'rss' => array(
				'name' => 'RSS',
				'url'  => '',
			),
		) );
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 * @since 1.0.0
	 *
	 *
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		// Get social services.
		$social_services = isset( $instance['social_services'] ) ? $instance['social_services'] : '';

		// Return if no services defined.
		if ( ! $social_services ) {
			return;
		}

		// Define vars.
		$output        = '';
		$title         = isset( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : '';
		$description   = isset( $instance['description'] ) ? $instance['description'] : '';
		$style         = isset( $instance['style'] ) ? $instance['style'] : '';
		$type          = isset( $instance['type'] ) ? $instance['type'] : '';
		$target        = isset( $instance['target'] ) ? $instance['target'] : '';
		$size          = isset( $instance['size'] ) ? intval( $instance['size'] ) : '';
		$font_size     = isset( $instance['font_size'] ) ? $instance['font_size'] : '';
		$border_radius = isset( $instance['border_radius'] ) ? $instance['border_radius'] : '';
		$align         = isset( $instance['align'] ) ? $instance['align'] : '';
		$nofollow      = isset( $instance['nofollow'] ) ? $instance['nofollow'] : false;
		$expand        = isset( $instance['expand'] ) ? $instance['expand'] : false;
		$space_between = ! empty( $instance['space_between'] ) ? absint( $instance['space_between'] ) : '5';

		// Parse style.
		$style = $this->parse_style( $style, $type ); // Fallback for OLD styles pre-1.0.0

		// Sanitize vars.
		$size          = ttc_sanitize_data( $size, 'px' );
		$font_size     = ttc_sanitize_data( $font_size, 'font_size' );
		$border_radius = ttc_sanitize_data( $border_radius, 'border_radius' );

		// Wrapper style.
		$ul_style = '';
		if ( $font_size ) {
			$ul_style .= ' style="font-size:' . esc_attr( $font_size ) . ';"';
		}

		// Inline style.
		$add_style = '';
		if ( $size ) {
			$add_style .= 'height:' . esc_attr( $size ) . ';';
			if ( ! $expand ) {
				$add_style .= 'width:' . esc_attr( $size ) . ';';
			}
			$add_style .= 'line-height:' . esc_attr( $size ) . ';';
		}
		if ( $border_radius ) {
			$add_style .= 'border-radius:' . esc_attr( $border_radius ) . ';';
		}
		if ( $add_style ) {
			$add_style = $add_style;
		}

		// Before widget hook.
		$output .= $args['before_widget'];

		// Display title.
		if ( $title ) {
			$output .= $args['before_title'] . $title . $args['after_title'];
		}

		// Get align class.
		$align = ( $align && in_array( $align, array( 'left', 'center', 'right' ) ) ) ? ' text' . $align : '';

		// Begin widget output.
		$output .= '<div class="wpex-fa-social-widget' . esc_attr( $align ) . '">';

			// Description.
			if ( $description ) :

				$output .= '<div class="desc wpex-last-mb-0 wpex-mb-20 wpex-clr">';

					$output .= wp_kses_post( $description );

				$output .= '</div>';

			endif;

			$ul_class = 'wpex-list-none wpex-m-0 wpex-last-mr-0 wpex-text-md';

			if ( $expand ) {
				$ul_class .= ' wpex-flex';
			}

			$output .= '<ul class="' . esc_attr( $ul_class ) . '" ' . $ul_style . '>';

				// Original Array.
				$get_social_options = $this->get_social_options();

				// Loop through each item in the array.
				foreach( $social_services as $key => $val ) :

					$link = ! empty( $social_services[$key]['url'] ) ? do_shortcode( $social_services[$key]['url'] ) : null;

					if ( $link ) {

						if ( empty( $get_social_options[$key] ) ) {
							continue;
						}

						$name     = $get_social_options[$key]['name'];
						$nofollow = ( $nofollow || isset( $get_social_options[$key]['nofollow'] ) ) ? 'nofollow' : '';

						$a_attrs = array(
							'href'   => esc_url( $link ),
							'title'  => $name,
							'class'  => 'wpex-' . sanitize_html_class( $key ),
							'rel'    => $nofollow,
							'target' => $target,
							'style'  => $add_style,
						);

						if ( function_exists( 'wpex_get_social_button_class' ) ) {
							$a_attrs['class'] .= ' ' . esc_attr( wpex_get_social_button_class( $style ) );
						}

						$key = 'vimeo-square' == $key ? 'vimeo' : $key;

						$li_class = 'wpex-inline-block wpex-mb-' . $space_between . ' wpex-mr-' . $space_between;

						if ( $expand ) {
							$li_class .= ' wpex-flex-grow';
							$a_attrs['class'] .= ' wpex-w-100';
						}

						$output .= '<li class="' . esc_attr ( $li_class ) . '">';

							$output .= '<a';

								if ( function_exists( 'wpex_parse_attrs' ) ) {
									$output .= ' ' . wpex_parse_attrs( $a_attrs );
								} else {
									foreach ( $a_attrs as $attr_k => $attr_v ) {
										$output .= ' ' . $attr_k . '=' . '"' . esc_attr( $attr_v ) . '"';
									}
								}

							$output .= '>';

								$output .= '<span class="' . esc_attr( $this->get_icon_class( $key ) ) . '" aria-hidden="true"></span>';

								if ( ! defined( 'TOTAL_THEME_ACTIVE' ) ) {
									$output .= '<span class="ttc-social-widget-label">' . esc_html( $name ) . '</span>';
								} else {
									$output .= '<span class="screen-reader-text">' . esc_html( $name ) . '</span>';
								}

							$output .= '</a>';

						$output .= '</li>';

					}

				endforeach;

			$output .= '</ul>';

		$output .= '</div>';

		// After widget hook.
		$output .= $args['after_widget'];

		// Echo output.
		echo $output;

	}

	/**
	 * Return icon class based on profile.
	 *
	 * @since 1.3
	 */
	public function get_icon_class( $profile = '' ) {
		switch ( $profile ) {
			case 'youtube':
				$profile = 'youtube-play';
				break;
			case 'bloglovin':
				$profile = 'heart';
			case 'vimeo-square':
				$profile = 'vimeo';
				break;
		}
		return 'ticon ticon-' . sanitize_html_class( $profile );
	}

	/**
	 * Parses style attribute for fallback styles.
	 *
	 * @since 1.0.0
	 */
	public function parse_style( $style = '', $type = '' ) {
		if ( 'color' === $style && 'flat' === $type ) {
			return 'flat-color';
		} elseif ( 'color' === $style && 'graphical' === $type ) {
			return 'graphical-rounded';
		} elseif ( 'black' === $style && 'flat' === $type ) {
			return 'black-rounded';
		} elseif ( 'black' === $style && 'graphical' === $type ) {
			return 'black-rounded';
		} elseif ( 'black-color-hover' === $style && 'flat' === $type ) {
			return 'black-ch-rounded';
		} elseif ( 'black-color-hover' === $style && 'graphical' === $type ) {
			return 'black-ch-rounded';
		}
		return $style;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 * @since 1.0.0
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new, $old ) {
		$instance = $old;
		$instance['title']           = ! empty( $new['title'] ) ? wp_strip_all_tags( $new['title'] ) : null;
		$instance['description']     = ! empty( $new['description'] ) ? wp_kses_post( $new['description'] ) : null;
		$instance['style']           = ! empty( $new['style'] ) ? wp_strip_all_tags( $new['style'] ) : 'flat-color';
		$instance['target']          = ! empty( $new['target'] ) ? wp_strip_all_tags( $new['target'] ) : 'blank';
		$instance['size']            = ! empty( $new['size'] ) ? wp_strip_all_tags( $new['size'] ) : '';
		$instance['align']           = ! empty( $new['align'] ) ? wp_strip_all_tags( $new['align'] ) : '';
		$instance['border_radius']   = ! empty( $new['border_radius'] ) ? wp_strip_all_tags( $new['border_radius'] ) : '';
		$instance['font_size']       = ! empty( $new['font_size'] ) ? wp_strip_all_tags( $new['font_size'] ) : '';
		$instance['space_between']   = ! empty( $new['space_between'] ) ? wp_strip_all_tags( $new['space_between'] ) : '';
		$instance['nofollow']        = ! empty( $new['nofollow'] ) ? 'on' : null;
		$instance['expand']          = ! empty( $new['expand'] ) ? 'on' : null;
		$instance['social_services'] = $new['social_services'];
		$instance['type']            = null;
		return $instance;

	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 * @since 1.0.0
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args( ( array ) $instance, array(
			'title'           => '',
			'description'     => '',
			'style'           => 'flat-color',
			'type'            => '',
			'font_size'       => '',
			'border_radius'   => '',
			'target'          => 'blank',
			'size'            => '',
			'social_services' => $this->get_social_options(),
			'align'           => 'left',
			'nofollow'        => '',
			'expand'          => '',
			'space_between'   => '',
		) );

		?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title', 'total-theme-core' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description','total-theme-core' ); ?>:</label>
			<textarea class="widefat" rows="5" cols="20" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo ttc_sanitize_data( $instance['description'], 'html' ); ?></textarea>
		</p>

		<?php
		// Styles.
		$social_styles = function_exists( 'wpex_social_button_styles' ) ? wpex_social_button_styles() : array();

		if ( $social_styles ) {

			// Parse style.
			$style = $this->parse_style( $instance['style'], $instance['type'] ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>"><?php esc_html_e( 'Style', 'total-theme-core' ); ?>:</label>
				<br>
				<select class="wpex-widget-select" name="<?php echo esc_attr( $this->get_field_name( 'style' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'style' ) ); ?>">
					<?php foreach ( $social_styles as $key => $val ) { ?>
						<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $style, $key ) ?>><?php echo strip_tags( $val ); ?></option>
					<?php } ?>
				</select>
			</p>

		<?php } ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>"><?php esc_html_e( 'Link Target', 'total-theme-core' ); ?>:</label>
			<br>
			<select class="wpex-widget-select" name="<?php echo esc_attr( $this->get_field_name( 'target' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'target' ) ); ?>">
				<option value="blank" <?php selected( $instance['target'], 'blank' ) ?>><?php esc_html_e( 'Blank', 'total-theme-core' ); ?></option>
				<option value="self" <?php selected( $instance['target'], 'self' ) ?>><?php esc_html_e( 'Self', 'total-theme-core' ); ?></option>
			</select>
		</p>

		<p>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'nofollow' ) ); ?>" type="checkbox" <?php checked( 'on', $instance['nofollow'], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'nofollow' ) ); ?>"><?php esc_html_e( 'Add nofollow attribute to links. ','total-theme-core' ); ?></label>
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>"><?php esc_attr_e( 'Align', 'total-theme-core' ); ?>:</label>
			<br>
			<select class='wpex-widget-select' name="<?php echo esc_attr( $this->get_field_name( 'align' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'align' ) ); ?>">
				<option value="" <?php selected( $instance['align'], '' ); ?>><?php esc_attr_e( 'Default', 'total-theme-core' ); ?></option>
				<option value="left" <?php selected( $instance['align'], 'left' ); ?>><?php esc_attr_e( 'Left', 'total-theme-core' ); ?></option>
				<option value="center" <?php selected( $instance['align'], 'center' ); ?>><?php esc_attr_e( 'Center', 'total-theme-core' ); ?></option>
				<option value="right" <?php selected( $instance['align'], 'right' ); ?>><?php esc_attr_e( 'Right', 'total-theme-core' ); ?></option>
			</select>
		</p>

		<p>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'expand' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'expand' ) ); ?>" type="checkbox" <?php checked( 'on', $instance['expand'], true ); ?>>
			<label for="<?php echo esc_attr( $this->get_field_id( 'expand' ) ); ?>"><?php esc_html_e( 'Expand items to fit the widget area?','total-theme-core' ); ?></label>
		</p>

		<?php if ( function_exists( 'wpex_utl_margins' ) ) {
			$space_between_choices = wpex_utl_margins();
			if ( $space_between_choices && is_array( $space_between_choices ) ) { ?>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id( 'space_between' ) ); ?>"><?php esc_html_e( 'Spacing', 'total-theme-core' ); ?>:</label>
					<br>
					<select class="wpex-widget-select" name="<?php echo esc_attr( $this->get_field_name( 'space_between' ) ); ?>" id="<?php echo esc_attr( $this->get_field_id( 'space_between' ) ); ?>">
						<?php foreach( $space_between_choices as $space_between_choice_k => $space_between_choice_v ) { ?>
							<option value="<?php echo esc_attr( $space_between_choice_k ); ?>" <?php selected( $instance['space_between'], esc_attr( $space_between_choice_k ), true ); ?>><?php echo esc_html( $space_between_choice_v ); ?></option>
						<?php } ?>
					</select>
				</p>
			<?php } ?>
		<?php } ?>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>"><?php esc_html_e( 'Dimensions', 'total-theme-core' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'size' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['size'] ); ?>" placeholder="40px">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'font_size' ) ); ?>"><?php esc_html_e( 'Size', 'total-theme-core' ); ?>:</label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'font_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'font_size' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['font_size'] ); ?>" placeholder="13px">
		</p>

		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'border_radius' ) ); ?>"><?php esc_html_e( 'Border Radius', 'total-theme-core' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'border_radius' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'border_radius' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['border_radius'] ); ?>" placeholder="4px">
		</p>

		<?php
		$field_id_services   = $this->get_field_id( 'social_services' );
		$field_name_services = $this->get_field_name( 'social_services' );
		?>

		<label for="<?php echo esc_attr( $this->get_field_id( 'social_services' ) ); ?>"><?php esc_attr_e( 'Social Links', 'total-theme-core' ); ?>:</label>

		<small style="display:block;padding-top:5px;"><?php esc_html_e( 'You can click and drag & drop your items to re-order them. ', 'total-theme-core' ); ?></small>

		<ul id="<?php echo esc_attr( $field_id_services ); ?>" class="wpex-social-widget-services-list">
			<input type="hidden" id="<?php echo esc_attr( $field_name_services ); ?>" value="<?php echo esc_attr( $field_name_services ); ?>" class="wpex-social-widget-services-hidden-field">
			<?php
			// Social array.
			$get_social_options = $this->get_social_options();

			// Get current services display.
			$display_services = isset ( $instance['social_services'] ) ? $instance['social_services'] : '';

			// Add new items to the end of array.
			foreach( $get_social_options as $key => $val ) {
				if ( ! array_key_exists( $key, $display_services ) ) {
					$display_services[$key] = $val;
				}
			}

			// Loop through saved items.
			foreach( $display_services as $key => $val ) {

				if ( empty( $get_social_options[$key] ) ) {
					continue;
				}

				$url  = ! empty( $display_services[$key]['url'] ) ? $display_services[$key]['url'] : null;
				$name = $get_social_options[$key]['name'];

				?>

				<li id="<?php echo esc_attr( $field_id_services ); ?>_0<?php echo esc_attr( $key ); ?>">
					<p>
						<label for="<?php echo esc_attr( $field_id_services ); ?>-<?php echo esc_attr( $key ); ?>-name"><span class="<?php echo esc_attr( $this->get_icon_class( $key ) ); ?>"></span><?php echo strip_tags( $name ); ?>:</label>
						<input type="hidden" id="<?php echo esc_attr( $field_id_services ); ?>-<?php echo esc_attr( $key ); ?>-name" name="<?php echo esc_attr( $field_name_services . '[' .$key. '][name]' ); ?>" value="<?php echo esc_attr( $name ); ?>">
						<input type="text" id="<?php echo esc_attr( $field_id_services ); ?>-<?php echo esc_attr( $key ); ?>-url" name="<?php echo esc_attr( $field_name_services . '[' .$key. '][url]' ); ?>" value="<?php echo esc_attr( $url ); ?>" class="widefat">
					</p>
				</li>

			<?php } ?>

		</ul>

	<?php
	}

}
register_widget( 'TotalThemeCore\Widget_Social_Profiles' );