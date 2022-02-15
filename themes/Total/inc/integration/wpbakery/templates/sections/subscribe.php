<?php
defined( 'ABSPATH' ) || exit;

$templates['subscribe-1'] = array();
$templates['subscribe-1']['name'] = esc_html__( 'Subscribe', 'total' ) . ' 1';
$templates['subscribe-1']['category'] = 'subscribe';
$templates['subscribe-1']['content'] = <<<CONTENT
[vc_row remove_bottom_col_margin="true" css=".vc_custom_1627255279491{padding-top: 60px !important;padding-bottom: 70px !important;}"][vc_column][vcex_heading text="Join our Newsletter" text_align="center" tag="h2" font_size="30px" font_weight="700"][vcex_spacing size="20px"][vc_column_text width="650px" align="center" text_align="center"]Subscribe to the newsletter to receive our latest tips, tricks, tutorials and news directly in your inbox. We will not spam and you can cancel at anytime.[/vc_column_text][vcex_spacing size="35px"][vcex_newsletter_form input_align="center" fullwidth_mobile="true" input_width="550px"][/vc_column][/vc_row]
CONTENT;

$templates['subscribe-2'] = array();
$templates['subscribe-2']['name'] = esc_html__( 'Subscribe', 'total' ) . ' 2';
$templates['subscribe-2']['category'] = 'subscribe';
$templates['subscribe-2']['content'] = <<<CONTENT
[vc_row full_width="stretch_row" remove_bottom_col_margin="true" css=".vc_custom_1627252924489{padding-top: 60px !important;padding-bottom: 70px !important;background-color: #3858e9 !important;}"][vc_column][vcex_heading text="Join our Newsletter" text_align="center" tag="h2" font_size="30px" font_weight="700" color="#ffffff" el_class="wpex-align-top"][vcex_spacing size="20px"][vc_column_text width="650px" align="center" text_align="center" color="rgba(255,255,255,0.7)"]Subscribe to the newsletter to receive our latest tips, tricks, tutorials and news directly in your inbox. We will not spam and you can cancel at anytime.[/vc_column_text][vcex_spacing size="35px"][vcex_newsletter_form input_align="center" fullwidth_mobile="true" gap="15px" input_width="550px" submit_bg="#222222" submit_color="#ffffff" input_border="0px"][/vc_column][/vc_row]
CONTENT;

$templates['subscribe-3'] = array();
$templates['subscribe-3']['name'] = esc_html__( 'Subscribe', 'total' ) . ' 3';
$templates['subscribe-3']['category'] = 'subscribe';
$templates['subscribe-3']['content'] = <<<CONTENT
[vc_row content_placement="middle" remove_bottom_col_margin="true"][vc_column width="1/2" css=".vc_custom_1627253537830{padding-top: 60px !important;padding-bottom: 60px !important;}"][vcex_heading text="Join the Newsletter" tag="h2" font_size="30px" font_weight="700"][vcex_spacing size="15px"][vc_column_text]Subscribe to the newsletter to receive our latest tips, tricks, tutorials and news directly in your inbox. We will not spam and you can cancel at anytime.[/vc_column_text][vcex_spacing][vcex_newsletter_form fullwidth_mobile="true"][/vc_column][vc_column width="1/2"][vcex_image source="external" external_image="{$ph_landscape}"][/vc_column][/vc_row]
CONTENT;

$templates['subscribe-4'] = array();
$templates['subscribe-4']['name'] = esc_html__( 'Subscribe', 'total' ) . ' 4';
$templates['subscribe-4']['category'] = 'subscribe';
$templates['subscribe-4']['content'] = <<<CONTENT
[vc_row full_width="stretch_row" remove_bottom_col_margin="true" css=".vc_custom_1626913069316{padding-top: 60px !important;padding-bottom: 60px !important;background-color: #f7f7f7 !important;}"][vc_column][vcex_flex_container shadow="shadow-xl" flex_direction="column" css=".vc_custom_1626914557049{padding-top: 50px !important;padding-right: 40px !important;padding-bottom: 50px !important;padding-left: 40px !important;background-color: #ffffff !important;border-radius: 10px !important;}" gap="0px" width="900px"][vcex_heading text="Join Our Newsletter" bottom_margin="20px" text_align="center" tag="h2" font_size="30px" font_weight="700"][vc_column_text width="650px" align="center" text_align="center" css=".vc_custom_1627253471796{margin-bottom: 25px !important;}"]Subscribe to the newsletter to receive our latest tips, tricks, tutorials and news directly in your inbox. We will not spam and you can cancel at anytime.[/vc_column_text][vcex_newsletter_form input_align="center" fullwidth_mobile="true" gap="10px" placeholder_text="email@example.com" submit_border_radius="8px" input_border_color="#bdbdbd" input_border_radius="8px" input_font_size="16px" input_width="550px" submit_letter_spacing="1px"][/vcex_flex_container][/vc_column][/vc_row]
CONTENT;

