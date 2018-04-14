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

		<div class="glossary">
			<div class="glossary__letters glossaryLetters">
				<div class="glossaryLetters__in contentIn contentIn--small">
					<a class="glossaryLetters__letter">А</a>
					<a class="glossaryLetters__letter">Б</a>
					<a class="glossaryLetters__letter">В</a>
					<a class="glossaryLetters__letter">Г</a>
					<a class="glossaryLetters__letter">Д</a>
					<a class="glossaryLetters__letter">Е</a>
					<a class="glossaryLetters__letter">Ж</a>
					<a class="glossaryLetters__letter">З</a>
					<a class="glossaryLetters__letter">И</a>
					<a class="glossaryLetters__letter">К</a>
					<a class="glossaryLetters__letter">Л</a>
					<a class="glossaryLetters__letter">М</a>
					<a class="glossaryLetters__letter">Н</a>
					<a class="glossaryLetters__letter">О</a>
					<a class="glossaryLetters__letter">П</a>
					<a class="glossaryLetters__letter">Р</a>
					<a class="glossaryLetters__letter">С</a>
					<a class="glossaryLetters__letter">Т</a>
					<a class="glossaryLetters__letter">У</a>
					<a class="glossaryLetters__letter">Ф</a>
					<a class="glossaryLetters__letter">Х</a>
					<a class="glossaryLetters__letter">Ц</a>
					<a class="glossaryLetters__letter">Ч</a>
					<a class="glossaryLetters__letter">Ш</a>
					<a class="glossaryLetters__letter">Щ</a>
					<a class="glossaryLetters__letter">Э</a>
					<a class="glossaryLetters__letter">Ю</a>
					<a class="glossaryLetters__letter">Я</a>
				</div>
			</div>

			<div class="glossary__in contentIn contentIn--small">
				<?php while ( $wpb_all_query->have_posts() ) : $wpb_all_query->the_post(); ?>
					<h3 class="glossary__title"><a class="glossary__link" href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
				<?php endwhile; ?>

				<?php wp_reset_postdata(); ?>
			</div>
		</div>

	<?php else : ?>
		<p><?php _e( 'Извините, нет записей, соответствуюших Вашему запросу.' ); ?></p>
	<?php endif; ?>

</main>

<?php get_footer(); ?>
