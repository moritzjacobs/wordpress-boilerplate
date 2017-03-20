<?php get_header(); ?>

<?php if (have_posts()) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		
		<?php get_template_part('content'); ?>
	
	<?php endwhile; // end of the loop. ?>
	
	<?php wp_reset_postdata(); ?>
<?php else : ?>
	no post found
<?php endif; ?>

<?php get_footer(); ?>