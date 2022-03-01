<?php
/**
 * Social share functions.
 *
 * @package TotalTheme
 * @subpackage Functions
 * @version 5.3.1
 */

defined( 'ABSPATH' ) || exit;

/*-------------------------------------------------------------------------------*/
/* [ Table of contents ]
/*-------------------------------------------------------------------------------*

	# General
	# Classes
	# Heading
	# Data
	# Items list (the buttons)

/*-------------------------------------------------------------------------------*/
/* [ General ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if social share is enabled.
 *
 * @since 4.0
 */
function wpex_has_social_share() {
	if ( post_password_required() ) {
		return; // no need for social if post is password protected.
	}

	// Disabled by default.
	$check = false;

	// Get current post ID.
	$post_id = wpex_get_current_post_id();

	// Check page settings to overrides theme mods and filters.
	if ( $post_id ) {

		// Meta check.
		if ( $meta = get_post_meta( $post_id, 'wpex_disable_social', true ) ) {

			// Check if disabled by meta options.
			if ( 'on' === $meta ) {
				return false;
			}

			// Return true if enabled via meta option.
			if ( 'enable' === $meta ) {
				return true;
			}

		}

		// Dynamic template check.
		if ( wpex_post_has_dynamic_template() ) {
			return true; // so that the post content module works correctly - @todo update so this isn't needed.
		}

		// Remove on woo cart/checkout pages.
		if ( ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_checkout') && is_checkout() ) ) {
			return false;
		}

		// Check if social share is enabled for specific post types.
		if ( 'product' === get_post_type() ) {
			$check = wp_validate_boolean( get_theme_mod( 'social_share_woo', false ) );
		} else {
			$blocks = wpex_single_blocks();
			if ( $blocks && is_array( $blocks ) ) {
				foreach ( $blocks as $block ) {
					if ( ( 'social_share' == $block || 'share' == $block ) ) {
						$check = true;
					}
				}
			}
		}

	}

	/**
	 * Filters whether the social share buttons are enabled.
	 *
	 * @param bool $check
	 */
	$check = (bool) apply_filters( 'wpex_has_social_share', $check );

	return $check;
}

/**
 * Checks if there are any social sharing sites enabled.
 *
 * @since 1.0.0
 */
function wpex_has_social_share_sites() {
	return (bool) wpex_social_share_sites();
}

/**
 * Returns social sharing sites.
 *
 * @since 2.0.0
 */
function wpex_social_share_sites() {
	$sites = get_theme_mod( 'social_share_sites', array( 'twitter', 'facebook', 'linkedin', 'email' ) );

	/**
	 * Filters the available sites for the social share buttons.
	 *
	 * @param array|string $sites
	 */
	$sites = apply_filters( 'wpex_social_share_sites', $sites );

	if ( $sites && is_string( $sites ) ) {
		$sites = explode( ',', $sites );
	}

	return $sites;
}

/**
 * Parses Social share arguments.
 *
 * @since 5.1
 */
function wpex_parse_social_share_args( $args = array() ) {
	$defaults = array(
		'align'         => get_theme_mod( 'social_share_align' ) ?: '',
		'style'         => wpex_social_share_style(),
		'position'      => wpex_social_share_position(),
		'has_labels'    => wpex_social_share_has_labels(),
		'stretch_items' => get_theme_mod( 'social_share_stretch_items', false ),
	);

	if ( 'custom' === $defaults['style'] ) {
		$defaults['link_border_radius'] = get_theme_mod( 'social_share_link_border_radius' );
	}

	if ( ! array_key_exists( 'contain', $args )
		&& 'horizontal' === $defaults['position']
		&& 'full-screen' === wpex_content_area_layout() ) {
		$defaults['contain'] = true; // contain the social share on full-screen layouts.
	}

	$args = wp_parse_args( $args, $defaults );

	if ( 'vertical' === $args['position'] ) {
		$args['has_labels'] = false; // remove labels on vertical share style.
	}

	// Magazine style tweaks.
	if ( 'mag' === $defaults['style'] ) {
		$args['has_labels'] = true;
		$args['position'] = 'horizontal';
	}

	/**
	 * Filters the social share arguments.
	 *
	 * @param array $args
	 */
	$args = (array) apply_filters( 'wpex_social_share_args', $args );

	return $args;
}

/**
 * Returns correct social share position.
 *
 * @since 2.0.0
 */
