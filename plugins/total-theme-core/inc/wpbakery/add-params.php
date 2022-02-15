<?php
/**
 * Add Custom WPBakery Shortcode Parameters.
 *
 * @package TotalThemeCore
 * @version 1.2.8
 */

defined( 'ABSPATH' ) || exit;

$js = vcex_wpbakery_asset_url( 'js/params/vcex-params.min.js?v=' . TTC_VERSION );

vc_add_shortcode_param(
	'vcex_colorpicker',
	array( 'TotalThemeCore\WPBakery\Params\Colorpicker', 'output' )
);

vc_add_shortcode_param(
	'vcex_subheading',
	array( 'TotalThemeCore\WPBakery\Params\Subheading', 'output' )
);

vc_add_shortcode_param(
	'vcex_attach_images',
	array( 'TotalThemeCore\WPBakery\Params\Attach_Images', 'output' )
);

vc_add_shortcode_param(
	'vcex_sorter',
	array( 'TotalThemeCore\WPBakery\Params\Sorter', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_button_colors',
	array( 'TotalThemeCore\WPBakery\Params\Button_Colors', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_button_styles',
	array( 'TotalThemeCore\WPBakery\Params\Button_Styles', 'output' )
);

vc_add_shortcode_param(
	'vcex_carousel_arrow_positions',
	array( 'TotalThemeCore\WPBakery\Params\Carousel_Arrow_Position', 'output' )
);

vc_add_shortcode_param(
	'vcex_carousel_arrow_styles',
	array( 'TotalThemeCore\WPBakery\Params\Carousel_Arrow_Style', 'output' )
);

vc_add_shortcode_param(
	'vcex_font_family_select',
	array( 'TotalThemeCore\WPBakery\Params\Font_Family', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_font_weight',
	array( 'TotalThemeCore\WPBakery\Params\Font_Weight', 'output' )
);

vc_add_shortcode_param(
	'vcex_grid_columns',
	array( 'TotalThemeCore\WPBakery\Params\Grid_Column', 'output' )
);

vc_add_shortcode_param(
	'vcex_column_gaps',
	array( 'TotalThemeCore\WPBakery\Params\Grid_Column_Gap', 'output' )
);

vc_add_shortcode_param(
	'vcex_grid_columns_responsive',
	array( 'TotalThemeCore\WPBakery\Params\Grid_Column_Responsive', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_hover_animations',
	array( 'TotalThemeCore\WPBakery\Params\Hover_Animation', 'output' )
);

vc_add_shortcode_param(
	'vcex_image_crop_locations',
	array( 'TotalThemeCore\WPBakery\Params\Image_Crop_Location', 'output' )
);

vc_add_shortcode_param(
	'vcex_overlay',
	array( 'TotalThemeCore\WPBakery\Params\Image_Overlay', 'output' )
);

vc_add_shortcode_param(
	'vcex_image_filters',
	array( 'TotalThemeCore\WPBakery\Params\Image_Filter', 'output' )
);

vc_add_shortcode_param(
	'vcex_image_hovers',
	array( 'TotalThemeCore\WPBakery\Params\Image_Hover', 'output' )
);

vc_add_shortcode_param(
	'vcex_image_sizes',
	array( 'TotalThemeCore\WPBakery\Params\Image_Size', 'output' )
);

vc_add_shortcode_param(
	'vcex_menus_select',
	array( 'TotalThemeCore\WPBakery\Params\Menu', 'output' )
);

vc_add_shortcode_param(
	'vcex_notice',
	array( 'TotalThemeCore\WPBakery\Params\Notice', 'output' )
);

vc_add_shortcode_param(
	'vcex_number',
	array( 'TotalThemeCore\WPBakery\Params\Number', 'output' )
);

vc_add_shortcode_param(
	'vcex_ofswitch',
	array( 'TotalThemeCore\WPBakery\Params\On_Off_Switch', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_orderby',
	array( 'TotalThemeCore\WPBakery\Params\Query_Orderby', 'output' )
);

vc_add_shortcode_param(
	'vcex_responsive_sizes',
	array( 'TotalThemeCore\WPBakery\Params\Responsive_Input', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_visibility',
	array( 'TotalThemeCore\WPBakery\Params\Select_Visibility', 'output' )
);

vc_add_shortcode_param(
	'vcex_select_buttons',
	array( 'TotalThemeCore\WPBakery\Params\Select_Buttons', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_social_button_styles',
	array( 'TotalThemeCore\WPBakery\Params\Select_Social_Button_Style', 'output' )
);

vc_add_shortcode_param(
	'vcex_text_alignments',
	array( 'TotalThemeCore\WPBakery\Params\Text_Align', 'output' )
);

vc_add_shortcode_param(
	'vcex_text_transforms',
	array( 'TotalThemeCore\WPBakery\Params\Text_Transform', 'output' )
);

vc_add_shortcode_param(
	'vcex_trbl',
	array( 'TotalThemeCore\WPBakery\Params\Top_Right_Bottom_Left', 'output' ),
	$js
);

vc_add_shortcode_param(
	'vcex_template_select',
	array( 'TotalThemeCore\WPBakery\Params\Select_Template', 'output' )
);

if ( get_theme_mod( 'cards_enable', true ) ) {

	vc_add_shortcode_param(
		'vcex_wpex_card_select',
		array( 'TotalThemeCore\WPBakery\Params\Select_Card_Style', 'output' )
	);

}

if ( defined( 'WPCF7_VERSION' ) ) {
	vc_add_shortcode_param(
		'vcex_cf7_select',
		array( 'TotalThemeCore\WPBakery\Params\Cf7_Select', 'output' )
	);
}