<?php 
/**
 * template name: 空页面
 */

get_header();
?>

<section class="container">

	<?php while (have_posts()) : the_post(); ?>

		<header class="article-header">
			<h1 class="article-title"><?php the_title(); ?></h1>
		</header>

		<article class="article-content">
			<?php the_content(); ?>
		</article>

	<?php endwhile; ?>

    <?php get_template_part( 'content', 'module-share' ); ?> 

    <?php comments_template('', true); ?>

</section>

<?php get_footer(); ?>