function wpex_social_share_position() {
	$position = get_theme_mod( 'social_share_position' );

	if ( ! $position || 'mag' == wpex_social_share_style() ) {
		$position = 'horizontal';
	}

	/**
	 * Filters the social share buttons position.
	 *
	 * @param string $position
	 */
	$position = (string) apply_filters( 'wpex_social_share_position', $position );

	return $position;
}

/**
 * Returns correct social share style.
 *
 * @since 2.0.0
 */
function wpex_social_share_style() {
	$style = get_theme_mod( 'social_share_style' );

	if ( function_exists( 'is_product' ) && is_product() ) {
		$woo_style = get_theme_mod( 'woo_product_social_share_style', $style );
		if ( $woo_style ) {
			$style = $woo_style;
		}
	}

	if ( ! $style || ! is_string( $style ) ) {
		$style = 'flat'; // style can't be empty.
	}

	/**
	 * Filters the social share buttons style.
	 *
	 * @param string $style.
	 */
	$style = (string) apply_filters( 'wpex_social_share_style', $style );

	return $style;
}

/**
 * Check if social share labels should display.
 *
 * @since 4.9.8
 */
function wpex_social_share_has_labels() {
	$check = get_theme_mod( 'social_share_label', true );

	if ( function_exists( 'is_product' ) && is_product() ) {
		$check = get_theme_mod( 'woo_social_share_label', true );
	}

	/**
	 * Filters whether the social share buttons should have a label.
	 *
	 * @param bool $check
	 */
	$check = (bool) apply_filters( 'wpex_social_share_has_labels', $check );

	return $check;
}

/**
 * Checks if we are using custom social share.
 *
 * @since 5.0
 */
function wpex_has_custom_social_share() {
	return (bool) wpex_custom_social_share();
}

/**
 * Checks if we are using custom social share.
 *
 * @since 5.0
 */
function wpex_custom_social_share() {
	$custom_share = get_theme_mod( 'social_share_shortcode' );

	/**
	 * Filters the custom social share button output.
	 *
	 * @param string $custom_share
	 */
	$custom_share = apply_filters( 'wpex_custom_social_share', $custom_share );

	return $custom_share;
}

/*-------------------------------------------------------------------------------*/
/* [ Classes ]
/*-------------------------------------------------------------------------------*/

/**
 * Social share class.
 *
 * @since 5.0
 */
function wpex_social_share_class( $args = array() ) {
	$classes = array();

	if ( empty( $args ) && wpex_has_custom_social_share() ) {

		$classes = array(
			'wpex-custom-social-share',
			'wpex-mb-40',
			'wpex-clr',
		);

		if ( 'full-screen' === wpex_content_area_layout() ) {
			$classes[] = 'container';
		}

	} else {

		$args = wpex_parse_social_share_args( $args );

		$classes = array(
			'class' => 'wpex-social-share',
		);

		$style = ! empty( $args['style'] ) ? $args['style'] : '';
		$position = ! empty( $args['position'] ) ? $args['position'] : '';
		$has_labels = ! empty( $args['has_labels'] );
		$strech_items = ! empty( $args['stretch_items'] );

		if ( $style ) {
			$classes[] = 'style-' . sanitize_html_class( $style );
		}

		if ( $position ) {
			$classes[] = 'position-' . sanitize_html_class( $position );
		}

		switch ( $position ) {
			case 'vertical':
				$classes[] = 'is-animated';

				if ( wpex_has_vertical_header() ) {
					$classes[] = 'on-right';
				} else {
					$classes[] = 'on-left';
				}

				if ( 'rounded' === $style || 'mag' === $style ) {
					$classes[] = 'has-side-margin';
				}
				break;
			case 'horizontal':
				$classes[] = 'wpex-mx-auto';
				$classes[] = 'wpex-mb-40';
				break;
		}

		if ( ! $has_labels ) {
			$classes[] = 'disable-labels';
		}

		if ( $strech_items && 'horizontal' === $position ) {
			$classes[] = 'wpex-social-share--stretched';
		}

		if ( ! empty( $args['contain'] ) ) {
			$classes[] = 'container';
		}

	}

	/**
	 * Filters the social share wrap class.
	 *
	 * @param array $classes
	 */
	$classes = (array) apply_filters( 'wpex_social_share_class', $classes );

	if ( $classes ) {
		echo 'class="' . esc_attr( implode( ' ', $classes ) ) . '"';
	}
}

/*-------------------------------------------------------------------------------*/
/* [ Heading ]
/*-------------------------------------------------------------------------------*/

/**
 * Checks if the social sharing style supports a custom heading.
 *
 * @since 5.0
 */
