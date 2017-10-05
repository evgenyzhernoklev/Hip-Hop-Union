<?php
/*
Template Name: Шаблон страницы партнеров
*/
?>

<?php get_header(); ?>

<main class="contentIn" role="main">
	<?php the_title( '<h1 class="titleMain">', '</h1>' ); ?>

	<?php
	// запрос
	$wpb_all_query = new WP_Query(array('post_type'=>'partner', 'post_status'=>'publish', 'posts_per_page'=>-1)); ?>

	<?php if ( $wpb_all_query->have_posts() ) : ?>

		<div class="cols cols--2">
			<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>

				<?php $link = get_post_meta($post->ID, '_partner_link_meta_key', true); ?>

				<div class="cols__col cols__col--2">

					<?php
						if ( $link ) :
							echo 'nigger';
						else :
							echo 'nothing for nigger';
						endif;
					?>

					<div class="partner">
						<div class="partnerImg">
							<a class="partnerImg__link" href="<?php echo $link ?>" target="_blank">
								<?php the_post_thumbnail(); ?>
							</a>
						</div>
						<h2 class="partnerTitle"><a class="partnerTitle__link"
																				href="<?php echo $link ?>"
																				target="_blank"><?php the_title(); ?></a></h2>
						<div class="partnerInfo">
							<?php the_excerpt(); ?>
						</div>
					</div>
				</div>

			<?php endwhile; ?>
		</div>

		<?php wp_reset_postdata(); ?>

	<?php else : ?>
		<p><?php _e( 'Извините, нет записей, соответствуюших Вашему запросу.' ); ?></p>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
