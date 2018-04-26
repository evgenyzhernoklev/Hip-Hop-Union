<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<main class="contentIn" role="main">
	<?php
	while ( have_posts() ) : the_post();

		$post_type = get_post_type();

		if ( $post_type == 'news' ) :
			get_template_part( 'template-parts/content', 'single-en' );
		else :
			// Include the single post content template.
			get_template_part( 'template-parts/content', 'single' );

			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		endif;

	endwhile;
	?>

</main>

<?php get_footer(); ?>
