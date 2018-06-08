<?php _the_focusbox( '', get_the_title(), get_the_excerpt() ); ?>

<section class="container">

	<?php while (have_posts()) : the_post(); ?>

	<article class="article-content">
		<?php the_content(); ?>
	</article>

	<?php endwhile; ?>
    
    <?php get_template_part( 'content', 'module-share' ); ?> 

    <?php comments_template('', true); ?>

</section>