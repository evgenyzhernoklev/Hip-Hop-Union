<?php
/**
 * The template part for displaying a message that posts cannot be found
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<h1 class="titlePage">No posts found.</h1>

<div class="entry-content searchContent contentIn contentIn--small">
	<?php if ( is_search() ) : ?>

		<p class="searchContent__info"><?php echo 'Sorry, but nothing matched your search terms. Please try again with some different keywords.'; ?></p>
		<?php get_search_form(); ?>

	<?php else : ?>

		<p class="searchContent__info"><?php echo 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.'; ?></p>
		<?php get_search_form(); ?>

	<?php endif; ?>
</div>
