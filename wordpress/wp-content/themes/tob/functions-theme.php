<?php

/**
 * display errors
 */
if( is_super_admin() ){
    // error_reporting(E_ALL);
    // ini_set("display_errors", 1);
}else{
    // exit;
}



/**
 * remove actions from wp_head
 */
remove_action( 'wp_head',   'feed_links_extra', 3 ); 
remove_action( 'wp_head',   'rsd_link' ); 
remove_action( 'wp_head',   'wlwmanifest_link' ); 
remove_action( 'wp_head',   'index_rel_link' ); 
remove_action( 'wp_head',   'start_post_rel_link', 10, 0 ); 
remove_action( 'wp_head',   'wp_generator' ); 



/**
 * WordPress Emoji Delete
 */
remove_action( 'admin_print_scripts','print_emoji_detection_script');
remove_action( 'admin_print_styles','print_emoji_styles');
remove_action( 'wp_head',  'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles','print_emoji_styles');
remove_filter( 'the_content_feed','wp_staticize_emoji');
remove_filter( 'comment_text_rss','wp_staticize_emoji');
remove_filter( 'wp_mail','wp_staticize_emoji_for_email');



/**
 * wp-json delete
 */
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );



/**
 * post formats
 */
add_theme_support( 'post-formats', array( 'gallery', 'image', 'video' ) );  
add_post_type_support( 'page', 'post-formats' );




/**
 * add theme thumbnail
 */
if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}


/**
 * register menus
 */
if (function_exists('register_nav_menus')){
    register_nav_menus( array(
        'nav' => __('导航')
    ));
}


/**
 * register sidebar
 */
if (function_exists('register_sidebar')){
    $sidebars = array(
        'single' => '文章页侧栏',
        'page' => '页面侧栏',
    );
    foreach ($sidebars as $key => $value) {
        register_sidebar(array(
            'name'          => $value,
            'id'            => $key,
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3>',
            'after_title'   => '</h3>'
        ));
    };
}




/**
 * the theme
 */

$current_theme = wp_get_theme();


function _the_theme_name(){
    global $current_theme;
    return $current_theme->get( 'Name' );
}


function _the_theme_version(){
    global $current_theme;
    return $current_theme->get( 'Version' );
}


function _the_theme_thumb(){
    return _hui('post_default_thumb') ? _hui('post_default_thumb') : get_stylesheet_directory_uri() . '/img/thumb.png';
}


function _the_theme_avatar(){
    return get_stylesheet_directory_uri() . '/img/avatar.png';
}


function _get_description_max_length(){
    return 200;
}


function _get_delimiter(){
    return _hui('connector') ? _hui('connector') : '-';
}




/**
 * get theme option         
 */
function _hui( $name, $default = false ) {

    $option_name = _the_theme_name();

    $options = get_option( $option_name );

    if ( isset( $options[$name] ) ) {
        return $options[$name];
    }

    return $default;
}




/**
 * Widgets
 */
require_once (get_stylesheet_directory() . '/functions-widgets.php');



/**
 * Functions for admin
 */
if( is_admin() ){
    require_once (get_stylesheet_directory() . '/functions-admin.php');
}


/**
 * target blank
 */
function _target_blank(){
    return _hui('target_blank') ? ' target="_blank"' : '';
}




/**
 * Disable embeds
 */
