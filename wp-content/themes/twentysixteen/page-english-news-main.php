<?php
/*
Template Name: Шаблон страницы english -> main news
*/

get_header(); ?>

<main class="contentIn contentIn--full" role="main">

	<?php
	// запрос
	$wpb_all_query = new WP_Query(array(
																	'post_type'				=>'news',
																	'category_name'		=> 'Main news',
																	'post_status'			=>'publish',
																	'posts_per_page'	=> 1
																)); ?>

	<?php if ( $wpb_all_query->have_posts() ) : ?>
		<?php the_title( '<h1 class="entry-title titlePage">', '</h1>' ); ?>

		<div class="clear posts">
			<?php
				while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post();
					get_template_part( 'template-parts/content', 'archive' );
				endwhile;
			?>
		</div>

		<?php wp_pagenavi( array( 'query' => $wpb_all_query ) ); ?>

		<?php wp_reset_postdata(); ?>

	<?php
	else :
		get_template_part( 'template-parts/content', 'none-en' );
	endif;
	?>

</main>

<?php get_footer(); ?>
