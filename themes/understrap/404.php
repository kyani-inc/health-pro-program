<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @package understrap
 */

// Exit if accessed directly.
defined('ABSPATH') || exit;

header("HTTP/1.1 301 Moved Permanently");
header("Location: ".get_bloginfo('url'));
exit();
