<?php
/**
 * The template part for displaying content
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header postHeader">
		<div class="postHeaderImg">
			<a href="<?php the_permalink() ?>" class="postHeaderImg__link" style="background-image: url('<?php the_post_thumbnail_url(); ?>')"></a>
		</div>

		<?php echo get_the_category_list(); ?>
	</header>

	<div class="entry-content postContent">
		<div class="postContentInfo">
			<?php the_title( sprintf( '<h2 class="entry-title postContentInfo__title"><a class="postContentInfo__link" href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
		</div>

		<a class="postContentHidden" href="<?php the_permalink() ?>">
			<?php twentysixteen_excerpt(); ?>
		</a>
	</div>
</article>