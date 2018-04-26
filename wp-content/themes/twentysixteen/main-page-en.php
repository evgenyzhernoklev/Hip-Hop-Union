<?php
/*
Template Name: Шаблон главной страницы (english)
*/
?>

<?php get_header(); ?>

<main class="contentIn contentIn--full" role="main">

	<?php
	// запрос
	$wpb_all_query = new WP_Query(array(
																	'post_type'				=>'news',
																	'category_name'		=> 'Main news',
																	'post_status'			=>'publish',
																	'posts_per_page'	=> 3
																)); ?>

	<?php if ( $wpb_all_query->have_posts() ) : ?>

		<div class="clear posts">
			<?php
				while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
					get_template_part( 'template-parts/content', 'archive' );
				endwhile;
			?>
		</div>

		<?php wp_reset_postdata(); ?>

	<?php else : ?>
		<p><?php echo 'No posts found.'; ?></p>
	<?php endif; ?>

	<div class="idea overlay" style="background-image: url('<?php the_post_thumbnail_url(); ?>');">
		<div class="overlay__in"></div>
		<div class="ideaIn colsFlex">
			<div class="colsFlex__col--2">
				<?php the_custom_logo(); ?>
			</div>
			<div class="ideaInContent colsFlex__col--2">
				<?php the_content(); ?>
			</div>
		</div>
	</div>

	<?php
		$id_current = get_the_ID();
		$we_do = get_post_meta($id_current, 'what_we_do');
		$we_do_title = get_post_meta($id_current, 'what_we_do_title', true);
		$we_do_bg = get_post_meta($id_current, 'what_we_do_bg', true);
	?>
	<div class="weDo overlay" style="background-image: url('<?php echo $we_do_bg; ?>');">
		<div class="overlay__in"></div>
		<div class="weDoIn">
			<h2 class="subtitle subtitle--white"><?php echo $we_do_title; ?></h2>
			<div class="weDoCols colsFlex">
				<?php
					foreach ($we_do as $element) {
						echo '<div class="weDoCol colsFlex__col--3"><div class="weDoCol__in">';
						echo $element;
						echo '</div></div>';
					}
				?>
			</div>
			<div class="weDoInfo">
				You can contact the leaders of the movement through a
				<a href="/en/contacts/">special form</a> on our website.
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
