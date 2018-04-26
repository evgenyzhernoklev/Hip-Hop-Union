<?php
/*
Template Name: Шаблон страницы библиотеки
*/
?>

<?php get_header(); ?>

<main class="" role="main">
	<?php
		$posts = get_posts(array(
			'post_type'				=>'glossary',
			'post_status'			=>'publish',
			'posts_per_page'	=> -1,
			'order' 					=> 'ASC',
			'orderby' 				=> 'title'
		));

		the_title( '<h1 class="titlePage contentIn contentIn--small">', '</h1>' );
	?>

	<div class="glossaryWrapper js-glossary">
		<div class="content glossary__catalog glossaryCatalog js-glossary-catalog">
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
		<?php
			foreach ( $posts as $k => $post ) {
				// первая буква
				$fl = get_first_letter( $post->post_title );
				$prev_fl = isset( $posts[ ($k-1) ] ) ? get_first_letter( $posts[ ($k-1) ]->post_title ) : '';
				$next_fl = isset( $posts[ ($k+1) ] ) ? get_first_letter( $posts[ ($k+1) ]->post_title ) : '';
				$file_url = get_post_meta($post->ID, 'pfdFile', true);

				if ( $prev_fl !== $fl )
					echo '<div class="glossaryList__item js-glossary-post">' .
						'<div class="contentIn contentIn--small">' .
						'<div class="glossaryList__letter js-glossary-post-letter">' . $fl . '</div>';

				// данные
				echo '<h3 class="glossary__title"><a class="glossary__link" href="' .
					get_permalink($post->post_link) . '">' . $post->post_title . '</a>' .
					'<a href="' . $file_url . '" class="glossary__download" download></a></h3>';

				if ( $next_fl !== $fl )
					echo '</div></div>';
			}

			wp_reset_postdata();

			function get_first_letter( $str ) {
				return mb_substr($str, 0, 1, 'utf-8');
			}
		?>
		</div>

	</div>
</main>

<?php get_footer(); ?>
