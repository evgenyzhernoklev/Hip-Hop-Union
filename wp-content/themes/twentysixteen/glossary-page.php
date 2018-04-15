<?php
/*
Template Name: Шаблон страницы библиотеки
*/
?>

<?php get_header(); ?>

<main class="" role="main">
	<?php the_title( '<h1 class="titlePage contentIn contentIn--small">', '</h1>' ); ?>

	<?php
	// запрос
	$wpb_all_query = new WP_Query(array(
																	'post_type'				=>'glossary',
																	'post_status'			=>'publish',
																	'posts_per_page'	=> -1,
																	'order' 					=> 'ASC',
																	'orderby' 				=> 'menu_order'
																)); ?>

	<?php if ( $wpb_all_query->have_posts() ) : ?>

		<div class="glossary js-glossary">
			<div class="glossary__catalog glossaryCatalog js-glossary-catalog">
				<div class="glossaryCatalog__in contentIn contentIn--small">
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">А</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Б</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">В</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Г</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Д</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Е</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Ж</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">З</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">И</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">К</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Л</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">М</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Н</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">О</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">П</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Р</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">С</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Т</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">У</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Ф</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Х</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Ц</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Ч</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Ш</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Щ</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Э</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Ю</a>
					<a class="glossaryCatalog__letter js-glossary-catalog-letter">Я</a>
				</div>
			</div>

			<div class="glossary__in glossaryList">
				<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
					<h3 class="glossary__title js-glossary-title"><a class="glossary__link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>

					<!-- <div class="glossaryList__item">
						<div class="contentIn contentIn--small">
							<div class="glossaryList__letter">A</div>
								<h3 class="glossary__title js-glossary-title"><a class="glossary__link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
								<h3 class="glossary__title js-glossary-title"><a class="glossary__link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
								<h3 class="glossary__title js-glossary-title"><a class="glossary__link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
						</div>
					</div> -->
				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>
			</div>
		</div>

	<?php else : ?>
		<p><?php _e( 'Извините, нет записей, соответствуюших Вашему запросу.' ); ?></p>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
