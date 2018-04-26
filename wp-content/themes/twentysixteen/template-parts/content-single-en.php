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
	$post_type = get_post_type();
	$author = '';
	$title_length = mb_strlen( get_the_title() );
	$title_class = '';

	if ($post_type == 'glossary') :
		$author = get_post_meta($post->ID, '_glossary_author_meta_key', true);
	else :
		$author = get_post_meta($post->ID, '_post_author_meta_key', true);
	endif;

	if (!$author) :
		$author = get_the_author();
	endif;

	if ($title_length > 100) :
		$title_class = ' singleHeader__title--small';
	endif;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header singleHeader">
		<div class="singleHeader__img" style="background-image: url('<?php the_post_thumbnail_url(); ?>')"></div>
		<div class="singleHeader__overlay"></div>

		<div class="contentIn contentIn--small">
			<span class="singleHeader__date"><?php echo mysql2date('F j, Y', $post->post_date, false ); ?></span>
			<span class="singleHeader__author">Author: <?php echo $author; ?></span>
			<?php the_title( '<h1 class="entry-title singleHeader__title' . $title_class . '">', '</h1>' ); ?>
			<?php echo get_the_category_list(); ?>
		</div>
	</header>

	<div class="entry-content singleContent contentIn contentIn--small">
		<?php
			twentysixteen_excerpt();
			the_content();
			echo attached_file();
		?>
	</div>

	<footer class="entry-footer singleFooter contentIn contentIn--small">
		<div class="singleFooterIn">

				<?php
					$posttags = get_the_tags();
					if ($posttags) {
				?>
					<div class="tags">
						<h4 class="tags__title subtitle">Tags</h4>
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
						<h5 class="postsRelated__title subtitle">Recommended</h5>

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