function wpex_has_social_share_heading() {
	$check = ( 'horizontal' === wpex_social_share_position() );

	/**
	 * Filters whether the social share buttons have a heading.
	 *
	 * @param bool $check
	 */
	$check = (bool) apply_filters( 'wpex_has_social_share_heading', $check );

	return $check;
}

/**
 * Returns the social share heading.
 *
 * @since 2.0.0
 */
function wpex_social_share_heading() {
	if ( ! wpex_has_social_share_heading() ) {
		return;
	}

	$heading = wpex_get_translated_theme_mod( 'social_share_heading', esc_html__( 'Share This', 'total' ) );

	if ( function_exists( 'is_product' ) && is_product() ) {
		$heading = wpex_get_translated_theme_mod( 'woo_product_social_share_heading', $heading );
	}

	/**
	 * Filters the social share heading text.
	 *
	 * @param string $heading
	 */
	$heading = apply_filters( 'wpex_social_share_heading', $heading );

	if ( $heading ) {

		$heading_args = array(
			'tag'           => get_theme_mod( 'social_share_heading_tag' ) ?: 'h3',
			'content'		=> $heading,
			'classes'		=> array( 'social-share-title' ),
			'apply_filters'	=> 'social_share',
		);

		if ( function_exists( 'is_product' ) && is_product() ) {
			$heading_args[ 'style' ] = 'plain';
		}

		wpex_heading( $heading_args );

	}
}

/*-------------------------------------------------------------------------------*/
/* [ Data ]
/*-------------------------------------------------------------------------------*/

/**
 * Output social share data.
 *
 * @since 5.0
 */
function wpex_social_share_data( $post_id = 0, $sites = array() ) {
	$data = wpex_get_social_share_data( $post_id, $sites );

	if ( ! empty( $data ) && is_array( $data ) ) {

		$html = '';

		foreach ( $data as $k => $v ) {
			$html .=' data-' . esc_attr( $k ) .'="' . esc_attr( $v ) . '"';
		}

		echo trim( $html );

	}
}

/**
 * Return social share data.
 *
 * @since 4.5.5.1
 */
