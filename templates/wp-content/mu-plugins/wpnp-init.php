<?php
/*
Plugin Name: Wordpress Init
Description: Redefines Wordpress directory and path structure and sets other important defauls
Author: Neonpastell GmbH
Author URI: http://www.neonpastell.de
Version: 1.0.1
 */

if (!is_blog_installed()) {return;}

// get server url
$server_port = ((!empty($_SERVER['SERVER_PORT']) AND $_SERVER['SERVER_PORT'] != '80') ? (":" . $_SERVER['SERVER_PORT']) : '');
$server_protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')) ? 'https://' : 'http://';
$server_name = $server_protocol . (!empty($server_port) ? ($_SERVER['SERVER_NAME'] . $server_port) : $_SERVER['SERVER_NAME']);

// set paths for media folder, siteurl and homeurl
$siteurl = $server_name . '/' . $wp_core_dir;
$home = $server_name;
$upload_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $media_dir;
$upload_url_path = $server_name . '/' . $media_dir;

// @todo check if wordpress in subfolder/subdomain works
if ($siteurl == get_option('home') || $home != get_option('home')) {
	update_option('siteurl', $siteurl);
	update_option('home', $home);
	update_option('permalink_structure', '/%postname%/');
}
if ($use_media_dir) {
	// set upload folder path
	if (get_option('upload_path') == '' || get_option('upload_path') == $media_dir || strcmp(get_option('upload_path'), $upload_path) !== 0) {
		update_option('upload_path', $upload_path);
	}
	// set upload folder url
	if (get_option('upload_url_path') == '' || get_option('upload_url_path') != $upload_url_path) {
		update_option('upload_url_path', $upload_url_path);
	}
}

// registers the new theme directory
register_theme_directory(ABSPATH . $content_dir . '/themes/');

/**
 * WP Updates
 */
// auto wordpress updates despite .git files
add_filter('automatic_updates_is_vcs_checkout', '__return_false', 1);
// specify what to update
add_filter('allow_dev_auto_core_updates', '__return_true'); // Enable development updates
add_filter('allow_minor_auto_core_updates', '__return_true'); // Enable minor updates
add_filter('allow_major_auto_core_updates', '__return_false'); // Disable major updates

?>