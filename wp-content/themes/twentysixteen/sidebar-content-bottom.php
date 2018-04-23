<?php
/**
 * The template for the content bottom widget areas in footer
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

if ( !is_active_sidebar( 'sidebar-2' ) ) {
	return;
}

// If we get this far, we have widgets. Let's do this.
?>

<?php if ( is_active_sidebar( 'sidebar-2' ) ) : ?>
	<?php dynamic_sidebar( 'sidebar-2' ); ?>
<?php endif; ?>
