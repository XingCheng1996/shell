=== Easy Add Thumbnail ===
Contributors: samuelaguilera
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=8ER3Y2THBMFV6
Tags: thumbnail, thumbnails, featured image, automatic thumbail, automatic feature image
Requires at least: 3.7
Tested up to: 4.7
Stable tag: 1.1.1
License: GPL2

Automatically sets the featured image to the first image uploaded into the post (any post type with thumbnail support). So easy like that...

== Description ==

**NOTE: This plugin functionality is guaranteed by more than 10,000 active installs. But before install this plugin bear in mind that its only purpose is to ADD the feature image ID to your post (in the same way that you would do using WP editor), it does not remove ANYTHING after deactivation.
Before asking for support please read [FAQ](https://wordpress.org/plugins/easy-add-thumbnail/faq/) and [this support thread](https://wordpress.org/support/topic/please-read-before-posting-4)**

= How it works? =

Checks if the post (any post type with thumbnail support, including pages) has already a featured image associated, and if not sets it using one of the following methods:

1. Dinamically, for old published posts, the featured images are set only when needed to show them in the frontend. This means that the featured image is set (only first time) when a visitor loads the page where it needs to be shown.

2. For new content, the featured image is set in the publishing process.

**No options page to setup**, simply install and activate.

If you want to exclude certain post type (e.g. pages), you can do it by using a filter. See [FAQ](https://wordpress.org/plugins/easy-add-thumbnail/faq/) for more details.

**Easy Add Thumbnail don't add any plugin data** to your WordPress database. It simply adds the _thumbnail_id meta key with the ID of the attachment to be used as feature image (just the same that WordPress does when you set it manually).

Therefore it requires to have attached/uploaded at least one image to the post. If there's not any image attached to the post, this plugin can't help you.

Easy Add Thumbnail has not any control over how/when/where your featured image is displayed in your site, **the display of the featured image in your site is TOTALLY controlled by your theme**, this includes size, position, display it or not...

= Requirements =

* WordPress 3.7 or higher.
    	
== Installation ==

* Extract the zip file and just drop the contents in the <code>wp-content/plugins/</code> directory of your WordPress installation (or install it directly from your dashboard) and then activate the Plugin from Plugins page.

<strong>IMPORTANT!</strong> Remember that your theme must support the use of thumbnails, if not, the thumbnail information will be set in the database but you'll not see them in your site.
  
== Frequently Asked Questions ==

= Can I use this plugin for setting featured image using some image not attached to the post? =

No. This plugin uses only standard WordPress functions to set the featured image. And using this standard (and friendly) method WordPress simply has not any knowing about images not attached to the post.

= How can I check if a post has "attached" images? =

In that post edit screen, click the "Add Media button", then click to "Media Library" tab, and select "Uploaded to this post" in the dropdown, you must see at least one image. If you see the "No items found." message, that means that your images were not uploaded within that post. And therefore, the plugin can't use any image to set as feature image.

If you can see images when selecting "Uploaded to this post" but the plugin is not using it for feature image. You need to try for theme/plugins conflicting. Try using a WordPress default theme (i.e. Twenty Fifteen) and disabling all other plugins. 

= My theme is showing big images instead of thumbnail sizes, what happens? =

As stated above this plugin uses standard WordPress method to set the featured image, this does not include any size information. **The size used by your theme for displaying image thumbnails depends totally on how your theme was coded.**

Contact to your theme author for support if you're having this problem.

You can find more information about how to properly show thumbnails in your theme on codex reference for [the_post_thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail) (check 'Thumbnail Sizes' section) and [set_post_thumbnail_size](http://codex.wordpress.org/Function_Reference/set_post_thumbnail_size) functions.

= How can I exclude pages or other post types ? = 

If you don't want to use Easy Add Thumbnail for your pages or any other post type, you can exclude them by simply adding a little snippet of code to your theme functions.php file **before enabling the plugin**.
The following example will exclude pages:

`add_filter ('eat_exclude_types', 'my_excluded_types', 10, 1);
function my_excluded_types ( $exclude_types ){
	$exclude_types[] = 'page'; 
	return $exclude_types;
}`

If you want to exclude a custom post type you need to know the value of 'name' used in [register_post_type()](https://codex.wordpress.org/Function_Reference/register_post_type) function for registering that post type.
e.g. If you have a custom post type and its 'name' is 'book' the you'll use:

`add_filter ('eat_exclude_types', 'my_excluded_types', 10, 1);
function my_excluded_types ( $exclude_types ){
	$exclude_types[] = 'book'; 
	return $exclude_types;
}`

If you want to exclude more than one post type just duplicate the $exclude_types[] line for each one.

**This snippet must be added to your site BEFORE enabling Easy Add Thumbnail in your site**,
if you add it later it will stop assigning the thumbnails for new posts in the excluded types after that moment, previous posts will not be modified.

= Is the post thumbnail and featured image the same? =

Yes. When I released first version of this plugin years ago, featured images was named as [post thumbnails](http://codex.wordpress.org/Post_Thumbnails), but later WordPress team decided to change the name to "featured image".

In fact, WordPress core functions for featured image, still uses original [thumbnail](http://codex.wordpress.org/Function_Reference/the_post_thumbnail) names.

That's because the plugin name (that can't be changed in the Extend directory without having issues) says "thumbnail".

= Will this plugin works in WordPress older than 3.7? =

WordPress installs below 3.7 are not supported. But you can use [Easy Add Thumbnail 1.0.2](http://downloads.wordpress.org/plugin/easy-add-thumbnail.1.0.2.zip) if you use WordPress 2.9 or higher 

== Changelog ==

= 1.1.1 =

* Added 'eat_exclude_types' filter to allow excluding post types from the process
* Minor code cleanup and more comments added

= 1.1 =

* Changed plugin code to be WordPres 3.7 or higher compatible. So now WordPress 3.7 or higher is required and older WordPress versions are not supported.
* Cleanup some code not needed after raising min. required version to 3.7 

= 1.0.2 =

* When updating the readme.txt I copied by error another plugin readme to trunk, causing the plugin closed by WordPress.org staff. This release is only to fix the mistake made with readme as requested by WordPress.org staff. Sorry!!

= 1.0.1 =

* Hooks added to set the thumbnail when publishing too.

= 1.0 =

* Initial release.

== Upgrade Notice ==

= 1.1 =

* This upgrade requires WordPress 3.7 or higher!
