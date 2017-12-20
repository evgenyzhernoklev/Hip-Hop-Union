<?php
/**
 * The template for displaying search results pages
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>

<main class="contentIn searchResults" role="main">
	<?php if ( have_posts() ) : ?>

		<header class="page-header">
			<h1 class="page-title titlePage"><?php printf( __( 'Search Results for: %s', 'twentysixteen' ), '<br/><span class="titlePage__request">&quot;' . esc_html( get_search_query() ) . '&quot;</span>' ); ?></h1>
		</header>

		<?php
		// Start the loop.
		while ( have_posts() ) : the_post();

			/**
			 * Run the loop for the search to output the results.
			 * If you want to overload this in a child theme then include a file
			 * called content-search.php and that will be used instead.
			 */
			get_template_part( 'template-parts/content', 'search' );

		// End the loop.
		endwhile;

		wp_pagenavi();

	// If no content, include the "No posts found" template.
	else :
		get_template_part( 'template-parts/content', 'none' );
	endif;
	?>
</main>

<?php get_footer(); ?>