function wpex_get_social_share_data( $post_id = 0, $sites = array() ) {
	if ( ! $post_id ) {
		$post_id = wpex_get_current_post_id();
	}

	if ( ! $sites ) {
		$sites = wpex_social_share_sites();
	}

	/**
	 * Filters the social share URL.
	 *
	 * @todo rename "wpex_social_share_current_url"
	 */
	$url = apply_filters( 'wpex_social_share_url', wpex_get_current_url() );

	$data = array();

	// Singular data
	if ( $post_id ) {

		$title = wpex_get_esc_title();

		if ( in_array( 'pinterest', $sites ) || in_array( 'linkedin', $sites ) ) {

			$summary = wpex_get_excerpt( apply_filters( 'wpex_social_share_excerpt_args', array(
				'post_id' => $post_id,
				'length'  => 30,
				'echo'    => false,
				'more'    => '',
			) ) );

		}

	}

	// Most likely an archive.
	else {
		$title = get_the_archive_title();
		$summary = get_the_archive_description();
	}

	/**
	 * Filters the social share source url.
	 *
	 * @param string $source
	 */
	$source = apply_filters( 'wpex_social_share_data_source', home_url( '/' ) );
	$data['source'] = rawurlencode( esc_url( $source ) );

	/**
	 * Filters the social share url.
	 *
	 * @param string $url
	 */
	$url = apply_filters( 'wpex_social_share_data_url', $url );
	$data['url'] = rawurlencode( esc_url( $url ) );

	/**
	 * Filters the social share title.
	 *
	 * @param string $title
	 */
	$title = apply_filters( 'wpex_social_share_data_title', $title );
	$data['title'] = html_entity_decode( wp_strip_all_tags( $title ) );

	// Thumbnail.
	if ( is_singular() && has_post_thumbnail() ) {

		/**
		 * Filters the social share image.
		 *
		 * @param sting $image
		 */
		$image = apply_filters( 'wpex_social_share_data_image', wp_get_attachment_url( get_post_thumbnail_id( $post_id ) ) );
		$data['image'] = rawurlencode( esc_url( $image ) );
	}

	// Add twitter handle.
	if ( $handle = get_theme_mod( 'social_share_twitter_handle' ) ) {
		$data['twitter-handle'] = esc_attr( $handle );
	}

	// Share summary.
	if ( ! empty( $summary ) ) {

		/**
		 * Filters the social share summary.
		 *
		 * @param string $summary
		 */
		$summary = apply_filters( 'wpex_social_share_data_summary', wp_strip_all_tags( strip_shortcodes( $summary ) ) );
		$data['summary'] = rawurlencode( html_entity_decode( $summary ) );
	}

	// Get WordPress SEO meta share values.
	if ( class_exists( 'WPSEO_Meta' ) && method_exists( 'WPSEO_Meta', 'get_value' ) ) {
		$twitter_title = WPSEO_Meta::get_value( 'twitter-title', $post_id );
		if ( ! empty( $twitter_title ) ) {
			if ( class_exists( 'WPSEO_Replace_Vars' ) ) {
				$replace_vars = new WPSEO_Replace_Vars();
				$twitter_title = $replace_vars->replace( $twitter_title, get_post() );
			}
			$data['twitter-title'] = rawurlencode( html_entity_decode( wp_strip_all_tags( $twitter_title ) ) );
		}
		$twitter_desc =  WPSEO_Meta::get_value( 'twitter-description', $post_id );
		if ( ! empty( $twitter_desc ) ) {
			if ( class_exists( 'WPSEO_Replace_Vars' ) ) {
				$replace_vars = new WPSEO_Replace_Vars();
				$twitter_desc = $replace_vars->replace( $twitter_desc, get_post() );
			}
			if ( $twitter_title ) {
				$data['twitter-title'] = rawurlencode( html_entity_decode( wp_strip_all_tags( $twitter_title . ': ' . $twitter_desc ) ) );
			} else {
				$data['twitter-title'] = rawurlencode( html_entity_decode( wp_strip_all_tags( $data['title'] . ': ' . $twitter_desc ) ) );
			}
		}
	}

	// Email data.
	if ( in_array( 'email', $sites ) ) {

		/**
		 * Filters the social share email subject.
		 *
		 * @param string $subject
		 */
		$data['email-subject'] = apply_filters( 'wpex_social_share_data_email_subject', esc_html__( 'I wanted you to see this link', 'total' ) );

		$body = esc_html__( 'I wanted you to see this link', 'total' ) . ' '. rawurlencode( esc_url( $url ) );

		/**
		 * Filters the social share email body text.
		 *
		 * @param string $body
		 */
		$data['email-body'] = apply_filters( 'wpex_social_share_data_email_body', $body );
	}

	// Specs.
	$data['specs'] = 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600';

	/**
	 * Filters the social share data attributes.
	 *
	 * @param array $data
	 * @todo rename to "wpex_social_share_data"
	 */
	$data = (array) apply_filters( 'wpex_get_social_share_data', $data );

	return $data;
}

/*-------------------------------------------------------------------------------*/
/* [ Items List ]
/*-------------------------------------------------------------------------------*/

/**
 * Output social share list.
 *
 * @since 5.0
 */
