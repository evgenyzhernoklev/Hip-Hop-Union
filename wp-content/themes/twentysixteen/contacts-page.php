<?php
/*
Template Name: Шаблон страницы контактов
*/

get_header(); ?>

<main class="contentIn contentIn--small" role="main">
	<?php the_title( '<h1 class="titlePage">', '</h1>' ); ?>

	<?php
		while ( have_posts() ) : the_post();
			the_content();
		endwhile;
	?>

</main>

<?php get_footer(); ?>
