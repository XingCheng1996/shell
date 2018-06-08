<?php
/*
 Plugin Name: Image Paste
 Plugin URI: http://www.zingiri.com
 Description: Image Paste allows you to copy and paste images from your desktop to the Wordpress editor
 Author: Zingiri
 Version: 1.0.2
 Author URI: http://www.zingiri.com/
 */

if (!defined("IMAGEPASTE_PLUGIN")) {
	$imagepaste_plugin=str_replace(realpath(dirname(__FILE__).'/..'),"",dirname(__FILE__));
	$imagepaste_plugin=substr($imagepaste_plugin,1);
	define("IMAGEPASTE_PLUGIN", $imagepaste_plugin);
}

if (file_exists(dirname(__FILE__).'/source.inc.php')) require(dirname(__FILE__).'/source.inc.php');
define("IMAGEPASTE_URL", WP_CONTENT_URL . "/plugins/".IMAGEPASTE_PLUGIN."/");
if (!defined("IMAGEPASTE_JSDIR")) define("IMAGEPASTE_JSDIR","");

add_action("init","imagepaste_init");
add_action('admin_head','imagepaste_admin_header');
add_action('admin_notices', 'imagepaste_admin_notices');

function imagepaste_admin_notices() {
	$errors=array();

	$upload=wp_upload_dir();
	if ($upload['error']) $errors[]=$upload['error'];
	if (count($errors) > 0) {
		echo "<div id='zing-warning' style='background-color:pink' class='updated fade'><p><strong>";
		foreach ($errors as $message) echo 'Image Paste: '.$message.'<br />';
		echo "</strong></p></div>";
	}
}

function imagepaste_admin_header() {
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) return;
	echo '<script type="text/javascript" src="'.IMAGEPASTE_URL.'js/'.IMAGEPASTE_JSDIR.'jquery.paste_image_reader.js" ></script>';
}

function imagepaste_init() {
	if (is_admin()) wp_enqueue_script('jquery');
}

function imagepaste_addbuttons() {
	// Don't bother doing this stuff if the current user lacks permissions
	if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
	return;

	// Add only in Rich Editor mode
	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "add_imagepaste_tinymce_plugin");
		add_filter('mce_buttons', 'register_imagepaste_button');
	}
}


function register_imagepaste_button($buttons) {
	array_push($buttons, "separator", "tinyimagepaste");
	return $buttons;
}


// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function add_imagepaste_tinymce_plugin($plugin_array) {
	$plugin_array['tinyimagepaste'] = plugins_url().'/'.IMAGEPASTE_PLUGIN.'/tinymce/'.IMAGEPASTE_JSDIR.'editor_plugin.js';
	return $plugin_array;
}

// init process for button control
add_action('init', 'imagepaste_addbuttons');

add_action('wp_ajax_imagepaste_action', 'imagepaste_action_callback');

function imagepaste_action_callback() {
	$result=array('error'=>'');
	$upload = wp_upload_dir();
	$uploadUrl=$upload['url'];
	$uploadDir=$upload['path'];

	list($data,$image)=explode(';',$_REQUEST['dataurl']);
	list($field,$type)=explode(':',$data);
	list($encoding,$content)=explode(',',$image);
	if ($type=='image/png') $extension='png';

	$name=md5($_REQUEST['dataurl']);
	if (!$extension) {
		$result['error']="Could not determine image extension type";
	} else {
		$file=$uploadDir.'/'.$name.'.'.$extension;
		$fileUrl=$uploadUrl.'/'.$name.'.'.$extension;
		file_put_contents($file,base64_decode($content));
		if (defined('W3TC')) {
			$result['w3tc']=1;
			ob_start();
			$w3tc=new ImagepasteW3tc();
			//$result['w3tcconfig']=$w3tc->config;
			if ($w3tc->engine=='rscf') $w3tc->upload($uploadDir,$uploadUrl,$name.'.'.$extension);
			$result['log']=$w3tc->log;
			$result['output']=ob_get_clean();
		} else $result['w3tc']=0;

		$result['url']=$fileUrl;
		$result['elementid']=$_REQUEST['elementid'];
	}
	echo json_encode($result);
	die(); // this is required to return a proper result
}

class ImagepasteW3tc {
	var $log=array();
	
	function __construct() {
		$this->config=array();
		$file=W3TC_CACHE_CONFIG_DIR.'/master.php';
		if (file_exists($file)) $this->config=include(W3TC_CACHE_CONFIG_DIR.'/master.php');
		$this->engine=$this->config['cdn.engine'];
		$this->username=$this->config['cdn.rscf.user'];
		$this->apikey=$this->config['cdn.rscf.key'];
		$this->container=$this->config['cdn.rscf.container'];
	}

	function upload($uploadDir,$uploadUrl,$file) {
		if (!file_exists(W3TC_LIB_DIR.'/CF/cloudfiles.php')) return;
		
		require_once(W3TC_LIB_DIR.'/CF/cloudfiles.php');

		$prefix=str_replace(home_url().'/','',$uploadUrl);

		// Lets connect to Rackspace
		$authentication = new CF_Authentication($this->username, $this->apikey);
		$authentication->authenticate();
		$connection = null;
		try {
			$connection = new CF_Connection($authentication);
		}
		catch(AuthenticationException $e) {
			$this->log('Error 1: '.$e->getMessage());
		}

		$container = null;
		try
		{
			$container = $connection->get_container($this->container);
			$container->make_public();
			$this->log('Connected to container '.$this->container);
		}
		catch(Exception $e) {
			$this->log('Error 2: '.$e->getMessage());
		}

		try {
			$object = $container->create_object($prefix.'/'.$file);
			$object->load_from_filename($uploadDir.'/'.$file);
			$this->log('Copied '.$file.' to '.$object->public_uri(), 'info');
		} catch(Exception $e) {
			$this->log('Error 3: '.$e->getMessage());
		}
	}
	
	function log($s) {
		$this->log[]=is_array($s) ? print_r($s,true) : $s;
	}
}