$templates['subscribe-5'] = array();
$templates['subscribe-5']['name'] = esc_html__( 'Subscribe', 'total' ) . ' 5';
$templates['subscribe-5']['category'] = 'subscribe';
$templates['subscribe-5']['content'] = <<<CONTENT
[vc_row][vc_column][vcex_flex_container flex_direction="column" css=".vc_custom_1626979445556{border-top-width: 1px !important;border-right-width: 1px !important;border-bottom-width: 1px !important;border-left-width: 1px !important;padding-top: 50px !important;padding-right: 50px !important;padding-bottom: 50px !important;padding-left: 50px !important;background-color: #ffffff !important;border-left-color: #e0e0e0 !important;border-left-style: solid !important;border-right-color: #e0e0e0 !important;border-right-style: solid !important;border-top-color: #e0e0e0 !important;border-top-style: solid !important;border-bottom-color: #e0e0e0 !important;border-bottom-style: solid !important;}" gap="0px" width="480px"][vcex_icon icon="ticon ticon-envelope-open-o" bottom_margin="20px" align="center" custom_size="45px" el_class="wpex-leading-none" color="#424242"][vcex_heading text="Join Our Newsletter" bottom_margin="15px" text_align="center" tag="h2" font_size="1.8em" font_weight="600"][vc_column_text text_align="center" css=".vc_custom_1626979426054{margin-bottom: 30px !important;}"]Subscribe to our newsletter for the latest news and products straight to your inbox.[/vc_column_text][vc_column_text font_weight="bold" css=".vc_custom_1626979441732{margin-bottom: 5px !important;}"]Email Address<sup style="color: #ff0000;">*</sup>[/vc_column_text][vcex_newsletter_form stack_fields="true" placeholder_text="you@example.com" submit_text="Subscribe" submit_border_radius="4px" input_border_color="#bdbdbd" input_border_radius="4px" input_font_size="1.1em"][vc_column_text width="650px" align="center" text_align="center" css=".vc_custom_1626914944446{margin-top: 15px !important;}" color="#9e9e9e"]We respect your privacy. No spam![/vc_column_text][/vcex_flex_container][/vc_column][/vc_row]
CONTENT;

$templates['subscribe-6'] = array();
$templates['subscribe-6']['name'] = esc_html__( 'Subscribe', 'total' ) . ' 6';
$templates['subscribe-6']['category'] = 'subscribe';
$templates['subscribe-6']['content'] = <<<CONTENT
[vc_row full_width="stretch_row" remove_bottom_col_margin="true" css=".vc_custom_1626982780556{padding-top: 60px !important;padding-bottom: 60px !important;background-color: #222222 !important;}"][vc_column][vcex_flex_container flex_grow="true" flex_wrap="true" flex_direction="row" flex_basis="480px,auto" gap="30px"][vc_column_text color="#ffffff" font_size="1.6em" font_family="PT Serif"]Join over 50,000 entrepreneurs and freelancers and take your business to the next level.[/vc_column_text][vcex_newsletter_form gap="10px" placeholder_text="email@example.com" submit_text="Subscribe" input_border_radius="4px" submit_border_radius="4px" input_bg="rgba(34,34,34,0.01)" input_border="2px solid #616161" input_font_size="16px" submit_letter_spacing="1px" submit_weight="bold" input_color="#ffffff"][/vcex_flex_container][/vc_column][/vc_row]
CONTENT;
// IMPORTANT - Space required to prevent HEREDOC errors.
?>