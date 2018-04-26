<?php
/**
 * The template for displaying the header
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<?php $isEn = has_slug('en') || has_slug('news'); ?>

<body <?php body_class(); ?>>
	<header class="header content" role="banner">
		<div class="contentIn">
			<?php twentysixteen_the_custom_logo(); ?>

			<?php if ( has_nav_menu( 'primary' ) && !$isEn ) : ?>
				<div class="navigationWrapper">
					<?php if ( has_nav_menu( 'primary' ) ) : ?>
						<nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
							<?php
								wp_nav_menu( array(
									'theme_location' => 'primary',
									'menu_class'     => 'primary-menu',
								 ) );
							?>
						</nav>
					<?php endif; ?>

					<div class="searchHeader">
						<div class="search__icon toggle-search" aria-hidden="true">
							<span class="circle"></span>
							<span class="handle"></span>
						</div>
						<?php get_search_form(); ?>
					</div>

					<a class="langLink langLink--en" href="/en/"></a>
				</div>

				<div class="hamburger hamburger-spin">
					<div class="hamburger-box">
						<div class="hamburger-inner"></div>
					</div>
				</div>
			<?php elseif ( $isEn ) : ?>
				<div class="navigationWrapper navigationWrapper--en">
					<nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
						<?php
							wp_nav_menu( array(
								'menu' => 'english menu',
								'theme_location' => 'primary',
								'menu_class'     => 'primary-menu',
							 ) );
						?>
					</nav>

					<a class="langLink langLink--ru" href="/"></a>
				</div>

				<div class="hamburger hamburger-spin">
					<div class="hamburger-box">
						<div class="hamburger-inner"></div>
					</div>
				</div>
			<?php endif; ?>

		</div>
	</header>

	<div id="content" class="content contentWrapper">