if ( !function_exists( 'disable_embeds_init' ) ) :
    function disable_embeds_init(){
        global $wp;
        $wp->public_query_vars = array_diff($wp->public_query_vars, array('embed'));
        remove_action('rest_api_init', 'wp_oembed_register_route');
        add_filter('embed_oembed_discover', '__return_false');
        remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links');
        remove_action('wp_head', 'wp_oembed_add_host_js');
        add_filter('tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin');
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
    }
    add_action('init', 'disable_embeds_init', 9999);

    function disable_embeds_tiny_mce_plugin($plugins){
        return array_diff($plugins, array('wpembed'));
    }
    function disable_embeds_rewrites($rules){
        foreach ($rules as $rule => $rewrite) {
            if (false !== strpos($rewrite, 'embed=true')) {
                unset($rules[$rule]);
            }
        }
        return $rules;
    }
    function disable_embeds_remove_rewrite_rules(){
        add_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_activation_hook(__FILE__, 'disable_embeds_remove_rewrite_rules');

    function disable_embeds_flush_rewrite_rules(){
        remove_filter('rewrite_rules_array', 'disable_embeds_rewrites');
        flush_rewrite_rules();
    }
    register_deactivation_hook(__FILE__, 'disable_embeds_flush_rewrite_rules');
endif;




/**
 * SSL Gravatar
 */
if (!function_exists('_get_ssl2_avatar')) :
    function _get_ssl2_avatar($avatar) {
        $avatar = preg_replace('/.*\/avatar\/(.*)\?s=([\d]+)&.*/','<img src="https://secure.gravatar.com/avatar/$1?s=$2&d=mm" class="avatar avatar-$2" height="$2" width="$2">',$avatar);
        return $avatar;
    }
    add_filter('get_avatar', '_get_ssl2_avatar');
endif;




/**
 * Remove Open Sans that WP adds from frontend
 */
if (!function_exists('remove_wp_open_sans')) :
    function remove_wp_open_sans() {
        wp_deregister_style( 'open-sans' );
        wp_register_style( 'open-sans', false );
    }
    add_action('wp_enqueue_scripts', 'remove_wp_open_sans');
endif;

if (!function_exists('remove_open_sans')) :
    function remove_open_sans() {    
        wp_deregister_style( 'open-sans' );    
        wp_register_style( 'open-sans', false );    
        wp_enqueue_style('open-sans','');    
    }    
    add_action( 'init', 'remove_open_sans' );
endif;




/**
 * title
 */
function _title() {

    global $paged;

    $html = '';
    $t = trim(wp_title('', false));

    if ($t) {
        $html .= $t . _get_delimiter();
    }

    if ( get_query_var('page') ) {
        $html .= '第' . get_query_var('page') . '页' . _get_delimiter();
    }

    $html .= get_bloginfo('name');

    if (is_home()) {
        if ($paged > 1) {
            $html .= _get_delimiter() . '最新发布';
        }else{
            $html .= _get_delimiter() . get_option('blogdescription');
        }
    }

    if ($paged > 1) {
        $html .= _get_delimiter() . '第' . $paged . '页';
    }

    return $html;
}



/**
 * menu
 */
function _the_menu($location = 'nav') {
    echo str_replace("</ul></div>", "", preg_replace("/<div[^>]*><ul[^>]*>/", "", wp_nav_menu(array('theme_location' => $location, 'echo' => false))));
}



/**
 * logo
 */
function _the_logo() {
    $tag = is_home() ? 'h1' : 'div';
    $src = _hui('logo_src');
    if( wp_is_mobile() && _hui('logo_src_m') ){
        $src = _hui('logo_src_m');
    }
    echo '<' . $tag . ' class="logo"><a href="' . get_bloginfo('url') . '" title="' . get_bloginfo('name') . _get_delimiter() . get_bloginfo('description') . '"><img src="'.$src.'"><span>' . get_bloginfo('name') . '</span></a></' . $tag . '>';
}



/**
 * ads
 */
function _the_ads($name='', $class=''){
    if( !_hui($name.'_s') ) return;

    if( wp_is_mobile() ){
        echo '<div class="asst asst-m asst-'.$class.'">'._hui($name.'_m').'</div>';
    }else{
        echo '<div class="asst asst-'.$class.'">'._hui($name).'</div>';
    }
}



/**
 * leadpager
 * @return [type] [description]
 */
function _the_leadpager(){
    global $paged;
    if( $paged && $paged > 1 ){
        echo '<div class="leadpager">第 '.$paged.' 页</div>';
    }
}



/**
 * focusbox
 */
function _the_focusbox($title_tag='h1', $title='', $text=''){
    if( $title ){
        if( !$title_tag ){
            $title_tag = 'h1';
        }
        $title = '<'.$title_tag.' class="focusbox-title">'.$title.'</'.$title_tag.'>';
    }

    if( $text ){
        $text = '<div class="focusbox-text">'.$text.'</div>';
    }
    echo '<div class="focusbox"><div class="container">'.$title.$text.'</div></div>';
}



/**
 * bodyclass
 */
function _bodyclass() {
    $class = '';

    if ((is_single() || is_page()) && comments_open()) {
        $class .= ' comment-open';
    }

    if( (is_single() || is_page()) && get_post_format() ){
        $class .= ' postformat-'.get_post_format();
    }
    
    if (is_super_admin()) {
        $class .= ' logged-admin';
    }

    return trim($class);
}



/**
 * head
 */
function _the_head() { 
    _head_css();
    _keywords();
    _description();
    _post_views_record();
    if( _hui('headcode') ) echo _hui('headcode');
}
add_action('wp_head', '_the_head');



/**
 * foot
 */
function _the_footer() { 
    
}
// add_action('wp_footer', '_the_footer');




function _the_404(){
    echo '<div class="f404"><img src="'.get_stylesheet_directory_uri().'/img/404.png"><h2>404 . Not Found</h2><h3>沒有找到你要的内容！</h3><p><a class="btn btn-primary" href="'.get_bloginfo('url').'">返回首页</a></p></div>';
}




function _str_cut($str ,$start , $width ,$trimmarker ){
    $output = preg_replace('/^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$start.'}((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$width.'}).*/s','\1',$str);
    return $output.$trimmarker;
}

function _get_excerpt($limit=200, $after=''){
    $excerpt = get_the_excerpt();
    if ( mb_strlen( $excerpt ) > $limit ) {
        return _str_cut(strip_tags($excerpt), 0, $limit, $after);
    }else{
        return $excerpt;
    }
}

function _excerpt_length( $length ) {
    return 200;
}
add_filter( 'excerpt_length', '_excerpt_length' );




function _get_post_thumbnail_url($themethumb=true, $size='thumbnail') { 
    if ( has_post_thumbnail() ) {
        $thumb_id = get_post_thumbnail_id(get_the_ID());
        $thumb = wp_get_attachment_image_src($thumb_id, $size);
        return $thumb[0];
    } else {       
        return $themethumb ? _the_theme_thumb() : '';
    }
}

function _get_post_thumbnail() { 
    return '<img src="'._the_theme_thumb().'" data-src="'. _get_post_thumbnail_url() .'" class="thumb">';
}


function _get_user_avatar($user_email='', $src=false, $size=50){

    $avatar = get_avatar( $user_email, $size , _the_theme_avatar());
    if( $src ){
        return $avatar;
    }else{
        return str_replace(' src=', ' data-src=', $avatar);
    }

}




/**
 * set postthumbnail
 */
if( _hui('set_postthumbnail') && !function_exists('_set_postthumbnail') ){
    function _set_postthumbnail() {
        global $post;
        if( empty($post) ) return;
        $already_has_thumb = has_post_thumbnail($post->ID);
        if (!$already_has_thumb){
            $attached_image = get_children("post_parent=$post->ID&post_type=attachment&post_mime_type=image&numberposts=1");
            if ($attached_image){
                foreach ($attached_image as $attachment_id => $attachment) {
                    set_post_thumbnail($post->ID, $attachment_id);
                }
            }
        }
    }

    // add_action('the_post', '_set_postthumbnail');
    add_action('save_post', '_set_postthumbnail');
    add_action('draft_to_publish', '_set_postthumbnail');
    add_action('new_to_publish', '_set_postthumbnail');
    add_action('pending_to_publish', '_set_postthumbnail');
    add_action('future_to_publish', '_set_postthumbnail');
}





/* 
 * keywords
 * ====================================================
*/
function _keywords() 
{
  global $s, $post;
  $keywords = '';
  if ( is_single() ) {
    if ( get_the_tags( $post->ID ) ) {
      foreach ( get_the_tags( $post->ID ) as $tag ) $keywords .= $tag->name . ', ';
    }
    foreach ( get_the_category( $post->ID ) as $category ) $keywords .= $category->cat_name . ', ';
    if( _hui('post_keywords_description_s') ) {
        $the = trim(get_post_meta($post->ID, 'keywords', true));
        if( $the ) $keywords = $the;
    }else{
        $keywords = substr_replace( $keywords , '' , -2);
    }
    
  } elseif ( is_home () )    { $keywords = _hui('keywords');
  } elseif ( is_tag() )      { $keywords = single_tag_title('', false);
  } elseif ( is_category() ) { $keywords = single_cat_title('', false);

    /*if( _hui('cat_keyworks_s') ){
        $description = trim(strip_tags(category_description()));
        if( $description && strstr($description, '::::::') ){
            $desc = explode('::::::', $description);
            $keywords .= ','.trim($desc[0]);
        }
    }*/

  } elseif ( is_search() )   { $keywords = esc_html( $s, 1 );
  } else { $keywords = trim( wp_title('', false) );
  }
  if ( $keywords ) {
    echo "<meta name=\"keywords\" content=\"$keywords\">\n";
  }
}


/* 
 * description
 * ====================================================
*/
function _description() 
{
  global $s, $post;
  $description = '';
  $blog_name = get_bloginfo('name');
  if ( is_singular() ) {
    if( !empty( $post->post_excerpt ) ) {
      $text = $post->post_excerpt;
    } else {
      $text = $post->post_content;
    }
    $description = trim( str_replace( array( "\r\n", "\r", "\n", "　", " "), " ", str_replace( "\"", "'", strip_tags( $text ) ) ) );
    if ( !( $description ) ) $description = $blog_name . "-" . trim( wp_title('', false) );
    if( _hui('post_keywords_description_s') ) {
        $the = trim(get_post_meta($post->ID, 'description', true));
        if( $the ) $description = $the;
    }
  } elseif ( is_home () )    { $description = _hui('description');
  } elseif ( is_tag() )      { $description = $blog_name . "'" . single_tag_title('', false) . "'";
  } elseif ( is_category() ) { 

    $description = trim(strip_tags(category_description()));

    /*if( _hui('cat_keyworks_s') && $description && strstr($description, '::::::') ){
        $desc = explode('::::::', $description);
        $description = trim($desc[1]);
    }*/

  } elseif ( is_archive() )  { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
  } elseif ( is_search() )   { $description = $blog_name . ": '" . esc_html( $s, 1 ) . "' ".__('的搜索結果', 'haoui');
  } else { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
  }
  $description = mb_substr( $description, 0, _get_description_max_length(), 'utf-8' );
  echo "<meta name=\"description\" content=\"$description\">\n";
}




function _smilies_src ($img_src, $img, $siteurl){
    return get_stylesheet_directory_uri().'/img/smilies/'.$img;
}
add_filter('smilies_src','_smilies_src',1,10); 




function _noself_ping( &$links ) {
    $home = get_option( 'home' );
    foreach ( $links as $l => $link )
    if ( 0 === strpos( $link, $home ) )
    unset($links[$l]);
}
add_action('pre_ping','_noself_ping');





function _hide_admin_bar($flag) {
    return false;
}
add_filter('show_admin_bar','_hide_admin_bar');





function _res_from_email($email) {
    $wp_from_email = get_option('admin_email');
    return $wp_from_email;
}
add_filter('wp_mail_from', '_res_from_email');

function _res_from_name($email){
    $wp_from_name = get_option('blogname');
    return $wp_from_name;
}
add_filter('wp_mail_from_name', '_res_from_name');





function _comment_mail_notify($comment_id) {
  $admin_notify = '1'; 
  $admin_email = get_bloginfo ('admin_email'); 
  $comment = get_comment($comment_id);
  $comment_author_email = trim($comment->comment_author_email);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  global $wpdb;
  if ($wpdb->query("Describe {$wpdb->comments} comment_mail_notify") == '')
    $wpdb->query("ALTER TABLE {$wpdb->comments} ADD COLUMN comment_mail_notify TINYINT NOT NULL DEFAULT 0;");
  if (($comment_author_email != $admin_email && isset($_POST['comment_mail_notify'])) || ($comment_author_email == $admin_email && $admin_notify == '1'))
    $wpdb->query("UPDATE {$wpdb->comments} SET comment_mail_notify='1' WHERE comment_ID='$comment_id'");
  $notify = $parent_id ? get_comment($parent_id)->comment_mail_notify : '0';
  $spam_confirmed = $comment->comment_approved;
  if ($parent_id != '' && $spam_confirmed != 'spam' && $notify == '1') {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); 
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = 'Hi，您在 [' . get_option("blogname") . '] 的留言有人回复啦！';
    $message = '
    <div style="color:#333;font:100 14px/24px microsoft yahei;">
      <p>' . trim(get_comment($parent_id)->comment_author) . ', 您好!</p>
      <p>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
       . trim(get_comment($parent_id)->comment_content) . '</p>
      <p>' . trim($comment->comment_author) . ' 给您的回应:<br /> &nbsp;&nbsp;&nbsp;&nbsp; '
       . trim($comment->comment_content) . '<br /></p>
      <p>点击 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看回应完整內容</a></p>
      <p>欢迎再次光临 <a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
      <p style="color:#999">(此邮件由系统自动发出，请勿回复.)</p>
    </div>';
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post','_comment_mail_notify'); 





function _comment_mail_add_checkbox() {
  echo '<label for="comment_mail_notify" class="hide" style="padding-top:0"><input type="checkbox" name="comment_mail_notify" id="comment_mail_notify" value="comment_mail_notify" checked="checked"/>'.__('有人回复时邮件通知我', 'haoui').'</label>';
}
add_action('comment_form','_comment_mail_add_checkbox');





function _the_shares() {
    echo '<div class="shares">
        <strong>分享到：</strong>
        <a href="javascript:;" data-url="'. get_the_permalink() .'" class="share-weixin" title="分享到微信"><i class="fa">&#xe60e;</i></a><a etap="share" data-share="qzone" class="share-qzone" title="分享到QQ空间"><i class="fa">&#xe607;</i></a><a etap="share" data-share="weibo" class="share-tsina" title="分享到新浪微博"><i class="fa">&#xe608;</i></a><a etap="share" data-share="tqq" class="share-tqq" title="分享到腾讯微博"><i class="fa">&#xe60c;</i></a><a etap="share" data-share="qq" class="share-sqq" title="分享到QQ好友"><i class="fa">&#xe609;</i></a><a etap="share" data-share="renren" class="share-renren" title="分享到人人网"><i class="fa">&#xe60a;</i></a><a etap="share" data-share="douban" class="share-douban" title="分享到豆瓣网"><i class="fa">&#xe60b;</i></a>
    </div>';
}

function _get_post_time() {
    return (time()-strtotime(get_the_time('Y-m-d')))>86400 ? get_the_date() : get_the_time();
}





function _load_scripts() {
    if (!is_admin()) {
        wp_enqueue_style( 'main', get_stylesheet_directory_uri().'/style.css', array(), _the_theme_version(), 'all' );
        wp_deregister_script( 'jquery' );
        wp_deregister_script( 'l10n' ); 

        $jss = array(
            'no' => array(
                'jquery' => get_stylesheet_directory_uri().'/js/jquery.js'
            ),
            'baidu' => array(
                'jquery' => 'http://apps.bdimg.com/libs/jquery/2.0.0/jquery.min.js'
            ),
            '360' => array(
                'jquery' => 'http://libs.useso.com/js/jquery/2.0.0/jquery.min.js'
            )
        );
        wp_register_script( 'jquery', _hui('js_outlink') ? $jss[_hui('js_outlink')]['jquery'] : $jss['no']['jquery'], false, _the_theme_version(), false );

        
        wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/js/main.js', array('jquery'), _the_theme_version(), true );

    }
}
add_action('wp_enqueue_scripts', '_load_scripts');






function _head_css() {

    $styles = '';

    if( _hui('site_gray') ){
        $styles .= "html{overflow-y:scroll;filter:progid:DXImageTransform.Microsoft.BasicImage(grayscale=1);-webkit-filter: grayscale(100%);}";
    }

    if( _hui('theme_skin_custom') ){
        $skc = _hui('theme_skin_custom');
    }else{
        $skc = _hui('theme_skin');
    }
    
    if( $skc && $skc !== '#FF6651' ){
        $styles .= "a:hover{color:{$skc}}.sitenav > ul > li.menu-item-has-children:hover > a::before{border-top-color:{$skc}}.sitenav ul li:hover > a,.sitenav ul li.active a:hover,.sitenav ul li a:hover{color:{$skc}}.sitenav ul li.current-menu-item > a,.sitenav ul li.current-menu-parent > a{color:{$skc};border-bottom-color:{$skc}}.searchform .sinput:focus{border-color:{$skc}}.searchform .sbtn{background-color:{$skc}}.post-like.actived{color:{$skc}}.excerpt time.hot{color:{$skc}}.excerpt-combine footer time.hot{background-color:{$skc}}.pagination ul li.next-page a{background-color:{$skc}}.ias_trigger a{background-color:{$skc}}.article-content h2{border-left:7px solid {$skc}}.article-actions .action-like{background-color:{$skc}}.article-tags a:hover{background-color:{$skc}}.image-navigation a{background-color:{$skc}}.tagslist li .name:hover{background-color:{$skc}}.comt-submit{background:{$skc};border-color:{$skc}}@media (max-width:768px){.sitenav ul li.current-menu-item > a{background-color:{$skc}}.sitenav ul li.current-menu-parent > a{color:{$skc}}.sitenav ul li.current-menu-item>a{color:#fff;}}@media (max-width:544px){.excerpt-combine h2 a:hover{color:{$skc}}.excerpt-combine footer time.hot{color:{$skc}}}";
    }

    $styles .= _hui('csscode');

    if( $styles ) echo '<style>'.$styles.'</style>';
}






/**
 * post like
 */
function _get_post_like_data($post_id=0){
    $count = get_post_meta( $post_id, 'like', true );

    return (object) array(
        'liked' => _is_user_has_like($post_id),
        'count' => $count ? $count : 0
    );
}

function _is_user_has_like($post_id=0){
    if( empty($_COOKIE['likes']) || !in_array($post_id, explode('.', $_COOKIE['likes'])) ){
        return false;
    }

    return true;
}


/**
 * post views
 */
function _post_views_record(){
    if (is_singular()){
      global $post;
      $post_ID = $post->ID;
      if($post_ID){
          $post_views = (int)get_post_meta($post_ID, 'views', true);
          if(!update_post_meta($post_ID, 'views', ($post_views+1))){
            add_post_meta($post_ID, 'views', 1, true);
          }
      }
    }
}

function _get_post_views($before='阅读(',$after=')'){
    global $post;
    $post_ID = $post->ID;
    $views = (int)get_post_meta($post_ID, 'views', true);
    if( $views>=1000 ){
        $views = round($views/1000, 2).'K';
    }
    return $before.$views.$after;
}



/**
 * post commemnts
 */
function _get_post_comments($before='评论(', $after=')'){
  return $before.get_comments_number('0', '1', '%').$after;
}









/**
 * no category
 */
if( _hui('no_categoty') && !function_exists('no_category_base_refresh_rules') ){

    /*
    Plugin Name: No Category Base (WPML)
    Version: 1.2
    Plugin URI: http://infolific.com/technology/software-worth-using/no-category-base-for-wordpress/
    Description: Removes '/category' from your category permalinks. WPML compatible.
    Author: Marios Alexandrou
    Author URI: http://infolific.com/technology/
    License: GPLv2 or later
    Text Domain: no-category-base-wpml
    */

    /*
    Copyright 2015 Marios Alexandrou
    Copyright 2011 Mines (email: hi@mines.io)
    Copyright 2008 Saurabh Gupta (email: saurabh0@gmail.com)

    Based on the work by Saurabh Gupta (email : saurabh0@gmail.com)

    This program is free software; you can redistribute it and/or
    modify it under the terms of the GNU General Public License
    as published by the Free Software Foundation; either version 2
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
    */

    /* hooks */
    register_activation_hook(__FILE__,'no_category_base_refresh_rules');
    register_deactivation_hook(__FILE__,'no_category_base_deactivate');

    /* actions */
    add_action('created_category','no_category_base_refresh_rules');
    add_action('delete_category','no_category_base_refresh_rules');
    add_action('edited_category','no_category_base_refresh_rules');
    add_action('init','no_category_base_permastruct');

    /* filters */
    add_filter('category_rewrite_rules', 'no_category_base_rewrite_rules');
    add_filter('query_vars','no_category_base_query_vars');    // Adds 'category_redirect' query variable
    add_filter('request','no_category_base_request');       // Redirects if 'category_redirect' is set

    function no_category_base_refresh_rules() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    function no_category_base_deactivate() {
        remove_filter( 'category_rewrite_rules', 'no_category_base_rewrite_rules' ); // We don't want to insert our custom rules again
        no_category_base_refresh_rules();
    }

    /**
     * Removes category base.
     *
     * @return void
     */
    function no_category_base_permastruct()
    {
        global $wp_rewrite;
        global $wp_version;

        if ( $wp_version >= 3.4 ) {
            $wp_rewrite->extra_permastructs['category']['struct'] = '%category%';
        } else {
            $wp_rewrite->extra_permastructs['category'][0] = '%category%';
        }
    }

    /**
     * Adds our custom category rewrite rules.
     *
     * @param  array $category_rewrite Category rewrite rules.
     *
     * @return array
     */
    function no_category_base_rewrite_rules($category_rewrite) {
        global $wp_rewrite;
        $category_rewrite=array();

        /* WPML is present: temporary disable terms_clauses filter to get all categories for rewrite */
        if ( class_exists( 'Sitepress' ) ) {
            global $sitepress;

            remove_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
            $categories = get_categories( array( 'hide_empty' => false ) );
            add_filter( 'terms_clauses', array( $sitepress, 'terms_clauses' ) );
        } else {
            $categories = get_categories( array( 'hide_empty' => false ) );
        }

        foreach( $categories as $category ) {
            $category_nicename = $category->slug;

            if ( $category->parent == $category->cat_ID ) {
                $category->parent = 0;
            } elseif ( $category->parent != 0 ) {
                $category_nicename = get_category_parents( $category->parent, false, '/', true ) . $category_nicename;
            }

            $category_rewrite['('.$category_nicename.')/(?:feed/)?(feed|rdf|rss|rss2|atom)/?$'] = 'index.php?category_name=$matches[1]&feed=$matches[2]';
            $category_rewrite["({$category_nicename})/{$wp_rewrite->pagination_base}/?([0-9]{1,})/?$"] = 'index.php?category_name=$matches[1]&paged=$matches[2]';
            $category_rewrite['('.$category_nicename.')/?$'] = 'index.php?category_name=$matches[1]';
        }

        // Redirect support from Old Category Base
        $old_category_base = get_option( 'category_base' ) ? get_option( 'category_base' ) : 'category';
        $old_category_base = trim( $old_category_base, '/' );
        $category_rewrite[$old_category_base.'/(.*)$'] = 'index.php?category_redirect=$matches[1]';

        return $category_rewrite;
    }

    function no_category_base_query_vars($public_query_vars) {
        $public_query_vars[] = 'category_redirect';
        return $public_query_vars;
    }

    /**
     * Handles category redirects.
     *
     * @param $query_vars Current query vars.
     *
     * @return array $query_vars, or void if category_redirect is present.
     */
    function no_category_base_request($query_vars) {
        if( isset( $query_vars['category_redirect'] ) ) {
            $catlink = trailingslashit( get_option( 'home' ) ) . user_trailingslashit( $query_vars['category_redirect'], 'category' );
            status_header( 301 );
            header( "Location: $catlink" );
            exit();
        }

        return $query_vars;
    }

}








/**
 * get post mostviews
 */
function _posts_mostviews($mode = 'post', $limit = 10, $days = 15, $display = true) {
    global $wpdb, $post;
    $limit_date = current_time('timestamp') - ($days*86400);
    $limit_date = date("Y-m-d H:i:s",$limit_date);
    $where = '';
    $temp = '';

    if(!empty($mode) && $mode != 'both') {
        $where = "post_type = '$mode'";
    } else {
        $where = '1=1';
    }

    $most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");

    if($most_viewed) {
        $i = 1;
        foreach ($most_viewed as $post) {
            $post_title = get_the_title();
            $post_views = intval($post->views);
            // $post_views = number_format($post_views);

            // $temp .= "<li><a href=\"".get_permalink()."\">$post_title</a> - $post_views ".__('views', 'wp-postviews')."</li>";
            $temp .= '<li class="item-'.$i.'"><a href="'.get_permalink($postid).'"><b>'.$i.'</b><span class="thumbnail">'._get_post_thumbnail().'</span><h2>'.$post_title.'</h2><p>'.timeago( get_the_time('Y-m-d H:i:s') ).'<span class="post-views">阅读('.$post_views.')</span></p></a></li>';
                $i++;
        }
    } else {
        $temp = '<li>'.__('N/A', 'wp-postviews').'</li>'."\n";
    }

    if($display) {
        echo $temp;
    } else {
        return $temp;
    }
}




function _posts_orderby_views($days = 30, $limit = 12, $display = true, $mode = 'post') {
    global $wpdb, $post;
    $limit_date = current_time('timestamp') - ($days*86400);
    $limit_date = date("Y-m-d H:i:s",$limit_date);
    $where = '';
    $temp = '';

    if(!empty($mode) && $mode != 'both') {
        $where = "post_type = '$mode'";
    } else {
        $where = '1=1';
    }

    $most_viewed = $wpdb->get_results("SELECT DISTINCT $wpdb->posts.*, (meta_value+0) AS views FROM $wpdb->posts LEFT JOIN $wpdb->postmeta ON $wpdb->postmeta.post_id = $wpdb->posts.ID WHERE post_date < '".current_time('mysql')."' AND post_date > '".$limit_date."' AND $where AND post_status = 'publish' AND meta_key = 'views' AND post_password = '' ORDER  BY views DESC LIMIT $limit");

    if($most_viewed) {
        foreach ($most_viewed as $post) {
            $temp .= '<li><a class="thumbnail" href="'.get_permalink().'">'._get_post_thumbnail(array()).'<h2>'.get_the_title().'</h2></a></li>';
        }
    } else {
        $temp = '<li>暂无内容！</li>'."\n";
    }

    if($display) {
        echo $temp;
    } else {
        return $temp;
    }
}


// Posts Related
function _posts_related($limit=8){
    global $post;

    $exclude_id = $post->ID; 
    $posttags = get_the_tags(); 
    $i = 0;

    if ( $posttags ) { 
        $tags = ''; foreach ( $posttags as $tag ) $tags .= $tag->name . ',';
        $args = array(
            'post_status'         => 'publish',
            'tag_slug__in'        => explode(',', $tags), 
            'post__not_in'        => explode(',', $exclude_id), 
            'ignore_sticky_posts' => 1, 
            // 'orderby'             => 'comment_date', 
            'posts_per_page'      => $limit
        );
        query_posts($args); 
        while( have_posts() ) { the_post();
            echo '<li><a class="thumbnail" href="'.get_permalink().'">'._get_post_thumbnail().get_the_title().'</a></li>';
            $exclude_id .= ',' . $post->ID; $i ++;
        };
        wp_reset_query();
    }
    if ( $i < $limit ) { 
        $cats = ''; foreach ( get_the_category() as $cat ) $cats .= $cat->cat_ID . ',';
        $args = array(
            'category__in'        => explode(',', $cats), 
            'post__not_in'        => explode(',', $exclude_id),
            'ignore_sticky_posts' => 1,
            // 'orderby'             => 'comment_date',
            'posts_per_page'      => $limit - $i
        );
        query_posts($args);
        while( have_posts() ) { the_post();
            echo '<li><a class="thumbnail" href="'.get_permalink().'">'._get_post_thumbnail().get_the_title().'</a></li>';
            $i ++;
        };
        wp_reset_query();
    }
    if ( $i == 0 ){
        return false;
    }
    
}





// PAGING
if ( ! function_exists( '_paging' ) ) :

    function _paging() {
        $p = 3;
        if ( is_singular() ) return;
        global $wp_query, $paged;
        $max_page = $wp_query->max_num_pages;
        if ( $max_page == 1 ) return; 
        echo '<div class="pagination'.(_hui('paging_type') == 'multi'?' pagination-multi':'').'"><ul>';
        if ( empty( $paged ) ) $paged = 1;
        if( _hui('paging_type') == 'multi' && $paged !== 1 ) _paging_link(0);
        // echo '<span class="pages">Page: ' . $paged . ' of ' . $max_page . ' </span> '; 
        echo '<li class="prev-page">'; previous_posts_link(__('上一页', 'haoui')); echo '</li>';

        if( _hui('paging_type') == 'multi' ){
            if ( $paged > $p + 1 ) _paging_link( 1, '<li>'.__('第一页', 'haoui').'</li>' );
            if ( $paged > $p + 2 ) echo "<li><span>···</span></li>";
            for( $i = $paged - $p; $i <= $paged + $p; $i++ ) { 
                if ( $i > 0 && $i <= $max_page ) $i == $paged ? print "<li class=\"active\"><span>{$i}</span></li>" : _paging_link( $i );
            }
            if ( $paged < $max_page - $p - 1 ) echo "<li><span> ... </span></li>";
        }
        //if ( $paged < $max_page - $p ) _paging_link( $max_page, '&raquo;' );
        echo '<li class="next-page">'; next_posts_link(__('下一页', 'haoui')); echo '</li>';
        if( _hui('paging_type') == 'multi' && $paged < $max_page ) _paging_link($max_page, '', 1);
        if( _hui('paging_type') == 'multi' ) echo '<li><span>'.__('共', 'haoui').' '.$max_page.' '.__('页', 'haoui').'</span></li>';

        echo '</ul></div>';
    }

    function _paging_link( $i, $title = '', $w='' ) {
        if ( $title == '' ) $title = __('页', 'haoui')." {$i}";
        $itext = $i;
        if( $i == 0 ){
            $itext = __('首页', 'haoui');
        }
        if( $w ){
            $itext = __('尾页', 'haoui');
        }
        echo "<li><a href='", esc_html( get_pagenum_link( $i ) ), "'>{$itext}</a></li>";
    }

endif;













function tb_gallery_shortcode($attr) {
    $post = get_post();

    static $instance = 0;
    $instance++;

    if ( ! empty( $attr['ids'] ) ) {
         // 'ids' is explicitly ordered, unless you specify otherwise.
         if ( empty( $attr['orderby'] ) )
                 $attr['orderby'] = 'post__in';
         $attr['include'] = $attr['ids'];
    }

    // Allow plugins/themes to override the default gallery template.
    $output = apply_filters('post_gallery', '', $attr);
    if ( $output != '' )
         return $output;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
         $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
         if ( !$attr['orderby'] )
                 unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
         'order'      => 'ASC',
         'orderby'    => 'menu_order ID',
         'id'         => $post->ID,
         'itemtag'    => 'div',
         'icontag'    => 'div',
         'captiontag' => 'div',
         'columns'    => 3,
         'size'       => 'thumbnail',
         'include'    => '',
         'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order )
         $orderby = 'none';

    if ( !empty($include) ) {
         $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

         $attachments = array();
         foreach ( $_attachments as $key => $val ) {
                 $attachments[$val->ID] = $_attachments[$key];
         }
    } elseif ( !empty($exclude) ) {
         $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
         $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
         return '';

    if ( is_feed() ) {
         $output = "\n";
         foreach ( $attachments as $att_id => $attachment )
                 $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
         return $output;
    }

    $itemtag = tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $icontag = tag_escape($icontag);
    $valid_tags = wp_kses_allowed_html( 'post' );
    if ( ! isset( $valid_tags[ $itemtag ] ) )
         $itemtag = 'dl';
    if ( ! isset( $valid_tags[ $captiontag ] ) )
         $captiontag = 'dd';
    if ( ! isset( $valid_tags[ $icontag ] ) )
         $icontag = 'dt';

    $columns = intval($columns);
    $itemwidth = $columns > 0 ? floor(100/$columns) : 100;
    $float = is_rtl() ? 'right' : 'left';

    $selector = "gallery-{$instance}";

    $gallery_style = $gallery_div = '';

    $size_class = sanitize_html_class( $size );


    $gallery_div = "<div class='gallery galleryid-{$id} gallerylink-{$attr['link']} gallery-columns-{$columns} gallery-size-{$size_class}'>";
    $output = apply_filters( 'gallery_style', $gallery_style . "\n\t\t" . $gallery_div );

    if( $size == 'full' && _hui('full_gallery') ){
        $output .= '<div class="glide">
                    <div class="glide__arrows">
                        <button class="glide__arrow prev" data-glide-dir="<"><i class="fa">&#xe610;</i></button>
                        <button class="glide__arrow next" data-glide-dir=">"><i class="fa">&#xe603;</i></button>
                    </div>
                    <div class="glide__wrapper">
                        <ul class="glide__track">';
    }


    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
         $link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

        if( $size == 'full' && _hui('full_gallery') ){
            $output .= '<li class="glide__slide"><div class="gallery-item">'.$link.'</div>'. ($attachment->post_excerpt ? '<div class="gallery-itemdesc">'.wptexturize($attachment->post_excerpt).'</div>' : '') .'</li>';
        }else{
            $output .= "<{$itemtag} class='gallery-item'>";
            $output .= "
                 <{$icontag} class='gallery-icon'>
                         $link
                 </{$icontag}>";
            if ( $captiontag && trim($attachment->post_excerpt) ) {
                 $output .= "
                         <{$captiontag} class='gallery-caption'>
                         " . wptexturize($attachment->post_excerpt) . "
                         </{$captiontag}>";
            }
            $output .= "</{$itemtag}>";
        }

    }

    if( $size == 'full' && _hui('full_gallery') ){
        $output .= '</ul>
                    </div>
                    <div class="glide__bullets"></div>
                </div>';
    }

    $output .= "

         </div>\n";

    return $output;

}

remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'tb_gallery_shortcode');