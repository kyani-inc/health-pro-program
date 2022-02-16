<?php
/**
 * Skip To Content.
 *
 * @package Total WordPress theme
 * @subpackage Partials
 * @version 5.2
 */

defined( 'ABSPATH' ) || exit;

// Get default content ID.
$id = ( $id = get_theme_mod( 'skip_to_content_id' ) ) ? $id : 'content';

// Check for meta set value.
$meta = get_post_meta( wpex_get_current_post_id(), 'skip_to_content_id', true );
if ( $meta && is_string( $meta ) ) {
    $id = $meta;
}

/**
 * Filters the skip to content id.
 *
 * @param string $id Element ID name for the skip to content link, default is "content".
 */
$id = apply_filters( 'wpex_skip_to_content_id', $id );
?>

<a href="<?php echo esc_url( '#' . str_replace( '#', '', $id ) ); ?>" class="skip-to-content"<?php wpex_aria_landmark( 'skip_to_content' ); ?>><?php echo esc_html__( 'skip to Main Content', 'total' ); ?></a>