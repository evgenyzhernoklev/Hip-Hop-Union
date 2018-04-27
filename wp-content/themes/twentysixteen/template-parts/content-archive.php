<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

	$isEn = has_slug('en');
	$post_date = $isEn ? mysql2date('F j, Y', $post->post_date, false ) : get_the_date('F j, Y');
?>

<article id="post-<?php the_ID(); ?>" class="post">
	<header class="entry-header postHeader">
		<div class="postHeaderImg">
			<a href="<?php the_permalink() ?>" class="postHeaderImg__link" style="background-image: url('<?php the_post_thumbnail_url(); ?>')"></a>
		</div>

		<?php
			if ( !$isEn ) :
				echo get_the_category_list();
			endif;
		?>

	</header>

	<div class="entry-content postContent">
		<div class="postContentInfo">
			<?php the_title( sprintf( '<h2 class="entry-title postContentInfo__title"><a class="postContentInfo__link" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
			<span class="postContentInfo__date"><?php echo $post_date; ?></span>
		</div>

		<a class="postContentHidden" href="<?php the_permalink() ?>">
			<?php
				$excerpt_length = strlen(get_the_excerpt());

				if ($excerpt_length > 120) :
					echo mb_substr( strip_tags( get_the_excerpt() ), 0, 100 ) . '...';
				else :
					echo get_the_excerpt();
				endif;
			?>
		</a>
	</div>
</article>
