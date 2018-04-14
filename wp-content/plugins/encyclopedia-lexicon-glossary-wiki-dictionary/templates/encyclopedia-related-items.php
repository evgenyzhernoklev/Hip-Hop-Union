<?php Namespace WordPress\Plugin\Encyclopedia;

if ($related_items): ?>
<h3><?php printf(I18n::t('Related %s'), Post_Type_Labels::getItemPluralName()) ?></h3>
<ul class="related-items">
	<?php while ($related_items->have_Posts()): $related_items->the_Post() ?>
	<li class="item"><a href="<?php the_Permalink() ?>" title="<?php the_Title_Attribute() ?>"><?php the_Title() ?></a></li>
	<?php endwhile; WP_Reset_Postdata() ?>
</ul>
<?php endif;
