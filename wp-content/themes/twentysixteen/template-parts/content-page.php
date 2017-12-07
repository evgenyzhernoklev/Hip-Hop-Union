<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title titlePage titlePage--page">', '</h1>' ); ?>
	</header>

	<?php
		// twentysixteen_post_thumbnail();
	?>

	<div class="entry-content singleContent">
		<?php the_content(); ?>
	</div>

	<footer class="entry-footer singleFooter">
		<div class="singleFooterIn">

		</div>
	</footer>

	<span class="scroll-top"></span>
</article>