function wpex_social_share_list( $args = array(), $sites = array() ) {
	if ( ! $sites ) {
		$sites = wpex_social_share_sites();
	}

	$items = wpex_social_share_items();

	if ( empty( $sites ) || empty( $items ) ) {
		return;
	}

	// Good place to load the social share scripts
	wp_enqueue_script( 'wpex-social-share' );

	$args = wpex_parse_social_share_args( $args );

	$style = $args['style'];
	$position = $args['position'] ?? '';
	$has_labels = false;

	if ( array_key_exists( 'has_labels', $args ) && wpex_validate_boolean( $args['has_labels' ] ) ) {
		$has_labels = true;
	}

	$item_class = 'wpex-social-share__item';

	$sq_links = ( ! $has_labels || 'vertical' === $position );

	if ( 'custom' === $style ) {

		if ( ! empty( $args['link_border_radius'] ) ) {
			$link_border_radius_class = wpex_parse_border_radius_class( $args['link_border_radius'] );
		}

		if ( isset( $link_border_radius_class ) && 'vertical' === $args['position'] ) {
			$item_class .= ' wpex-ml-5 wpex-mb-5';
		}

	}

	// Define list class.
	$list_class = 'wpex-social-share__list';

	if ( 'horizontal' === $position ) {
		$list_class .= ' wpex-flex wpex-flex-wrap';
	}

	if ( ! empty( $args['align'] ) ) {

		$align_class = '';

		switch ( $args['align'] ) {
			case 'left':
			case 'start':
				$align_class = 'wpex-justify-start';
				break;
			case 'center':
				$align_class = 'wpex-justify-center';
				break;
			case 'right':
			case 'end':
				$align_class = 'wpex-justify-end';
				break;
		}

		if ( $align_class ) {
			$list_class .= ' ' . $align_class;
		}
	}

	?>

	<ul class="<?php echo esc_attr( $list_class ); ?>"><?php

		// Loop through sites and save new array with filters for output
		foreach ( $sites as $site ) :

			if ( ! isset( $items[ $site ] ) ) {
				continue;
			}

			$item = isset( $items[ $site ] ) ? $items[ $site ] : '';

			if ( ! $item ) {
				continue;
			}

			// Define li class.
			$li_class = isset( $item[ 'li_class' ] ) ? ' ' . $item[ 'li_class' ] : '';

			// Define link class.
			$link_class = 'wpex-social-share__link wpex-' . sanitize_html_class( $site );

			if ( $sq_links ) {
				$link_class .= ' wpex-social-share__link--sq';
			}

			if ( isset( $link_border_radius_class ) ) {
				$link_class .= ' ' . sanitize_html_class( $link_border_radius_class );
			}

			// Add abstract classes for social colors.
			if ( 'custom' !== $style ) {

				if ( in_array( $style, array( 'flat', 'three-d' ) ) ) {
					$link_class .= ' wpex-social-bg';
				}

				if ( in_array( $style, array( 'minimal' ) ) && 'email' !== $site ) {
					$link_class .= ' wpex-social-color-hover';
				}

				if ( 'rounded' === $style ) {
				//	$link_class .= ' wpex-bg-white';
					if ( 'email' !== $site ) {
						$link_class .= ' wpex-social-border wpex-social-color';
					}
				}

			}

			/**
			 * Filters the link class.
			 *
			 * @param string $class.
			 */
			$link_class = apply_filters( 'wpex_social_share_item_link_class', $link_class, $site );

			?>

			<li class="<?php echo esc_attr( $item_class ); ?><?php echo esc_attr( $li_class ); ?>">

				<?php if ( isset( $item[ 'href' ] ) ) { ?>

					<a href="<?php echo esc_attr( $item[ 'href' ] ); ?>" role="button" class="<?php echo esc_attr( $link_class ); ?>">

				<?php } else { ?>

					<a href="#" role="button" class="<?php echo esc_attr( $link_class ); ?>">

				<?php } ?>

					<?php
					/**
					 * Display custom item icon.
					 */

					// Check for icon param first which overrides default icon class.
					if ( ! empty( $item[ 'icon' ] ) ) {
						$allowed_icon_html = array(
							'img' => array(
								'id' => array(),
								'class' => array(),
								'src' => array(),
								'title' => array(),
								'alt' => array(),
								'width' => array(),
								'height' => array()
							),
							'span' => array(
								'id' => array(),
								'class' => array(),
								'aria-hidden' => array(),
							),
							'svg' => array(
								'class' => true,
								'aria-hidden' => true,
								'aria-labelledby' => true,
								'role' => true,
								'xmlns' => true,
								'width' => true,
								'height' => true,
								'viewbox' => true,
							),
							'g' => array( 'fill' => true ),
							'title' => array( 'title' => true ),
							'path' => array( 'd' => true, 'fill' => true,  ),
						);
						echo '<span class="wpex-social-share__icon wpex-flex">' . wp_kses( $item[ 'icon' ], $allowed_icon_html ) . '</span>';
					}

					// If an icon param doesn't exist return font icon.
					elseif ( ! empty( $item[ 'icon_class' ] ) ) {

						if ( 0 === strpos( $item[ 'icon_class' ], 'ticon' ) ) {
							echo '<span class="wpex-social-share__icon">' . wpex_get_theme_icon_html( $item[ 'icon_class' ] ) . '</span>';
						} else {
							echo '<span class="wpex-social-share__icon"><span class="' . esc_attr( $item[ 'icon_class' ] ) . '"></span></span>';
						}

					} ?>

					<?php
					// Display labels.
					if ( $has_labels ) { ?>
						<span class="wpex-social-share__label wpex-label"><?php echo esc_html( $item[ 'label' ] ); ?></span>
					<?php }
					// Screen reader text if labels are disabled.
					else {

						$text = ! empty( $item['reader_text'] ) ? $item['reader_text'] : $site;

						?>
						<span class="screen-reader-text"><?php echo esc_html( $text ); ?></span>
					<?php } ?>

				</a>

			</li>

		<?php endforeach; ?></ul>

	<?php
}