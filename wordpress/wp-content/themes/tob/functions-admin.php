<?php 

// IN OPTIONS
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/inc/' );
require_once dirname( __FILE__ ) . '/inc/options-framework.php';
require_once dirname( __FILE__ ) . '/inc/options.php';




// JPEG QUALITY
function _jpeg_quality($arg){
    return 100;
}
add_filter('jpeg_quality', '_jpeg_quality', 10);




// EDITOR STYLE
add_editor_style(get_stylesheet_directory_uri().'/editor-style.css');

if( !function_exists('_add_editor_buttons') ) :

    function _add_editor_buttons($buttons) {
        $buttons[] = 'fontselect';
        $buttons[] = 'fontsizeselect';
        $buttons[] = 'cleanup';
        $buttons[] = 'styleselect';
        $buttons[] = 'del';
        $buttons[] = 'sub';
        $buttons[] = 'sup';
        $buttons[] = 'copy';
        $buttons[] = 'paste';
        $buttons[] = 'cut';
        $buttons[] = 'image';
        $buttons[] = 'anchor';
        $buttons[] = 'backcolor';
        $buttons[] = 'wp_page';
        $buttons[] = 'charmap';
        return $buttons;
    }
    add_filter("mce_buttons", "_add_editor_buttons");

endif;



// MD5 FILENAME
if ( _hui('newfilename') && !function_exists('_new_filename') ) :

    function _new_filename($filename) {
        $info = pathinfo($filename);
        $ext = empty($info['extension']) ? '' : '.' . $info['extension'];
        $name = basename($filename, $ext);
        return substr(md5($name), 0, 15) . $ext;
    }
    add_filter('sanitize_file_name', '_new_filename', 10);

endif;




// COMMENT Ctrl+Enter
if ( !function_exists('_admin_comment_ctrlenter') ) :

    function _admin_comment_ctrlenter(){
        echo '<script type="text/javascript">
            jQuery(document).ready(function($){
                $("textarea").keypress(function(e){
                    if(e.ctrlKey&&e.which==13||e.which==10){
                        $("#replybtn").click();
                    }
                });
            });
        </script>';
    };
    add_action('admin_footer', '_admin_comment_ctrlenter');

endif;





// ADMIN SCRIPTS
// if ( !function_exists('_admin_comment_ctrlenter') ) :

//     function _admin_scripts() {  
//         wp_register_script( '_admin', get_stylesheet_directory_uri().'/js/admin.js', array(), '' );  
//         wp_enqueue_script( '_admin' );  
//     }  
//     add_action( 'admin_enqueue_scripts', '_admin_scripts' );

// endif;





// ON THEME INIT
if ( isset($_GET['activated']) ){


    // THUMBNAIL SIZE
    if( get_option('thumbnail_size_w') < 230 ){
        update_option('thumbnail_size_w', 240);
        update_option('thumbnail_size_h', 180);
    }

    update_option('thumbnail_crop', 1);


    // CREATE PAGES
    $init_pages = array(
        'pages/posts-likes.php' => array( '点赞排行', 'likes' ),
        'pages/posts-week.php'  => array( '7天热门', 'week' ),
        'pages/posts-month.php' => array( '30天热门', 'month' ),
        'pages/tags.php'        => array( '热门标签', 'tags' ),
    );

    foreach ($init_pages as $template => $item) {
        
        $one_page = array(
            'post_title'  => $item[0],
            'post_name'   => $item[1],
            'post_status' => 'publish',
            'post_type'   => 'page',
            'post_author' => 1
        );

        $one_page_check = get_page_by_title( $item[0] );

        if(!isset($one_page_check->ID)){
            $one_page_id = wp_insert_post($one_page);
            update_post_meta($one_page_id, '_wp_page_template', $template);
        }

    }

}




















$postmeta_keywords_description = array(
    array(
        "name" => "keywords",
        "std" => "",
        "title" => '关键字：'
    ),
    array(
        "name" => "description",
        "std" => "",
        "title" => '描述：'
        )
);
if( _hui('post_keywords_description_s') ){
    add_action('admin_menu', '_postmeta_keywords_description_create');
    add_action('save_post', '_postmeta_keywords_description_save');
}

function _postmeta_keywords_description() {
    global $post, $postmeta_keywords_description;
    foreach($postmeta_keywords_description as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo'<p>'.$meta_box['title'].'</p>';
        if( $meta_box['name'] == 'keywords' ){
            echo '<p><input type="text" style="width:98%" value="'.$meta_box_value.'" name="'.$meta_box['name'].'"></p>';
        }else{
            echo '<p><textarea style="width:98%" name="'.$meta_box['name'].'">'.$meta_box_value.'</textarea></p>';
        }
    }
   
    echo '<input type="hidden" name="post_newmetaboxes_noncename" id="post_newmetaboxes_noncename" value="'.wp_create_nonce( plugin_basename(__FILE__) ).'" />';
}

function _postmeta_keywords_description_create() {
    global $theme_name;
    if ( function_exists('add_meta_box') ) {
        add_meta_box( 'postmeta_keywords_description_boxes', __('自定义关键字和描述', 'haoui'), '_postmeta_keywords_description', 'post', 'normal', 'high' );
    }
}

function _postmeta_keywords_description_save( $post_id ) {
    global $postmeta_keywords_description;
   
    if ( !wp_verify_nonce( isset($_POST['post_newmetaboxes_noncename']) ? $_POST['post_newmetaboxes_noncename'] : '', plugin_basename(__FILE__) ))
        return;
   
    if ( !current_user_can( 'edit_posts', $post_id ))
        return;
                   
    foreach($postmeta_keywords_description as $meta_box) {
        $data = $_POST[$meta_box['name']];
        if(get_post_meta($post_id, $meta_box['name']) == "")
            add_post_meta($post_id, $meta_box['name'], $data, true);
        elseif($data != get_post_meta($post_id, $meta_box['name'], true))
            update_post_meta($post_id, $meta_box['name'], $data);
        elseif($data == "")
            delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
    }
}