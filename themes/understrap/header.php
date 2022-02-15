<?php

/**
 * The header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$container = get_theme_mod('understrap_container_type');

$logoLink = "";
$logoWidth = "";
$homeLink = "";

if (isset($_SERVER['HTTP_X_KYANI_REP'])) {
	$rep = explode(';', $_SERVER['HTTP_X_KYANI_REP'])[0];
	if (!($rep === "")) {
		$logoLink = "kyani-blue-logo-bp.svg";
		$logoWidth = "180";
		$homeLink = $rep . '.' . $_SERVER['HTTP_HOST'] . get_blog_details(get_current_blog_id())->path;
	} else {
		$logoLink = "kyani-blue-logo.svg";
		$logoWidth = "80";
	}

} else {
	$logoLink = "kyani-blue-logo.svg";
	$logoWidth = "80";
}


?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>


<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<script src="https://kit.fontawesome.com/0b3c9b4cc0.js" crossorigin="anonymous"></script>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
	<title><?php echo esc_html(wp_title()); ?> </title>
</head>

<body <?php body_class(); ?> <?php understrap_body_attributes(); ?> onload="console.log('test');">

<!-- Google Tag Manager (noscript) -->
<noscript>
	<iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P97Z6NN"
			height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->

<?php do_action('wp_body_open'); ?>
<div class="site" id="page">

	<!-- ******************* The Navbar Area ******************* -->
	<?php if ((is_page() || is_singular())) : ?>
	<div id="wrapper-navbar">

		<a class="skip-link sr-only sr-only-focusable"
		   href="#content"><?php esc_html_e('Skip to content', 'understrap'); ?></a>
		<?php if (is_admin_bar_showing()) { ?>
		<nav id="main-nav" class="navbar navbar-expand-md navbar-dark fixed-top kyani-nav px-5"
			 aria-labelledby="main-nav-label" style="margin-top: 32px">
			<?php } else { ?>
			<nav id="main-nav" class="navbar navbar-expand-md navbar-dark fixed-top kyani-nav"
				 aria-labelledby="main-nav-label">
				<?php } ?>
				<h2 id="main-nav-label" class="sr-only">
					<?php esc_html_e('Main Navigation', 'understrap'); ?>
				</h2>

				<?php if ('container' === $container) : ?>
				<div class="container">
					<?php endif; ?>

					<a href="<?php echo($homeLink != "" ? "//" . $homeLink : esc_url(home_url('/'))); ?>"
					   class="navbar-brand"><img
								src="<?php echo esc_url(bloginfo('template_directory') . "/images/" . $logoLink) ?>"
								alt=""
								width=<?php echo esc_attr($logoWidth) ?>></a>
					<ul class="navbar-nav desktop-only">
					<?php echo do_shortcode('[replicatedDisplay]'); ?>
					<?php echo do_shortcode('[navShopLink]'); ?>
					</ul>
					<?php wp_nav_menu(
							array(
									'theme_location' => 'primary',
									'container_class' => 'collapse navbar-collapse',
									'container_id' => 'navbarNavDropdown ',
									'menu_class' => 'navbar-nav ml-auto desktop-only',
									'fallback_cb' => '',
									'menu_id' => 'main-menu',
									'depth' => 3,
									'walker' => new Custom_WP_Bootstrap_Navwalker()
							)
					); ?>
					<a class="navbar-toggler nav-button ml-auto mobile-only"><span
								id="nav-icon3">
							<span class="side-panel-btn"></span>
							<span class="side-panel-btn"></span>
							<span class="side-panel-btn"></span>
							<span class="side-panel-btn"></span>
							</span></a>
					<?php if ('container' === $container) : ?>
				</div>
			<?php endif; ?>
			</nav><!-- .site-navigation -->
			<?php endif; ?>
				<div class="main-menu" id="side-panel-menu">
					<div class="main-menu-container flex-column d-flex">
						<ul class="nav flex-column">
						<?php echo do_shortcode('[replicatedDisplayMobile]'); ?>
						<?php echo do_shortcode('[navShopLink]'); ?>
						</ul>
						<?php
						wp_nav_menu(array(
								'theme_location' => 'mobile',
								'container' => false,
								'menu_class' => 'nav flex-column flex-fill',
								'add_li_class' => 'nav-item',
								'depth' => 3,
								'walker' => new Custom_WP_Bootstrap_Navwalker(),
						));
						?>
					</div>
				</div>
			<!--main-menu end-->
	</div><!-- #wrapper-navbar end -->
