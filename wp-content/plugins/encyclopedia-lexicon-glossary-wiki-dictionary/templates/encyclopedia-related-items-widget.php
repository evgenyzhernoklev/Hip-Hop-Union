<?php /* Global: $widget, $options */ ?>

<ul class="related-items">
	<?php while ($widget->items->have_Posts()): $widget->items->the_Post() ?>
	<li class="item"><a href="<?php the_Permalink() ?>" title="<?php the_Title_Attribute() ?>"><?php the_Title() ?></a></li>
	<?php endwhile; WP_Reset_Postdata() ?>
</ul>
