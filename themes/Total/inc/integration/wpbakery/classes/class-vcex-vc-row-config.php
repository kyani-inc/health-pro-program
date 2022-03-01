<?php
use TotalTheme\Integration\WPBakery\Deprecated_CSS_Params_Style;

defined( 'ABSPATH' ) || exit;

/**
 * WPBakery Row Configuration.
 *
 * @package TotalTheme
 * @subpackage WPBakery
 * @version 5.3
 */
if ( ! class_exists( 'VCEX_VC_Row_Config' ) ) {

	class VCEX_VC_Row_Config {

		/**
		 * Main constructor.
		 *
		 * @since 2.0.0
		 */
		public function __construct() {
			add_action( 'vc_after_init', __CLASS__ . '::add_params', 40 ); // add params first
			add_action( 'vc_after_init', __CLASS__ . '::modify_params', 40 ); // priority is crucial.
			add_filter( 'vc_edit_form_fields_attributes_vc_row', __CLASS__ . '::edit_form_fields' );
			add_filter( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, __CLASS__ . '::shortcode_classes', 10, 3 );
			add_filter( 'shortcode_atts_vc_row', 'vcex_parse_row_atts', 99 );
			add_filter( 'wpex_vc_row_wrap_atts', __CLASS__ . '::wrap_attributes', 10, 2 );
			add_filter( 'wpex_hook_vc_row_top', __CLASS__ . '::center_row_open', 10, 2 );
			add_filter( 'wpex_hook_vc_row_bottom', __CLASS__ . '::center_row_close', 10, 2 );
			add_filter( 'vc_shortcode_output', __CLASS__ . '::custom_output', 10, 4 );
		}

		/**
		 * Adds new params to vc_map.
		 *
		 * @since 2.0.0
		 */
		public static function add_params() {
			if ( function_exists( 'vc_add_params' ) ) {
				vc_add_params( 'vc_row', self::get_vc_row_custom_params() );
				vc_add_params( 'vc_row_inner', self::get_vc_row_inner_custom_params() );
			}
		}

		/**
		 * Get custom params for rows.
		 *
		 * @since 5.3
		 */
		public static function get_vc_row_custom_params() {

			$params = array(
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Access', 'total' ),
					'param_name' => 'vcex_user_access',
					'weight' => 99,
					'value' => array(
						esc_html__( 'All', 'total' ) => '',
						esc_html__( 'Logged in', 'total' ) => 'logged_in',
						esc_html__( 'Logged out', 'total' ) => 'logged_out',
						esc_html__( 'First paginated page only', 'total' ) => 'not_paged',
						esc_html__( 'Custom', 'total' ) => 'custom',
					)
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Access', 'total' ),
					'param_name' => 'vcex_user_access_callback',
					'description' => esc_html__( 'Enter your callback function name here.', 'total' ),
					'weight' => 99,
					'dependency' => array( 'element' => 'vcex_user_access', 'value' => 'custom' ),
				),
				array(
					'type' => 'vcex_visibility',
					'heading' => esc_html__( 'Visibility', 'total' ),
					'param_name' => 'visibility',
					'weight' => 99,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Local Scroll ID', 'total' ),
					'param_name' => 'local_scroll_id',
					'description' => esc_html__( 'Unique identifier for local scrolling links.', 'total' ),
					'weight' => 99,
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Minimum Height', 'total' ),
					'description' => esc_html__( 'Adds a minimum height to the row so you can have a row without any content but still display it at a certain height. Such as a background with a video or image background but without any content.', 'total' ),
					'param_name' => 'min_height',
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Max Width', 'total' ),
					'param_name' => 'max_width',
					'value' => array(
						esc_html__( 'None', 'total' ) => '',
						'10%' => '10',
						'20%' => '20',
						'30%' => '30',
						'40%' => '40',
						'50%' => '50',
						'60%' => '60',
						'70%' => '70',
						'80%' => '80',
					),
					'description' => esc_html__( 'The max width is done by setting a percentage margin on the left and right of your row. You can visit the Design Options tab to enter custom percentage margins yourself if you prefer. Or use the "Custom Max Width" option below to enter a custom max-width in pixels.', 'total' ),
					'dependency' => array( 'element' => 'full_width', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Custom Max Width (px)', 'total' ),
					'param_name' => 'max_width_custom',
					'dependency' => array( 'element' => 'full_width', 'is_empty' => true ),
				),
				array(
					'type' => 'vcex_text_alignments',
					'heading' => esc_html__( 'Aligment', 'total-theme-core' ),
					'param_name' => 'max_width_align',
					'dependency' => array( 'element' => 'max_width_custom', 'not_empty' => true ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Inner Column Gap', 'total' ),
					'param_name' => 'column_spacing',
					'value' => array(
						esc_html__( 'Default', 'total' ) => '',
						'0px' => '0px',
						'1px' => '1',
						'5px' => '5',
						'10px' => '10',
						'20px' => '20',
						'30px' => '30',
						'40px' => '40',
						'50px' => '50',
						'60px' => '60',
					),
					'description' => esc_html__( 'Alter the inner column spacing.', 'total' ),
					'weight' => 40,
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Remove Bottom Column Margin', 'total' ),
					'param_name' => 'remove_bottom_col_margin',
					'std' => 'false',
					'description' => esc_html__( 'Enable to remove the default bottom margin on all the columns inside this row.', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Float Columns Right', 'total' ),
					'param_name' => 'columns_right',
					'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
					'std' => 'no',
					'description' => esc_html__( 'Most useful when you want to alternate content such as an image to the right and content to the left but display the image at the top on mobile.', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Full-Width Columns On Tablets', 'total' ),
					'param_name' => 'tablet_fullwidth_cols',
					'vcex' => array(
						'off' => 'no',
						'on' => 'yes',
					),
					'std' => 'no',
					'description' => esc_html__( 'Enable to make all columns inside this row full-width for tablets', 'total' ) . ' (min-width: 768px) and (max-width: 959px)',
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Offset Overlay Header', 'total' ),
					'param_name' => 'offset_overlay_header',
					'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
					'std' => 'no',
					'description' => esc_html__( 'Check this box to add an offset spacing before this row equal to the height of your header to prevent issues with the header Overlay when enabled.', 'total' ),
				),

				// Design options
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Use Featured Image as Background?', 'total' ),
					'param_name' => 'wpex_post_thumbnail_bg', //@todo rename to something else since it can also get category images.
					'std' => 'false',
					'description' => esc_html__( 'Enable this option to use the current post featured image as the row background.', 'total' ),
					'group' => esc_html__( 'Design Options', 'total' ),
					'weight' => -2,
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Fixed Background Style', 'total' ),
					'param_name' => 'wpex_fixed_bg',
					'group' => esc_html__( 'Design Options', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
					'value' => array(
						esc_html__( 'None', 'total' ) => '',
						esc_html__( 'Fixed', 'total' ) => 'fixed',
						esc_html__( 'Fixed top', 'total' ) => 'fixed-top',
						esc_html__( 'Fixed bottom', 'total' ) => 'fixed-bottom',
					),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Background Position', 'total' ),
					'param_name' => 'wpex_bg_position',
					'group' => esc_html__( 'Design Options', 'total' ),
					'description' => esc_html__( 'Enter your custom background position. Example: "center center"', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Background Image Size', 'total' ),
					'param_name' => 'wpex_bg_size',
					'group' => esc_html__( 'Design Options', 'total' ),
					'description' => esc_html__( 'Specify the size of the background image. Example: 100% 100% ', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
				),
				array(
					'type' => 'textfield',
					'heading' => esc_html__( 'Z-Index', 'total' ),
					'param_name' => 'wpex_zindex',
					'group' => esc_html__( 'Design Options', 'total' ),
					'description' => esc_html__( 'Note: Adding z-index values on rows containing negative top/bottom margins will allow you to overlay the rows, however, this can make it hard to access the page builder tools in the frontend editor and you may need to use the backend editor to modify the overlapped rows.', 'total' ),
					'weight' => -2,
					'dependency' => array( 'element' => 'parallax', 'is_empty' => true ),
				),
				array(
					'type' => 'dropdown',
					'heading' => esc_html__( 'Typography Style', 'total' ),
					'param_name' => 'typography_style',
					'value' => array_flip( wpex_typography_styles() ),
					'description' => esc_html__( 'Will alter the font colors of all child elements. This is an older setting that is somewhat deprecated.', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Center Row Content (deprecated)', 'total' ),
					'param_name' => 'center_row',
					'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
					'std' => 'no',
					'dependency' => array( 'element' => 'full_width', 'is_empty' => true ),
					'description' => esc_html__( 'Use this option is used to center the inner content horizontally in your row when using the "Full Screen" layout for your post/page. This was added prior to the stretch row setting it is now best to use the no-sidebar layout and the stretch row function to achive full-screen sections. If enable certain settings such as the "Content position" may not work correctly.', 'total' ),
				),

				// Deprecated params.
				array( 'type' => 'hidden', 'param_name' => 'id' ),
				array( 'type' => 'hidden', 'param_name' => 'style' ),
				array( 'type' => 'hidden', 'param_name' => 'bg_style' ),
				array( 'type' => 'hidden', 'param_name' => 'no_margins' ),
				array( 'type' => 'hidden', 'param_name' => 'video_bg_overlay' ),
				array( 'type' => 'hidden', 'param_name' => 'match_column_height' ),
			);

			if ( wpex_vc_maybe_parse_deprecated_css_options( 'vc_row' ) ) {

				$css_options = array(
					'bg_color',
					'bg_image',
					'border_style',
					'border_color',
					'border_width',
					'margin_top',
					'margin_bottom',
					'margin_left',
					'padding_top',
					'padding_bottom',
					'padding_left',
					'padding_right',
				);

				foreach ( $css_options as $param ) {
					$params[] = array(
						'type' => 'hidden',
						'param_name' => $param,
					);
				}

			}

			return $params;
		}

		/**
		 * Get custom params for inner rows.
		 *
		 * @since 5.3
		 */
		public static function get_vc_row_inner_custom_params() {
			return array(
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Remove Bottom Column Margin', 'total' ),
					'param_name' => 'remove_bottom_col_margin',
					'std' => 'false',
					'description' => esc_html__( 'Enable to remove the default bottom margin on all the columns inside this row.', 'total' ),
				),
				array(
					'type' => 'vcex_ofswitch',
					'heading' => esc_html__( 'Float Columns Right', 'total' ),
					'param_name' => 'columns_right',
					'vcex' => array( 'off' => 'no', 'on' => 'yes', ),
					'std' => 'no',
					'description' => esc_html__( 'Most useful when you want to alternate content such as an image to the right and content to the left but display the image at the top on mobile.', 'total' ),
				)
			);
		}

		/**
		 * Modify default params.
		 *
		 * @since 3.0.0
		 */
		public static function modify_params() {

			if ( ! function_exists( 'vc_update_shortcode_param' ) ) {
				return;
			}

			// Move el_id.
			$param = \WPBMap::getParam( 'vc_row', 'el_id' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_row', $param );
			}

			// Move el_class.
			$param = \WPBMap::getParam( 'vc_row', 'el_class' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_row', $param );
			}

			// Move css_animation.
			$param = \WPBMap::getParam( 'vc_row', 'css_animation' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_row', $param );
			}

			// Move full_width.
			$param = \WPBMap::getParam( 'vc_row', 'full_width' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_row', $param );
			}

			// Move content_placement.
			$param = \WPBMap::getParam( 'vc_row', 'content_placement' );
			if ( $param ) {
				$param['weight'] = 99;
				vc_update_shortcode_param( 'vc_row', $param );
			}

			// Change gap text.
			$param = \WPBMap::getParam( 'vc_row', 'gap' );
			if ( $param ) {
				$param['heading'] = esc_html__( 'Outer Column Gap', 'total' );
				$param['description'] = esc_html__( 'Alters the outer column gap to be used when adding backgrounds to your inner columns. To increase the default space between the columns without backgrounds use the "Inner Column Gap" setting instead.', 'total' );
				$param['weight'] = 40;
				vc_update_shortcode_param( 'vc_row', $param );
			}

			// Move css.
			$param = \WPBMap::getParam( 'vc_row', 'css' );
			if ( $param ) {
				$param['weight'] = -1;
				vc_update_shortcode_param( 'vc_row', $param );
			}

		}

		/**
		 * Tweaks row attributes on edit.
		 *
		 * @since 2.0.2
		 */
		public static function edit_form_fields( $atts ) {

			// Parse ID.
			if ( empty( $atts['el_id'] ) && ! empty( $atts['id'] ) ) {
				$atts['el_id'] = $atts['id'];
				unset( $atts['id'] );
			}

			// Convert match_column_height to equal_height.
			if ( ! empty( $atts['match_column_height'] ) ) {
				$atts['equal_height'] = 'yes';
				unset( $atts['match_column_height'] );
			}

			// Parse $style into $typography_style.
			if ( empty( $atts['typography_style'] ) && ! empty( $atts['style'] ) ) {
				if ( in_array( $atts['style'], array_flip( wpex_typography_styles() ) ) ) {
					$atts['typography_style'] = $atts['style'];
					unset( $atts['style'] );
				}
			}

			// Convert 'no-margins' to '0px' column_spacing.
			if ( empty( $atts['column_spacing'] ) && ! empty( $atts['no_margins'] ) && 'true' == $atts['no_margins'] ) {
				$atts['column_spacing'] = '0px';
				unset( $atts['no_margins'] );
			}

			// Parse css.
			if ( empty( $atts['css'] ) && wpex_vc_maybe_parse_deprecated_css_options( 'vc_row' ) ) {

				// Convert deprecated fields to css field.
				$atts['css'] = Deprecated_CSS_Params_Style::generate_css( $atts );

				// Unset deprecated vars.
				unset( $atts['bg_image'] );
				unset( $atts['bg_color'] );

				unset( $atts['margin_top'] );
				unset( $atts['margin_bottom'] );
				unset( $atts['margin_right'] );
				unset( $atts['margin_left'] );

				unset( $atts['padding_top'] );
				unset( $atts['padding_bottom'] );
				unset( $atts['padding_right'] );
				unset( $atts['padding_left'] );

				unset( $atts['border_width'] );
				unset( $atts['border_style'] );
				unset( $atts['border_color'] );

			}

			// Return $atts.
			return $atts;

		}

		/**
		 * Tweak shortcode classes.
		 *
		 * @since 4.0
		 */
		public static function shortcode_classes( $class_string, $tag, $atts ) {

			if ( 'vc_row' !== $tag && 'vc_row_inner' !== $tag ) {
				return $class_string;
			}

			$add_classes = array();

			// Tweak vc_row-has-fill class and add custom fill class.
			if ( false !== strpos( $class_string, 'vc_row-has-fill' ) ) {
				$class_string = str_replace( 'vc_row-has-fill', '', $class_string );
				$add_classes['wpex-vc_row-has-fill'] = 'wpex-vc_row-has-fill';
			}

			// Add fill class for parallax and video backgrounds.
			elseif ( ! empty( $atts['vcex_parallax'] ) || ! empty( $atts['wpex_self_hosted_video_bg'] ) ) {
				$add_classes['wpex-vc_row-has-fill'] = 'wpex-vc_row-has-fill';
			}

			// Visibility.
			if ( ! empty( $atts['visibility'] ) ) {
				$add_classes[] = wpex_visibility_class( $atts['visibility'] );
			}

			// Typography.
			if ( ! empty( $atts['typography_style'] ) ) {
				$add_classes[] = $atts['typography_style'];
			}

			// Full width.
			if ( ! empty( $atts['full_width'] ) ) {
				$add_classes[] = 'wpex-vc-row-stretched';
			}
			if ( ! empty( $atts['full_width_boxed_layout'] ) ) {
				$add_classes[] = 'wpex-vc-row-boxed-layout-stretched';
				if ( isset( $atts['full_width_style'] ) && 'stretch_row_content_no_spaces' == $atts['full_width_style'] ) {
					$add_classes[] = 'vc_row-no-padding';
				}
			}

			// Max width.
			if ( empty( $atts['full_width'] ) && ! empty( $atts['max_width'] ) ) {
				$add_classes[] = 'vc-has-max-width vc-max-width-' . $atts['max_width'];
			}

			// Custom max-width alignment
			if ( empty( $atts['full_width'] ) && ! empty( $atts['max_width_custom'] ) ) {
				$align = ! empty( $atts['max_width_align'] ) ? $atts['max_width_align'] : 'center';
				switch ( $align ) {
					case 'left':
						$add_classes[] = 'wpex-vc_row-mr-auto';
						break;
					case 'right':
						$add_classes[] = 'wpex-vc_row-ml-auto';
						break;
					case 'center':
					default:
						$add_classes[] = 'wpex-vc_row-mx-auto';
						break;
				}
			}

			// Centered row.
			if ( isset( $atts['center_row'] ) && 'yes' === $atts['center_row'] ) {
				$add_classes[] = 'wpex-vc-row-centered';
			}

			// Column spacing.
			if ( ! empty( $atts['column_spacing'] ) ) {
				$add_classes[] = 'wpex-vc-has-custom-column-spacing';
				$add_classes[] = 'wpex-vc-column-spacing-' . $atts['column_spacing'];
			}

			// Remove column bottom margin.
			if ( isset( $atts['remove_bottom_col_margin'] ) && 'true' == $atts['remove_bottom_col_margin'] ) {
				$add_classes[] = 'no-bottom-margins';
			}

			// Tablet.
			if ( isset( $atts['tablet_fullwidth_cols'] ) && 'yes' === $atts['tablet_fullwidth_cols'] ) {
				$add_classes[] = 'tablet-fullwidth-columns';
			}

			// Right hand columns.
			if ( isset( $atts['columns_right'] ) && 'yes' === $atts['columns_right'] ) {
				$add_classes[] = 'wpex-cols-right';
			}

			// BG class
			// @deprecated fallback
			if ( ! empty( $atts['bg_style_class'] ) ) {
				$add_classes[] = $atts['bg_style_class'];
			}

			// Fixed background.
			if ( ! empty( $atts['wpex_fixed_bg'] ) ) {
				$add_classes[] = 'bg-' . sanitize_html_class( $atts['wpex_fixed_bg'] );
			}

			// Header overlay offset.
			if ( isset( $atts['offset_overlay_header'] ) && 'yes' === $atts['offset_overlay_header'] ) {
				$add_classes[] = 'add-overlay-header-offset';
			}

			// Remove negative margins.
			if ( empty( $atts['full_width'] ) && isset( $add_classes['wpex-vc_row-has-fill'] ) ) {
				$add_classes[] = 'wpex-vc-reset-negative-margin';
			}

			// Add custom classes.
			if ( $add_classes ) {
				$add_classes = array_filter( $add_classes, 'sanitize_html_class' );
				$add_classes = array_filter( $add_classes, 'trim' );
				$class_string .= ' ' . implode( ' ', $add_classes );
			}

			// Return class string.
			return $class_string;

		}

		/**
		 * Add custom attributes to the row wrapper.
		 *
		 * @since 4.0
		 */
		public static function wrap_attributes( $wrapper_attributes, $atts ) {
			$inline_style = '';

			// Local scroll ID.
			if ( ! empty( $atts['local_scroll_id'] ) ) {
				$wrapper_attributes[] = 'data-ls_id="#' . esc_attr( $atts['local_scroll_id'] ) . '"';
			}

			// Z-Index.
			if ( ! empty( $atts['wpex_zindex'] ) ) {
				$inline_style .= 'z-index:' . esc_attr( $atts['wpex_zindex'] ) . '!important;';
			}

			// Custom background.
			if ( isset( $atts['wpex_post_thumbnail_bg'] ) && 'true' === $atts['wpex_post_thumbnail_bg'] ) {

				$thumbnail_id = '';

				$post_id = wpex_get_dynamic_post_id();

				if ( $post_id ) {
					$thumbnail_id = get_post_thumbnail_id( $post_id );
				} else {
					$thumbnail_id = wpex_get_term_thumbnail_id();
				}

				if ( $thumbnail_id && 0 !== $thumbnail_id ) {

					$inline_style .= 'background-image:url(' . esc_url( wp_get_attachment_image_url( $thumbnail_id, 'full' ) ) . ')';

					if ( apply_filters( 'wpex_vc_row_post_thumbnail_bg_has_important', true ) ) {
						$inline_style .= '!important';
					}

					$inline_style .= ';';

				}

			}

			// Min Height.
			if ( ! empty( $atts['min_height'] ) ) {

				// Sanitize min-height value.
				$min_height = $atts['min_height'];
				if ( ! preg_match('/[A-z]/', $min_height ) && strpos( $min_height, '%' ) === false ) {
					$min_height = intval( $min_height ) . 'px';
				}

				// Add min-height inline style.
				if ( $min_height ) {
					$inline_style .= 'min-height:' . $min_height . ';';
				}

			}

			// Max Width
			if ( empty( $atts['full_width'] ) && ! empty( $atts['max_width_custom'] ) ) {
				$inline_style .= 'max-width:' . absint( $atts['max_width_custom'] ) . 'px;';
			}

			// Background position.
			if ( ! empty( $atts['wpex_bg_position'] ) ) {
				$inline_style .= 'background-position:' . esc_attr( $atts['wpex_bg_position'] ) . ' !important;';
			}

			// Background size.
			if ( ! empty( $atts['wpex_bg_size'] ) ) {
				$inline_style .= 'background-size:' . esc_attr( $atts['wpex_bg_size'] ) . ' !important;';
			}

			// Inline css styles.
			// Fallback For OLD Total Params.
			if ( empty( $atts['css'] ) && wpex_vc_maybe_parse_deprecated_css_options( 'vc_row' ) ) {
				$inline_style .= Deprecated_CSS_Params_Style::generate_css( $atts, 'inline_css' );
			}

			// Add inline style to wrapper attributes.
			if ( $inline_style ) {
				$wrapper_attributes[] = 'style="' . esc_attr( $inline_style ) . '"';
			}

			// Return attributes.
			return $wrapper_attributes;

		}

		/**
		 * Open center row.
		 *
		 * Priority: 10
		 *
		 * @since 4.0
		 * @todo deprecate
		 */
		public static function center_row_open( $content, $atts ) {

			if ( ! empty( $atts['center_row'] ) ) {
				$content .= '<div class="center-row container"><div class="center-row-inner wpex-clr">';
			}

			return $content;

		}

		/**
		 * Close center row.
		 *
		 * Priority: 10
		 *
		 * @since 4.0
		 * @todo deprecate
		 */
		public static function center_row_close( $content, $atts ) {
			if ( ! empty( $atts['center_row'] ) ) {
				$content .= '</div></div>';
			}
			return $content;
		}

		/**
		 * Custom HTML output.
		 *
		 * @since 4.1
		 */
		public static function custom_output( $output, $obj, $atts, $shortcode ) {
			if ( 'vc_row' !== $shortcode ) {
				return $output;
			}

			// Check user settings.
			if ( isset( $atts['vcex_user_access'] )
				&& ! is_admin()
				&& ! vc_is_inline()
			) {
				$callback = ( 'custom' === $atts['vcex_user_access'] && isset( $atts['vcex_user_access_callback'] ) ) ? $atts['vcex_user_access_callback'] : '';
				if ( ! wpex_user_can_access( $atts['vcex_user_access'], $callback ) ) {
					return;
				}
			}

			// Return output.
			return $output;

		}

	}

}
new VCEX_VC_Row_Config();