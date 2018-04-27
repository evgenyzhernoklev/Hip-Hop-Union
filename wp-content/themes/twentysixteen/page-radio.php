<?php
/*
Template Name: Шаблон страницы radio
*/

get_header();

$bg = get_the_post_thumbnail_url();

?>

<main class="contentIn contentIn--full" role="main">
	<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" class="page page--radio hentry" style="background-image: url('<?php echo $bg; ?>')">
		<div class="page__overlay"></div>

		<header class="entry-header">
			<?php the_title( '<h1 class="entry-title titlePage titlePage--page">', '</h1>' ); ?>
		</header>

		<div class="entry-content singleContent contentIn contentIn--small">
			<?php the_content(); ?>
		</div>
	</article>

	<?php endwhile;?>

</main>

<?php get_footer(); ?>
