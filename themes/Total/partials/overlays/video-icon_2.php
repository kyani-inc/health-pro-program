<?php
defined( 'ABSPATH' ) || exit;

if ( 'inside_link' !== $position ) {
	return;
}

?>

<div class="overlay-video-icon_2 theme-overlay wpex-absolute wpex-inset-0 wpex-flex wpex-items-center wpex-justify-center" aria-hidden="true">
    <span class="overlay-bg wpex-bg-<?php echo wpex_overlay_bg( 'video-icon_2' ); ?> wpex-block wpex-absolute wpex-inset-0 wpex-opacity-<?php echo wpex_overlay_opacity( 'video-icon_2', '20' ); ?>"></span>
    <svg class="overlay__video-svg wpex-transition-transform wpex-duration-300 wpex-max-w-20 wpex-relative" xmlns="http://www.w3.org/2000/svg" height="60px" width="60px" viewBox="0 0 24 24" fill="#FFFFFF"><path d="M8 6.82v10.36c0 .79.87 1.27 1.54.84l8.14-5.18c.62-.39.62-1.29 0-1.69L9.54 5.98C8.87 5.55 8 6.03 8 6.82z"/></svg>
</div>