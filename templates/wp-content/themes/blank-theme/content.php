<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						
	<header class="entry-header">
		<a href="<?php the_permalink(); ?>"><h2 class="entry-title"><?php the_title(); ?></h2></a>
	</header>

	<div class="entry-content">
		<?php if(has_post_thumbnail()) : ?>
			<figure class="entry-image">
				<?php the_post_thumbnail('large'); ?>
			</figure>
		<?php endif; ?>
		<div class="entry-text"><?php the_content(); ?></div>
	</div>

</article>