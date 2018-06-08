<?php get_header(); ?>

<?php _the_focusbox( '', single_cat_title('', false), trim(strip_tags(category_description())) ); ?>

<section class="container">
	<?php get_template_part( 'excerpt' ); ?>
</section>

<?php get_footer(); ?>