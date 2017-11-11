<?php
/**
 * The template part for displaying results in search pages
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( sprintf( '<h2 class="entry-title searchResults__title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

		<p class="searchResults__date"><?php echo get_the_date('F j, Y'); ?></p>
	</header>

	<div class="searchResults__imgWrapper">
		<?php twentysixteen_post_thumbnail(); ?>
	</div>

	<div class="searchResults__info">
		<?php twentysixteen_excerpt(); ?>
	</div>


	<?php if ( 'post' === get_post_type() ) : ?>

		<footer class="searchResults__footer entry-footer">
			<?php
				// twentysixteen_entry_meta();
				$posttags = get_the_tags();
				if ($posttags) {
					echo '<div class="searchResults__meta"><span class="searchResults__metaTitle">Метки:</span>';
					foreach($posttags as $tag) {
						$name = $tag->name;
						$link = get_tag_link($tag->term_id);
						echo '<a class="" href="' . $link . '">' . $name . '</a>';
					}
					echo '</div>';
				}

				$categories = get_the_category($post_id);
				if ($categories) {
					echo '<div class="searchResults__meta"><span class="searchResults__metaTitle">Категории:</span>';
					foreach ($categories as $category) {
						echo '<a href="'. get_category_link($category->term_id) . '">' . $category->cat_name . '</a>';
					}
					echo '</div>';
				}
			?>
		</footer>

	<?php else : ?>



	<?php endif; ?>
</article>
