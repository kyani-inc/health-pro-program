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
		$homeLink = '.nitronutritionlife.com/';
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
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
		  rel="stylesheet">
	<?php wp_head(); ?>
	<title><?php echo esc_html(wp_title()); ?> </title>
</head>

<body <?php body_class(); ?> <?php understrap_body_attributes(); ?>>

<!-- Google Tag Manager (noscript) -->
<script>
	function setCookie(sname, svalue, days) {
		const d = new Date();
		d.setTime(d.getTime() + (days * 24 * 60 * 60 * 1000));
		let expires = "expires=" + d.toUTCString();
		document.cookie = sname + "=" + svalue + ";" + expires + ";path=/;domain=nitronutritionlife.com";
	}

	function getCookie(sname) {
		let name = sname + "=";
		let sa = document.cookie.split(";");
		for (let i = 0; i < sa.length; i++) {
			let s = sa[i];
			while (s.charAt(0) == " ") {
				s = s.substring(1);
			}
			if (s.indexOf(name) == 0) {
				return s.substring(name.length, s.length);
			}
		}
		return "";
	}

	let host = window.location.host;
	let subdomain = host.split(".")[0];
	let path = window.location.pathname;
	let sponsor = getCookie("sponsor");
	if ((subdomain !== "nitronutritionlife") && (sponsor != subdomain)) {
		setCookie("sponsor", subdomain, 5);
	}

	var url = window.location.search;
	if (url.indexOf('?user=health-pro') !== -1) {
		setCookie("user", "health-pro", 1);
	}


</script>

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
					<a class="navbar-toggler nav-button mobile-only"><span
								id="nav-icon3">
							<span class="side-panel-btn"></span>
							<span class="side-panel-btn"></span>
							<span class="side-panel-btn"></span>
							<span class="side-panel-btn"></span>
							</span></a>
					<a href="<?php echo($homeLink != "" ? "//" . $homeLink : esc_url(home_url('/'))); ?>"
					   class="navbar-brand"><img
								src="<?php echo esc_url(bloginfo('template_directory') . "/images/kyani-blue-logo.svg") ?>"
								alt=""
								width="80"></a>
					<ul class="navbar-nav rep-nav">
						<?php echo do_shortcode('[replicatedDisplay]'); ?>
					</ul>
					<?php wp_nav_menu(
							array(
									'theme_location' => 'primary',
									'container_class' => 'collapse navbar-collapse',
									'container_id' => 'navbarNavDropdown ',
									'menu_class' => 'navbar-nav mr-auto desktop-only',
									'fallback_cb' => '',
									'menu_id' => 'main-menu',
									'depth' => 3,
									'walker' => new Custom_WP_Bootstrap_Navwalker()
							)
					); ?>
					<?php wp_nav_menu(
							array(
									'theme_location' => 'secondary',
									'container_class' => 'collapse navbar-collapse',
									'container_id' => 'navbarNavDropdown ',
									'menu_class' => 'navbar-nav ml-auto desktop-only',
									'fallback_cb' => '',
									'menu_id' => 'main-menu',
									'depth' => 3,
									'walker' => new Custom_WP_Bootstrap_Navwalker()
							)
					); ?>
					<ul class="navbar-nav cart-nav">
						<span class="mobile-only">
							<a href="<?php echo wc_get_cart_url(); ?>"><i class="fas fa-shopping-basket"></i></a>
						</span>
						<?php echo do_shortcode("[woo_cart_but]"); ?>
					</ul>
					<?php if ('container' === $container) : ?>
				</div>
			<?php endif; ?>
			</nav><!-- .site-navigation -->
			<?php endif; ?>
			<div class="main-menu" id="side-panel-menu">
				<div class="main-menu-container flex-column d-flex">
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
