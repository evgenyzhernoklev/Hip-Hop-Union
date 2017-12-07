<?php
/**
 * The template part for displaying single posts
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<?php
	$author = get_post_meta($post->ID, '_post_author_meta_key', true);

	if (!$author) :
		$author = get_the_author();
	endif;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header singleHeader">
		<div class="singleHeader__img" style="background-image: url('<?php the_post_thumbnail_url(); ?>')"></div>
		<div class="singleHeader__overlay"></div>

		<div class="contentIn contentIn--small">
			<span class="singleHeader__date"><?php echo get_the_date('F j, Y'); ?></span>
			<span class="singleHeader__author">Автор: <?php echo $author; ?></span>
			<?php the_title( '<h1 class="entry-title singleHeader__title">', '</h1>' ); ?>
			<?php echo get_the_category_list(); ?>
		</div>
	</header>

	<div class="entry-content singleContent contentIn contentIn--small">
		<?php twentysixteen_excerpt(); ?>

		<?php
			the_content();

			// wp_link_pages( array(
			// 	'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentysixteen' ) . '</span>',
			// 	'after'       => '</div>',
			// 	'link_before' => '<span>',
			// 	'link_after'  => '</span>',
			// 	'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>%',
			// 	'separator'   => '<span class="screen-reader-text">, </span>',
			// ) );

		?>
	</div>

	<footer class="entry-footer singleFooter contentIn contentIn--small">
		<div class="singleFooterIn">

				<?php
					$posttags = get_the_tags();
					if ($posttags) {
				?>
					<div class="tags">
						<h4 class="tags__title subtitle">Метки</h4>
						<?php
							foreach($posttags as $tag) {
								$name = $tag->name;
								$link = get_tag_link($tag->term_id);
								echo '<a class="tags__link" href="' . $link . '">' . $name . '</a>';
							}
						?>
					</div>
				<?php } ?>

				<?php
					$categories = get_the_category($post->ID);
					if ($categories) {
				?>

					<div class="postsRelated">
						<h5 class="postsRelated__title subtitle">Рекомендуем</h5>

						<?php
							$category_ids = array();

							foreach ($categories as $individual_category) $category_ids[] = $individual_category->term_id;

							$args = array(
								'category__in' 			=> $category_ids,
								'post__not_in' 			=> array($post->ID),
								'showposts'		 			=> 2,
								'orderby'			 			=> rand,
								'caller_get_posts'	=> 1
							);
							$my_query = new wp_query($args);

							if ( $my_query->have_posts() ) {
								echo '<div class="clear posts posts--related">';

								while ($my_query->have_posts()) {
									$my_query->the_post();

									get_template_part( 'template-parts/content', 'archive' );

								}

								echo '</div>';
							}

							wp_reset_query();
						?>

					</div>

				<?php
					}
				?>

		</div>
	</footer>

	<span class="scroll-top"></span>
</article>
