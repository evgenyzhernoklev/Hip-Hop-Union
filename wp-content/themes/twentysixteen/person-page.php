<?php
/*
Template Name: Шаблон страницы персон
*/

	$isEn = has_slug('/en');
	$excerpt = get_post_meta($post->ID, '_page_excerpt_meta_key', true);

get_header(); ?>

<main class="contentIn" role="main">
	<?php the_title( '<h1 class="titlePage">', '</h1>' ); ?>

	<?php if ( $excerpt ) : ?>
		<div class="singleContent singleContent--category">
			<div class="entry-summary">
				<?php echo $excerpt; ?>
			</div>
		</div>
	<?php endif; ?>

	<?php
	// запрос
	$wpb_all_query = new WP_Query(array(
																	'post_type'				=>'person',
																	'post_status'			=>'publish',
																	'posts_per_page'	=> -1,
																	'order' 					=> 'ASC',
																	'orderby' 				=> 'menu_order'
																)); ?>

	<?php if ( $wpb_all_query->have_posts() ) : ?>

		<div class="colsFlex colsFlex--3 colsFlex--center">
			<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>

				<?php
					$link = get_post_meta($post->ID, '_person_link_meta_key', true);
					$title = get_the_title();
					$excerpt = get_the_excerpt();
					$title_english = get_post_meta($post->ID, '_person_name_english_meta_key', true);
					$excerpt_english = get_post_meta($post->ID, '_person_excerpt_meta_key', true);

					if ( $isEn ) :
						$title = $title_english;
						$excerpt = $excerpt_english;
					endif;
				?>

				<div class="colsFlex__col colsFlex__col--3">
					<div class="partner">

						<?php if ( $link ) : ?>
							<div class="partnerImg partnerImg--big">
								<a class="partnerImg__link" href="<?php echo $link ?>" target="_blank">
									<?php the_post_thumbnail(); ?>
								</a>
							</div>
							<h2 class="partnerTitle"><a class="partnerTitle__link"
																					href="<?php echo $link ?>"
																					target="_blank"><?php echo $title; ?></a></h2>
						<?php else : ?>
							<div class="partnerImg partnerImg--big">
								<?php the_post_thumbnail(); ?>
							</div>
							<h2 class="partnerTitle"><?php echo $title; ?></h2>
						<?php endif; ?>

						<div class="partnerInfo">
							<?php echo $excerpt; ?>
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
