<?php
/*
Template Name: Шаблон страницы контактов
*/

get_header(); ?>

<main class="contentIn contentIn--small" role="main">
	<?php the_title( '<h1 class="titlePage">', '</h1>' ); ?>

	<?php
	// запрос
	$wpb_all_query = new WP_Query(array(
																	'post_type'				=>'person',
																	'post_status'			=>'publish',
																	'posts_per_page'	=> -1,
																	'order' 					=> 'ASC',
																	'orderby' 				=> 'menu_order',
																	'category_name' 	=> 'контакты'
																)); ?>

	<?php if ( $wpb_all_query->have_posts() ) : ?>

		<div class="colsFlex colsFlex--2 colsFlex--center">
			<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>

				<?php $link = get_post_meta($post->ID, '_person_link_meta_key', true); ?>

				<div class="colsFlex__col colsFlex__col--2">
					<div class="partner">

						<?php if ( $link ) : ?>
							<div class="partnerImg partnerImg--big">
								<a class="partnerImg__link" href="<?php echo $link ?>" target="_blank">
									<?php the_post_thumbnail(); ?>
								</a>
							</div>
							<h2 class="partnerTitle"><a class="partnerTitle__link"
																					href="<?php echo $link ?>"
																					target="_blank"><?php the_title(); ?></a></h2>
						<?php else : ?>
							<div class="partnerImg partnerImg--big">
								<?php the_post_thumbnail(); ?>
							</div>
							<h2 class="partnerTitle"><?php the_title(); ?></h2>
						<?php endif; ?>

						<?php $link_phone = get_post_meta($post->ID, '_person_phone_meta_key', true); ?>

						<div class="partnerInfo">
							<?php echo $link_phone ?>
						</div>
					</div>
				</div>

			<?php endwhile; ?>
		</div>

		<?php wp_reset_postdata(); ?>

	<?php else : ?>
		<p><?php _e( 'Извините, нет записей, соответствуюших Вашему запросу.' ); ?></p>
	<?php endif; ?>

	<?php
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
	?>

</main>

<?php get_footer(); ?>