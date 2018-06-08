<?php 
/**
 * template name: 空页面(标题栏)
 */

get_header();
?>

<?php _the_focusbox( '', get_the_title() ); ?>

<section class="container">

	<?php while (have_posts()) : the_post(); ?>

		<article class="article-content">
			<?php the_content(); ?>
		</article>

	<?php endwhile; ?>

    <?php get_template_part( 'content', 'module-share' ); ?> 

    <?php comments_template('', true); ?>

</section>

<?php get_footer(); ?>