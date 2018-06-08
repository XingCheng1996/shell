<?php 

$p_meta = _hui('post_plugin');

$cols = _hui('list_cols', 5);
if( _hui('list_imagetext') ){
    $cols .= ' excerpt-combine';
}

if( _hui('list_hover_plugin') ){
    $cols .= ' excerpt-hoverplugin';
}

$p_like = _get_post_like_data(get_the_ID());

echo '<article class="excerpt excerpt-c'.$cols.'">';

    echo '<a'. _target_blank() .' class="thumbnail" href="'.get_permalink().'">'._get_post_thumbnail().'</a>';
    
    echo '<h2><a'. _target_blank() .' href="'.get_permalink().'">'.get_the_title().'</a></h2>';

    echo '<footer>';

        if( $p_meta && $p_meta['like'] ){
            echo '<a href="javascript:;" class="post-like'.($p_like->liked?' actived':'').'" data-pid="'.get_the_ID().'" etap="like"><i class="fa">&#xe60d;</i><span>'.$p_like->count.'</span></a>';
        }

        if( isset($is_hotposts) ){
            echo '<time class="hot">热门</time>';
        }else{
            echo '<time>'._get_post_time().'</time>';
        }

        if( $p_meta && $p_meta['view'] ){
            echo '<span class="post-view">'._get_post_views().'</span>';
        }

        if( $p_meta && $p_meta['comm'] ){
            echo '<span class="post-comm">'._get_post_comments().'</span>';
        }

    echo '</footer>';
    
echo '</article>';