<?php
/*
Template Name: Шаблон главной страницы
*/
?>

<?php get_header(); ?>

<main class="contentIn contentIn--full" role="main">
	<?php
	// запрос
	$wpb_all_query = new WP_Query(array(
																	'post_type'				=>'post',
																	'post_status'			=>'publish',
																	'posts_per_page'	=> -1,
																	'order' 					=> 'ASC',
																	'posts_per_page'	=> 6
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
		<p><?php _e( 'Извините, нет записей, соответствуюших Вашему запросу.' ); ?></p>
	<?php endif; ?>
</main>

<?php get_footer(); ?>
