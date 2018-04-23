<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

	$title = sprintf( __( '%1$s' ), single_term_title( '', false ) );
	$category_description = category_description();

get_header(); ?>

<main class="contentIn contentIn--full" role="main">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<h1 class="titlePage"><?php echo $title; ?></h1>
		</header>

		<?php if ( $category_description ) : ?>
			<div class="singleContent singleContent--category">
				<div class="entry-summary">
					<?php echo $category_description; ?>
				</div>
			</div>
		<?php endif; ?>

		<?php
		echo '<div class="clear posts">';
		// Start the Loop.
		while ( have_posts() ) : the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content', 'archive' );

		// End the loop.
		endwhile;
		echo '</div>';

		wp_pagenavi();
	// If no content, include the "No posts found" template.
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>

</main>

<?php get_footer(); ?>
