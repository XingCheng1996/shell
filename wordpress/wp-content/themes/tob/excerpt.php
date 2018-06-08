<?php 

_the_ads('ad_list_header', 'list-header');

_the_leadpager(); 

if ( have_posts() ):

    echo '<div class="excerpts-wrapper">';
	    echo '<div class="excerpts">';

	        while ( have_posts() ) : the_post();
	            get_template_part( 'excerpt', 'item' );
	        endwhile; 

	        wp_reset_query();

	    echo '</div>';
    echo '</div>';

    _paging();

else:

     get_template_part( 'excerpt', 'none' );

endif; 

_the_ads('ad_list_footer', 'list-footer');
