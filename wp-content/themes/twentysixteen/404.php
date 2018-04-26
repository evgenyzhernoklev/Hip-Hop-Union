<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

get_header(); ?>



<main class="contentIn contentIn--small" role="main">
	<h1 class="titlePage">Страница не найдена</h1>

	<div class="entry-content searchContent">
		<img class="searchContent__imgError" src="/wp-content/uploads/icons/404.svg" />
		<?php get_search_form(); ?>
	</div>
</main>

<?php get_footer(); ?>
