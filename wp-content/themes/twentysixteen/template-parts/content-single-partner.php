<?php
/**
 * The template part for displaying single persons
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */
?>

<?php
	$title = get_the_title();
	$link = get_post_meta($post->ID, '_partner_link_meta_key', true);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<div class="post-thumbnail">
			<a href="<?php echo $link ?>" target="_blank">
				<?php the_post_thumbnail(); ?>
			</a>
		</div>

		<h1 class="entry-title"><a href="<?php echo $link ?>" target="_blank"><?php echo $title ?></a></h1>
	</header>

	<div class="entry-content">
		<?php twentysixteen_excerpt(); ?>
	</div>
</article>
