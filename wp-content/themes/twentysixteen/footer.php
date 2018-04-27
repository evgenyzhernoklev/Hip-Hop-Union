<?php
/**
 * The template for displaying the footer
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

	</div><!-- .site-content -->

	<footer class="footer content" role="contentinfo">
		<div class="contentIn">

			<?php echo hip_logo(); ?>

			<?php
				if ( has_slug('en') || has_slug('news') ) :
					get_sidebar( 'content-bottom-english' );
				else :
					get_sidebar( 'content-bottom' );
				endif;
			?>

			<?php if ( has_nav_menu( 'social' ) ) : ?>
				<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Social Links Menu', 'twentysixteen' ); ?>">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'social',
							'menu_class'     => 'social-links-menu',
							'depth'          => 1,
							'link_before'    => '<span class="screen-reader-text">',
							'link_after'     => '</span>',
						) );
					?>
				</nav>
			<?php endif; ?>

		</div>
	</footer>

<?php wp_footer(); ?>
</body>
</html>
